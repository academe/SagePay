<?php

/**
 * Used to store, retrieve and update transaction details between pages.
 * Add your own concrete storage methods, such as active record or a direct
 * database table.
 *
 * Data put into these fields should be truncated and formatted correctly.
 * That includes numbers to the correct number of decimal points, text fields
 * truncated to their maximum length (take ml features into account), and
 * charactersets converted (that last one should probably be done in the final
 * transport stage - keep the characterset here in one that the application
 * understands).
 */

namespace Academe\SagePay\Model;

abstract class TransactionAbstract
{
    /**
     * The fields we need to (and would like to) track for a transaction.
     * Tracked fields are saved to storage between pages.
     * The fields are populated from the transaction metadata in the constructor.
     * Fields with a NULL value will not be written to the database at all.
     */

    protected $fields = array();

    /**
     * The reference ID of the partner that referred the vendor to SagePay.
     * Max 40 character.
     * Optional.
     * Supplied in transaction registration.
     */

    protected $ReferrerID = 'academe';

    /**
     * The ISO639-2 2-digit code the language to use.
     * Will default to the user's browser if not supplied.
     * Note: 2-digit codes are not sufficient to cover all languages, so for future-proofing
     * this ought to be used 3-digit codes.
     * Exactly 2 character.
     * e.g. en=English; fr-French; de=German; es=Spanish
     * Optional.
     * Supplied in transaction registration.
     */
    protected $Language = null;

    /**
     * Referenced to the website transaction came from.
     * Max 100 characters.
     * Optional.
     * Supplied in transaction registration.
     */

    protected $Website = null;

     /**
     * The notificatinon URL that SagePay will be using to notify results to the vendor site.
     * Supplied in transaction registration.
     */

    protected $NotificationURL = '';

    /**
     * Indicates the layout format (complete page or simple).
     * Values: NORMAL or LOW
     * Supplied in transaction registration.
     */

    protected $Profile = null;

    /**
     * The status detail message split into a code and a message.
     */

    protected $StatusDetailCode = null;
    protected $StatusDetailMessage = null;

    /**
     * The next URL the user will be sent to, provided by SagePay.
     * This could be to enter payment details for the server method, or to
     * authorise PayPal payment for the direct method.
     */

    protected $NextURL = null;

    /**
     * Some fields for use by SagePay Direct (and not SagePay Server).
     * We don't want these in the standard set of tracked fields for security reasons,
     * though the application may choose to store these fields somewhere else.
     */

    /**
     * Card details.
     * Not required if CardType == "PAYPAL"
     */

    protected $CardHolder = null;
    protected $CardNumber = null;
    protected $ExpiryDate = null;
    protected $CV2 = null;
    protected $CardType = null;

    /**
     * Additional fields we need to handle, but not be saved in storage (at least under these names).
     */

    protected $ReleaseAmount = null;

    /**
     * Use the metadata to set up the fields to be tracked and saved.
     * Further custom fields can be added here if required.
     */

    public function __construct()
    {
        $this->fields = $this->transactionFields();
    }

    /**
     * Add a custom transaction field for storage.
     */

    public function addCustomField($name, $default)
    {
        if ( ! isset($this->fields[$name])) {
            $this->fields[$name] = $default;
        }
    }

    /**
     * Return the list of transaction fields to maintain for the transaction.
     * All these fields will be saved to persistent storage.
     */

    public function transactionFields()
    {
        // We only want the fields that will be stored.
        // Actually, we want all fields as we don't know what we will be using.
        $metadata = \Academe\SagePay\Metadata\Transaction::get('array', array('store' => true));

        $result = array();

        foreach($metadata as $name => $attr) {
            // Get its default value.
            if (isset($attr['default'])) {
                // A default value has been supplied in the metadata.
                $default = $attr['default'];
            } else {
                // Default required fields to an empty string; they will have to be saved.
                $default = !empty($attr['required']) ? '' : null;
            }

            $result[$name] = $default;
        }

        return $result;
    }

    /**
     * Get the SagePay protocol version.
     */

    public function getProtocolVersion()
    {
        return $this->get_field('VPSProtocol');
    }

    /**
     * Get a field value.
     * Check the model field array first, then the class properties.
     */

    public function getField($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }

        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return null;
    }

    /**
     * Split the statusDetail field into separate code and message strings.
     * Returned as an associative array('code'=>'the code', 'message'=>'the message')
     * or a numeric indexed array.
     * A default code of zero means there was no error or a code could not be extracted.
     * returned code will be numeric, so leading zeros will be gone.
     * $index 'associative' or 'numeric'
     */

    public function splitStatusDetail($status_detail, $index = 'associative')
    {
        // Split at the first colon.
        $split = explode(':', $status_detail, 2);

        if (count($split) == 2) {
            list($code, $message) = $split;

            $code = trim($code);
            $message = trim($message);

            // The code should be numeric (hopefully, but who knows without SagePay
            // providing definitive documentation?).
            // Strip out non-numeric characters from the status code, to be safe.

            $code = preg_replace('/[^0-9]/', '', $code);

            if (is_numeric($code)) {
                $code = (int)$code;
            } else{
                $code = 0;
            }
        } else {
            // Could not find a code, so return the original detail as the message.
            $code = 0;
            $message = trim($status_detail);
        }

        if ($index == 'associative') {
            return array('code' => $code, 'message' => $message);
        } else {
            return array($code, $message);
        }
    }

    /**
     * Set a field value.
     * Check if the field exists in the model field array first, then the class properties.
     * Force the value into the field data array or a property using $force='data' or $force='property'
     */

    public function setField($name, $value, $force = null)
    {
        // If setting the StatusDetail, then split this up into a message and code.
        // Status messages may come from other sources, so be prepared that a StatusDetail
        // value is not a simple "{code} : {message}"
        // There us also no documentation that states this format will not change.

        if ($name == 'StatusDetail') {
            list($code, $message) = $this->splitStatusDetail($value, 'numeric');

            $this->setField('StatusDetailCode', $code);
            $this->setField('StatusDetailMessage', $message);
        }

        if (array_key_exists($name, $this->fields) || $force == 'data') {
            $this->fields[$name] = $value;
            return;
        }

        if (property_exists($this, $name) || $force == 'property') {
            $this->{$name} = $value;
            return;
        }

        return null;
    }

    /**
     * Get all fields for saving to the store as an array.
     */

    public function toArray()
    {
        return $this->fields;
    }

    /**
     * Save the transaction record to storage.
     * Return true if successful, false if not.
     * This may involved creating a new record, or may involve updating an existing record.
     */

    abstract public function save();

    /**
     * Find a saved transaction by its VendorTxCode.
     * Returns the TransactionAbstract object or null.
     * TODO: or should it always return $this, with an exception if not found?
     */

    abstract public function find($VendorTxCode);

    /**
     * Make a new VendorTxCode.
     * To be give the code some context, we start it with a timestamp then add
     * on a number based on milliseconds.
     * The VendorTxCode is limited to 40 characters.
     * This is 17 + 13 = 30 characters.
     */

    public function makeVendorTxCode()
    {
        $VendorTxCode = uniqid(date('Ymd-His-'), false);
        return $VendorTxCode;
    }

    /**
     * Returns true if the status of the transaction is one resulting from a
     * successful payment.
     */

    public function isPaymentSuccess()
    {
        // There may be no status at all during testing, so account for that.
        // Credit https://github.com/phillbrown

        $Status = $this->getField('Status');

        return (
            isset($Status)
            && ($Status == 'OK' || $Status == 'AUTHENTICATED' || $Status == 'REGISTERED')
        );
    }
}

