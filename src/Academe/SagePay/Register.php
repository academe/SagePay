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
        return $this->tx_model->getField('VendorTxCode');
    }

    /**
     * Set the transaction type.
     * Default in the model is 'PAYMENT'.
     */

    public function setTxType($tx_type)
    {
        $this->tx_model->setField('TxType', strtoupper($tx_type));
        return $this;
    }

    /**
     * Set the vendor.
     */

    public function setVendor($vendor)
    {
        $this->tx_model->setField('Vendor', $vendor);
        return $this;
    }

    /**
     * Set the ammount and currency.
     */

    public function setAmount($amount, $currency = 'GBP')
    {
        $this->tx_model->setField('Amount', $this->formatAmount($amount));
        $this->tx_model->setField('Currency', $currency);
        return $this;
    }

    /**
     * Set the description.
     */

    public function setDescription($description)
    {
        $this->tx_model->setField('Description', $description);
        return $this;
    }

    /**
     * Set the notification URL.
     */

    public function setUrl($url)
    {
        $this->tx_model->setField('NotificationURL', $url);
        return $this;
    }

    /**
     * Set the main details in one go.
     */

    public function setMain($tx_type, $vendor, $amount, $currency, $description, $url)
    {
        $this->setTxType($tx_type);
        $this->setVendor($vendor);
        $this->setAmount($amount, $currency);
        $this->setDescription($description);
        $this->setUrl($url);
        return $this;
    }

    /**
     * The customer email.
     */

    public function setCustomerEMail($email)
    {
        $this->tx_model->setField('CustomerEMail', $email);
        return $this;
    }

    /**
     * Set the Gift Aid flag.
     */

    public function setAllowGiftAid($flag)
    {
        $this->tx_model->setField('AllowGiftAid', $flag);
        return $this;
    }

    /**
     * Set the Apply AVS/CV2 flag.
     */

    public function setApplyAVSCV2($flag)
    {
        $this->tx_model->setField('ApplyAVSCV2', $flag);
        return $this;
    }

    /**
     * Set the Apply 3DSecure flag.
     */

    public function setApply3DSecure($flag)
    {
        $this->tx_model->setField('Apply3DSecure', $flag);
        return $this;
    }

    /**
     * Set the display profile.
     */

    public function setProfile($flag)
    {
        $this->tx_model->setField('Profile', $flag);
        return $this;
    }

    /**
     * Set the Billing Agreement flag.
     */

    public function setBillingAgreement($flag)
    {
        $this->tx_model->setField('BillingAgreement', $flag);
        return $this;
    }

    /**
     * Set the Account Type flag.
     */

    public function setAccountType($flag)
    {
        $this->tx_model->setField('AccountType', $flag);
        return $this;
    }

    /**
     * Set the Create Token flag.
     */

    public function setCreateToken($flag)
    {
        $this->tx_model->setField('CreateToken', $flag);
        return $this;
    }

    /**
     * Set the Vendor Data.
     */

    public function setVendorData($data)
    {
        $this->tx_model->setField('VendorData', $data);
        return $this;
    }

    /**
     * Set the Referrer ID.
     */

    public function setReferrerID($id)
    {
        $this->tx_model->setField('ReferrerID', $id);
        return $this;
    }

    /**
     * Set the Language.
     */

    public function setLanguage($lang)
    {
        $this->tx_model->setField('Language', $lang);
        return $this;
    }

    /**
     * Set the referring Website.
     */

    public function setWebsite($website)
    {
        $this->tx_model->setField('Website', $website);
        return $this;
    }

    /**
     * Set the billing address.
     */

    public function setBillingAddress(Model\AddressAbstract $address)
    {
        $this->billing_address = $address;

        return $this->billing_address;
    }

    /**
     * Set the billing address.
     */

    public function setDeliveryAddress(Model\AddressAbstract $address)
    {
        $this->delivery_address = $address;

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

    public function queryData()
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

        return $query;
    }
}

