<?php

/**
 * List of ISO4217 currency codes.
 * See: http://en.wikipedia.org/wiki/ISO_4217
 * Source: http://www.currency-iso.org/en/home/tables/table-a1.html
 * Not all will be supported by SagePay.
 */

namespace Academe\SagePay\Metadata;

class Iso4217
{

    /**
     * Currency code list.
     */

    public static $data_json = <<<ENDDATA
        {
          "AED":{
            "numeric":"784",
            "alpha":"AED",
            "minor":2,
            "name":"UAE Dirham"
          },
          "AFN":{
            "numeric":"971",
            "alpha":"AFN",
            "minor":2,
            "name":"Afghani"
          },
          "ALL":{
            "numeric":"008",
            "alpha":"ALL",
            "minor":2,
            "name":"Lek"
          },
          "AMD":{
            "numeric":"051",
            "alpha":"AMD",
            "minor":2,
            "name":"Armenian Dram"
          },
          "ANG":{
            "numeric":"532",
            "alpha":"ANG",
            "minor":2,
            "name":"Netherlands Antillean Guilder"
          },
          "AOA":{
            "numeric":"973",
            "alpha":"AOA",
            "minor":2,
            "name":"Kwanza"
          },
          "ARS":{
            "numeric":"032",
            "alpha":"ARS",
            "minor":2,
            "name":"Argentine Peso"
          },
          "AUD":{
            "numeric":"036",
            "alpha":"AUD",
            "minor":2,
            "name":"Australian Dollar"
          },
          "AWG":{
            "numeric":"533",
            "alpha":"AWG",
            "minor":2,
            "name":"Aruban Florin"
          },
          "AZN":{
            "numeric":"944",
            "alpha":"AZN",
            "minor":2,
            "name":"Azerbaijanian Manat"
          },
          "BAM":{
            "numeric":"977",
            "alpha":"BAM",
            "minor":2,
            "name":"Convertible Mark"
          },
          "BBD":{
            "numeric":"052",
            "alpha":"BBD",
            "minor":2,
            "name":"Barbados Dollar"
          },
          "BDT":{
            "numeric":"050",
            "alpha":"BDT",
            "minor":2,
            "name":"Taka"
          },
          "BGN":{
            "numeric":"975",
            "alpha":"BGN",
            "minor":2,
            "name":"Bulgarian Lev"
          },
          "BHD":{
            "numeric":"048",
            "alpha":"BHD",
            "minor":3,
            "name":"Bahraini Dinar"
          },
          "BIF":{
            "numeric":"108",
            "alpha":"BIF",
            "minor":0,
            "name":"Burundi Franc"
          },
          "BMD":{
            "numeric":"060",
            "alpha":"BMD",
            "minor":2,
            "name":"Bermudian Dollar"
          },
          "BND":{
            "numeric":"096",
            "alpha":"BND",
            "minor":2,
            "name":"Brunei Dollar"
          },
          "BOB":{
            "numeric":"068",
            "alpha":"BOB",
            "minor":2,
            "name":"Boliviano"
          },
          "BOV":{
            "numeric":"984",
            "alpha":"BOV",
            "minor":2,
            "name":"Mvdol"
          },
          "BRL":{
            "numeric":"986",
            "alpha":"BRL",
            "minor":2,
            "name":"Brazilian Real"
          },
          "BSD":{
            "numeric":"044",
            "alpha":"BSD",
            "minor":2,
            "name":"Bahamian Dollar"
          },
          "BTN":{
            "numeric":"064",
            "alpha":"BTN",
            "minor":2,
            "name":"Ngultrum"
          },
          "BWP":{
            "numeric":"072",
            "alpha":"BWP",
            "minor":2,
            "name":"Pula"
          },
          "BYR":{
            "numeric":"974",
            "alpha":"BYR",
            "minor":0,
            "name":"Belarussian Ruble"
          },
          "BZD":{
            "numeric":"084",
            "alpha":"BZD",
            "minor":2,
            "name":"Belize Dollar"
          },
          "CAD":{
            "numeric":"124",
            "alpha":"CAD",
            "minor":2,
            "name":"Canadian Dollar"
          },
          "CDF":{
            "numeric":"976",
            "alpha":"CDF",
            "minor":2,
            "name":"Congolese Franc"
          },
          "CHE":{
            "numeric":"947",
            "alpha":"CHE",
            "minor":2,
            "name":"WIR Euro"
          },
          "CHF":{
            "numeric":"756",
            "alpha":"CHF",
            "minor":2,
            "name":"Swiss Franc"
          },
          "CHW":{
            "numeric":"948",
            "alpha":"CHW",
            "minor":2,
            "name":"WIR Franc"
          },
          "CLF":{
            "numeric":"990",
            "alpha":"CLF",
            "minor":0,
            "name":"Unidades de fomento"
          },
          "CLP":{
            "numeric":"152",
            "alpha":"CLP",
            "minor":0,
            "name":"Chilean Peso"
          },
          "CNY":{
            "numeric":"156",
            "alpha":"CNY",
            "minor":2,
            "name":"Yuan Renminbi"
          },
          "COP":{
            "numeric":"170",
            "alpha":"COP",
            "minor":2,
            "name":"Colombian Peso"
          },
          "COU":{
            "numeric":"970",
            "alpha":"COU",
            "minor":2,
            "name":"Unidad de Valor Real"
          },
          "CRC":{
            "numeric":"188",
            "alpha":"CRC",
            "minor":2,
            "name":"Costa Rican Colon"
          },
          "CUC":{
            "numeric":"931",
            "alpha":"CUC",
            "minor":2,
            "name":"Peso Convertible"
          },
          "CUP":{
            "numeric":"192",
            "alpha":"CUP",
            "minor":2,
            "name":"Cuban Peso"
          },
          "CVE":{
            "numeric":"132",
            "alpha":"CVE",
            "minor":2,
            "name":"Cape Verde Escudo"
          },
          "CZK":{
            "numeric":"203",
            "alpha":"CZK",
            "minor":2,
            "name":"Czech Koruna"
          },
          "DJF":{
            "numeric":"262",
            "alpha":"DJF",
            "minor":0,
            "name":"Djibouti Franc"
          },
          "DKK":{
            "numeric":"208",
            "alpha":"DKK",
            "minor":2,
            "name":"Danish Krone"
          },
          "DOP":{
            "numeric":"214",
            "alpha":"DOP",
            "minor":2,
            "name":"Dominican Peso"
          },
          "DZD":{
            "numeric":"012",
            "alpha":"DZD",
            "minor":2,
            "name":"Algerian Dinar"
          },
          "EGP":{
            "numeric":"818",
            "alpha":"EGP",
            "minor":2,
            "name":"Egyptian Pound"
          },
          "ERN":{
            "numeric":"232",
            "alpha":"ERN",
            "minor":2,
            "name":"Nakfa"
          },
          "ETB":{
            "numeric":"230",
            "alpha":"ETB",
            "minor":2,
            "name":"Ethiopian Birr"
          },
          "EUR":{
            "numeric":"978",
            "alpha":"EUR",
            "minor":2,
            "name":"Euro"
          },
          "FJD":{
            "numeric":"242",
            "alpha":"FJD",
            "minor":2,
            "name":"Fiji Dollar"
          },
          "FKP":{
            "numeric":"238",
            "alpha":"FKP",
            "minor":2,
            "name":"Falkland Islands Pound"
          },
          "GBP":{
            "numeric":"826",
            "alpha":"GBP",
            "minor":2,
            "name":"Pound Sterling"
          },
          "GEL":{
            "numeric":"981",
            "alpha":"GEL",
            "minor":2,
            "name":"Lari"
          },
          "GHS":{
            "numeric":"936",
            "alpha":"GHS",
            "minor":2,
            "name":"Ghana Cedi"
          },
          "GIP":{
            "numeric":"292",
            "alpha":"GIP",
            "minor":2,
            "name":"Gibraltar Pound"
          },
          "GMD":{
            "numeric":"270",
            "alpha":"GMD",
            "minor":2,
            "name":"Dalasi"
          },
          "GNF":{
            "numeric":"324",
            "alpha":"GNF",
            "minor":0,
            "name":"Guinea Franc"
          },
          "GTQ":{
            "numeric":"320",
            "alpha":"GTQ",
            "minor":2,
            "name":"Quetzal"
          },
          "GYD":{
            "numeric":"328",
            "alpha":"GYD",
            "minor":2,
            "name":"Guyana Dollar"
          },
          "HKD":{
            "numeric":"344",
            "alpha":"HKD",
            "minor":2,
            "name":"Hong Kong Dollar"
          },
          "HNL":{
            "numeric":"340",
            "alpha":"HNL",
            "minor":2,
            "name":"Lempira"
          },
          "HRK":{
            "numeric":"191",
            "alpha":"HRK",
            "minor":2,
            "name":"Croatian Kuna"
          },
          "HTG":{
            "numeric":"332",
            "alpha":"HTG",
            "minor":2,
            "name":"Gourde"
          },
          "HUF":{
            "numeric":"348",
            "alpha":"HUF",
            "minor":2,
            "name":"Forint"
          },
          "IDR":{
            "numeric":"360",
            "alpha":"IDR",
            "minor":2,
            "name":"Rupiah"
          },
          "ILS":{
            "numeric":"376",
            "alpha":"ILS",
            "minor":2,
            "name":"New Israeli Sheqel"
          },
          "INR":{
            "numeric":"356",
            "alpha":"INR",
            "minor":2,
            "name":"Indian Rupee"
          },
          "IQD":{
            "numeric":"368",
            "alpha":"IQD",
            "minor":3,
            "name":"Iraqi Dinar"
          },
          "IRR":{
            "numeric":"364",
            "alpha":"IRR",
            "minor":2,
            "name":"Iranian Rial"
          },
          "ISK":{
            "numeric":"352",
            "alpha":"ISK",
            "minor":0,
            "name":"Iceland Krona"
          },
          "JMD":{
            "numeric":"388",
            "alpha":"JMD",
            "minor":2,
            "name":"Jamaican Dollar"
          },
          "JOD":{
            "numeric":"400",
            "alpha":"JOD",
            "minor":3,
            "name":"Jordanian Dinar"
          },
          "JPY":{
            "numeric":"392",
            "alpha":"JPY",
            "minor":0,
            "name":"Yen"
          },
          "KES":{
            "numeric":"404",
            "alpha":"KES",
            "minor":2,
            "name":"Kenyan Shilling"
          },
          "KGS":{
            "numeric":"417",
            "alpha":"KGS",
            "minor":2,
            "name":"Som"
          },
          "KHR":{
            "numeric":"116",
            "alpha":"KHR",
            "minor":2,
            "name":"Riel"
          },
          "KMF":{
            "numeric":"174",
            "alpha":"KMF",
            "minor":0,
            "name":"Comoro Franc"
          },
          "KPW":{
            "numeric":"408",
            "alpha":"KPW",
            "minor":2,
            "name":"North Korean Won"
          },
          "KRW":{
            "numeric":"410",
            "alpha":"KRW",
            "minor":0,
            "name":"Won"
          },
          "KWD":{
            "numeric":"414",
            "alpha":"KWD",
            "minor":3,
            "name":"Kuwaiti Dinar"
          },
          "KYD":{
            "numeric":"136",
            "alpha":"KYD",
            "minor":2,
            "name":"Cayman Islands Dollar"
          },
          "KZT":{
            "numeric":"398",
            "alpha":"KZT",
            "minor":2,
            "name":"Tenge"
          },
          "LAK":{
            "numeric":"418",
            "alpha":"LAK",
            "minor":2,
            "name":"Kip"
          },
          "LBP":{
            "numeric":"422",
            "alpha":"LBP",
            "minor":2,
            "name":"Lebanese Pound"
          },
          "LKR":{
            "numeric":"144",
            "alpha":"LKR",
            "minor":2,
            "name":"Sri Lanka Rupee"
          },
          "LRD":{
            "numeric":"430",
            "alpha":"LRD",
            "minor":2,
            "name":"Liberian Dollar"
          },
          "LSL":{
            "numeric":"426",
            "alpha":"LSL",
            "minor":2,
            "name":"Loti"
          },
          "LTL":{
            "numeric":"440",
            "alpha":"LTL",
            "minor":2,
            "name":"Lithuanian Litas"
          },
          "LVL":{
            "numeric":"428",
            "alpha":"LVL",
            "minor":2,
            "name":"Latvian Lats"
          },
          "LYD":{
            "numeric":"434",
            "alpha":"LYD",
            "minor":3,
            "name":"Libyan Dinar"
          },
          "MAD":{
            "numeric":"504",
            "alpha":"MAD",
            "minor":2,
            "name":"Moroccan Dirham"
          },
          "MDL":{
            "numeric":"498",
            "alpha":"MDL",
            "minor":2,
            "name":"Moldovan Leu"
          },
          "MGA":{
            "numeric":"969",
            "alpha":"MGA",
            "minor":2,
            "name":"Malagasy Ariary"
          },
          "MKD":{
            "numeric":"807",
            "alpha":"MKD",
            "minor":2,
            "name":"Denar"
          },
          "MMK":{
            "numeric":"104",
            "alpha":"MMK",
            "minor":2,
            "name":"Kyat"
          },
          "MNT":{
            "numeric":"496",
            "alpha":"MNT",
            "minor":2,
            "name":"Tugrik"
          },
          "MOP":{
            "numeric":"446",
            "alpha":"MOP",
            "minor":2,
            "name":"Pataca"
          },
          "MRO":{
            "numeric":"478",
            "alpha":"MRO",
            "minor":2,
            "name":"Ouguiya"
          },
          "MUR":{
            "numeric":"480",
            "alpha":"MUR",
            "minor":2,
            "name":"Mauritius Rupee"
          },
          "MVR":{
            "numeric":"462",
            "alpha":"MVR",
            "minor":2,
            "name":"Rufiyaa"
          },
          "MWK":{
            "numeric":"454",
            "alpha":"MWK",
            "minor":2,
            "name":"Kwacha"
          },
          "MXN":{
            "numeric":"484",
            "alpha":"MXN",
            "minor":2,
            "name":"Mexican Peso"
          },
          "MXV":{
            "numeric":"979",
            "alpha":"MXV",
            "minor":2,
            "name":"Mexican Unidad de Inversion (UDI)"
          },
          "MYR":{
            "numeric":"458",
            "alpha":"MYR",
            "minor":2,
            "name":"Malaysian Ringgit"
          },
          "MZN":{
            "numeric":"943",
            "alpha":"MZN",
            "minor":2,
            "name":"Mozambique Metical"
          },
          "NAD":{
            "numeric":"516",
            "alpha":"NAD",
            "minor":2,
            "name":"Namibia Dollar"
          },
          "NGN":{
            "numeric":"566",
            "alpha":"NGN",
            "minor":2,
            "name":"Naira"
          },
          "NIO":{
            "numeric":"558",
            "alpha":"NIO",
            "minor":2,
            "name":"Cordoba Oro"
          },
          "NOK":{
            "numeric":"578",
            "alpha":"NOK",
            "minor":2,
            "name":"Norwegian Krone"
          },
          "NPR":{
            "numeric":"524",
            "alpha":"NPR",
            "minor":2,
            "name":"Nepalese Rupee"
          },
          "NZD":{
            "numeric":"554",
            "alpha":"NZD",
            "minor":2,
            "name":"New Zealand Dollar"
          },
          "OMR":{
            "numeric":"512",
            "alpha":"OMR",
            "minor":3,
            "name":"Rial Omani"
          },
          "PAB":{
            "numeric":"590",
            "alpha":"PAB",
            "minor":2,
            "name":"Balboa"
          },
          "PEN":{
            "numeric":"604",
            "alpha":"PEN",
            "minor":2,
            "name":"Nuevo Sol"
          },
          "PGK":{
            "numeric":"598",
            "alpha":"PGK",
            "minor":2,
            "name":"Kina"
          },
          "PHP":{
            "numeric":"608",
            "alpha":"PHP",
            "minor":2,
            "name":"Philippine Peso"
          },
          "PKR":{
            "numeric":"586",
            "alpha":"PKR",
            "minor":2,
            "name":"Pakistan Rupee"
          },
          "PLN":{
            "numeric":"985",
            "alpha":"PLN",
            "minor":2,
            "name":"Zloty"
          },
          "PYG":{
            "numeric":"600",
            "alpha":"PYG",
            "minor":0,
            "name":"Guarani"
          },
          "QAR":{
            "numeric":"634",
            "alpha":"QAR",
            "minor":2,
            "name":"Qatari Rial"
          },
          "RON":{
            "numeric":"946",
            "alpha":"RON",
            "minor":2,
            "name":"New Romanian Leu"
          },
          "RSD":{
            "numeric":"941",
            "alpha":"RSD",
            "minor":2,
            "name":"Serbian Dinar"
          },
          "RUB":{
            "numeric":"643",
            "alpha":"RUB",
            "minor":2,
            "name":"Russian Ruble"
          },
          "RWF":{
            "numeric":"646",
            "alpha":"RWF",
            "minor":0,
            "name":"Rwanda Franc"
          },
          "SAR":{
            "numeric":"682",
            "alpha":"SAR",
            "minor":2,
            "name":"Saudi Riyal"
          },
          "SBD":{
            "numeric":"090",
            "alpha":"SBD",
            "minor":2,
            "name":"Solomon Islands Dollar"
          },
          "SCR":{
            "numeric":"690",
            "alpha":"SCR",
            "minor":2,
            "name":"Seychelles Rupee"
          },
          "SDG":{
            "numeric":"938",
            "alpha":"SDG",
            "minor":2,
            "name":"Sudanese Pound"
          },
          "SEK":{
            "numeric":"752",
            "alpha":"SEK",
            "minor":2,
            "name":"Swedish Krona"
          },
          "SGD":{
            "numeric":"702",
            "alpha":"SGD",
            "minor":2,
            "name":"Singapore Dollar"
          },
          "SHP":{
            "numeric":"654",
            "alpha":"SHP",
            "minor":2,
            "name":"Saint Helena Pound"
          },
          "SLL":{
            "numeric":"694",
            "alpha":"SLL",
            "minor":2,
            "name":"Leone"
          },
          "SOS":{
            "numeric":"706",
            "alpha":"SOS",
            "minor":2,
            "name":"Somali Shilling"
          },
          "SRD":{
            "numeric":"968",
            "alpha":"SRD",
            "minor":2,
            "name":"Surinam Dollar"
          },
          "SSP":{
            "numeric":"728",
            "alpha":"SSP",
            "minor":2,
            "name":"South Sudanese Pound"
          },
          "STD":{
            "numeric":"678",
            "alpha":"STD",
            "minor":2,
            "name":"Dobra"
          },
          "SVC":{
            "numeric":"222",
            "alpha":"SVC",
            "minor":2,
            "name":"El Salvador Colon"
          },
          "SYP":{
            "numeric":"760",
            "alpha":"SYP",
            "minor":2,
            "name":"Syrian Pound"
          },
          "SZL":{
            "numeric":"748",
            "alpha":"SZL",
            "minor":2,
            "name":"Lilangeni"
          },
          "THB":{
            "numeric":"764",
            "alpha":"THB",
            "minor":2,
            "name":"Baht"
          },
          "TJS":{
            "numeric":"972",
            "alpha":"TJS",
            "minor":2,
            "name":"Somoni"
          },
          "TMT":{
            "numeric":"934",
            "alpha":"TMT",
            "minor":2,
            "name":"Turkmenistan New Manat"
          },
          "TND":{
            "numeric":"788",
            "alpha":"TND",
            "minor":3,
            "name":"Tunisian Dinar"
          },
          "TOP":{
            "numeric":"776",
            "alpha":"TOP",
            "minor":2,
            "name":"Pa’anga"
          },
          "TRY":{
            "numeric":"949",
            "alpha":"TRY",
            "minor":2,
            "name":"Turkish Lira"
          },
          "TTD":{
            "numeric":"780",
            "alpha":"TTD",
            "minor":2,
            "name":"Trinidad and Tobago Dollar"
          },
          "TWD":{
            "numeric":"901",
            "alpha":"TWD",
            "minor":2,
            "name":"New Taiwan Dollar"
          },
          "TZS":{
            "numeric":"834",
            "alpha":"TZS",
            "minor":2,
            "name":"Tanzanian Shilling"
          },
          "UAH":{
            "numeric":"980",
            "alpha":"UAH",
            "minor":2,
            "name":"Hryvnia"
          },
          "UGX":{
            "numeric":"800",
            "alpha":"UGX",
            "minor":0,
            "name":"Uganda Shilling"
          },
          "USD":{
            "numeric":"840",
            "alpha":"USD",
            "minor":2,
            "name":"US Dollar"
          },
          "USN":{
            "numeric":"997",
            "alpha":"USN",
            "minor":2,
            "name":"US Dollar (Next day)"
          },
          "USS":{
            "numeric":"998",
            "alpha":"USS",
            "minor":2,
            "name":"US Dollar (Same day)"
          },
          "UYI":{
            "numeric":"940",
            "alpha":"UYI",
            "minor":0,
            "name":"Uruguay Peso en Unidades Indexadas (URUIURUI)"
          },
          "UYU":{
            "numeric":"858",
            "alpha":"UYU",
            "minor":2,
            "name":"Peso Uruguayo"
          },
          "UZS":{
            "numeric":"860",
            "alpha":"UZS",
            "minor":2,
            "name":"Uzbekistan Sum"
          },
          "VEF":{
            "numeric":"937",
            "alpha":"VEF",
            "minor":2,
            "name":"Bolivar"
          },
          "VND":{
            "numeric":"704",
            "alpha":"VND",
            "minor":0,
            "name":"Dong"
          },
          "VUV":{
            "numeric":"548",
            "alpha":"VUV",
            "minor":0,
            "name":"Vatu"
          },
          "WST":{
            "numeric":"882",
            "alpha":"WST",
            "minor":2,
            "name":"Tala"
          },
          "XAF":{
            "numeric":"950",
            "alpha":"XAF",
            "minor":0,
            "name":"CFA Franc BEAC"
          },
          "XCD":{
            "numeric":"951",
            "alpha":"XCD",
            "minor":2,
            "name":"East Caribbean Dollar"
          },
          "XOF":{
            "numeric":"952",
            "alpha":"XOF",
            "minor":0,
            "name":"CFA Franc BCEAO"
          },
          "XPF":{
            "numeric":"953",
            "alpha":"XPF",
            "minor":0,
            "name":"CFP Franc"
          },
          "YER":{
            "numeric":"886",
            "alpha":"YER",
            "minor":2,
            "name":"Yemeni Rial"
          },
          "ZAR":{
            "numeric":"710",
            "alpha":"ZAR",
            "minor":2,
            "name":"Rand"
          },
          "ZMW":{
            "numeric":"967",
            "alpha":"ZMW",
            "minor":2,
            "name":"Zambian Kwacha"
          },
          "ZWL":{
            "numeric":932,
            "alpha":"ZWL",
            "minor":2,
            "name":"Zimbabwe Dollar"
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

?>
