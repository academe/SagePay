<?php

/**
 * Register a transaction with SagePay.
 * This is the first stage that provides the details of the transaction
 * to SagePay and gets things started.
 */

namespace Academe\SagePay;

class Register extends Model\XmlAbstract
{
    /**
     * The model used to store, retrieve and update the transaction.
     */

    protected $tx_model = null;

    /**
     * The timeout, in seconds, waiting fot SagePay to respond to a registration request.
     */

    protected $timeout = 30;

    /**
     * All SagePay URL parts.
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
     * A list of services for transaction types:
     *  vspserver-register.vsp for PAYMENT, DEFERRED and AUTHENTICATE.
     *  release.vsp for RELEASE (to release a DEFERRED or REPEATDEFERRED Payment)
     *  abort.vsp for ABORT
     *  refund.vsp for REFUND
     *  repeat.vsp for REPEAT and REPEATDEFERRED
     *  void.vsp for VOID
     *  manualpayment.vsp for MANUAL
     *  directrefund.vsp for DIRECTREFUND
     *  authorise.vsp for AUTHORISE
     *  cancel.vsp for CANCEL
     */

    protected $sagepay_url_service = array(
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
     * Save the transaction data to storage.
     */

    public function save()
    {
        $this->expandModels();
        return $this->tx_model->save();
    }

    /**
     * Return all the current model data as an array.
     */

    public function toArray()
    {
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
        $this->setField('Amount', $this->formatAmount($amount));
        $this->setField('Currency', $currency);
        return $this;
    }

    /**
     * Set the main details in one go.
     */

    public function setMain($tx_type, $vendor, $amount, $currency, $description, $url)
    {
        $this->setField('TxType', $tx_type);
        $this->setField('Vendor', $vendor);
        $this->setAmount($amount, $currency);
        $this->setField('Description', $description);
        $this->setField('NotificationURL', $url);
        return $this;
    }

    /**
     * Set the platform we are going to use.
     * Platforms are 'test' or 'live' (there is no simulator for V3 protocol).
     */

    public function setPlatform($platform)
    {
        if (isset($this->sagepay_platform[strtolower($platform)])) {
            $this->sagepay_platform = strtolower($platform);
        }

        return $this;
    }

    /**
     * Get the URL for the platform and service selected.
     */

    public function getUrl()
    {
        $sub = '{service}';

        $url_base = $this->sagepay_url_base[$this->sagepay_platform];
        $service = $this->sagepay_url_service[$this->getField('TxType')][$this->sagepay_platform];

        if (!isset($service)) {
            // No URL - this platform/service is not supported.
            return null;
        } else {
            return str_replace($sub, $service, $url_base);
        }
    }

    
    /**
     * Set the value of a field in the transaction model.
     */

    public function setField($name, $value)
    {
        $this->tx_model->setField($name, $value);
        return $this;
    }

    /**
     * Get the value of a field in the transaction model.
     */

    public function getField($name)
    {
        return $this->tx_model->getField($name);
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
     */

    protected function expandModels()
    {
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
     */

    public function queryData($format_as_querystring = true)
    {
        // Make sure all the models are expanded into the main transaction data model.
        $this->expandModels();

        $query = array();

        // TODO: this could perhaps go into a data file.
        // Loop through the fields, both optional and mandatory.
        $fields_to_send = array(
            'VPSProtocol',
            'TxType',
            'Vendor',
            'VendorTxCode',
            'Amount',
            'Currency',
            'Description',
            'NotificationURL',

            'BillingSurname',
            'BillingFirstnames',
            'BillingAddress1',
            'BillingAddress2',
            'BillingCity',
            'BillingPostCode',
            'BillingCountry',
            'BillingState',
            'BillingPhone',

            'DeliverySurname',
            'DeliveryFirstnames',
            'DeliveryAddress1',
            'DeliveryAddress2',
            'DeliveryCity',
            'DeliveryPostCode',
            'DeliveryCountry',
            'DeliveryState',
            'DeliveryPhone',

            'CustomerEMail',
            'AllowGiftAid',
            'ApplyAVSCV2',
            'Apply3DSecure',
            'Profile',
            'BillingAgreement',
            'AccountType',
            'CreateToken',
            'BasketXML',
            'CustomerXML',
            'SurchargeXML',
            'VendorData',
            'ReferrerID',
            'Language',
            'Website',
        );

        $optional_fields = array(
            'BillingAddress2',
            'BillingState',
            'BillingPhone',
            'DeliveryAddress2',
            'DeliveryState',
            'DeliveryPhone',
            'CustomerEMail',
            'AllowGiftAid',
            'ApplyAVSCV2',
            'Apply3DSecure',
            'Profile',
            'BillingAgreement',
            'AccountType',
            'CreateToken',
            'BasketXML',
            'CustomerXML',
            'SurchargeXML',
            'VendorData',
            'ReferrerID',
            'Language',
            'Website',
        );

        foreach($fields_to_send as $field) {
            $value = $this->tx_model->getField($field);
            if ( ! in_array($field, $optional_fields) || isset($value)) {
                // If the input characterset is UTF-8, then convert the string to ISO-8859-1
                // for transfer to SagePay.
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
     */

    public function postSagePay($sagepay_url, $query_string, $timeout = 30)
    {
        $curlSession = curl_init();

        // Set the URL
        curl_setopt ($curlSession, CURLOPT_URL, $sagepay_url);

        // No headers.
        curl_setopt ($curlSession, CURLOPT_HEADER, 0);

        // It's a POST request
        curl_setopt ($curlSession, CURLOPT_POST, 1);

        // Set the fields for the POST
        curl_setopt ($curlSession, CURLOPT_POSTFIELDS, $query_string);

        // Return it direct, don't print it out
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER,1);

        // This connection will timeout in 30 seconds
        curl_setopt($curlSession, CURLOPT_TIMEOUT, $timeout);

        // The next two lines must be present for the kit to work with newer version of cURL
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 2);

        // Send the request and store the result in an array
        $rawresponse = curl_exec($curlSession);

        // Split response into name=value pairs
        // The documentation states "CRLF" divides the lines. We will use the regex match \R to 
        // catch any platform-specific version of line endings that the future may throw at us.
        $response = preg_split('/$\R?^/m', $rawresponse);

        $output = array();

        // Check that a connection was made
        if (curl_error($curlSession))
        {
            // If it wasn't...
            $output['Status'] = 'FAIL';
            $output['StatusDetail'] = trim(curl_error($curlSession));
        }

        // Close the cURL session
        curl_close($curlSession);

        // Tokenise the response
        // Note: sometimes the response cannot be tokenised
        foreach($response as $value) {
            if (strpos($value, '=') !== false) {
                list($k, $v) = explode('=', $value, 2);
                $output[trim($k)] = trim($v);
            } else {
                $output[] = trim($value);
            }
        }

        return $output;
    }

    /**
     * Send the registration to SagePay and save the reply.
     * TODO: make sure the transactino type is valid (one of three allowed).
     */

    public function sendRegistration()
    {
        // Construct the query string from data in the model.
        $query_string = $this->queryData();

        // Get the URL, which is derived from the platform and the 
        $sagepay_url = $this->getUrl();

        // Post the request to SagePay
        $output = $this->postSagePay($sagepay_url, $query_string, $this->timeout);

        // If successful, save the returned data to the model, save it, then return the model.
        // TODO: include all extra fields that the V3 protocol introduces.
        if ($output['Status'] == 'OK') {
            $this->setField('VPSTxId', $output['VPSTxId']);
            $this->setField('SecurityKey', $output['SecurityKey']);

            // Save the status as PENDING, to indicate we are waing for a response from SagePay.
            $this->setField('Status', 'PENDING');

            $this->setField('StatusDetail', $output['StatusDetail']);

            // CHECKME: Does the next URL need to go into the model? I expect not.
            $this->setField('NextURL', $output['NextURL']);

            // Save the transaction to storage.
            // TODO: catch failures.
            $this->save();
        } else {
            // SagePay has rejected what we have sent.
            // TODO: do we save the transaction at this point? It kind of makes sense. Unless the
            // vendor tx code is saved in the session, even after a failure, then each total
            // failure will result in a new record being writtem to storage, which will give us an
            // audit trail. Perhaps make it an option.
            $this->setField('Status', $output['Status']);
            $this->setField('StatusDetail', $output['StatusDetail']);
        }
    }
}

