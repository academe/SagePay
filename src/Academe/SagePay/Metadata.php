<?php

/**
 * Metadata for the SagePay data fields.
 */

namespace Academe\SagePay;

class Metadata
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
     *
     * Data types are:
     *  string
     *  integer
     *  currency a monetory value
     *  enum one of a list of values
     *  iso4217 three-digit currency code
     *  iso3166 two-digit country code
     *  html TBC
     *  xml an XML string
     *  rfc1738 a URL
     *  rfc532n an email
     *  iso639-2 two-digit language code
     *  date date in YYYY-MM-DD format
     *  base64 A-Z, a-z, 0-9, _ and /
     *
     * TODO: fields sent in notification callback
     * TODO: fields in surcharge record
     * TODO: fields in basket record
     * TODO: fields in customer record
     */

    public static $data_json = <<<ENDDATA
        {
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
                "values": ["PAYMENT", "DEFERRED", "AUTHENTICATE"],
                "min": 1,
                "max": 15,
                "default": "PAYMENT",
                "source": "registration",
                "notes": "Other values shared with Direct protocol are allowed",
                "store": true
            },
            "Vendor": {
                "required": true,
                "type": "string",
                "min": 1,
                "max": 15,
                "chars": ["A", "a", "9"],
                "source": "registration",
                "store": true
            },
            "VendorTxCode": {
                "required": true,
                "type": "string",
                "min": 1,
                "max": 40,
                "chars": ["A", "a", "9", "{", ".", "-", "_"],
                "source": "registration",
                "store": true
            },
            "Amount": {
                "required": true,
                "type": "currency",
                "min": 100000,
                "max": 0.01,
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
                "chars": ["A"],
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
                "source": "sagepay",
                "store": true
            },
            "StatusDetail": {
                "required": false,
                "type": "enum",
                "values": ["OK", "MALFORMED", "INVALID", "ERROR"],
                "min": 1,
                "max": 255,
                "source": "sagepay",
                "store": true
            },
            "VPSTxId": {
                "required": false,
                "type": "string",
                "min": 38,
                "max": 38,
                "source": "sagepay",
                "store": true
            },
            "SecurityKey": {
                "required": false,
                "type": "string",
                "min": 10,
                "max": 10,
                "source": "sagepay",
                "store": true
            }

        }
ENDDATA;

    public static function get()
    {
        return json_decode(static::$data_json);
    }
}

