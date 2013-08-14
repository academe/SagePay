<?php

/**
 * Metadata for the SagePay data fields.
 * This is pure data, and no functionality that uses this data.
 * Functionality could include:
 *  - creating a database table
 *  - validating data
 *  - cleaning data (e.g. removing invalid characters from a product line descriptio).
 * Note the lengths of fields are in bytes, for a single-character encoding (ISO 8859-1).
 * Your website is likely to use UTF-8, and you may want to store the UTF-8 data in the
 * database, so the database columns must be sized appropriately.
 *
 * FIXME: the validation on currency fields should actually vary, depending on the
 * currency. Some currencies have three decimal places, so can support a wider range
 * of fractional values (e.g. a min value of 0.001 rather than the SagePay documented 0.01).
 */

namespace Academe\SagePay\Metadata;

class Transaction
{
    /**
     * Allowable characters are:
     *  "A" Upper case ASCII letters A-Z
     *  "a" Upper case ASCII letters a-z
     *  "9" Digits 0-9
     *  "^" Extended ASCII letters
     *  " " Space
     *  "/" Forward or backward slashes
     *  "&" Ampersand
     *  "." Full stop/period
     *  "-" Hyphen
     *  "'" Single quote
     *  ":" Colon
     *  "," Comma
     *  "(" Open or close brackets
     *  "{" Open or close curly brackets
     *  "+" Plus
     *  "n" Carriage return and line feed
     *  "&" Ampersand
     *  "*" No restrictions
     *
     * Data types are:
     *  string
     *  integer
     *  currency - a monetory value
     *  enum - one of a list of values
     *  iso4217 - three-digit currency code
     *  iso3166 - two-digit country code
     *  html - TBC
     *  xml - an XML string
     *  rfc1738 - a URL
     *  rfc532n - an email
     *  iso639-2 - two-digit language code
     *  date - a date in YYYY-MM-DD format
     *  base64 - A-Z, a-z, 0-9, _ and /
     *
     * Source can be:
     *  registration - created as a part of the initial transaction registration
     *  notification - received from SageaPay in the notification callback
     *  custom - custom data maintained by the application (use as you like)
     *
     * TODO: fields in surcharge record
     * TODO: fields in basket record
     * TODO: fields in customer record
     *
     * An optional field with a minimum length of 1 must not be sent to SagePay
     * if it is empty, i.e. do not pass an empty string but simply don't send
     * the field at all. An optional field with a minimum length of zero *can*
     * be sent to the payment gateway as an empty string.
     */

    public static $data_json = <<<ENDDATA
        {
            "VendorTxCode": {
                "required": true,
                "type": "string",
                "min": 1,
                "max": 40,
                "chars": ["A", "a", "9", "{", ".", "-", "_"],
                "source": "registration",
                "tamper": true,
                "store": true
            },
            "VPSProtocol": {
                "required": true,
                "type": "string",
                "min": 4,
                "max": 4,
                "default": "3.00",
                "source": "registration",
                "store": true
            },
            "TxType": {
                "required": true,
                "type": "enum",
                "values": ["PAYMENT", "DEFERRED", "AUTHENTICATE", "RELEASE", "ABORT", "REFUND", "REPEAT", "REPEATDEFERRED", "VOID", "MANUAL", "DIRECTREFUND", "AUTHORISE", "CANCEL"],
                "min": 1,
                "max": 15,
                "default": "PAYMENT",
                "source": "registration",
                "store": true
            },
            "Vendor": {
                "required": true,
                "type": "string",
                "min": 1,
                "max": 15,
                "chars": ["A", "a", "9"],
                "source": "registration",
                "tamper": true,
                "store": true
            },
            "Amount": {
                "required": true,
                "type": "currency",
                "min": 0.01,
                "max": 100000,
                "chars": ["9", ",", "."],
                "source": "registration",
                "store": true
            },
            "Currency": {
                "required": true,
                "type": "iso4217",
                "min": 3,
                "max": 3,
                "source": "registration",
                "store": true
            },
            "Description": {
                "required": true,
                "min": 1,
                "max": 100,
                "type": "html",
                "source": "registration",
                "store": true
            },
            "NotificationURL": {
                "required": true,
                "min": 1,
                "max": 255,
                "type": "rfc1738",
                "source": "registration",
                "store": true
            },

            "BillingSurname": {
                "required": true,
                "type": "string",
                "chars": ["A", "a", "^", " ", "/", "&", ".", "-", "'"],
                "min": 1,
                "max": 20,
                "source": "registration",
                "store": true
            },
            "BillingFirstnames": {
                "required": true,
                "type": "string",
                "chars": ["A", "a", "^", " ", "/", "&", ".", "-", "'"],
                "min": 1,
                "max": 20,
                "source": "registration",
                "store": true
            },
            "BillingAddress1": {
                "required": true,
                "type": "string",
                "chars": ["A", "a", "^", "9", " ", "+", "'", "/", "&", ":", ",", ".", "-", "n", "("],
                "min": 1,
                "max": 100,
                "source": "registration",
                "store": true
            },
            "BillingAddress2": {
                "required": false,
                "type": "string",
                "chars": ["A", "a", "^", "9", " ", "+", "'", "/", "&", ":", ",", ".", "-", "n", "("],
                "min": 0,
                "max": 100,
                "source": "registration",
                "store": true
            },
            "BillingCity": {
                "required": true,
                "type": "string",
                "chars": ["A", "a", "^", "9", " ", "+", "'", "/", "&", ":", ",", ".", "-", "n", "("],
                "min": 1,
                "max": 40,
                "source": "registration",
                "store": true
            },
            "BillingPostCode": {
                "required": true,
                "type": "string",
                "chars": ["A", "a", "9", " ", "-"],
                "min": 1,
                "max": 10,
                "source": "registration",
                "store": true
            },
            "BillingCountry": {
                "required": true,
                "type": "iso3166",
                "chars": ["A"],
                "min": 2,
                "max": 2,
                "source": "registration",
                "store": true
            },
            "BillingState": {
                "required": false,
                "type": "us",
                "chars": ["A"],
                "min": 2,
                "max": 2,
                "source": "registration",
                "store": true
            },
            "BillingPhone": {
                "required": false,
                "type": "string",
                "chars": ["9", "-", "A", "a", "+", " ", "("],
                "min": 0,
                "max": 20,
                "source": "registration",
                "store": true
            },

            "DeliverySurname": {
                "required": true,
                "type": "string",
                "chars": ["A", "a", "^", " ", "/", "&", ".", "-", "'"],
                "min": 1,
                "max": 20,
                "source": "registration",
                "store": true
            },
            "DeliveryFirstnames": {
                "required": true,
                "type": "string",
                "chars": ["A", "a", "^", " ", "/", "&", ".", "-", "'"],
                "min": 1,
                "max": 20,
                "source": "registration",
                "store": true
            },
            "DeliveryAddress1": {
                "required": true,
                "type": "string",
                "chars": ["A", "a", "^", "9", " ", "+", "'", "/", "&", ":", ",", ".", "-", "n", "("],
                "min": 1,
                "max": 100,
                "source": "registration",
                "store": true
            },
            "DeliveryAddress2": {
                "required": false,
                "type": "string",
                "chars": ["A", "a", "^", "9", " ", "+", "'", "/", "&", ":", ",", ".", "-", "n", "("],
                "min": 0,
                "max": 100,
                "source": "registration",
                "store": true
            },
            "DeliveryCity": {
                "required": true,
                "type": "string",
                "chars": ["A", "a", "^", "9", " ", "+", "'", "/", "&", ":", ",", ".", "-", "n", "("],
                "min": 1,
                "max": 40,
                "source": "registration",
                "store": true
            },
            "DeliveryPostCode": {
                "required": true,
                "type": "string",
                "chars": ["A", "a", "9", " ", "-"],
                "min": 1,
                "max": 10,
                "source": "registration",
                "store": true
            },
            "DeliveryCountry": {
                "required": true,
                "type": "iso3166",
                "chars": ["A"],
                "min": 2,
                "max": 2,
                "source": "registration",
                "store": true
            },
            "DeliveryState": {
                "required": false,
                "type": "us",
                "chars": ["A"],
                "min": 2,
                "max": 2,
                "source": "registration",
                "store": true
            },
            "DeliveryPhone": {
                "required": false,
                "type": "string",
                "chars": ["9", "-", "A", "a", "+", " ", "("],
                "min": 0,
                "max": 20,
                "source": "registration",
                "store": true
            },

            "CustomerEMail": {
                "required": false,
                "type": "rfc532n",
                "min": 1,
                "max": 255,
                "source": "registration",
                "store": true
            },

            "Basket": {
                "required": false,
                "type": "html",
                "min": 1,
                "max": 7500,
                "source": "registration",
                "store": true
            },

            "AllowGiftAid": {
                "required": false,
                "type": "enum",
                "values": ["0", "1"],
                "default": "0",
                "min": 1,
                "max": 1,
                "source": "registration",
                "store": true
            },
            "ApplyAVSCV2": {
                "required": false,
                "type": "enum",
                "values": ["0", "1", "2", "3"],
                "default": "0",
                "min": 1,
                "max": 1,
                "source": "registration",
                "store": true
            },
            "Apply3DSecure": {
                "required": false,
                "type": "enum",
                "values": ["0", "1", "2", "3"],
                "default": "0",
                "min": 1,
                "max": 1,
                "source": "registration",
                "store": true
            },
            "Profile": {
                "required": false,
                "type": "enum",
                "values": ["NORMAL", "LOW"],
                "default": "NORMAL",
                "min": 3,
                "max": 6,
                "source": "registration",
                "store": true
            },
            "BillingAgreement": {
                "required": false,
                "type": "enum",
                "values": ["0", "1"],
                "default": "0",
                "min": 1,
                "max": 1,
                "source": "registration",
                "store": true
            },
            "AccountType": {
                "required": false,
                "type": "enum",
                "values": ["E", "M", "C"],
                "default": "E",
                "min": 1,
                "max": 1,
                "source": "registration",
                "store": true
            },
            "CreateToken": {
                "required": false,
                "type": "enum",
                "values": ["0", "1"],
                "default": "0",
                "min": 1,
                "max": 1,
                "source": "registration",
                "store": true
            },

            "BasketXML": {
                "required": false,
                "type": "xml",
                "min": 1,
                "max": 20000,
                "source": "registration",
                "store": true
            },
            "CustomerXML": {
                "required": false,
                "type": "xml",
                "min": 1,
                "max": 2000,
                "source": "registration",
                "store": true
            },
            "SurchargeXML": {
                "required": false,
                "type": "xml",
                "min": 1,
                "max": 800,
                "source": "registration",
                "store": true
            },
            "VendorData": {
                "required": false,
                "type": "string",
                "chars": ["9", "A", "a", " "],
                "min": 1,
                "max": 200,
                "source": "registration",
                "store": true
            },
            "ReferrerID": {
                "required": false,
                "type": "string",
                "chars": ["A", "a", "^", "9", " ", "+", "'", "/", "&", ":", ",", ".", "-", "n", "("],
                "min": 1,
                "max": 40,
                "source": "registration",
                "store": true
            },
            "Language": {
                "required": false,
                "type": "iso639-2",
                "chars": ["a"],
                "min": 2,
                "max": 2,
                "source": "registration",
                "store": false
            },
            "Website": {
                "required": false,
                "type": "string",
                "chars": ["A", "a", "^", "9", " ", "+", "'", "/", "&", ":", ",", ".", "-", "n", "("],
                "min": 1,
                "max": 100,
                "source": "registration",
                "store": true
            },

            "Status": {
                "required": false,
                "type": "enum",
                "chars": ["OK", "MALFORMED", "INVALID", "ERROR"],
                "min": 1,
                "max": 14,
                "source": "registration-response",
                "tamper": true,
                "store": true
            },
            "StatusDetail": {
                "required": false,
                "type": "enum",
                "values": ["OK", "MALFORMED", "INVALID", "ERROR"],
                "min": 1,
                "max": 255,
                "source": "registration-response",
                "store": true
            },
            "VPSTxId": {
                "required": false,
                "type": "string",
                "min": 38,
                "max": 38,
                "source": "registration-response",
                "tamper": true,
                "store": true
            },
            "SecurityKey": {
                "required": false,
                "type": "string",
                "min": 10,
                "max": 10,
                "source": "registration-response",
                "store": true
            },
            "TxAuthNo": {
                "required": false,
                "type": "string",
                "chars": ["9"],
                "min": 1,
                "max": 50,
                "source": "notification",
                "tamper": true,
                "store": true
            },
            "AVSCV2": {
                "required": false,
                "type": "enum",
                "values": ["ALL MATCH", "SECURITY CODE MATCH ONLY", "ADDRESS MATCH ONLY", "NO DATA MATCHES", "DATA NOT CHECKED"],
                "min": 1,
                "max": 50,
                "source": "notification",
                "tamper": true,
                "store": true
            },
            "AddressResult": {
                "required": false,
                "type": "enum",
                "values": ["NOTPROVIDED", "NOTCHECKED", "MATCHED", "NOTMATCHED"],
                "min": 1,
                "max": 20,
                "source": "notification",
                "tamper": true,
                "store": true
            },
            "PostCodeResult": {
                "required": false,
                "type": "enum",
                "values": ["NOTPROVIDED", "NOTCHECKED", "MATCHED", "NOTMATCHED"],
                "min": 1,
                "max": 20,
                "source": "notification",
                "tamper": true,
                "store": true
            },
            "CV2Result": {
                "required": false,
                "type": "enum",
                "values": ["NOTPROVIDED", "NOTCHECKED", "MATCHED", "NOTMATCHED"],
                "min": 1,
                "max": 20,
                "source": "notification",
                "tamper": true,
                "store": true
            },
            "GiftAid": {
                "required": false,
                "type": "enum",
                "values": ["0", "1"],
                "min": 1,
                "max": 1,
                "source": "notification",
                "tamper": true,
                "store": true
            },
            "3DSecureStatus": {
                "required": false,
                "type": "enum",
                "values": ["OK", "NOTCHECKED", "NOTAVAILABLE", "NOTAUTHED", "INCOMPLETE", "ERROR", "ATTEMPTONLY"],
                "min": 1,
                "max": 50,
                "source": "notification",
                "tamper": true,
                "store": true
             },
            "CAVV": {
                "required": false,
                "type": "enum",
                "chars": ["A", "a", "^", "9"],
                "min": 1,
                "max": 32,
                "source": "notification",
                "tamper": true,
                "store": true
             },
            "AddressStatus": {
                "required": false,
                "type": "enum",
                "values": ["NONE", "CONFIRMED", "UNCONFIRMED"],
                "min": 1,
                "max": 20,
                "source": "notification",
                "tamper": true,
                "store": true
             },
            "PayerStatus": {
                "required": false,
                "type": "enum",
                "values": ["VERIFIED", "UNVERIFIED"],
                "min": 1,
                "max": 20,
                "source": "notification",
                "tamper": true,
                "store": true
             },
            "CardType": {
                "required": false,
                "type": "enum",
                "values": ["VISA", "MC", "MCDEBIT", "DELTA", "MAESTRO", "UKE", "AMEX", "DC", "JCB", "LASER", "PAYPAL", "EPS", "GIROPAY", "IDEAL", "SOFORT", "ELV"],
                "min": 1,
                "max": 15,
                "source": "notification",
                "store": true
             },
            "Last4Digits": {
                "required": false,
                "type": "string",
                "chars": ["9"],
                "min": 1,
                "max": 4,
                "source": "notification",
                "store": true
             },
            "FraudResponse": {
                "required": false,
                "type": "enum",
                "chars": ["ACCEPT", "CHALLENGE", "DENY", "NOTCHECKED"],
                "min": 1,
                "max": 10,
                "source": "notification",
                "tamper": true,
                "store": true
             },
            "Surcharge": {
                "required": false,
                "type": "currency",
                "chars": ["9", ".", ","],
                "min": 0.01,
                "max": 100000.00,
                "source": "notification",
                "store": true
            },
            "BankAuthCode": {
                "required": false,
                "type": "string",
                "chars": ["9"],
                "min": 1,
                "max": 6,
                "source": "notification",
                "tamper": true,
                "store": true
            },
            "DeclineCode": {
                "required": false,
                "type": "string",
                "chars": ["9"],
                "min": 1,
                "max": 2,
                "source": "notification",
                "tamper": true,
                "store": true
            },
            "ExpiryDate": {
                "required": false,
                "type": "string",
                "chars": ["9"],
                "min": 4,
                "max": 4,
                "source": "notification",
                "tamper": true,
                "store": true
            },
            "Token": {
                "required": false,
                "type": "string",
                "chars": ["A", "a", "9", "-", "{"],
                "min": 38,
                "max": 38,
                "source": "notification",
                "store": true,
                "notes": "GUID format"
            },
            "CustomData": {
                "required": false,
                "type": "string",
                "chars": ["*"],
                "min": 0,
                "max": 4096,
                "source": "custom",
                "store": true,
                "notes": "Custom data to use as you like"
            }
        }
ENDDATA;

    /**
     * Return the data.
     * Format is "object" (default), "json" or "array".
     */

    public static function get($format = 'object')
    {
        if ($format == 'json') {
            return trim(static::$data_json);
        } elseif ($format == 'array') {
            return json_decode(trim(static::$data_json), true); // CHECKME true or false?
        } else {
            return json_decode(trim(static::$data_json));
        }
    }
}

