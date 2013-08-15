<?php

/**
 * Register a transaction with SagePay.
 * This is the first stage that provides the details of the transaction
 * to SagePay and gets things started.
 */

namespace Academe\SagePay;

use Academe\SagePay\Metadata as Metadata;
use Academe\SagePay\Exception as Exception;

class Register extends Model\XmlAbstract
{
    /**
     * The model used to store, retrieve and update the transaction.
     */

    protected $tx_model = null;

    /**
     * The SagePay method to be used.
     * The method is either 'direct' or 'server'.
     */

    protected $method = 'server';

    /**
     * Flag indicates whether failed transaction registrations should be saved to the transaction log.
     * Normally they would be logged by SagePay anyway, but discarded by the application, perhaps
     * presenting the user with the option to try again.
     */

    protected $save_failed_registrations = true;

    /**
     * The timeout, in seconds, waiting for SagePay to respond to a registration request.
     */

    protected $timeout = 30;

    /**
     * All SagePay Server and SagePay Direct URL base.
     */

    protected $sagepay_url_base = array(
        'simulator' => 'https://test.sagepay.com/Simulator/VSPServerGateway?service={service}',
        'test' => 'https://test.sagepay.com/gateway/service/{service}',
        'live' => 'https://live.sagepay.com/gateway/service/{service}',
    );

    /**
     * Mapping of the transaction type to URL service name.
     * Note some services are not supported by the simulator.
     *
     * A list of services for transaction types, to be added to the SagePay POST URL.
     *
     * The services are different for SagePay Direct
     * The "3DSECURE" service is the only service that does not require that service
     * name to be passed to SagePay in the TxType (transaction type) field of the POST.
     *
     * The "services" here are grouped into "methods" (server, direct and common).
     *
     * CHECKME: The v3.0 protocal documentation lists, in some places, the service for transaction
     * registration as "server-register.vsp" rather than "vspserver-register.vsp". But it does
     * contractict itself in a number of other places too.
     */

    protected $sagepay_url_services = array(
        'direct' => array(
            'PAYMENT' => array(
                'test' => 'vspdirect-register.vsp',
                'live' => 'vspdirect-register.vsp',
                'simulator' => null,
            ),
            'DEFERRED' => array(
                'test' => 'vspdirect-register.vsp',
                'live' => 'vspdirect-register.vsp',
                'simulator' => null,
            ),
            'AUTHENTICATE' => array(
                'test' => 'vspdirect-register.vsp',
                'live' => 'vspdirect-register.vsp',
                'simulator' => null,
            ),
            '3DSECURE' => array(
                'test' => 'direct3dcallback.vsp',
                'live' => 'direct3dcallback.vsp',
                'simulator' => null,
            ),
            'COMPLETE' => array(
                'test' => 'complete.vsp',
                'live' => 'complete.vsp',
                'simulator' => null,
            ),
        ),
        'server' => array(
            'PAYMENT' => array(
                'test' => 'vspserver-register.vsp',
                'live' => 'vspserver-register.vsp',
                'simulator' => null,
            ),
            'DEFERRED' => array(
                'test' => 'vspserver-register.vsp',
                'live' => 'vspserver-register.vsp',
                'simulator' => null,
            ),
            'AUTHENTICATE' => array(
                'test' => 'vspserver-register.vsp',
                'live' => 'vspserver-register.vsp',
                'simulator' => null,
            ),
        ),
        'common' => array(
            'RELEASE' => array(
                'test' => 'release.vsp',
                'live' => 'release.vsp',
                'simulator' => 'VendorReleaseTx',
            ),
            'ABORT' => array(
                'test' => 'abort.vsp',
                'live' => 'abort.vsp',
                'simulator' => 'VendorAbortTx',
            ),
            'REFUND' => array(
                'test' => 'refund.vsp',
                'live' => 'refund.vsp',
                'simulator' => 'VendorRefundTx',
            ),
            'REPEAT' => array(
                'test' => 'repeat.vsp',
                'live' => 'repeat.vsp',
                'simulator' => 'VendorRepeatTx',
            ),
            'REPEATDEFERRED' => array(
                'test' => 'repeat.vsp',
                'live' => 'repeat.vsp',
                'simulator' => 'VendorRepeatTx',
            ),
            'VOID' => array(
                'test' => 'void.vsp',
                'live' => 'void.vsp',
                'simulator' => 'VendorVoidTx',
            ),
            'MANUAL' => array(
                'test' => 'manualpayment.vsp',
                'live' => 'manualpayment.vsp',
                'simulator' => null,
            ),
            'DIRECTREFUND' => array(
                'test' => 'directrefund.vsp',
                'live' => 'directrefund.vsp',
                'simulator' => 'VendorDirectrefundTx',
            ),
            'AUTHORISE' => array(
                'test' => 'authorise.vsp',
                'live' => 'authorise.vsp',
                'simulator' => 'VendorAuthoriseTx',
            ),
            'CANCEL' => array(
                'test' => 'cancel.vsp',
                'live' => 'cancel.vsp',
                'simulator' => 'VendorCancelTx',
            ),
        ),
    );

    /**
     * The SagePay platform we will connect to - 'test' or 'live'.
     */

     protected $sagepay_platform = 'test';

    /**
     * The input characterset.
     * SagePay works only with ISO-8859-1, so conversion may be necessary.
     */

    public $input_charset = 'UTF-8';

    /**
     * The models used to store the shipping and billing addresses.
     */

    protected $billing_address = null;
    protected $delivery_address = null;

    /**
     * The optional basket details object.
     */

    protected $basket = null;

    /**
     * The optional customer details object.
     */

    protected $customer = null;

    /**
     * The optional surcharges object.
     */

    protected $surcharge = null;

    /**
     * Set the model used to store, retrieve and update the transaction.
     */

    public function setTransactionModel(Model\TransactionAbstract $tx_model)
    {
        $this->tx_model = $tx_model;

        // Return the transactino model so the caller can continue to set it up.
        return $this->tx_model;
    }

    /**
     * Get the injected model used to store, retrieve and update the transaction.
     */

    public function getTransactionModel()
    {
        return $this->tx_model;
    }

    /**
     * Return the service list available to the current selected method.
     * The services will be a merge of the method-specific services and the
     * common services.
     * $method defaults to the method selected for the request.
     */

    public function getServices($method = null)
    {
        if ( ! isset($method)) $method = $this->method;

        if ( ! isset($this->sagepay_url_services[$method])) {
            throw new Exception\InvalidArgumentException("Invalid method type '{$method}'");
        }

        return array_merge(
            $this->sagepay_url_services[$method],
            $this->sagepay_url_services['common']
        );
    }

    /**
     * Set the method used to access the services - 'direct' or 'server'.
     */

    public function setMethod($method)
    {
        $method = strtolower($method);

        // Should these be constants, or a array constant in this class? Making them public
        // kind of makes sense, even if they are unlikely to ever change.

        if ($method != 'direct' && $method != 'server') {
            throw new Exception\InvalidArgumentException("Invalid method type '{$method}'");
        }

        $this->method = $method;

        return $this;
    }

    /**
     * Get the method used to access the services.
     */

    public function getMethod()
    {
        return $this->$method;
    }

    /**
     * Set the model used for the basket.
     */

    public function setBasketModel(Model\BasketAbstract $basket)
    {
        $this->basket = $basket;

        // Return the basket model so the caller can continue to set it up.
        return $this->basket;
    }

    /**
     * Set the model used for the customer.
     */

    public function setCustomerModel(Model\CustomerAbstract $customer)
    {
        $this->customer = $customer;

        // Return the basket model so the caller can continue to set it up.
        return $this->customer;
    }

    /**
     * Set the model used for the surcharges.
     */

    public function setSurchargeModel(Model\SurchargeAbstract $surcharge)
    {
        $this->surcharge = $surcharge;

        // Return the model so the caller can continue to set it up.
        return $this->surcharge;
    }

    /**
     * Check a transaction model is set before we attempt to use it.
     */

    private function checkTxModel()
    {
        if (!isset($this->tx_model)) {
            throw new Exception\BadMethodCallException('Transaction model is not set');
        }
    }

    /**
     * Save the transaction data to storage.
     */

    public function save()
    {
        // Ensure we have a transaction model.
        $this->checkTxModel();

        $this->expandModels();
        return $this->tx_model->save();
    }

    /**
     * Return all the current model data as an array.
     */

    public function toArray()
    {
        $this->checkTxModel();

        $this->expandModels();
        return $this->tx_model->toArray();
    }

    /**
     * Return the vemdor transaction ID for the registration.
     */

    public function vendorTxCode()
    {
        return $this->getField('VendorTxCode');
    }

    /**
     * Set the transaction type.
     * Default in the model is 'PAYMENT'.
     */

    public function setTxType($tx_type)
    {
        // Make sure the transaction type is valid.
        $services = $this->getServices();
        if ( ! isset($services[$tx_type])) {
            throw new Exception\InvalidArgumentException("Invalid transaction type '{$tx_type}'");
        }

        $this->setField('TxType', strtoupper($tx_type));
        return $this;
    }

    /**
     * Set the vendor.
     */

    public function setVendor($vendor)
    {
        $this->setField('Vendor', $vendor);
        return $this;
    }

    /**
     * Set the ammount and currency.
     */

    public function setAmount($amount, $currency = 'GBP')
    {
        // Set the currency first, before we start formatting amounts.
        $this->setCurrency($currency);

        // Some minimal validation.
        $currency = strtoupper($currency);

        $this->setField('Amount', $this->formatAmount($amount));
        $this->setField('Currency', $currency);

        return $this;
    }

    /**
     * Set the main details in one go.
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
     * Set the platform we are going to use.
     * Platforms are 'test' or 'live' (there is no simulator for V3 protocol).
     *
     * @throws Academe\SagePay\Exception\InvalidArgumentException
     */

    public function setPlatform($platform)
    {
        $platform = strtolower($platform);

        if (isset($this->sagepay_url_base[$platform])) {
            $this->sagepay_platform = $platform;
        } else {
            // Supplied platform name not supported.
            throw new Exception\InvalidArgumentException("Invalid platform name '{$platform}'");
        }

        return $this;
    }

    /**
     * Get the URL for the platform and service selected.
     * FIXME: use the sagepay_url_services property to set the service suffix, in context with the method.
     *
     * The method, platform and transactino_type default to those selected for the transaction,
     * but you can pass in any valid values for testing, without affecting the values selected
     * for the transaction.
     *
     * @throws Academe\SagePay\Exception\InvalidArgumentException
     */

    public function getUrl($method = null, $platform = null, $transaction_type = null)
    {
        // The replacement field for the URL; where we place the service name.
        $sub = '{service}';

        $services = $this->getServices($method);
        if ( ! isset($platform)) $platform = $this->sagepay_platform;
        if ( ! isset($transaction_type)) $transaction_type = $this->getField('TxType');

        if ( ! isset($this->sagepay_url_base[$platform])) {
            throw new Exception\InvalidArgumentException("Invalid platform '{$platform}'");
        }

        $url_base = $this->sagepay_url_base[$platform];

        if ( ! isset($services[$transaction_type])) {
            throw new \InvalidArgumentException("Invalid transaction type '{$transaction_type}'");
        } elseif ( ! isset($services[$transaction_type][$platform])) {
            throw new Exception\InvalidArgumentException("Transaction type '{$transaction_type}' not supported by platform '{$platform}'");
        }

        $service = $services[$transaction_type][$platform];

        return str_replace($sub, $service, $url_base);
    }

    
    /**
     * Set the value of a field in the transaction model.
     */

    public function setField($name, $value)
    {
        $this->checkTxModel();

        $this->tx_model->setField($name, $value);
        return $this;
    }

    /**
     * Get the value of a field in the transaction model.
     */

    public function getField($name)
    {
        $this->checkTxModel();

        return $this->tx_model->getField($name);
    }

    /**
     * Get a transaction by its VendorTxCode.
     */

    public function findTransaction($VendorTxCode)
    {
        $this->checkTxModel();

        return $this->tx_model->find($VendorTxCode);
    }

    /**
     * Set the billing address.
     */

    public function setBillingAddress(Model\AddressAbstract $address)
    {
        $this->billing_address = $address;
        $this->expandModels();

        return $this->billing_address;
    }

    /**
     * Set the billing address.
     */

    public function setDeliveryAddress(Model\AddressAbstract $address)
    {
        $this->delivery_address = $address;
        $this->expandModels();

        return $this->delivery_address;
    }

    /**
     * Expand the address fields into the main data array.
     * Do this before reading the data array.
     * If either address is not set, then default it to the other address, as both
     * are mandatory.
     * Also expand all other objects in the request: basket, customer, surcharges.
     */

    protected function expandModels()
    {
        // Default the billing and delivery address from its opposite number if not set.
        $billing = (is_object($this->billing_address) ? $this->billing_address : $this->delivery_address);
        $delivery = (is_object($this->delivery_address) ? $this->delivery_address : $this->billing_address);

        // Expand the billing address.

        if (is_object($billing)) {
            foreach($billing->toArray() as $field_name => $field_value) {
                $this->tx_model->setField('Billing' . $field_name, $field_value);
            }
        }

        // Expand the delivery address.

        if (is_object($delivery)) {
            foreach($delivery->toArray() as $field_name => $field_value) {
                $this->tx_model->setField('Delivery' . $field_name, $field_value);
            }
        }

        // Expand the basket to XML.

        if (is_object($this->basket)) {
            $this->tx_model->setField('BasketXML', $this->basket->toXml());
        }

        // Expand the customer to XML.

        if (is_object($this->customer)) {
            $this->tx_model->setField('CustomerXML', $this->customer->toXml());
        }

        // Expand the surcharge to XML.

        if (is_object($this->surcharge)) {
            $this->tx_model->setField('SurchargeXML', $this->surcharge->toXml());
        }
    }

    /**
     * Collect he query data together for the transaction registration.
     * Returns key/value pairs
     * TODO: different types of transaction will need different sets of fields to
     * be sent to SagePay.
     */

    public function queryData($format_as_querystring = true)
    {
        $this->checkTxModel();

        // Make sure all the models are expanded into the main transaction data model.
        $this->expandModels();

        $query = array();

        // Get the list of fields we want to send to SagePay from the transaction metadata.

        $all_fields = Metadata\Transaction::get('array');

        $fields_to_send = array();
        $optional_fields = array();

        foreach($all_fields as $field_name => $field_meta) {
            if ( ! in_array('server-registration', $field_meta['source'])) continue;
            $fields_to_send[] = $field_name;
            if (empty($field_meta['required'])) $optional_fields[] = $field_name;
        }

        // Loop through the fields, both optional and mandatory.

        foreach($fields_to_send as $field) {
            $value = $this->tx_model->getField($field);
            if ( ! in_array($field, $optional_fields) || isset($value)) {
                // If the input characterset is UTF-8, then convert the string to ISO-8859-1
                // for transfer to SagePay.
                // FIXME: catch invalid UTF-8 stream errors. iconv() can easily fail if you
                // pass it duff data.

                if ($this->input_charset == 'UTF-8') {
                    $value = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $value);
                }

                $query[$field] = $value;
            }
        }

        if ($format_as_querystring) {
            // Return the query string
            return http_build_query($query, '', '&');
        } else {
            // Just return the data as an array.
            // This may be more useful for some transport packages.
            return $query;
        }
    }

    /**
     * Handle the POST to SagePay and collect the result.
     * TODO: move to Transport namespace.
     */

    public function postSagePay($sagepay_url, $query_string, $timeout = 30)
    {
        $curlSession = curl_init();

        // Set the URL
        curl_setopt($curlSession, CURLOPT_URL, $sagepay_url);

        // No headers.
        curl_setopt($curlSession, CURLOPT_HEADER, 0);

        // It's a POST request
        curl_setopt($curlSession, CURLOPT_POST, 1);

        // Set the fields for the POST
        curl_setopt($curlSession, CURLOPT_POSTFIELDS, $query_string);

        // Return it direct, don't print it out
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER,1);

        // This connection will timeout in 30 seconds
        curl_setopt($curlSession, CURLOPT_TIMEOUT, $timeout);

        // The next two lines must be present for the kit to work with newer version of cURL
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 2);

        // Send the request and store the result in an array
        $rawresponse = trim(curl_exec($curlSession));
        $curl_info = curl_getinfo($curlSession);

        // Split response into name=value pairs
        // The documentation states "CRLF" divides the lines. We will use the regex match \R to 
        // catch any platform-specific version of line endings that the future may throw at us.
        $response = preg_split('/$\R?^/m', $rawresponse);

        $output = array();

        // Check that a connection was made.
        if (curl_error($curlSession) || $curl_info['http_code'] != 200)
        {
            // If it wasn't...
            $output['Status'] = 'FAIL';
            $output['StatusDetail'] = $curl_info['http_code'] . ': ' . trim(curl_error($curlSession));
        }

        // Close the cURL session
        curl_close($curlSession);

        // Tokenise the response, if we have not already detected an HTTP error.
        // In the response should be the Status and StatusDetail, amoungst other fields.
        if ( ! isset($output['Status']) || $output['Status'] != 'FAIL') {
            foreach($response as $value) {
                if (strpos($value, '=') !== false) {
                    list($k, $v) = explode('=', $value, 2);
                    $output[trim($k)] = trim($v);
                } else {
                    $output[] = trim($value);
                }
            }
        }

        return $output;
    }

    /**
     * Send the registration to SagePay and save the reply.
     * TODO: make sure the transaction type is valid (one of three allowed):
     * PAYMENT, DEFERRED or AUTHENTICATE.
     *
     * Note: at present this is a "server" (not "direct") registration request method only. This
     * method will become the entry point for BOTH server and direct, while retaining backward
     * compatibility.
     */

    public function sendRegistration()
    {
        // Check the transaction type.

        $tx_type = $this->getField('TxType');
        if ($tx_type != 'PAYMENT' && $tx_type != 'DEFERRED' && $tx_type != 'AUTHENTICATE') {
            // The transaction type is not valid for sending a registration.
            throw new Exception\InvalidArgumentException("Invalid transaction type for registration '{$tx_type}'");
        }

        // Construct the query string from data in the model.
        $query_string = $this->queryData();

        // Get the URL, which is derived from the method, platform and the service.
        $sagepay_url = $this->getUrl();

        // Post the request to SagePay
        $output = $this->postSagePay($sagepay_url, $query_string, $this->timeout);

        // If successful, save the returned data to the model, save it, then return the model.
        // TODO: include all extra fields that the V3 protocol introduces.
        if (isset($output['Status']) && $output['Status'] == 'OK') {
            $this->setField('VPSTxId', $output['VPSTxId']);
            $this->setField('SecurityKey', $output['SecurityKey']);

            // Move the status as PENDING, to indicate we are waing for a response from SagePay.
            $this->setField('Status', 'PENDING');

            $this->setField('StatusDetail', $output['StatusDetail']);

            // Set the NextURL in the model. Itr won't get saved, but will be accessible to
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
            if ($this->save_failed_registrations) {
                $this->save();
            }
        }
    }

    /**
     * The notification callback.
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
        // End of line string.
        $eol = "\r\n";

        // Get the main details that identify the transaction.
        $Status = (isset($post['Status']) ? (string) $post['Status'] : '');
        $StatusDetail = (isset($post['StatusDetail']) ? (string) $post['StatusDetail'] : '');
        $VendorTxCode = (isset($post['VendorTxCode']) ? (string) $post['VendorTxCode'] : '');
        $VPSTxId = (isset($post['VPSTxId']) ? (string) $post['VPSTxId'] : '');
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
            $strMessage = 
                // V2.23 protocol
                $post['VPSTxId'] . $post['VendorTxCode'] . $post['Status']
                . $post['TxAuthNo'] . $this->getField('Vendor')
                . $post['AVSCV2'] . $this->getField('SecurityKey')
                . $post['AddressResult'] . $post['PostCodeResult'] . $post['CV2Result']
                . $post['GiftAid'] . $post['3DSecureStatus']
                . $post['CAVV'] . $post['AddressStatus'] . $post['PayerStatus']
                . $post['CardType'] . $post['Last4Digits']

                // New for V3 protocol.
                . $post['DeclineCode'] . $post['ExpiryDate']
                . $post['FraudResponse'] . $post['BankAuthCode'];

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

            // SagePay V2 fields.
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
            $this->setField('VPSSignature', $post['VPSSignature']);
            $this->setField('FraudResponse', $post['FraudResponse']);
            $this->setField('Surcharge', $post['Surcharge']);
            $this->setField('BankAuthCode', $post['BankAuthCode']);
            $this->setField('DeclineCode', $post['DeclineCode']);
            $this->setField('ExpiryDate', $post['ExpiryDate']);
            $this->setField('Token', $post['Token']);

            // Save the transaction record to local storage.
            $this->save();
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
    * Returns true if the status of the transaction is one resulting from a
    * successful payment.
    */

    public function isPaymentSuccess()
    {
        $this->checkTxModel();
        return $this->tx_model->isPaymentSuccess();
    }

}

