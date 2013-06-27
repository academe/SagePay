<?php

/**
 * Manages address data.
 */

namespace Academe\SagePay\Model;

abstract class AddressAbstract
{
    /**
     * Fields that constitute an address.
     */

    // Main fields for a simple payment.

    protected $Surname = null;
    protected $Firstnames = null;

    protected $Address1 = null;
    protected $Address2 = null;
    protected $City = null;
    protected $State = null;
    protected $PostCode = null;
    protected $Country = null;

    protected $Phone = null;

    // Additional fields for extending an XML basket line.

    protected $Salutation = null;
    protected $MiddleName = null;
    protected $Email = null;

    /**
     * Set a field value.
     */

    public function setField($name, $value)
    {
        // If the field name begins with "Billing" or "Delivery", then strip the prefix off.
        if (substr($name, 0, 7) == 'Billing') $name = substr($name, 7);
        if (substr($name, 0, 8) == 'Delivery') $name = substr($name, 8);

        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }

        return $this;
    }

    /**
     * Get a field value.
     */

    public function getField($name)
    {
        // If the field name begins with "Billing" or "Delivery", then strip the prefix off.
        if (substr($name, 0, 7) == 'Billing') $name = substr($name, 7);
        if (substr($name, 0, 8) == 'Delivery') $name = substr($name, 8);

        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return null;
    }

    /**
     * Set a field value.
     */

    public function toArray()
    {
        return get_object_vars($this);
    }
}

