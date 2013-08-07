<?php

/**
 * Models a single basket line.
 */

namespace Academe\SagePay\Model;

abstract class ProductLineAbstract extends XmlAbstract
{
    /**
     * Mandatory properties.
     */

    protected $description;
    protected $quantity;
    protected $unitNetAmount;
    protected $unitTaxAmount;
    protected $unitGrossAmount;
    protected $totalGrossAmount;

    /**
     * Optional properties.
     * Structure is idicated by underscores.
     */

    protected $productSku = null;
    protected $productCode = null;

    protected $recipientFName = null;
    protected $recipientLName = null;
    protected $recipientMName = null;
    protected $recipientSal = null;
    protected $recipientEmail = null;
    protected $recipientPhone = null;
    protected $recipientAdd1 = null;
    protected $recipientAdd2 = null;
    protected $recipientCity = null;
    protected $recipientState = null;
    protected $recipientCountry = null;
    protected $recipientPostCode = null;

    protected $itemShipNo = null;
    protected $itemGiftMsg = null;

    protected $currency = 'GBP';

    /**
     * Set the currency for amount formatting.
     */

    public function setCurrency($currency)
    {
        $this->currency = strtoupper($currency);

        return $this;
    }

    /**
     * Set the mandatory items.
     */

    public function setMain($description, $quantity, $unit_net, $unit_tax = 0, $unit_gross = null, $line_gross = null)
    {
        // Fill in some gaps, if required.
        if ( ! isset($unit_gross)) {
            $unit_gross = $unit_net + $unit_tax;
        }

        if ( ! isset($line_gross)) {
            $line_gross = $unit_gross * $quantity;
        }

        // CHECKME: we probably do not need to set the format here, as it is done later when
        // we output the XML. That allows us to set the currency later than would otherwise
        // be necessary.

        $this->description = $description;
        $this->quantity = $quantity;
        $this->unitNetAmount = $this->formatAmount($unit_net, $this->currency);
        $this->unitTaxAmount = $this->formatAmount($unit_tax, $this->currency);
        $this->unitGrossAmount = $this->formatAmount($unit_gross, $this->currency);
        $this->totalGrossAmount = $this->formatAmount($line_gross, $this->currency);

        return $this;
    }

    /**
     * Set a field.
     */

    public function setField($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }

        return $this;
    }

    /**
     * Set the optional product code.
     */

    public function setProductCode($productCode)
    {
        $this->setField('productCode', $productCode);
    }

    /**
     * Set the optional product SKU.
     */

    public function setProductSku($productSku)
    {
        $this->setField('productSku', $productSku);
    }

    /**
     * Set the optional ship number.
     */

    public function setShipNo($itemShipNo)
    {
        $this->setField('itemShipNo', $itemShipNo);
    }

    /**
     * Set the optional gift message.
     */

    public function setGiftMessage($itemGiftMsg)
    {
        $this->setField('itemGiftMsg', $itemGiftMsg);
    }

    /**
     * Set a recipient address.
     */

    public function setAddress(AddressAbstract $address)
    {
        // The main address fields - set these all in one go.
        $this->setField('recipientAdd1', $address->getField('Address1'));
        $this->setField('recipientAdd2', $address->getField('Address2'));
        $this->setField('recipientCity', $address->getField('City'));
        $this->setField('recipientState', $address->getField('State'));
        $this->setField('recipientCountry', $address->getField('Country'));
        $this->setField('recipientPostCode', $address->getField('PostCode'));
        $this->setField('recipientCity', $address->getField('City'));

        // And some optional fields that may be set separately, so don't overwrite
        // if not set in the address record.
        if ($address->getField('Firstnames') !== null) {
            $this->setField('recipientFName', $address->getField('Firstnames'));
        }
        if ($address->getField('Surname') !== null) {
            $this->setField('recipientLName', $address->getField('Surname'));
        }
        if ($address->getField('MiddleName') !== null) {
            $this->setField('recipientMName', $address->getField('MiddleName'));
        }
        if ($address->getField('Email') !== null) {
            $this->setField('recipientEmail', $address->getField('Email'));
        }
        if ($address->getField('Phone') !== null) {
            $this->setField('recipientPhone', $address->getField('Phone'));
        }
    }

    /**
     * Return the product line as a nested array in a structure that mimics
     * the XML format that SagePay expects.
     */

    public function toArray()
    {
        $structure = array();

        // A mix of optional and mandatory fields.
        // The order is important to prevent the basket being rejected as having and
        // "invalid format".

        $structure['description'] = $this->description;
        if (isset($this->productSku)) $structure['productSku'] = $this->productSku;
        if (isset($this->productCode)) $structure['productCode'] = $this->productCode;
        $structure['quantity'] = $this->quantity;
        $structure['unitNetAmount'] = $this->formatAmount($this->unitNetAmount, $this->currency);
        $structure['unitTaxAmount'] = $this->formatAmount($this->unitTaxAmount, $this->currency);
        $structure['unitGrossAmount'] = $this->formatAmount($this->unitGrossAmount, $this->currency);
        $structure['totalGrossAmount'] = $this->formatAmount($this->totalGrossAmount, $this->currency);

        // Note: the SagePay documentation says that accented (i.e. extended ASCII)
        // characters are allowed in the recipient's name. This does, however, result
        // in an "invalid basket format" if you do, at least at the tine of writing.
        // The API may be buggy, or the documenation may be wrong. It is not clear which.

        if (isset($this->recipientFName)) $structure['recipientFName'] = $this->recipientFName;
        if (isset($this->recipientLName)) $structure['recipientLName'] = $this->recipientLName;
        if (isset($this->recipientMName)) $structure['recipientMName'] = $this->recipientMName;
        if (isset($this->recipientSal)) $structure['recipientSal'] = $this->recipientSal;

        if (isset($this->recipientEmail)) $structure['recipientEmail'] = $this->recipientEmail;
        if (isset($this->recipientPhone)) $structure['recipientPhone'] = $this->recipientPhone;
        if (isset($this->recipientAdd1)) $structure['recipientAdd1'] = $this->recipientAdd1;
        if (isset($this->recipientAdd2)) $structure['recipientAdd2'] = $this->recipientAdd2;
        if (isset($this->recipientCity)) $structure['recipientCity'] = $this->recipientCity;
        if (isset($this->recipientState)) $structure['recipientState'] = $this->recipientState;
        if (isset($this->recipientCountry)) $structure['recipientCountry'] = $this->recipientCountry;
        if (isset($this->recipientPostCode)) $structure['recipientPostCode'] = $this->recipientPostCode;

        if (isset($this->itemShipNo)) $structure['itemShipNo'] = $this->itemShipNo;
        if (isset($this->itemGiftMsg)) $structure['itemGiftMsg'] = $this->itemGiftMsg;

        return array('item' => $structure);
    }

    /**
     * Return as an XML line item, to be embedded into a XML basket.
     */

    public function toXml()
    {
        return $this->xmlFragment($this->toArray());
    }
}
