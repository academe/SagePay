<?php

/**
 * SagePay Server services.
 * Register a transaction with SagePay Server and handle the notification.
 * This is the first stage that provides the details of the transaction
 * to SagePay and gets things started.
 */

namespace Academe\SagePay;

use RuntimeException;

use Academe\SagePay\Metadata;
use Academe\SagePay\Exception;

class Server extends Shared
{
    /**
     * The SagePay method to be used.
     * The method is either 'direct' or 'server' (both make 'shared' services available)
     * or 'shared' (for just the shared services).
     */

    protected $method = 'server';

    /**
     * The message type to be used
     */

    protected $message_type = 'server-registration';

    /**
     * Send the registration to SagePay and save the reply.
     *
     * Note: at present this is a "server" (not "direct") registration request method only. This
     * method will become the entry point for BOTH server and direct, while retaining backward
     * compatibility. Will probably split into sendServerRegistration() and sendDirectRegistration()
     * with this method routing to the appropriate service.
     */

    public function sendRegistration()
    {
        // Check the transaction type.

        $tx_type = $this->getField('TxType');
        if ($tx_type != 'PAYMENT' && $tx_type != 'DEFERRED' && $tx_type != 'AUTHENTICATE' && $tx_type != 'TOKEN') {
            // The transaction type is not valid for sending a registration.
            throw new Exception\InvalidArgumentException("Invalid transaction type for registration '{$tx_type}'");
        }

        // Save the transaction before we send it. This also generates a VendorTxCode, if one
        // has not already been created.
        $this->save();

        // Construct the query string from data in the model.
        $query_string = $this->queryData(true, $this->message_type);

        // Get the URL, which is derived from the method, platform and the service.
        $sagepay_url = $this->getUrl();

        // Post the request to SagePay
        $output = $this->postSagePay($sagepay_url, $query_string, $this->timeout);

        // If successful, put the returned data to the model, save it, then return the model.
        // TODO: include all extra fields that the V3 protocol introduces.
        // TODO: we may have a malformed return value (e.g. a HTTP error or dropped connection) so
        // that needs to be handled. i.e. there may not be a "Status" returned. This is partially
        // handled in postSagePay() anyway, so perhaps extend that.

        if (isset($output['Status']) && $output['Status'] == 'OK') {
            $this->setField('VPSTxId', $output['VPSTxId']);
            $this->setField('SecurityKey', $output['SecurityKey']);

            // Move the status as PENDING, to indicate we are waitng for a response from SagePay.
            // Note: SagePay has now introduced a PENDING status in connection with PayPal and
            // European payments. To support that, we can no longer use PENDING for our own
            // purposes.
            $this->setField('Status', 'PENDING');

            $this->setField('StatusDetail', $output['StatusDetail']);

            // Set the NextURL in the model. It won't get saved, but will be accessible to
            // the calling function to action.
            $this->setField('NextURL', $output['NextURL']);

            // Save the transaction to storage.
            // TODO: catch save failures.
            $this->save();
        } else {
            // SagePay has rejected what we have sent.
            $this->setField('Status', $output['Status']);
            $this->setField('StatusDetail', $output['StatusDetail']);

            // If the option is set, save the failed registration to the transaction to storage.
            // The failures are logged by SagePay anyway, and you probably don't want to clog up
            // storage with the failures, unless you have a specific reason to monitor this.
            // With the Issue #10 fix this is moot now, as the transaction is saved before
            // posting to SagePay regardless of the result. I'll probably remove this option and
            // just leave the cleaning out of failed transactions as an administratitive job.
            if ($this->save_failed_registrations) {
                $this->save();
            }
        }
    }

    /**
     * The notification callback for SagePay Server PAYMENT, DEFERRED, AUTHENTICATE.
     * This handles the callback from SagePay in response to a [successful] transaction registration.
     *
     * The redirect URL should not carray any information that allows an end user to be able to
     * highjack it and effect a payment. For this reason, SagePay will be sent to the same page
     * regardless of the status. That page can then inspect the transaction to decide what action
     * to take. A successful payment will be a status of OK, AUTHENTICATED or REGISTERED. A failed
     * payment will be ABORT, NOTAUTHED, REJECTED or ERROR. In the middle is PENDIND, where the
     * transaction is not yet complete (neither paid nor failed) and will take some time to process.
     *
     * Before redirecting to SagePay on registering the transaction, the VendorTxId needs to have been
     * saved to the session. That way the transaction result can be inspected on return to the store,
     * and appropriate action can be taken.
     *
     * @param $post array POST data sent to the page request.
     * @param $redirect_url string The URL SagePay should redirect to, regardless of status.
     */

    public function notification($post, $redirect_url)
    {
        // End of line characters.
        // This is what SagePay expects as a line terminator.
        $eol = "\r\n";

        // Get the main details that identify the transaction.
        $Status = (isset($post['Status']) ? (string) $post['Status'] : '');
        $StatusDetail = (isset($post['StatusDetail']) ? (string) $post['StatusDetail'] : '');
        $VendorTxCode = (isset($post['VendorTxCode']) ? (string) $post['VendorTxCode'] : '');
        $VPSTxId = (isset($post['VPSTxId']) ? (string) $post['VPSTxId'] : '');

        // Deal with the quirk noted on page 29 of the token registration docs, i.e. that braces are
        // not returned in the TOKEN notification post. Version 3.00 should have been a good
        // opportunity for SagePay to fix quirks like this, but instead we need to deal with it here.
        if (isset($post['TxType']) && $post['TxType'] == "TOKEN") {
            $VPSTxId = '{' . $VPSTxId . '}';
        }
        $VPSSignature = (isset($post['VPSSignature']) ? (string) $post['VPSSignature'] : '');

        // Assume this process will be successful.
        $retStatus = 'OK';
        $retStatusDetail = '';

        // If we have no VendorTxCode then we can go no further.
        if (empty($VendorTxCode)) {
            // Return an appropriate error to the caller.
            $retStatus = 'ERROR';
            $retStatusDetail = 'No VendorTxCode sent';
        }

        if ($retStatus == 'OK') {
            // Get the transaction record.
            // A transaction object should already have been injected to look this up.

            if ( $retStatus == 'OK' && $this->getTransactionModel() === null) {
                // Internal error.
                $retStatus = 'INVALID';
                $retStatusDetail = 'Internal error (missing transaction object)';
            } else {
                // Fetch the transaction record from storage.
                $this->findTransaction($VendorTxCode);
            }
        }

        if ($this->getField('VendorTxCode') !== null) {
            if ($this->getField('Status') != 'PENDING') {
                // Already processed status.
                $retStatus = 'INVALID';
                $retStatusDetail = 'Transaction has already been processed';
            } elseif ($VPSTxId != $this->getField('VPSTxId')) {
                // Mis-matching VPSTxId values.
                $retStatus = 'INVALID';
                $retStatusDetail = 'VPSTxId mismatch';
            }
        } else {
            // Return failure to find transaction.
            $retStatus = 'INVALID';
            $retStatusDetail = 'No transaction found';
        }

        // With some of the major checks done, let's dig a little deeper into
        // the transaction to see if it has been tampered with. The anit-tamper
        // checks allows us to used a non-secure connection for the .
        if ($retStatus == 'OK') {
            // Gather some additional parameters, making sure they are all set (defaulting to '').
            // Derive this list from the transaction metadata, with flag "tamper" set.

            $field_meta = Metadata\Transaction::get();

            foreach($field_meta as $field_name => $field) {
                if ( ! empty($field->tamper)) {
                    // Make sure a string has been passed in, defaulting to an empty string if necessary.
                    $post[$field_name] = (isset($post[$field_name]) ? (string) $post[$field_name] : '');
                }
            }

            /*
                From protocol V3 documentation:
                VPSTxId + VendorTxCode + Status
                + TxAuthNo + VendorName (aka Vendor)
                + AVSCV2 + SecurityKey (saved with the transaction registration)
                + AddressResult + PostCodeResult + CV2Result
                + GiftAid + 3DSecureStatus
                + CAVV + AddressStatus + PayerStatus
                + CardType + Last4Digits
                + DeclineCode + ExpiryDate
                + FraudResponse + BankAuthCode
            */

            // Construct a concatenated POST string hash.
            // These could be constructed from the transaction metadata.
            if (isset($post['TxType']) && $post['TxType'] == "TOKEN") {
                $strMessage =
                    // Token protocol
                    $post['VPSTxId'] . $post['VendorTxCode'] . $post['Status']
                    . $this->getField('Vendor') . $post['Token']
                    . $this->getField('SecurityKey');
            } else {
                $strMessage =
                    // First V2.23 protocol fields
                    $post['VPSTxId'] . $post['VendorTxCode'] . $post['Status']
                    . $post['TxAuthNo'] . $this->getField('Vendor')
                    . $post['AVSCV2'] . $this->getField('SecurityKey')
                    . $post['AddressResult'] . $post['PostCodeResult'] . $post['CV2Result']
                    . $post['GiftAid'] . $post['3DSecureStatus']
                    . $post['CAVV'] . $post['AddressStatus'] . $post['PayerStatus']
                    . $post['CardType'] . $post['Last4Digits']

                    // Additional fields for V3 protocol.
                    . $post['DeclineCode'] . $post['ExpiryDate']
                    . $post['FraudResponse'] . $post['BankAuthCode'];
            }

            $MySignature = strtoupper(md5($strMessage));

            if ($MySignature !== $VPSSignature) {
                // Message that record has been tampered with.
                $retStatus = 'ERROR';
                $retStatusDetail = 'Notification has been tampered with';
            }
        }

        // If still a success, then all tests have passed.
        if ($retStatus == 'OK') {
            // We found a PENDING transaction, so update it.
            // We don't want to be updating the local transaction in any other circumstance.
            // However, we might want to log the errors somewhere else.

            // First SagePay V2 fields.
            $this->setField('Status', $Status);
            $this->setField('StatusDetail', $StatusDetail);
            $this->setField('TxAuthNo', $post['TxAuthNo']);
            $this->setField('AVSCV2', $post['AVSCV2']);
            $this->setField('AddressResult', $post['AddressResult']);
            $this->setField('PostCodeResult', $post['PostCodeResult']);
            $this->setField('CV2Result', $post['CV2Result']);
            $this->setField('GiftAid', $post['GiftAid']);
            $this->setField('3DSecureStatus', $post['3DSecureStatus']);
            $this->setField('CAVV', $post['CAVV']);
            $this->setField('AddressStatus', $post['AddressStatus']);
            $this->setField('PayerStatus', $post['PayerStatus']);
            $this->setField('CardType', $post['CardType']);
            $this->setField('Last4Digits', $post['Last4Digits']);

            // SagePay V3.00 fields.
            // No need to store, or attempt to store, the VPSSignature - it is a throw-away
            // hash of the notification data, with local salt.
            $this->setField('FraudResponse', $post['FraudResponse']);
            $this->setField('Surcharge', $this->arrayElement($post, 'Surcharge'));
            $this->setField('BankAuthCode', $post['BankAuthCode']);
            $this->setField('DeclineCode', $post['DeclineCode']);
            $this->setField('ExpiryDate', $post['ExpiryDate']);
            $this->setField('Token', $this->arrayElement($post, 'Token'));

            // Save the transaction record to local storage.
            // We don't want to throw exceptions here; SagePay must get its response.
            try{
                $this->save();
            }
            catch (Exception\RuntimeException $e) {
                $retStatus = 'ERROR';
                $retStatusDetail = 'Cannot save result to database: ' . $e->getMessage();
            }
            catch (RuntimeException $e) {
                $retStatus = 'ERROR';
                $retStatusDetail = 'Cannot save result to database: ' . $e->getMessage();
            }
        }

        // Finally return the result to SagePay, including the relevant redirect URL.

        // If the status sent to us is ERROR, then return INVALID to SagePay.
        // It is not clear why, but the sample code provided by SagePay does this.
        if ($Status == 'ERROR') {
            $retStatus = 'INVALID';
        }

        // Replace any tokens in the URL with values from the transaction, if required.
        // The tokens will be {fieldName} for inserting in a path part of the URL or
        // {{fieldName}} for inserting into a query parameter of the URL.
        // e.g. http://example.com/notification_{Status}.php?id={{VendorTxCode}}
        // although you probably don't want to expose the VendorTxCode.

        $fields = $this->toArray();
        foreach ($fields as $field => $value) {
            $token_path = '{' . $field . '}';
            $token_query = '{' . $token_path . '}';

            // Query parameters and path parts use different escaping.

            if (strpos($redirect_url, $token_query) !== false) {
                $redirect_url = str_replace($token_query, urlencode($value), $redirect_url);
            }

            if (strpos($redirect_url, $token_path) !== false) {
                $redirect_url = str_replace($token_path, rawurlencode($value), $redirect_url);
            }
        }

        // The return string should be fed out to the caller as the only result.
        // The status we send back is one of OK, INVALID or ERROR.

        return 'Status=' . $retStatus . $eol
            . 'StatusDetail=' . $retStatusDetail . $eol
            . 'RedirectURL=' . $redirect_url . $eol;
    }

    /**
     * Read an element from an array, providing a default where an element is not set.
     */

    public function arrayElement($array, $key, $default = '')
    {
        return (array_key_exists($key, $array) ? $array[$key] : $default);
    }

    /**
     * Set the main details for a transaction in one go.
     */

    public function setMain($tx_type, $vendor, $amount, $currency, $description, $url)
    {
        $this->setAmount($amount, $currency);
        $this->setField('TxType', $tx_type);
        $this->setField('Vendor', $vendor);
        $this->setField('Description', $description);
        $this->setField('NotificationURL', $url);
        return $this;
    }

    /**
     * Set the token registration details
     */

    public function setStandaloneTokenFields($vendor, $currency, $url)
    {
        $this->message_type =  'server-standalone-token';

        // Set the transaction type.
        $this->setField('TxType', 'TOKEN');
        $this->setField('Vendor', $vendor);
        $this->setField('Currency', $currency);
        $this->setField('NotificationURL', $url);
        return $this;
    }

}
