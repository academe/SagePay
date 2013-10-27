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
     * TODO: store this data as a PHP array, then it is easier to manipulate
     * without the need to convert. Or maybe convert once and cache.
     */

    public static $data = array (
        'AED' => 
        array (
          'numeric' => '784',
          'alpha' => 'AED',
          'minor' => 2,
          'name' => 'UAE Dirham',
        ),
        'AFN' => 
        array (
          'numeric' => '971',
          'alpha' => 'AFN',
          'minor' => 2,
          'name' => 'Afghani',
        ),
        'ALL' => 
        array (
          'numeric' => '008',
          'alpha' => 'ALL',
          'minor' => 2,
          'name' => 'Lek',
        ),
        'AMD' => 
        array (
          'numeric' => '051',
          'alpha' => 'AMD',
          'minor' => 2,
          'name' => 'Armenian Dram',
        ),
        'ANG' => 
        array (
          'numeric' => '532',
          'alpha' => 'ANG',
          'minor' => 2,
          'name' => 'Netherlands Antillean Guilder',
        ),
        'AOA' => 
        array (
          'numeric' => '973',
          'alpha' => 'AOA',
          'minor' => 2,
          'name' => 'Kwanza',
        ),
        'ARS' => 
        array (
          'numeric' => '032',
          'alpha' => 'ARS',
          'minor' => 2,
          'name' => 'Argentine Peso',
        ),
        'AUD' => 
        array (
          'numeric' => '036',
          'alpha' => 'AUD',
          'minor' => 2,
          'name' => 'Australian Dollar',
        ),
        'AWG' => 
        array (
          'numeric' => '533',
          'alpha' => 'AWG',
          'minor' => 2,
          'name' => 'Aruban Florin',
        ),
        'AZN' => 
        array (
          'numeric' => '944',
          'alpha' => 'AZN',
          'minor' => 2,
          'name' => 'Azerbaijanian Manat',
        ),
        'BAM' => 
        array (
          'numeric' => '977',
          'alpha' => 'BAM',
          'minor' => 2,
          'name' => 'Convertible Mark',
        ),
        'BBD' => 
        array (
          'numeric' => '052',
          'alpha' => 'BBD',
          'minor' => 2,
          'name' => 'Barbados Dollar',
        ),
        'BDT' => 
        array (
          'numeric' => '050',
          'alpha' => 'BDT',
          'minor' => 2,
          'name' => 'Taka',
        ),
        'BGN' => 
        array (
          'numeric' => '975',
          'alpha' => 'BGN',
          'minor' => 2,
          'name' => 'Bulgarian Lev',
        ),
        'BHD' => 
        array (
          'numeric' => '048',
          'alpha' => 'BHD',
          'minor' => 3,
          'name' => 'Bahraini Dinar',
        ),
        'BIF' => 
        array (
          'numeric' => '108',
          'alpha' => 'BIF',
          'minor' => 0,
          'name' => 'Burundi Franc',
        ),
        'BMD' => 
        array (
          'numeric' => '060',
          'alpha' => 'BMD',
          'minor' => 2,
          'name' => 'Bermudian Dollar',
        ),
        'BND' => 
        array (
          'numeric' => '096',
          'alpha' => 'BND',
          'minor' => 2,
          'name' => 'Brunei Dollar',
        ),
        'BOB' => 
        array (
          'numeric' => '068',
          'alpha' => 'BOB',
          'minor' => 2,
          'name' => 'Boliviano',
        ),
        'BOV' => 
        array (
          'numeric' => '984',
          'alpha' => 'BOV',
          'minor' => 2,
          'name' => 'Mvdol',
        ),
        'BRL' => 
        array (
          'numeric' => '986',
          'alpha' => 'BRL',
          'minor' => 2,
          'name' => 'Brazilian Real',
        ),
        'BSD' => 
        array (
          'numeric' => '044',
          'alpha' => 'BSD',
          'minor' => 2,
          'name' => 'Bahamian Dollar',
        ),
        'BTN' => 
        array (
          'numeric' => '064',
          'alpha' => 'BTN',
          'minor' => 2,
          'name' => 'Ngultrum',
        ),
        'BWP' => 
        array (
          'numeric' => '072',
          'alpha' => 'BWP',
          'minor' => 2,
          'name' => 'Pula',
        ),
        'BYR' => 
        array (
          'numeric' => '974',
          'alpha' => 'BYR',
          'minor' => 0,
          'name' => 'Belarussian Ruble',
        ),
        'BZD' => 
        array (
          'numeric' => '084',
          'alpha' => 'BZD',
          'minor' => 2,
          'name' => 'Belize Dollar',
        ),
        'CAD' => 
        array (
          'numeric' => '124',
          'alpha' => 'CAD',
          'minor' => 2,
          'name' => 'Canadian Dollar',
        ),
        'CDF' => 
        array (
          'numeric' => '976',
          'alpha' => 'CDF',
          'minor' => 2,
          'name' => 'Congolese Franc',
        ),
        'CHE' => 
        array (
          'numeric' => '947',
          'alpha' => 'CHE',
          'minor' => 2,
          'name' => 'WIR Euro',
        ),
        'CHF' => 
        array (
          'numeric' => '756',
          'alpha' => 'CHF',
          'minor' => 2,
          'name' => 'Swiss Franc',
        ),
        'CHW' => 
        array (
          'numeric' => '948',
          'alpha' => 'CHW',
          'minor' => 2,
          'name' => 'WIR Franc',
        ),
        'CLF' => 
        array (
          'numeric' => '990',
          'alpha' => 'CLF',
          'minor' => 0,
          'name' => 'Unidades de fomento',
        ),
        'CLP' => 
        array (
          'numeric' => '152',
          'alpha' => 'CLP',
          'minor' => 0,
          'name' => 'Chilean Peso',
        ),
        'CNY' => 
        array (
          'numeric' => '156',
          'alpha' => 'CNY',
          'minor' => 2,
          'name' => 'Yuan Renminbi',
        ),
        'COP' => 
        array (
          'numeric' => '170',
          'alpha' => 'COP',
          'minor' => 2,
          'name' => 'Colombian Peso',
        ),
        'COU' => 
        array (
          'numeric' => '970',
          'alpha' => 'COU',
          'minor' => 2,
          'name' => 'Unidad de Valor Real',
        ),
        'CRC' => 
        array (
          'numeric' => '188',
          'alpha' => 'CRC',
          'minor' => 2,
          'name' => 'Costa Rican Colon',
        ),
        'CUC' => 
        array (
          'numeric' => '931',
          'alpha' => 'CUC',
          'minor' => 2,
          'name' => 'Peso Convertible',
        ),
        'CUP' => 
        array (
          'numeric' => '192',
          'alpha' => 'CUP',
          'minor' => 2,
          'name' => 'Cuban Peso',
        ),
        'CVE' => 
        array (
          'numeric' => '132',
          'alpha' => 'CVE',
          'minor' => 2,
          'name' => 'Cape Verde Escudo',
        ),
        'CZK' => 
        array (
          'numeric' => '203',
          'alpha' => 'CZK',
          'minor' => 2,
          'name' => 'Czech Koruna',
        ),
        'DJF' => 
        array (
          'numeric' => '262',
          'alpha' => 'DJF',
          'minor' => 0,
          'name' => 'Djibouti Franc',
        ),
        'DKK' => 
        array (
          'numeric' => '208',
          'alpha' => 'DKK',
          'minor' => 2,
          'name' => 'Danish Krone',
        ),
        'DOP' => 
        array (
          'numeric' => '214',
          'alpha' => 'DOP',
          'minor' => 2,
          'name' => 'Dominican Peso',
        ),
        'DZD' => 
        array (
          'numeric' => '012',
          'alpha' => 'DZD',
          'minor' => 2,
          'name' => 'Algerian Dinar',
        ),
        'EGP' => 
        array (
          'numeric' => '818',
          'alpha' => 'EGP',
          'minor' => 2,
          'name' => 'Egyptian Pound',
        ),
        'ERN' => 
        array (
          'numeric' => '232',
          'alpha' => 'ERN',
          'minor' => 2,
          'name' => 'Nakfa',
        ),
        'ETB' => 
        array (
          'numeric' => '230',
          'alpha' => 'ETB',
          'minor' => 2,
          'name' => 'Ethiopian Birr',
        ),
        'EUR' => 
        array (
          'numeric' => '978',
          'alpha' => 'EUR',
          'minor' => 2,
          'name' => 'Euro',
        ),
        'FJD' => 
        array (
          'numeric' => '242',
          'alpha' => 'FJD',
          'minor' => 2,
          'name' => 'Fiji Dollar',
        ),
        'FKP' => 
        array (
          'numeric' => '238',
          'alpha' => 'FKP',
          'minor' => 2,
          'name' => 'Falkland Islands Pound',
        ),
        'GBP' => 
        array (
          'numeric' => '826',
          'alpha' => 'GBP',
          'minor' => 2,
          'name' => 'Pound Sterling',
        ),
        'GEL' => 
        array (
          'numeric' => '981',
          'alpha' => 'GEL',
          'minor' => 2,
          'name' => 'Lari',
        ),
        'GHS' => 
        array (
          'numeric' => '936',
          'alpha' => 'GHS',
          'minor' => 2,
          'name' => 'Ghana Cedi',
        ),
        'GIP' => 
        array (
          'numeric' => '292',
          'alpha' => 'GIP',
          'minor' => 2,
          'name' => 'Gibraltar Pound',
        ),
        'GMD' => 
        array (
          'numeric' => '270',
          'alpha' => 'GMD',
          'minor' => 2,
          'name' => 'Dalasi',
        ),
        'GNF' => 
        array (
          'numeric' => '324',
          'alpha' => 'GNF',
          'minor' => 0,
          'name' => 'Guinea Franc',
        ),
        'GTQ' => 
        array (
          'numeric' => '320',
          'alpha' => 'GTQ',
          'minor' => 2,
          'name' => 'Quetzal',
        ),
        'GYD' => 
        array (
          'numeric' => '328',
          'alpha' => 'GYD',
          'minor' => 2,
          'name' => 'Guyana Dollar',
        ),
        'HKD' => 
        array (
          'numeric' => '344',
          'alpha' => 'HKD',
          'minor' => 2,
          'name' => 'Hong Kong Dollar',
        ),
        'HNL' => 
        array (
          'numeric' => '340',
          'alpha' => 'HNL',
          'minor' => 2,
          'name' => 'Lempira',
        ),
        'HRK' => 
        array (
          'numeric' => '191',
          'alpha' => 'HRK',
          'minor' => 2,
          'name' => 'Croatian Kuna',
        ),
        'HTG' => 
        array (
          'numeric' => '332',
          'alpha' => 'HTG',
          'minor' => 2,
          'name' => 'Gourde',
        ),
        'HUF' => 
        array (
          'numeric' => '348',
          'alpha' => 'HUF',
          'minor' => 2,
          'name' => 'Forint',
        ),
        'IDR' => 
        array (
          'numeric' => '360',
          'alpha' => 'IDR',
          'minor' => 2,
          'name' => 'Rupiah',
        ),
        'ILS' => 
        array (
          'numeric' => '376',
          'alpha' => 'ILS',
          'minor' => 2,
          'name' => 'New Israeli Sheqel',
        ),
        'INR' => 
        array (
          'numeric' => '356',
          'alpha' => 'INR',
          'minor' => 2,
          'name' => 'Indian Rupee',
        ),
        'IQD' => 
        array (
          'numeric' => '368',
          'alpha' => 'IQD',
          'minor' => 3,
          'name' => 'Iraqi Dinar',
        ),
        'IRR' => 
        array (
          'numeric' => '364',
          'alpha' => 'IRR',
          'minor' => 2,
          'name' => 'Iranian Rial',
        ),
        'ISK' => 
        array (
          'numeric' => '352',
          'alpha' => 'ISK',
          'minor' => 0,
          'name' => 'Iceland Krona',
        ),
        'JMD' => 
        array (
          'numeric' => '388',
          'alpha' => 'JMD',
          'minor' => 2,
          'name' => 'Jamaican Dollar',
        ),
        'JOD' => 
        array (
          'numeric' => '400',
          'alpha' => 'JOD',
          'minor' => 3,
          'name' => 'Jordanian Dinar',
        ),
        'JPY' => 
        array (
          'numeric' => '392',
          'alpha' => 'JPY',
          'minor' => 0,
          'name' => 'Yen',
        ),
        'KES' => 
        array (
          'numeric' => '404',
          'alpha' => 'KES',
          'minor' => 2,
          'name' => 'Kenyan Shilling',
        ),
        'KGS' => 
        array (
          'numeric' => '417',
          'alpha' => 'KGS',
          'minor' => 2,
          'name' => 'Som',
        ),
        'KHR' => 
        array (
          'numeric' => '116',
          'alpha' => 'KHR',
          'minor' => 2,
          'name' => 'Riel',
        ),
        'KMF' => 
        array (
          'numeric' => '174',
          'alpha' => 'KMF',
          'minor' => 0,
          'name' => 'Comoro Franc',
        ),
        'KPW' => 
        array (
          'numeric' => '408',
          'alpha' => 'KPW',
          'minor' => 2,
          'name' => 'North Korean Won',
        ),
        'KRW' => 
        array (
          'numeric' => '410',
          'alpha' => 'KRW',
          'minor' => 0,
          'name' => 'Won',
        ),
        'KWD' => 
        array (
          'numeric' => '414',
          'alpha' => 'KWD',
          'minor' => 3,
          'name' => 'Kuwaiti Dinar',
        ),
        'KYD' => 
        array (
          'numeric' => '136',
          'alpha' => 'KYD',
          'minor' => 2,
          'name' => 'Cayman Islands Dollar',
        ),
        'KZT' => 
        array (
          'numeric' => '398',
          'alpha' => 'KZT',
          'minor' => 2,
          'name' => 'Tenge',
        ),
        'LAK' => 
        array (
          'numeric' => '418',
          'alpha' => 'LAK',
          'minor' => 2,
          'name' => 'Kip',
        ),
        'LBP' => 
        array (
          'numeric' => '422',
          'alpha' => 'LBP',
          'minor' => 2,
          'name' => 'Lebanese Pound',
        ),
        'LKR' => 
        array (
          'numeric' => '144',
          'alpha' => 'LKR',
          'minor' => 2,
          'name' => 'Sri Lanka Rupee',
        ),
        'LRD' => 
        array (
          'numeric' => '430',
          'alpha' => 'LRD',
          'minor' => 2,
          'name' => 'Liberian Dollar',
        ),
        'LSL' => 
        array (
          'numeric' => '426',
          'alpha' => 'LSL',
          'minor' => 2,
          'name' => 'Loti',
        ),
        'LTL' => 
        array (
          'numeric' => '440',
          'alpha' => 'LTL',
          'minor' => 2,
          'name' => 'Lithuanian Litas',
        ),
        'LVL' => 
        array (
          'numeric' => '428',
          'alpha' => 'LVL',
          'minor' => 2,
          'name' => 'Latvian Lats',
        ),
        'LYD' => 
        array (
          'numeric' => '434',
          'alpha' => 'LYD',
          'minor' => 3,
          'name' => 'Libyan Dinar',
        ),
        'MAD' => 
        array (
          'numeric' => '504',
          'alpha' => 'MAD',
          'minor' => 2,
          'name' => 'Moroccan Dirham',
        ),
        'MDL' => 
        array (
          'numeric' => '498',
          'alpha' => 'MDL',
          'minor' => 2,
          'name' => 'Moldovan Leu',
        ),
        'MGA' => 
        array (
          'numeric' => '969',
          'alpha' => 'MGA',
          'minor' => 2,
          'name' => 'Malagasy Ariary',
        ),
        'MKD' => 
        array (
          'numeric' => '807',
          'alpha' => 'MKD',
          'minor' => 2,
          'name' => 'Denar',
        ),
        'MMK' => 
        array (
          'numeric' => '104',
          'alpha' => 'MMK',
          'minor' => 2,
          'name' => 'Kyat',
        ),
        'MNT' => 
        array (
          'numeric' => '496',
          'alpha' => 'MNT',
          'minor' => 2,
          'name' => 'Tugrik',
        ),
        'MOP' => 
        array (
          'numeric' => '446',
          'alpha' => 'MOP',
          'minor' => 2,
          'name' => 'Pataca',
        ),
        'MRO' => 
        array (
          'numeric' => '478',
          'alpha' => 'MRO',
          'minor' => 2,
          'name' => 'Ouguiya',
        ),
        'MUR' => 
        array (
          'numeric' => '480',
          'alpha' => 'MUR',
          'minor' => 2,
          'name' => 'Mauritius Rupee',
        ),
        'MVR' => 
        array (
          'numeric' => '462',
          'alpha' => 'MVR',
          'minor' => 2,
          'name' => 'Rufiyaa',
        ),
        'MWK' => 
        array (
          'numeric' => '454',
          'alpha' => 'MWK',
          'minor' => 2,
          'name' => 'Kwacha',
        ),
        'MXN' => 
        array (
          'numeric' => '484',
          'alpha' => 'MXN',
          'minor' => 2,
          'name' => 'Mexican Peso',
        ),
        'MXV' => 
        array (
          'numeric' => '979',
          'alpha' => 'MXV',
          'minor' => 2,
          'name' => 'Mexican Unidad de Inversion (UDI)',
        ),
        'MYR' => 
        array (
          'numeric' => '458',
          'alpha' => 'MYR',
          'minor' => 2,
          'name' => 'Malaysian Ringgit',
        ),
        'MZN' => 
        array (
          'numeric' => '943',
          'alpha' => 'MZN',
          'minor' => 2,
          'name' => 'Mozambique Metical',
        ),
        'NAD' => 
        array (
          'numeric' => '516',
          'alpha' => 'NAD',
          'minor' => 2,
          'name' => 'Namibia Dollar',
        ),
        'NGN' => 
        array (
          'numeric' => '566',
          'alpha' => 'NGN',
          'minor' => 2,
          'name' => 'Naira',
        ),
        'NIO' => 
        array (
          'numeric' => '558',
          'alpha' => 'NIO',
          'minor' => 2,
          'name' => 'Cordoba Oro',
        ),
        'NOK' => 
        array (
          'numeric' => '578',
          'alpha' => 'NOK',
          'minor' => 2,
          'name' => 'Norwegian Krone',
        ),
        'NPR' => 
        array (
          'numeric' => '524',
          'alpha' => 'NPR',
          'minor' => 2,
          'name' => 'Nepalese Rupee',
        ),
        'NZD' => 
        array (
          'numeric' => '554',
          'alpha' => 'NZD',
          'minor' => 2,
          'name' => 'New Zealand Dollar',
        ),
        'OMR' => 
        array (
          'numeric' => '512',
          'alpha' => 'OMR',
          'minor' => 3,
          'name' => 'Rial Omani',
        ),
        'PAB' => 
        array (
          'numeric' => '590',
          'alpha' => 'PAB',
          'minor' => 2,
          'name' => 'Balboa',
        ),
        'PEN' => 
        array (
          'numeric' => '604',
          'alpha' => 'PEN',
          'minor' => 2,
          'name' => 'Nuevo Sol',
        ),
        'PGK' => 
        array (
          'numeric' => '598',
          'alpha' => 'PGK',
          'minor' => 2,
          'name' => 'Kina',
        ),
        'PHP' => 
        array (
          'numeric' => '608',
          'alpha' => 'PHP',
          'minor' => 2,
          'name' => 'Philippine Peso',
        ),
        'PKR' => 
        array (
          'numeric' => '586',
          'alpha' => 'PKR',
          'minor' => 2,
          'name' => 'Pakistan Rupee',
        ),
        'PLN' => 
        array (
          'numeric' => '985',
          'alpha' => 'PLN',
          'minor' => 2,
          'name' => 'Zloty',
        ),
        'PYG' => 
        array (
          'numeric' => '600',
          'alpha' => 'PYG',
          'minor' => 0,
          'name' => 'Guarani',
        ),
        'QAR' => 
        array (
          'numeric' => '634',
          'alpha' => 'QAR',
          'minor' => 2,
          'name' => 'Qatari Rial',
        ),
        'RON' => 
        array (
          'numeric' => '946',
          'alpha' => 'RON',
          'minor' => 2,
          'name' => 'New Romanian Leu',
        ),
        'RSD' => 
        array (
          'numeric' => '941',
          'alpha' => 'RSD',
          'minor' => 2,
          'name' => 'Serbian Dinar',
        ),
        'RUB' => 
        array (
          'numeric' => '643',
          'alpha' => 'RUB',
          'minor' => 2,
          'name' => 'Russian Ruble',
        ),
        'RWF' => 
        array (
          'numeric' => '646',
          'alpha' => 'RWF',
          'minor' => 0,
          'name' => 'Rwanda Franc',
        ),
        'SAR' => 
        array (
          'numeric' => '682',
          'alpha' => 'SAR',
          'minor' => 2,
          'name' => 'Saudi Riyal',
        ),
        'SBD' => 
        array (
          'numeric' => '090',
          'alpha' => 'SBD',
          'minor' => 2,
          'name' => 'Solomon Islands Dollar',
        ),
        'SCR' => 
        array (
          'numeric' => '690',
          'alpha' => 'SCR',
          'minor' => 2,
          'name' => 'Seychelles Rupee',
        ),
        'SDG' => 
        array (
          'numeric' => '938',
          'alpha' => 'SDG',
          'minor' => 2,
          'name' => 'Sudanese Pound',
        ),
        'SEK' => 
        array (
          'numeric' => '752',
          'alpha' => 'SEK',
          'minor' => 2,
          'name' => 'Swedish Krona',
        ),
        'SGD' => 
        array (
          'numeric' => '702',
          'alpha' => 'SGD',
          'minor' => 2,
          'name' => 'Singapore Dollar',
        ),
        'SHP' => 
        array (
          'numeric' => '654',
          'alpha' => 'SHP',
          'minor' => 2,
          'name' => 'Saint Helena Pound',
        ),
        'SLL' => 
        array (
          'numeric' => '694',
          'alpha' => 'SLL',
          'minor' => 2,
          'name' => 'Leone',
        ),
        'SOS' => 
        array (
          'numeric' => '706',
          'alpha' => 'SOS',
          'minor' => 2,
          'name' => 'Somali Shilling',
        ),
        'SRD' => 
        array (
          'numeric' => '968',
          'alpha' => 'SRD',
          'minor' => 2,
          'name' => 'Surinam Dollar',
        ),
        'SSP' => 
        array (
          'numeric' => '728',
          'alpha' => 'SSP',
          'minor' => 2,
          'name' => 'South Sudanese Pound',
        ),
        'STD' => 
        array (
          'numeric' => '678',
          'alpha' => 'STD',
          'minor' => 2,
          'name' => 'Dobra',
        ),
        'SVC' => 
        array (
          'numeric' => '222',
          'alpha' => 'SVC',
          'minor' => 2,
          'name' => 'El Salvador Colon',
        ),
        'SYP' => 
        array (
          'numeric' => '760',
          'alpha' => 'SYP',
          'minor' => 2,
          'name' => 'Syrian Pound',
        ),
        'SZL' => 
        array (
          'numeric' => '748',
          'alpha' => 'SZL',
          'minor' => 2,
          'name' => 'Lilangeni',
        ),
        'THB' => 
        array (
          'numeric' => '764',
          'alpha' => 'THB',
          'minor' => 2,
          'name' => 'Baht',
        ),
        'TJS' => 
        array (
          'numeric' => '972',
          'alpha' => 'TJS',
          'minor' => 2,
          'name' => 'Somoni',
        ),
        'TMT' => 
        array (
          'numeric' => '934',
          'alpha' => 'TMT',
          'minor' => 2,
          'name' => 'Turkmenistan New Manat',
        ),
        'TND' => 
        array (
          'numeric' => '788',
          'alpha' => 'TND',
          'minor' => 3,
          'name' => 'Tunisian Dinar',
        ),
        'TOP' => 
        array (
          'numeric' => '776',
          'alpha' => 'TOP',
          'minor' => 2,
          'name' => 'Pa’anga',
        ),
        'TRY' => 
        array (
          'numeric' => '949',
          'alpha' => 'TRY',
          'minor' => 2,
          'name' => 'Turkish Lira',
        ),
        'TTD' => 
        array (
          'numeric' => '780',
          'alpha' => 'TTD',
          'minor' => 2,
          'name' => 'Trinidad and Tobago Dollar',
        ),
        'TWD' => 
        array (
          'numeric' => '901',
          'alpha' => 'TWD',
          'minor' => 2,
          'name' => 'New Taiwan Dollar',
        ),
        'TZS' => 
        array (
          'numeric' => '834',
          'alpha' => 'TZS',
          'minor' => 2,
          'name' => 'Tanzanian Shilling',
        ),
        'UAH' => 
        array (
          'numeric' => '980',
          'alpha' => 'UAH',
          'minor' => 2,
          'name' => 'Hryvnia',
        ),
        'UGX' => 
        array (
          'numeric' => '800',
          'alpha' => 'UGX',
          'minor' => 0,
          'name' => 'Uganda Shilling',
        ),
        'USD' => 
        array (
          'numeric' => '840',
          'alpha' => 'USD',
          'minor' => 2,
          'name' => 'US Dollar',
        ),
        'USN' => 
        array (
          'numeric' => '997',
          'alpha' => 'USN',
          'minor' => 2,
          'name' => 'US Dollar (Next day)',
        ),
        'USS' => 
        array (
          'numeric' => '998',
          'alpha' => 'USS',
          'minor' => 2,
          'name' => 'US Dollar (Same day)',
        ),
        'UYI' => 
        array (
          'numeric' => '940',
          'alpha' => 'UYI',
          'minor' => 0,
          'name' => 'Uruguay Peso en Unidades Indexadas (URUIURUI)',
        ),
        'UYU' => 
        array (
          'numeric' => '858',
          'alpha' => 'UYU',
          'minor' => 2,
          'name' => 'Peso Uruguayo',
        ),
        'UZS' => 
        array (
          'numeric' => '860',
          'alpha' => 'UZS',
          'minor' => 2,
          'name' => 'Uzbekistan Sum',
        ),
        'VEF' => 
        array (
          'numeric' => '937',
          'alpha' => 'VEF',
          'minor' => 2,
          'name' => 'Bolivar',
        ),
        'VND' => 
        array (
          'numeric' => '704',
          'alpha' => 'VND',
          'minor' => 0,
          'name' => 'Dong',
        ),
        'VUV' => 
        array (
          'numeric' => '548',
          'alpha' => 'VUV',
          'minor' => 0,
          'name' => 'Vatu',
        ),
        'WST' => 
        array (
          'numeric' => '882',
          'alpha' => 'WST',
          'minor' => 2,
          'name' => 'Tala',
        ),
        'XAF' => 
        array (
          'numeric' => '950',
          'alpha' => 'XAF',
          'minor' => 0,
          'name' => 'CFA Franc BEAC',
        ),
        'XCD' => 
        array (
          'numeric' => '951',
          'alpha' => 'XCD',
          'minor' => 2,
          'name' => 'East Caribbean Dollar',
        ),
        'XOF' => 
        array (
          'numeric' => '952',
          'alpha' => 'XOF',
          'minor' => 0,
          'name' => 'CFA Franc BCEAO',
        ),
        'XPF' => 
        array (
          'numeric' => '953',
          'alpha' => 'XPF',
          'minor' => 0,
          'name' => 'CFP Franc',
        ),
        'YER' => 
        array (
          'numeric' => '886',
          'alpha' => 'YER',
          'minor' => 2,
          'name' => 'Yemeni Rial',
        ),
        'ZAR' => 
        array (
          'numeric' => '710',
          'alpha' => 'ZAR',
          'minor' => 2,
          'name' => 'Rand',
        ),
        'ZMW' => 
        array (
          'numeric' => '967',
          'alpha' => 'ZMW',
          'minor' => 2,
          'name' => 'Zambian Kwacha',
        ),
        'ZWL' => 
        array (
          'numeric' => '932',
          'alpha' => 'ZWL',
          'minor' => 2,
          'name' => 'Zimbabwe Dollar',
        ),
    );


    /**
     * Return the data.
     * Format is "object", "json" or "array" (default).
     * DEPRECATED - use getCurrency() or getAll() instead.
     */

    public static function get($format = 'array')
    {
        if ($format == 'json') {
            return trim(static::$data_json);
        } elseif ($format == 'array') {
            return static::$data;
        } else {
            return json_decode(json_encode(static::$data), false);
        }
    }

    /**
     * Check if a currency code is valid. Returns true if it does, otherwise false.
     */
    public static function checkCurrency($currency_code)
    {
        return isset(static::$data[$currency_code]);
    }

    /**
     * Get all currencies as an array.
     */
    public static function getAll()
    {
        return static::$data[$currency_code];
    }

    /**
     * Get a single currency, given its code.
     */
    public static function getCurrency($currency_code)
    {
        return (isset(static::$data[$currency_code]) ? static::$data[$currency_code] : null);
    }

    /**
     * Return the minor unit for a currency code.
     */
    public static function minorUnit($currency_code)
    {
        if ($currency = static::getCurrency($currency_code)) {
            return $currency['minor'];
        } else {
            return null;
        }
    }
}

