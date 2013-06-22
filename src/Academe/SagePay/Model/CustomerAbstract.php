<?php

/**
 * Models a basket of products, with an XML output.
 * A basket can have quite a complex structure of elements. Much of the options
 * will not be supported in the first phase.
 */

namespace Academe\SagePay\Model;

abstract class CustomerAbstract extends XmlAbstract
{
    /**
     * Customer details.
     * All details are optional.
     */

    protected $customerMiddleInitial = null;
    protected $customerBirth = null;
    protected $customerWorkPhone = null;
    protected $customerMobilePhone = null;
    protected $previousCust = null;
    protected $timeOnFile = null;
    protected $customerId = null;

    /**
     * Set a field value.
     */

    public function setField($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }

        return $this;
    }

    /**
     * Return the basket as an XML string.
     * There are no attributes in this XML, which makes things a little simpler.
     */

    public function toXml()
    {
        $structure = array();

        if (isset($this->customerMiddleInitial)) {
            $structure['customerMiddleInitial'] = $this->customerMiddleInitial;
        }

        if (isset($this->customerBirth)) {
            $structure['customerBirth'] = $this->customerBirth;
        }

        if (isset($this->customerWorkPhone)) {
            $structure['customerWorkPhone'] = $this->customerWorkPhone;
        }

        if (isset($this->customerMobilePhone)) {
            $structure['customerMobilePhone'] = $this->customerMobilePhone;
        }

        if (isset($this->previousCust)) {
            $structure['previousCust'] = $this->previousCust;
        }

        if (isset($this->timeOnFile)) {
            $structure['timeOnFile'] = $this->timeOnFile;
        }

        if (isset($this->customerId)) {
            $structure['customerId'] = $this->customerId;
        }

        if (!empty($structure)) {
            return $this->xmlFragment(array('customer' => $structure));
        } else {
            return '';
        }
    }
}

