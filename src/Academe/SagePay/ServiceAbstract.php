<?php

/**
 * Common functionality inherited by Server, Direct and Shared services.
 */

namespace Academe\SagePay;

// These are namespaces.
use Academe\SagePay\Metadata as Metadata;
use Academe\SagePay\Exception as Exception;

// Pull in the helper class.
use Academe\SagePay\Helper\Helper;

class ServiceAbstract //extends Model\XmlAbstract
{
    /**
     * The currency for the service.
     */

    protected $currency = null;

    /**
     * The model used to store, retrieve and update the transaction.
     * Note "tx" is "transaction" and not "transmission". Sorry for the duff name.
     */

    protected $tx_model = null;

    /**
     * The transport object, used to talk to SagePay.
     */

    protected $transport_controller = null;

    /**
     * The SagePay method to be used.
     * The method is either 'direct' or 'server' (both make 'shared' services available)
     * or 'shared' (for just the shared services).
     */

    protected $method = 'shared';

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
                'simulator' => null,
                'test' => 'vspdirect-register.vsp',
                'live' => 'vspdirect-register.vsp',
            ),
            'DEFERRED' => array(
                'simulator' => null,
                'test' => 'vspdirect-register.vsp',
                'live' => 'vspdirect-register.vsp',
            ),
            'AUTHENTICATE' => array(
                'simulator' => null,
                'test' => 'vspdirect-register.vsp',
                'live' => 'vspdirect-register.vsp',
            ),
            '3DSECURE' => array(
                'simulator' => null,
                'test' => 'direct3dcallback.vsp',
                'live' => 'direct3dcallback.vsp',
            ),
            'COMPLETE' => array(
                'simulator' => null,
                'test' => 'complete.vsp',
                'live' => 'complete.vsp',
            ),
        ),
        'server' => array(
            'PAYMENT' => array(
                'simulator' => null,
                'test' => 'vspserver-register.vsp',
                'live' => 'vspserver-register.vsp',
            ),
            'DEFERRED' => array(
                'simulator' => null,
                'test' => 'vspserver-register.vsp',
                'live' => 'vspserver-register.vsp',
            ),
            'AUTHENTICATE' => array(
                'simulator' => null,
                'test' => 'vspserver-register.vsp',
                'live' => 'vspserver-register.vsp',
            ),
        ),
        'shared' => array(
            'RELEASE' => array(
                'simulator' => 'VendorReleaseTx',
                'test' => 'release.vsp',
                'live' => 'release.vsp',
            ),
            'ABORT' => array(
                'simulator' => 'VendorAbortTx',
                'test' => 'abort.vsp',
                'live' => 'abort.vsp',
            ),
            'REFUND' => array(
                'simulator' => 'VendorRefundTx',
                'test' => 'refund.vsp',
                'live' => 'refund.vsp',
            ),
            'REPEAT' => array(
                'simulator' => 'VendorRepeatTx',
                'test' => 'repeat.vsp',
                'live' => 'repeat.vsp',
            ),
            'REPEATDEFERRED' => array(
                'simulator' => 'VendorRepeatTx',
                'test' => 'repeat.vsp',
                'live' => 'repeat.vsp',
            ),
            'VOID' => array(
                'simulator' => 'VendorVoidTx',
                'test' => 'void.vsp',
                'live' => 'void.vsp',
            ),
            'MANUAL' => array(
                'simulator' => null,
                'test' => 'manualpayment.vsp',
                'live' => 'manualpayment.vsp',
            ),
            'DIRECTREFUND' => array(
                'simulator' => 'VendorDirectrefundTx',
                'test' => 'directrefund.vsp',
                'live' => 'directrefund.vsp',
            ),
            'AUTHORISE' => array(
                'simulator' => 'VendorAuthoriseTx',
                'test' => 'authorise.vsp',
                'live' => 'authorise.vsp',
            ),
            'CANCEL' => array(
                'simulator' => 'VendorCancelTx',
                'test' => 'cancel.vsp',
                'live' => 'cancel.vsp',
            ),
        ),
    );

    /**
     * The SagePay platform we will connect to - 'test' or 'live', or 'simulator' for
     * some services (not all).
     */

     protected $sagepay_platform = 'test';

    /**
     * The input characterset, i.e. the characterset used by the application.
     * SagePay works only with ISO-8859-1, so conversion may be necessary.
     */

    public $input_charset = 'UTF-8';

    /**
     * The models used to store the shipping and billing addresses.
     * Unlike the XML models (e.g. BasketXML) these expand to multiple fields
     * rather than serialise to a single field. However, maybe these could work
     * the same for storage, but expand when sending to SagePay? It would make
     * the address formats more flexible, but could make searching for transactions
     * more difficult, on an RDBMS at least.
     * At a minimum, these addresses probably belong on the transaction and not here
     * on the serice controller.
     */

    protected $billing_address = null;
    protected $delivery_address = null;

    /**
     * Due to Sagepay's IP restrictions, it may be necessary for some people
     * to set the interface curl communicates over if a server has more than one IP.
     */
     
    protected $interface = null;

    /**
     * Inject the transaction model.
     */

    public function setTransactionModel(Model\TransactionAbstract $tx_model)
    {
        $this->tx_model = $tx_model;

        // Return the transactino model so the caller can continue to set it up.
        return $this->tx_model;
    }

    /**
     * Set the currency for amount formatting.
     * The three-character ISO4217 currency code is required.
     */

    public function setCurrency($currency)
    {
        $currency = strtoupper($currency);

        if ( ! Metadata\Iso4217::checkCurrency($currency)) {
            throw new Exception\InvalidArgumentException("Invalid currency code '{$currency}'");
        }

        $this->currency = $currency;

        return $this;
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
     * shared services.
     * $method defaults to the method selected for the request.
     */

    public function getServices($method = null)
    {
        if ( ! isset($method)) $method = $this->method;

        if ( ! isset($this->sagepay_url_services[$method])) {
            throw new Exception\InvalidArgumentException("Invalid method type '{$method}'");
        }

        $method_services = $this->sagepay_url_services[$method];

        if ($method != 'shared') {
            $method_services = array_merge(
                $method_services,
                $this->sagepay_url_services['shared']
            );
        }

        return $method_services;
    }

    /**
     * Set the method used to access the services - 'direct', 'server' or 'shared'.
     * This is most likely not needed now, as the method is set in the split classes.
     */

    public function setMethod($method)
    {
        $method = strtolower($method);

        // Should these be constants, or an array constant in this class? Making them public
        // kind of makes sense, even if they are unlikely to ever change.

        if ( ! isset($this->sagepay_url_services[$method])) {
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
        $this->setField('BasketXML', $basket);

        // Return the model so the caller can continue to set it up.
        return $basket;
    }

    /**
     * Set the model used for the customer.
     */

    public function setCustomerModel(Model\CustomerAbstract $customer)
    {
        $this->setField('CustomerXML', $customer);

        // Return the model so the caller can continue to set it up.
        return $customer;
    }

    /**
     * Set the model used for the surcharges.
     */

    public function setSurchargeModel(Model\SurchargeAbstract $surcharge)
    {
        $this->setField('SurchargeXML', $surcharge);

        // Return the model so the caller can continue to set it up.
        return $surcharge;
    }

    /**
     * Check a transaction model is set before we attempt to use it.
     */

    protected function checkTxModel()
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
     * Return all the current transaction model data as an array.
     */

    public function toArray()
    {
        $this->checkTxModel();

        $this->expandModels();
        return $this->tx_model->toArray();
    }

    /**
     * Return the vendor transaction ID for the registration.
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

        $this->setField('Amount', Helper::formatAmount($amount, $this->currency));
        $this->setField('Currency', $currency);

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
     * but you can pass in any valid values for testing, without mutating the transaction (is that
     * the right term?).
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
        }
        if ( ! isset($services[$transaction_type][$platform])) {
            throw new Exception\InvalidArgumentException("Transaction type '{$transaction_type}' not supported by platform '{$platform}'");
        }

        $service = $services[$transaction_type][$platform];

        // Replace the service substitution field of the selected base URL with
        // the selected service URL part.

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
     * TODO: implement a "dirty" flag on all the injected data models. Use this to determine
     * whether a model needs to be expanded to XML or individual fields in the transaction.
     * The dirty flag would need to be inspected when doing any getField() on the transaction
     * to make sure the models are expanded if necessary.
     */

    protected function expandModels()
    {
        // Default the billing and delivery address from its opposite number if not set.
        $billing = (is_object($this->billing_address) ? $this->billing_address : $this->delivery_address);
        $delivery = (is_object($this->delivery_address) ? $this->delivery_address : $this->billing_address);

        // Expand the billing address.

        if (is_object($billing)) {
            foreach($billing->toArray() as $field_name => $field_value) {
                $this->setField('Billing' . $field_name, $field_value);
            }
        }

        // Expand the delivery address.

        if (is_object($delivery)) {
            foreach($delivery->toArray() as $field_name => $field_value) {
                $this->setField('Delivery' . $field_name, $field_value);
            }
        }
    }

    /**
     * Collect the query data together for the transaction registration.
     * Returns key/value pairs
     * Different types of transaction will need different sets of fields to
     * be sent to SagePay. This is indicated by the $message_type.
     */

    public function queryData($format_as_querystring = true, $message_type = 'server-registration')
    {
        $this->checkTxModel();

        // Make sure all the models are expanded into the main transaction data model.
        $this->expandModels();

        $query = array();

        // Get the list of fields we want to send to SagePay from the transaction metadata.
        // Filter the list by the message type. This is the superset of fields.

        $all_fields = Metadata\Transaction::get('array', array('source' => $message_type));

        $fields_to_send = array();
        $optional_fields = array();

        foreach($all_fields as $field_name => $field_meta) {
            $fields_to_send[] = $field_name;
            if (empty($field_meta['required'])) $optional_fields[] = $field_name;
        }

        // Some fields will need renaming before sending to SagePay. Do some prelimiary checks first.

        $TxType = $this->getField('TxType');
        $rename_vendor_tx_code = ($TxType == 'RELEASE' || $TxType == 'ABORT' || $TxType == 'VOID' || $TxType == 'CANCEL');

        // Loop through the fields, both optional and mandatory.

        foreach($fields_to_send as $field) {
            $value = $this->getField($field);
            if ( ! in_array($field, $optional_fields) || isset($value)) {
                // If the input characterset is UTF-8, then convert the string to ISO-8859-1
                // for transfer to SagePay.
                // FIXME: catch invalid UTF-8 stream errors. iconv() can easily fail if you
                // pass it duff data.
                // FIXME: don't make any assumptions about what the input chatset could be. It
                // my not be UTF-8 but maystill need conversion to ISO-8859-1

                if ($this->input_charset == 'UTF-8') {
                    $value = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $value);
                }

                // Some fields are sent to SagePay as a different name to their local storage.
                // An important one is VendorTxCode, which is used as the primary key of each transaction,
                // but also as a foreign key reference to linked transactions, but has the same name when
                // sent to SagePay in both cases.
                // But only for some transactino service types. Other services use RelatedVendorTxCode as
                // that same field name.

                if ($field == 'RelatedVendorTxCode' && $rename_vendor_tx_code) {
                    $field = 'VendorTxCode';
                }

                $query[$field] = $value;
            }
        }

        if ($format_as_querystring) {
            // Return the query string
            return http_build_query($query, '', '&');
        } else {
            // Just return the data as an array.
            // This may be more useful for some transport packages that like to construct
            // their own query string..
            return $query;
        }
    }


    /**
     * Set the interface CURL is to use
     */
    public function setInterface($interface)
    {
        return $this->interface = $interface;
    }

    /**
     * Send a POST message to SagePay and collect the result.
     * TODO: move to Transport namespace and a separate injected class.
     */

    public function postSagePay($sagepay_url, $query_string, $timeout = 30)
    {
        $curlSession = curl_init();

        // Set the URL.
        curl_setopt($curlSession, CURLOPT_URL, $sagepay_url);

        // No headers.
        curl_setopt($curlSession, CURLOPT_HEADER, 0);

        // It's a POST request.
        curl_setopt($curlSession, CURLOPT_POST, 1);

        // Set the fields for the POST.
        curl_setopt($curlSession, CURLOPT_POSTFIELDS, $query_string);

        // Return the result without echoing it.
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);

        // This connection will timeout in 30 seconds
        curl_setopt($curlSession, CURLOPT_TIMEOUT, $timeout);

        // The next two lines must be present for this to work with newer versions of cURL
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 2);

        if (!empty($this->interface)) {
            curl_setopt($curlSession, CURLOPT_INTERFACE, $this->interface);
        }

        // Send the request and store the result in an array.
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
    * Returns true if the status of the transaction is one resulting from a
    * successful payment.
    */

    public function isPaymentSuccess()
    {
        $this->checkTxModel();
        return $this->tx_model->isPaymentSuccess();
    }
}
