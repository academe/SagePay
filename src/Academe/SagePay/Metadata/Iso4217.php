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
          'symbol' => '',
        ),
        'AFN' => 
        array (
          'numeric' => '971',
          'alpha' => 'AFN',
          'minor' => 2,
          'name' => 'Afghani',
          'symbol' => '؋',
        ),
        'ALL' => 
        array (
          'numeric' => '008',
          'alpha' => 'ALL',
          'minor' => 2,
          'name' => 'Lek',
          'symbol' => 'Lek',
        ),
        'AMD' => 
        array (
          'numeric' => '051',
          'alpha' => 'AMD',
          'minor' => 2,
          'name' => 'Armenian Dram',
          'symbol' => '',
        ),
        'ANG' => 
        array (
          'numeric' => '532',
          'alpha' => 'ANG',
          'minor' => 2,
          'name' => 'Netherlands Antillean Guilder',
          'symbol' => 'ƒ',
        ),
        'AOA' => 
        array (
          'numeric' => '973',
          'alpha' => 'AOA',
          'minor' => 2,
          'name' => 'Kwanza',
          'symbol' => '',
        ),
        'ARS' => 
        array (
          'numeric' => '032',
          'alpha' => 'ARS',
          'minor' => 2,
          'name' => 'Argentine Peso',
          'symbol' => '$',
        ),
        'AUD' => 
        array (
          'numeric' => '036',
          'alpha' => 'AUD',
          'minor' => 2,
          'name' => 'Australian Dollar',
          'symbol' => '$',
        ),
        'AWG' => 
        array (
          'numeric' => '533',
          'alpha' => 'AWG',
          'minor' => 2,
          'name' => 'Aruban Florin',
          'symbol' => 'ƒ',
        ),
        'AZN' => 
        array (
          'numeric' => '944',
          'alpha' => 'AZN',
          'minor' => 2,
          'name' => 'Azerbaijanian Manat',
          'symbol' => 'ман',
        ),
        'BAM' => 
        array (
          'numeric' => '977',
          'alpha' => 'BAM',
          'minor' => 2,
          'name' => 'Convertible Mark',
          'symbol' => 'KM',
        ),
        'BBD' => 
        array (
          'numeric' => '052',
          'alpha' => 'BBD',
          'minor' => 2,
          'name' => 'Barbados Dollar',
          'symbol' => '$',
        ),
        'BDT' => 
        array (
          'numeric' => '050',
          'alpha' => 'BDT',
          'minor' => 2,
          'name' => 'Taka',
          'symbol' => '',
        ),
        'BGN' => 
        array (
          'numeric' => '975',
          'alpha' => 'BGN',
          'minor' => 2,
          'name' => 'Bulgarian Lev',
          'symbol' => 'лв',
        ),
        'BHD' => 
        array (
          'numeric' => '048',
          'alpha' => 'BHD',
          'minor' => 3,
          'name' => 'Bahraini Dinar',
          'symbol' => '',
        ),
        'BIF' => 
        array (
          'numeric' => '108',
          'alpha' => 'BIF',
          'minor' => 0,
          'name' => 'Burundi Franc',
          'symbol' => '',
        ),
        'BMD' => 
        array (
          'numeric' => '060',
          'alpha' => 'BMD',
          'minor' => 2,
          'name' => 'Bermudian Dollar',
          'symbol' => '$',
        ),
        'BND' => 
        array (
          'numeric' => '096',
          'alpha' => 'BND',
          'minor' => 2,
          'name' => 'Brunei Dollar',
          'symbol' => '$',
        ),
        'BOB' => 
        array (
          'numeric' => '068',
          'alpha' => 'BOB',
          'minor' => 2,
          'name' => 'Boliviano',
          'symbol' => '$b',
        ),
        'BOV' => 
        array (
          'numeric' => '984',
          'alpha' => 'BOV',
          'minor' => 2,
          'name' => 'Mvdol',
          'symbol' => '',
        ),
        'BRL' => 
        array (
          'numeric' => '986',
          'alpha' => 'BRL',
          'minor' => 2,
          'name' => 'Brazilian Real',
          'symbol' => 'R$',
        ),
        'BSD' => 
        array (
          'numeric' => '044',
          'alpha' => 'BSD',
          'minor' => 2,
          'name' => 'Bahamian Dollar',
          'symbol' => '$',
        ),
        'BTN' => 
        array (
          'numeric' => '064',
          'alpha' => 'BTN',
          'minor' => 2,
          'name' => 'Ngultrum',
          'symbol' => '',
        ),
        'BWP' => 
        array (
          'numeric' => '072',
          'alpha' => 'BWP',
          'minor' => 2,
          'name' => 'Pula',
          'symbol' => 'P',
        ),
        'BYR' => 
        array (
          'numeric' => '974',
          'alpha' => 'BYR',
          'minor' => 0,
          'name' => 'Belarussian Ruble',
          'symbol' => 'p.',
        ),
        'BZD' => 
        array (
          'numeric' => '084',
          'alpha' => 'BZD',
          'minor' => 2,
          'name' => 'Belize Dollar',
          'symbol' => 'BZ$',
        ),
        'CAD' => 
        array (
          'numeric' => '124',
          'alpha' => 'CAD',
          'minor' => 2,
          'name' => 'Canadian Dollar',
          'symbol' => '$',
        ),
        'CDF' => 
        array (
          'numeric' => '976',
          'alpha' => 'CDF',
          'minor' => 2,
          'name' => 'Congolese Franc',
          'symbol' => '',
        ),
        'CHE' => 
        array (
          'numeric' => '947',
          'alpha' => 'CHE',
          'minor' => 2,
          'name' => 'WIR Euro',
          'symbol' => '',
        ),
        'CHF' => 
        array (
          'numeric' => '756',
          'alpha' => 'CHF',
          'minor' => 2,
          'name' => 'Swiss Franc',
          'symbol' => 'CHF',
        ),
        'CHW' => 
        array (
          'numeric' => '948',
          'alpha' => 'CHW',
          'minor' => 2,
          'name' => 'WIR Franc',
          'symbol' => '',
        ),
        'CLF' => 
        array (
          'numeric' => '990',
          'alpha' => 'CLF',
          'minor' => 0,
          'name' => 'Unidades de fomento',
          'symbol' => '',
        ),
        'CLP' => 
        array (
          'numeric' => '152',
          'alpha' => 'CLP',
          'minor' => 0,
          'name' => 'Chilean Peso',
          'symbol' => '$',
        ),
        'CNY' => 
        array (
          'numeric' => '156',
          'alpha' => 'CNY',
          'minor' => 2,
          'name' => 'Yuan Renminbi',
          'symbol' => '¥',
        ),
        'COP' => 
        array (
          'numeric' => '170',
          'alpha' => 'COP',
          'minor' => 2,
          'name' => 'Colombian Peso',
          'symbol' => '$',
        ),
        'COU' => 
        array (
          'numeric' => '970',
          'alpha' => 'COU',
          'minor' => 2,
          'name' => 'Unidad de Valor Real',
          'symbol' => '',
        ),
        'CRC' => 
        array (
          'numeric' => '188',
          'alpha' => 'CRC',
          'minor' => 2,
          'name' => 'Costa Rican Colon',
          'symbol' => '₡',
        ),
        'CUC' => 
        array (
          'numeric' => '931',
          'alpha' => 'CUC',
          'minor' => 2,
          'name' => 'Peso Convertible',
          'symbol' => '',
        ),
        'CUP' => 
        array (
          'numeric' => '192',
          'alpha' => 'CUP',
          'minor' => 2,
          'name' => 'Cuban Peso',
          'symbol' => '₱',
        ),
        'CVE' => 
        array (
          'numeric' => '132',
          'alpha' => 'CVE',
          'minor' => 2,
          'name' => 'Cape Verde Escudo',
          'symbol' => '',
        ),
        'CZK' => 
        array (
          'numeric' => '203',
          'alpha' => 'CZK',
          'minor' => 2,
          'name' => 'Czech Koruna',
          'symbol' => 'Kč',
        ),
        'DJF' => 
        array (
          'numeric' => '262',
          'alpha' => 'DJF',
          'minor' => 0,
          'name' => 'Djibouti Franc',
          'symbol' => '',
        ),
        'DKK' => 
        array (
          'numeric' => '208',
          'alpha' => 'DKK',
          'minor' => 2,
          'name' => 'Danish Krone',
          'symbol' => 'kr',
        ),
        'DOP' => 
        array (
          'numeric' => '214',
          'alpha' => 'DOP',
          'minor' => 2,
          'name' => 'Dominican Peso',
          'symbol' => 'RD$',
        ),
        'DZD' => 
        array (
          'numeric' => '012',
          'alpha' => 'DZD',
          'minor' => 2,
          'name' => 'Algerian Dinar',
          'symbol' => '',
        ),
        'EGP' => 
        array (
          'numeric' => '818',
          'alpha' => 'EGP',
          'minor' => 2,
          'name' => 'Egyptian Pound',
          'symbol' => '£',
        ),
        'ERN' => 
        array (
          'numeric' => '232',
          'alpha' => 'ERN',
          'minor' => 2,
          'name' => 'Nakfa',
          'symbol' => '',
        ),
        'ETB' => 
        array (
          'numeric' => '230',
          'alpha' => 'ETB',
          'minor' => 2,
          'name' => 'Ethiopian Birr',
          'symbol' => '',
        ),
        'EUR' => 
        array (
          'numeric' => '978',
          'alpha' => 'EUR',
          'minor' => 2,
          'name' => 'Euro',
          'symbol' => '€',
        ),
        'FJD' => 
        array (
          'numeric' => '242',
          'alpha' => 'FJD',
          'minor' => 2,
          'name' => 'Fiji Dollar',
          'symbol' => '$',
        ),
        'FKP' => 
        array (
          'numeric' => '238',
          'alpha' => 'FKP',
          'minor' => 2,
          'name' => 'Falkland Islands Pound',
          'symbol' => '£',
        ),
        'GBP' => 
        array (
          'numeric' => '826',
          'alpha' => 'GBP',
          'minor' => 2,
          'name' => 'Pound Sterling',
          'symbol' => '£',
        ),
        'GEL' => 
        array (
          'numeric' => '981',
          'alpha' => 'GEL',
          'minor' => 2,
          'name' => 'Lari',
          'symbol' => '',
        ),
        'GHS' => 
        array (
          'numeric' => '936',
          'alpha' => 'GHS',
          'minor' => 2,
          'name' => 'Ghana Cedi',
          'symbol' => '¢',
        ),
        'GIP' => 
        array (
          'numeric' => '292',
          'alpha' => 'GIP',
          'minor' => 2,
          'name' => 'Gibraltar Pound',
          'symbol' => '£',
        ),
        'GMD' => 
        array (
          'numeric' => '270',
          'alpha' => 'GMD',
          'minor' => 2,
          'name' => 'Dalasi',
          'symbol' => '',
        ),
        'GNF' => 
        array (
          'numeric' => '324',
          'alpha' => 'GNF',
          'minor' => 0,
          'name' => 'Guinea Franc',
          'symbol' => '',
        ),
        'GTQ' => 
        array (
          'numeric' => '320',
          'alpha' => 'GTQ',
          'minor' => 2,
          'name' => 'Quetzal',
          'symbol' => 'Q',
        ),
        'GYD' => 
        array (
          'numeric' => '328',
          'alpha' => 'GYD',
          'minor' => 2,
          'name' => 'Guyana Dollar',
          'symbol' => '$',
        ),
        'HKD' => 
        array (
          'numeric' => '344',
          'alpha' => 'HKD',
          'minor' => 2,
          'name' => 'Hong Kong Dollar',
          'symbol' => '$',
        ),
        'HNL' => 
        array (
          'numeric' => '340',
          'alpha' => 'HNL',
          'minor' => 2,
          'name' => 'Lempira',
          'symbol' => 'L',
        ),
        'HRK' => 
        array (
          'numeric' => '191',
          'alpha' => 'HRK',
          'minor' => 2,
          'name' => 'Croatian Kuna',
          'symbol' => 'kn',
        ),
        'HTG' => 
        array (
          'numeric' => '332',
          'alpha' => 'HTG',
          'minor' => 2,
          'name' => 'Gourde',
          'symbol' => '',
        ),
        'HUF' => 
        array (
          'numeric' => '348',
          'alpha' => 'HUF',
          'minor' => 2,
          'name' => 'Forint',
          'symbol' => 'Ft',
        ),
        'IDR' => 
        array (
          'numeric' => '360',
          'alpha' => 'IDR',
          'minor' => 2,
          'name' => 'Rupiah',
          'symbol' => 'Rp',
        ),
        'ILS' => 
        array (
          'numeric' => '376',
          'alpha' => 'ILS',
          'minor' => 2,
          'name' => 'New Israeli Sheqel',
          'symbol' => '₪',
        ),
        'INR' => 
        array (
          'numeric' => '356',
          'alpha' => 'INR',
          'minor' => 2,
          'name' => 'Indian Rupee',
          'symbol' => '₹',
        ),
        'IQD' => 
        array (
          'numeric' => '368',
          'alpha' => 'IQD',
          'minor' => 3,
          'name' => 'Iraqi Dinar',
          'symbol' => '',
        ),
        'IRR' => 
        array (
          'numeric' => '364',
          'alpha' => 'IRR',
          'minor' => 2,
          'name' => 'Iranian Rial',
          'symbol' => '﷼',
        ),
        'ISK' => 
        array (
          'numeric' => '352',
          'alpha' => 'ISK',
          'minor' => 0,
          'name' => 'Iceland Krona',
          'symbol' => 'kr',
        ),
        'JMD' => 
        array (
          'numeric' => '388',
          'alpha' => 'JMD',
          'minor' => 2,
          'name' => 'Jamaican Dollar',
          'symbol' => 'J$',
        ),
        'JOD' => 
        array (
          'numeric' => '400',
          'alpha' => 'JOD',
          'minor' => 3,
          'name' => 'Jordanian Dinar',
          'symbol' => '',
        ),
        'JPY' => 
        array (
          'numeric' => '392',
          'alpha' => 'JPY',
          'minor' => 0,
          'name' => 'Yen',
          'symbol' => '¥',
        ),
        'KES' => 
        array (
          'numeric' => '404',
          'alpha' => 'KES',
          'minor' => 2,
          'name' => 'Kenyan Shilling',
          'symbol' => '',
        ),
        'KGS' => 
        array (
          'numeric' => '417',
          'alpha' => 'KGS',
          'minor' => 2,
          'name' => 'Som',
          'symbol' => 'лв',
        ),
        'KHR' => 
        array (
          'numeric' => '116',
          'alpha' => 'KHR',
          'minor' => 2,
          'name' => 'Riel',
          'symbol' => '៛',
        ),
        'KMF' => 
        array (
          'numeric' => '174',
          'alpha' => 'KMF',
          'minor' => 0,
          'name' => 'Comoro Franc',
          'symbol' => '',
        ),
        'KPW' => 
        array (
          'numeric' => '408',
          'alpha' => 'KPW',
          'minor' => 2,
          'name' => 'North Korean Won',
          'symbol' => '₩',
        ),
        'KRW' => 
        array (
          'numeric' => '410',
          'alpha' => 'KRW',
          'minor' => 0,
          'name' => 'Won',
          'symbol' => '₩',
        ),
        'KWD' => 
        array (
          'numeric' => '414',
          'alpha' => 'KWD',
          'minor' => 3,
          'name' => 'Kuwaiti Dinar',
          'symbol' => '',
        ),
        'KYD' => 
        array (
          'numeric' => '136',
          'alpha' => 'KYD',
          'minor' => 2,
          'name' => 'Cayman Islands Dollar',
          'symbol' => '$',
        ),
        'KZT' => 
        array (
          'numeric' => '398',
          'alpha' => 'KZT',
          'minor' => 2,
          'name' => 'Tenge',
          'symbol' => 'лв',
        ),
        'LAK' => 
        array (
          'numeric' => '418',
          'alpha' => 'LAK',
          'minor' => 2,
          'name' => 'Kip',
          'symbol' => '₭',
        ),
        'LBP' => 
        array (
          'numeric' => '422',
          'alpha' => 'LBP',
          'minor' => 2,
          'name' => 'Lebanese Pound',
          'symbol' => '£',
        ),
        'LKR' => 
        array (
          'numeric' => '144',
          'alpha' => 'LKR',
          'minor' => 2,
          'name' => 'Sri Lanka Rupee',
          'symbol' => '₨',
        ),
        'LRD' => 
        array (
          'numeric' => '430',
          'alpha' => 'LRD',
          'minor' => 2,
          'name' => 'Liberian Dollar',
          'symbol' => '$',
        ),
        'LSL' => 
        array (
          'numeric' => '426',
          'alpha' => 'LSL',
          'minor' => 2,
          'name' => 'Loti',
          'symbol' => '',
        ),
        'LTL' => 
        array (
          'numeric' => '440',
          'alpha' => 'LTL',
          'minor' => 2,
          'name' => 'Lithuanian Litas',
          'symbol' => 'Lt',
        ),
        'LVL' => 
        array (
          'numeric' => '428',
          'alpha' => 'LVL',
          'minor' => 2,
          'name' => 'Latvian Lats',
          'symbol' => 'Ls',
        ),
        'LYD' => 
        array (
          'numeric' => '434',
          'alpha' => 'LYD',
          'minor' => 3,
          'name' => 'Libyan Dinar',
          'symbol' => '',
        ),
        'MAD' => 
        array (
          'numeric' => '504',
          'alpha' => 'MAD',
          'minor' => 2,
          'name' => 'Moroccan Dirham',
          'symbol' => '',
        ),
        'MDL' => 
        array (
          'numeric' => '498',
          'alpha' => 'MDL',
          'minor' => 2,
          'name' => 'Moldovan Leu',
          'symbol' => '',
        ),
        'MGA' => 
        array (
          'numeric' => '969',
          'alpha' => 'MGA',
          'minor' => 2,
          'name' => 'Malagasy Ariary',
          'symbol' => '',
        ),
        'MKD' => 
        array (
          'numeric' => '807',
          'alpha' => 'MKD',
          'minor' => 2,
          'name' => 'Denar',
          'symbol' => 'ден',
        ),
        'MMK' => 
        array (
          'numeric' => '104',
          'alpha' => 'MMK',
          'minor' => 2,
          'name' => 'Kyat',
          'symbol' => '',
        ),
        'MNT' => 
        array (
          'numeric' => '496',
          'alpha' => 'MNT',
          'minor' => 2,
          'name' => 'Tugrik',
          'symbol' => '₮',
        ),
        'MOP' => 
        array (
          'numeric' => '446',
          'alpha' => 'MOP',
          'minor' => 2,
          'name' => 'Pataca',
          'symbol' => '',
        ),
        'MRO' => 
        array (
          'numeric' => '478',
          'alpha' => 'MRO',
          'minor' => 2,
          'name' => 'Ouguiya',
          'symbol' => '',
        ),
        'MUR' => 
        array (
          'numeric' => '480',
          'alpha' => 'MUR',
          'minor' => 2,
          'name' => 'Mauritius Rupee',
          'symbol' => '₨',
        ),
        'MVR' => 
        array (
          'numeric' => '462',
          'alpha' => 'MVR',
          'minor' => 2,
          'name' => 'Rufiyaa',
          'symbol' => '',
        ),
        'MWK' => 
        array (
          'numeric' => '454',
          'alpha' => 'MWK',
          'minor' => 2,
          'name' => 'Kwacha',
          'symbol' => '',
        ),
        'MXN' => 
        array (
          'numeric' => '484',
          'alpha' => 'MXN',
          'minor' => 2,
          'name' => 'Mexican Peso',
          'symbol' => '$',
        ),
        'MXV' => 
        array (
          'numeric' => '979',
          'alpha' => 'MXV',
          'minor' => 2,
          'name' => 'Mexican Unidad de Inversion (UDI)',
          'symbol' => '',
        ),
        'MYR' => 
        array (
          'numeric' => '458',
          'alpha' => 'MYR',
          'minor' => 2,
          'name' => 'Malaysian Ringgit',
          'symbol' => 'RM',
        ),
        'MZN' => 
        array (
          'numeric' => '943',
          'alpha' => 'MZN',
          'minor' => 2,
          'name' => 'Mozambique Metical',
          'symbol' => 'MT',
        ),
        'NAD' => 
        array (
          'numeric' => '516',
          'alpha' => 'NAD',
          'minor' => 2,
          'name' => 'Namibia Dollar',
          'symbol' => '$',
        ),
        'NGN' => 
        array (
          'numeric' => '566',
          'alpha' => 'NGN',
          'minor' => 2,
          'name' => 'Naira',
          'symbol' => '₦',
        ),
        'NIO' => 
        array (
          'numeric' => '558',
          'alpha' => 'NIO',
          'minor' => 2,
          'name' => 'Cordoba Oro',
          'symbol' => 'C$',
        ),
        'NOK' => 
        array (
          'numeric' => '578',
          'alpha' => 'NOK',
          'minor' => 2,
          'name' => 'Norwegian Krone',
          'symbol' => 'kr',
        ),
        'NPR' => 
        array (
          'numeric' => '524',
          'alpha' => 'NPR',
          'minor' => 2,
          'name' => 'Nepalese Rupee',
          'symbol' => '₨',
        ),
        'NZD' => 
        array (
          'numeric' => '554',
          'alpha' => 'NZD',
          'minor' => 2,
          'name' => 'New Zealand Dollar',
          'symbol' => '$',
        ),
        'OMR' => 
        array (
          'numeric' => '512',
          'alpha' => 'OMR',
          'minor' => 3,
          'name' => 'Rial Omani',
          'symbol' => '﷼',
        ),
        'PAB' => 
        array (
          'numeric' => '590',
          'alpha' => 'PAB',
          'minor' => 2,
          'name' => 'Balboa',
          'symbol' => 'B/.',
        ),
        'PEN' => 
        array (
          'numeric' => '604',
          'alpha' => 'PEN',
          'minor' => 2,
          'name' => 'Nuevo Sol',
          'symbol' => 'S/.',
        ),
        'PGK' => 
        array (
          'numeric' => '598',
          'alpha' => 'PGK',
          'minor' => 2,
          'name' => 'Kina',
          'symbol' => '',
        ),
        'PHP' => 
        array (
          'numeric' => '608',
          'alpha' => 'PHP',
          'minor' => 2,
          'name' => 'Philippine Peso',
          'symbol' => '₱',
        ),
        'PKR' => 
        array (
          'numeric' => '586',
          'alpha' => 'PKR',
          'minor' => 2,
          'name' => 'Pakistan Rupee',
          'symbol' => '₨',
        ),
        'PLN' => 
        array (
          'numeric' => '985',
          'alpha' => 'PLN',
          'minor' => 2,
          'name' => 'Zloty',
          'symbol' => 'zł',
        ),
        'PYG' => 
        array (
          'numeric' => '600',
          'alpha' => 'PYG',
          'minor' => 0,
          'name' => 'Guarani',
          'symbol' => 'Gs',
        ),
        'QAR' => 
        array (
          'numeric' => '634',
          'alpha' => 'QAR',
          'minor' => 2,
          'name' => 'Qatari Rial',
          'symbol' => '﷼',
        ),
        'RON' => 
        array (
          'numeric' => '946',
          'alpha' => 'RON',
          'minor' => 2,
          'name' => 'New Romanian Leu',
          'symbol' => 'lei',
        ),
        'RSD' => 
        array (
          'numeric' => '941',
          'alpha' => 'RSD',
          'minor' => 2,
          'name' => 'Serbian Dinar',
          'symbol' => 'Дин.',
        ),
        'RUB' => 
        array (
          'numeric' => '643',
          'alpha' => 'RUB',
          'minor' => 2,
          'name' => 'Russian Ruble',
          'symbol' => 'руб',
        ),
        'RWF' => 
        array (
          'numeric' => '646',
          'alpha' => 'RWF',
          'minor' => 0,
          'name' => 'Rwanda Franc',
          'symbol' => '',
        ),
        'SAR' => 
        array (
          'numeric' => '682',
          'alpha' => 'SAR',
          'minor' => 2,
          'name' => 'Saudi Riyal',
          'symbol' => '﷼',
        ),
        'SBD' => 
        array (
          'numeric' => '090',
          'alpha' => 'SBD',
          'minor' => 2,
          'name' => 'Solomon Islands Dollar',
          'symbol' => '$',
        ),
        'SCR' => 
        array (
          'numeric' => '690',
          'alpha' => 'SCR',
          'minor' => 2,
          'name' => 'Seychelles Rupee',
          'symbol' => '₨',
        ),
        'SDG' => 
        array (
          'numeric' => '938',
          'alpha' => 'SDG',
          'minor' => 2,
          'name' => 'Sudanese Pound',
          'symbol' => '',
        ),
        'SEK' => 
        array (
          'numeric' => '752',
          'alpha' => 'SEK',
          'minor' => 2,
          'name' => 'Swedish Krona',
          'symbol' => 'kr',
        ),
        'SGD' => 
        array (
          'numeric' => '702',
          'alpha' => 'SGD',
          'minor' => 2,
          'name' => 'Singapore Dollar',
          'symbol' => '$',
        ),
        'SHP' => 
        array (
          'numeric' => '654',
          'alpha' => 'SHP',
          'minor' => 2,
          'name' => 'Saint Helena Pound',
          'symbol' => '£',
        ),
        'SLL' => 
        array (
          'numeric' => '694',
          'alpha' => 'SLL',
          'minor' => 2,
          'name' => 'Leone',
          'symbol' => '',
        ),
        'SOS' => 
        array (
          'numeric' => '706',
          'alpha' => 'SOS',
          'minor' => 2,
          'name' => 'Somali Shilling',
          'symbol' => 'S',
        ),
        'SRD' => 
        array (
          'numeric' => '968',
          'alpha' => 'SRD',
          'minor' => 2,
          'name' => 'Surinam Dollar',
          'symbol' => '$',
        ),
        'SSP' => 
        array (
          'numeric' => '728',
          'alpha' => 'SSP',
          'minor' => 2,
          'name' => 'South Sudanese Pound',
          'symbol' => '',
        ),
        'STD' => 
        array (
          'numeric' => '678',
          'alpha' => 'STD',
          'minor' => 2,
          'name' => 'Dobra',
          'symbol' => '',
        ),
        'SVC' => 
        array (
          'numeric' => '222',
          'alpha' => 'SVC',
          'minor' => 2,
          'name' => 'El Salvador Colon',
          'symbol' => '$',
        ),
        'SYP' => 
        array (
          'numeric' => '760',
          'alpha' => 'SYP',
          'minor' => 2,
          'name' => 'Syrian Pound',
          'symbol' => '£',
        ),
        'SZL' => 
        array (
          'numeric' => '748',
          'alpha' => 'SZL',
          'minor' => 2,
          'name' => 'Lilangeni',
          'symbol' => '',
        ),
        'THB' => 
        array (
          'numeric' => '764',
          'alpha' => 'THB',
          'minor' => 2,
          'name' => 'Baht',
          'symbol' => '฿',
        ),
        'TJS' => 
        array (
          'numeric' => '972',
          'alpha' => 'TJS',
          'minor' => 2,
          'name' => 'Somoni',
          'symbol' => '',
        ),
        'TMT' => 
        array (
          'numeric' => '934',
          'alpha' => 'TMT',
          'minor' => 2,
          'name' => 'Turkmenistan New Manat',
          'symbol' => '',
        ),
        'TND' => 
        array (
          'numeric' => '788',
          'alpha' => 'TND',
          'minor' => 3,
          'name' => 'Tunisian Dinar',
          'symbol' => '',
        ),
        'TOP' => 
        array (
          'numeric' => '776',
          'alpha' => 'TOP',
          'minor' => 2,
          'name' => 'Pa’anga',
          'symbol' => '',
        ),
        'TRY' => 
        array (
          'numeric' => '949',
          'alpha' => 'TRY',
          'minor' => 2,
          'name' => 'Turkish Lira',
          'symbol' => '₤',
        ),
        'TTD' => 
        array (
          'numeric' => '780',
          'alpha' => 'TTD',
          'minor' => 2,
          'name' => 'Trinidad and Tobago Dollar',
          'symbol' => 'TT$',
        ),
        'TWD' => 
        array (
          'numeric' => '901',
          'alpha' => 'TWD',
          'minor' => 2,
          'name' => 'New Taiwan Dollar',
          'symbol' => 'NT$',
        ),
        'TZS' => 
        array (
          'numeric' => '834',
          'alpha' => 'TZS',
          'minor' => 2,
          'name' => 'Tanzanian Shilling',
          'symbol' => '',
        ),
        'UAH' => 
        array (
          'numeric' => '980',
          'alpha' => 'UAH',
          'minor' => 2,
          'name' => 'Hryvnia',
          'symbol' => '₴',
        ),
        'UGX' => 
        array (
          'numeric' => '800',
          'alpha' => 'UGX',
          'minor' => 0,
          'name' => 'Uganda Shilling',
          'symbol' => '',
        ),
        'USD' => 
        array (
          'numeric' => '840',
          'alpha' => 'USD',
          'minor' => 2,
          'name' => 'US Dollar',
          'symbol' => '$',
        ),
        'USN' => 
        array (
          'numeric' => '997',
          'alpha' => 'USN',
          'minor' => 2,
          'name' => 'US Dollar (Next day)',
          'symbol' => '',
        ),
        'USS' => 
        array (
          'numeric' => '998',
          'alpha' => 'USS',
          'minor' => 2,
          'name' => 'US Dollar (Same day)',
          'symbol' => '',
        ),
        'UYI' => 
        array (
          'numeric' => '940',
          'alpha' => 'UYI',
          'minor' => 0,
          'name' => 'Uruguay Peso en Unidades Indexadas (URUIURUI)',
          'symbol' => '',
        ),
        'UYU' => 
        array (
          'numeric' => '858',
          'alpha' => 'UYU',
          'minor' => 2,
          'name' => 'Peso Uruguayo',
          'symbol' => '$U',
        ),
        'UZS' => 
        array (
          'numeric' => '860',
          'alpha' => 'UZS',
          'minor' => 2,
          'name' => 'Uzbekistan Sum',
          'symbol' => 'лв',
        ),
        'VEF' => 
        array (
          'numeric' => '937',
          'alpha' => 'VEF',
          'minor' => 2,
          'name' => 'Bolivar',
          'symbol' => 'Bs',
        ),
        'VND' => 
        array (
          'numeric' => '704',
          'alpha' => 'VND',
          'minor' => 0,
          'name' => 'Dong',
          'symbol' => '₫',
        ),
        'VUV' => 
        array (
          'numeric' => '548',
          'alpha' => 'VUV',
          'minor' => 0,
          'name' => 'Vatu',
          'symbol' => '',
        ),
        'WST' => 
        array (
          'numeric' => '882',
          'alpha' => 'WST',
          'minor' => 2,
          'name' => 'Tala',
          'symbol' => '',
        ),
        'XAF' => 
        array (
          'numeric' => '950',
          'alpha' => 'XAF',
          'minor' => 0,
          'name' => 'CFA Franc BEAC',
          'symbol' => '',
        ),
        'XCD' => 
        array (
          'numeric' => '951',
          'alpha' => 'XCD',
          'minor' => 2,
          'name' => 'East Caribbean Dollar',
          'symbol' => '$',
        ),
        'XOF' => 
        array (
          'numeric' => '952',
          'alpha' => 'XOF',
          'minor' => 0,
          'name' => 'CFA Franc BCEAO',
          'symbol' => '',
        ),
        'XPF' => 
        array (
          'numeric' => '953',
          'alpha' => 'XPF',
          'minor' => 0,
          'name' => 'CFP Franc',
          'symbol' => '',
        ),
        'YER' => 
        array (
          'numeric' => '886',
          'alpha' => 'YER',
          'minor' => 2,
          'name' => 'Yemeni Rial',
          'symbol' => '﷼',
        ),
        'ZAR' => 
        array (
          'numeric' => '710',
          'alpha' => 'ZAR',
          'minor' => 2,
          'name' => 'Rand',
          'symbol' => 'S',
        ),
        'ZMW' => 
        array (
          'numeric' => '967',
          'alpha' => 'ZMW',
          'minor' => 2,
          'name' => 'Zambian Kwacha',
          'symbol' => '',
        ),
        'ZWL' => 
        array (
          'numeric' => '932',
          'alpha' => 'ZWL',
          'minor' => 2,
          'name' => 'Zimbabwe Dollar',
          'symbol' => 'Z$',
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

