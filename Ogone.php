<?php
namespace MatthiasMullie\Ogone;

/**
 * Wrapper class to make Ogone implementations a lot easier.
 *
 * @author Matthias Mullie <ogone@mullie.eu>
 * @author Tijs Verkoyen <ogone@verkoyen.eu>
 */
class Ogone
{
    /**
     * Allowed SHA.
     *
     * @var array
     */
    private $allowedSHA = array('SHA1', 'SHA256', 'SHA512');

    /**
     * Will contain more detailed info regarding the processing of Ogone's feedback.
     *
     * @param array
     */
    private $detailed = array();

    /**
     * The environment.
     *
     * @var string
     */
    private $environment = 'prod';

    /**
     * Request method.
     *
     * @var string
     */
    private $method = 'get';

    /**
     * Parameters that will be sent to Ogone.
     *
     * @var array
     */
    private $parametersIn = array();

    /**
     * The maximum length for a variable.
     *
     * @var array
     */
    private $parametersMaximumLength = array('AAVCHECK' => 2,
                                             'ACCEPTANCE' => 15,
                                             'ACCEPTURL' => 200,
                                             'ALIAS' => 50,
                                             'ALIASUSAGE' => 255,
                                             'AMOUNT' => 15,
                                             'BACKURL' => 200,
                                             'BRAND' => 25,
                                             'CANCELURL' => 200,
                                             'CARDNO' => 21,
                                             'CATALOGURL' => 200,
                                             'CCCTY' => 2,
                                             'CN' => 35,
                                             'COM' => 100,
                                             'COMPLUS' => 1000,
                                             'CURRENCY' => 3,
                                             'CVC' => 5,
                                             'CVCCHECK' => 2,
                                             'DECLINEURL' => 200,
                                             'ECI' => 1,
                                             'ECOM_PAYMENT_CARD_VERIFICATION' => 5,
                                             'ED' => 7,
                                             'EMAIL' => 50,
                                             'EXCEPTIONURL' => 200,
                                             'GLOBORDERID' => 15,
                                             'HOMEURL' => 200,
                                             'IPCTY' => 2,
                                             'LANGUAGE' => 5,
                                             'NCERROR' => 8,
                                             'NCERRORPLUS' => 255,
                                             'NCSTATUS' => 4,
                                             'OPERATION' => 3,
                                             'ORDERID' => 30,
                                             'OWNERADDRESS' => 35,
                                             'OWNERCTY' => 2,
                                             'OWNERTELNO' => 30,
                                             'OWNERTOWN' => 25,
                                             'OWNERZIP' => 10,
                                             'PARAMPLUS' => 1000,
                                             'PARAMVAR' => 50,
                                             'PM' => 25,
                                             'PMLIST' => 200,
                                             'PSPID' => 30,
                                             'RTIMEOUT' => 2,
                                             'SCO_CATEGORY' => 1,
                                             'SCORING' => 4,
                                             'STATUS' => 2,
                                             'USERID' => 15,
                                             'VC' => 3,
                                             'WIN3DS' => 6);

    /**
     * Parameters that are received from Ogone.
     *
     * @var array
     */
    private $parametersOut = array();

    /**
     * SHA-IN for Ogone.
     *
     * @var string
     */
    private $SHAIn = '';

    /**
     * SHA-OUT for Ogone.
     *
     * @var string
     */
    private $SHAOut = '';

    /**
     * SHA digest to use.
     *
     * @var string
     */
    private $SHA = '';

    /**
     * List of fields that need to be included in SHA-IN calculation.
     *
     * @var array
     */
    private $SHAInParameters = array('ACCEPTURL',                       'ADDMATCH',                         'ADDRMATCH',
                                     'ALIAS',                           'ALIASOPERATION',                   'ALIASUSAGE',
                                     'ALLOWCORRECTION',                 'AMOUNT',                           'AMOUNTHTVA',
                                     'AMOUNTTVA',                       'BACKURL',                          'BGCOLOR',
                                     'BRAND',                           'BRANDVISUAL',                      'BUTTONBGCOLOR',
                                     'BUTTONTXTCOLOR',                  'CANCELURL',                        'CARDNO',
                                     'CATALOGURL',                      'CERTID',                           'CHECK_AAV',
                                     'CIVILITY',                        'CN',                               'COM',
                                     'COMPLUS',                         'COSTCENTER',                       'CREDITCODE',
                                     'CUID',                            'CURRENCY',                         'CVC',
                                     'DATA',                            'DATATYPE',                         'DATEIN',
                                     'DATEOUT',                         'DECLINEURL',                       'DISCOUNTRATE',
                                     'ECI',                             'ECOM_BILLTO_POSTAL_CITY',          'ECOM_BILLTO_POSTAL_COUNTRYCODE',
                                     'ECOM_BILLTO_POSTAL_NAME_FIRST',   'ECOM_BILLTO_POSTAL_NAME_LAST',     'ECOM_BILLTO_POSTAL_POSTALCODE',
                                     'ECOM_BILLTO_POSTAL_STREET_LINE1', 'ECOM_BILLTO_POSTAL_STREET_LINE2',  'ECOM_BILLTO_POSTAL_STREET_NUMBER',
                                     'ECOM_CONSUMERID',                 'ECOM_CONSUMERORDERID',             'ECOM_CONSUMERUSERALIAS',
                                     'ECOM_PAYMENT_CARD_EXPDATE_MONTH', 'ECOM_PAYMENT_CARD_EXPDATE_YEAR',   'ECOM_PAYMENT_CARD_NAME',
                                     'ECOM_PAYMENT_CARD_VERIFICATION',  'ECOM_SHIPTO_COMPANY',              'ECOM_SHIPTO_DOB',
                                     'ECOM_SHIPTO_ONLINE_EMAIL',        'ECOM_SHIPTO_POSTAL_CITY',          'ECOM_SHIPTO_POSTAL_COUNTRYCODE',
                                     'ECOM_SHIPTO_POSTAL_NAME_FIRST',   'ECOM_SHIPTO_POSTAL_NAME_LAST',     'ECOM_SHIPTO_POSTAL_POSTALCODE',
                                     'ECOM_SHIPTO_POSTAL_STREET_LINE1', 'ECOM_SHIPTO_POSTAL_STREET_LINE2',  'ECOM_SHIPTO_POSTAL_STREET_NUMBER',
                                     'ECOM_SHIPTO_TELECOM_FAX_NUMBER',  'ECOM_SHIPTO_TELECOM_PHONE_NUMBER', 'ECOM_SHIPTO_TVA',
                                     'ED',                              'EMAIL',                            'EXCEPTIONURL',
                                     'EXCLPMLIST',                      'FIRSTCALL',                        'FLAG3D',
                                     'FONTTYPE',                        'FORCECODE1',                       'FORCECODE2',
                                     'FORCECODEHASH',                   'FORCETP',                          'GENERIC_BL',
                                     'GIROPAY_ACCOUNT_NUMBER',          'GIROPAY_BLZ',                      'GIROPAY_OWNER_NAME',
                                     'GLOBORDERID',                     'GUID',                             'HDFONTTYPE',
                                     'HDTBLBGCOLOR',                    'HDTBLTXTCOLOR',                    'HEIGHTFRAME',
                                     'HOMEURL',                         'HTTP_ACCEPT',                      'HTTP_USER_AGENT',
                                     'INCLUDE_BIN',                     'INCLUDE_COUNTRIES',                'INVDATE',
                                     'INVDISCOUNT',                     'INVLEVEL',                         'INVORDERID',
                                     'ISSUERID',                        'LANGUAGE',                         'LEVEL1AUTHCPC',
                                     'LIMITCLIENTSCRIPTUSAGE',          'LINE_REF',                         'LIST_BIN',
                                     'LIST_COUNTRIES',                  'LOGO',                             'MERCHANTID',
                                     'MODE',                            'MTIME',                            'MVER',
                                     'OPERATION',                       'OR_INVORDERID',                    'OR_ORDERID',
                                     'ORDERID',                         'ORIG',                             'OWNERADDRESS',
                                     'OWNERADDRESS2',                   'OWNERCTY',                         'OWNERTELNO',
                                     'OWNERTOWN',                       'OWNERZIP',                         'PAIDAMOUNT',
                                     'PARAMPLUS',                       'PARAMVAR',                         'PAYID',
                                     'PAYMETHOD',                       'PM',                               'PMLIST',
                                     'PMLISTPMLISTTYPE',                'PMLISTTYPE',                       'PMLISTTYPEPMLIST',
                                     'PMTYPE',                          'POPUP',                            'POST',
                                     'PSPID',                           'PSWD',                             'REF',
                                     'REF_CUSTOMERID',                  'REF_CUSTOMERREF',                  'REFER',
                                     'REFID',                           'REFKIND',                          'REMOTE_ADDR',
                                     'REQGENFIELDS',                    'RTIMEOUT',                         'RTIMEOUTREQUESTEDTIMEOUT',
                                     'SCORINGCLIENT',                   'SETT_BATCH',                       'SID',
                                     'TAAL',                            'TBLBGCOLOR',                       'TBLTXTCOLOR',
                                     'TID',                             'TITLE',                            'TOTALAMOUNT',
                                     'TP',                              'TRACK2',                           'TXTBADDR2',
                                     'TXTCOLOR',                        'TXTOKEN',                          'TXTOKENTXTOKENPAYPAL',
                                     'TYPE_COUNTRY',                    'UCAF_AUTHENTICATION_DATA',         'UCAF_PAYMENT_CARD_CVC2',
                                     'UCAF_PAYMENT_CARD_EXPDATE_MONTH', 'UCAF_PAYMENT_CARD_EXPDATE_YEAR',   'UCAF_PAYMENT_CARD_NUMBER',
                                     'USERID',                          'USERTYPE',                         'VERSION',
                                     'WBTU_MSISDN',                     'WBTU_ORDERID',                     'WEIGHTUNIT',
                                     'WIN3DS',                          'WITHROOT');

    /**
     * List of fields that need to be included in SHA-OUT calculation.
     *
     * @var array
     */
    private $SHAOutParameters = array('AAVADDRESS',                        'AAVCHECK',                            'AAVZIP',
                                      'ACCEPTANCE',                        'ALIAS',                            'AMOUNT',
                                      'BRAND',                            'CARDNO',                            'CCCTY',
                                      'SHA-OUT',                            'CN',                                'COMPLUS',
                                      'CURRENCY',                            'CVCCHECK',                            'DCC_COMMPERCENTAGE',
                                      'DCC_CONVAMOUNT',                    'DCC_CONVCCY',                        'DCC_EXCHRATE',
                                      'DCC_EXCHRATESOURCE',                'DCC_EXCHRATETS',                    'DCC_INDICATOR',
                                      'DCC_MARGINPERCENTAGE',                'DCC_VALIDHOUS',                    'DIGESTCARDNO',
                                      'ECI',                                'ED',                                'ENCCARDNO',
                                      'IP',                                'IPCTY',                            'NBREMAILUSAGE',
                                      'NBRIPUSAGE',                        'NBRIPUSAGE_ALLTX',                    'NBRUSAGE',
                                      'NCERROR',                            'ORDERID',                            'PAYID',
                                      'PM',                                'SCO_CATEGORY',                        'SCORING',
                                      'STATUS',                            'TRXDATE',                            'VC');

    /**
     * SHA verification method.
     *
     * @var string
     */
    private $verification = 'each';

    /**
     * Construct Ogone class.
     *
     * @param string           $orderID  Your order number (merchant reference). The system checks that a payment has not been requested twice for the same order.
     * @param float            $amount   Amount to be paid.
     * @param string           $pspId    Your affliation name in our system.
     * @param string           $currency Currency of the order (ISO alpha code).
     * @param string           $SHAIn    The SHA-In-string.
     * @param string           $SHAOut   The SHA-Out-string.
     * @param string[optional] $digest   The encryption-method to use, possible value are: SHA1, SHA256, SHA512.
     */
    public function __construct($orderID, $amount, $pspId, $currency, $SHAIn, $SHAOut, $digest = 'SHA1')
    {
        // set digest
        $this->setDigest($digest);

        // set SHA-IN & SHA-OUT
        $this->setSHAIn($SHAIn);
        $this->setSHAOut($SHAOut);

        // add form fields
        $this->setParameter('PSPID', $pspId);
        $this->setParameter('ORDERID', $orderID);
        $this->setParameter('AMOUNT', (float) $amount * 100);
        $this->setParameter('CURRENCY', $currency);
    }

    /**
     * Get a detail value (or all).
     *
     * @param string[optional] $key The name of the parameter to grab.
     * @return mixed
     */
    public function getDetail($key = null)
    {
        // single key requested
        if ($key !== null) {
            // redefine
            $key = mb_strtoupper((string) $key);

            // return
            return (isset($this->parametersOut[$key])) ? $this->parametersOut[$key] : null;
        }

        return $this->parametersOut;
    }

    /**
     * Get link to Ogone environment, where the form will be submitted to.
     *
     * @return string
     */
    public function getEnvironment()
    {
        return 'https://secure.ogone.com/ncol/'. $this->environment .'/orderstandard_utf8.asp';
    }

    /**
     * Get form parameters.
     *
     * @return array
     */
    public function getFormParameters()
    {
        // old sha verification method (only several parameters)
        if($this->verification == 'main') $sha = $this->getSHA1($this->parametersIn['ORDERID'] . $this->parametersIn['AMOUNT'] . $this->parametersIn['CURRENCY'] . $this->parametersIn['PSPID'] . $this->SHAIn);

        // new sha verification method (all parameters)
        elseif($this->verification == 'each') $sha = $this->getSHA($this->getRawSHA($this->parametersIn, $this->SHAInParameters, $this->SHAIn));

        // add SHA to parameters
        $this->setParameter('SHASign', $sha);

        return $this->parametersIn;
    }

    /**
     * Get the data to be digested.
     *
     * @param array  $parameters The parameters.
     * @param array  $include    Which parameters to include.
     * @param string $passphrase The passphrase to use.
     * @return string
     */
    private function getRawSHA($parameters, $include, $passphrase)
    {
        // alphabetically, based on keys
        ksort($parameters);

        // string to be encoded
        $params = array();

        // add required params to our digest
        foreach($parameters as $key => $value) if(in_array($key, $include) && $value != '') $params[$key] = $key .'='. $value;

        // add secret key and return
        return implode($passphrase, $params) . $passphrase;
    }

    /**
     * Get SHA digest.
     *
     * @param string $SHA The SHA-string.
     * @return string
     */
    public function getSHA($SHA)
    {
        return call_user_func(array($this, 'get'. ucfirst($this->SHA)), $SHA);
    }

    /**
     * Generate SHA1 digest.
     *
     * @param string $SHA The SHA-string.
     * @return string
     */
    public function getSHA1($SHA)
    {
        return mb_strtoupper(sha1($SHA));
    }

    /**
     * Generate SHA256 digest.
     *
     * @param string $SHA The SHA-string.
     * @return string
     */
    public function getSHA256($SHA)
    {
        // see if we can generate the SHA
        if(function_exists('hash')) return mb_strtoupper(hash('sha256', $SHA));

        // could not generator SHA, throw exception
        throw new Exception('SHA-256 could not be created');
    }

    /**
     * Generate SHA512 digest.
     *
     * @param string $SHA The SHA-string.
     * @return string
     */
    public function getSHA512($SHA)
    {
        // see if we can generate the SHA
        if(function_exists('hash')) return mb_strtoupper(hash('sha512', $SHA));

        // could not generator SHA, throw exception
        throw new Exception('SHA-512 could not be created');
    }

    /**
     * Set the encryption-method to use, possible value are: SHA1, SHA256, SHA512.
     *
     * @return string
     */
    public function getDigest()
    {
        return $this->SHA;
    }

    /**
     * This method checks the data integrity based on the SHA-string.
     *
     * @return bool returns true on success, false on failure.
     */
    public function isCorrectSHA()
    {
        // retrieve parameters from Ogone
        if($this->method == 'get') foreach((array) $_GET as $key => $value) $this->parametersOut[mb_strtoupper($key)] = urldecode($value);
        elseif($this->method == 'post') foreach((array) $_POST as $key => $value) $this->parametersOut[mb_strtoupper($key)] = urldecode($value);

        // parameters received?
        if (isset($this->parametersOut['SHASIGN'])) {
            // old sha verification method (only several parameters)
            if($this->verification == 'main') $sha = $this->getSHA1($this->parametersIn['ORDERID'] . $this->parametersIn['CURRENCY'] . $this->parametersIn['AMOUNT'] . $this->parametersIn['PM'] . $this->parametersIn['ACCEPTANCE'] . $this->parametersIn['STATUS'] . $this->parametersIn['CARDNO'] . $this->parametersIn['PAYID'] .$this->parametersIn['NCERROR'] .$this->parametersIn['BRAND'] . $this->SHAOut);

            // new sha verification method (all parameters)
            elseif($this->verification == 'each') $sha = $this->getSHA($this->getRawSHA($this->parametersOut, $this->SHAOutParameters, $this->SHAOut));

            // doublecheck SHA digest
            if($sha == $this->getSHA($this->getRawSHA($this->parametersOut, $this->SHAOutParameters, $this->SHAOut))) $status = 'accept';
            else $status = 'error';
        } else $status = 'error';

        // save
        $this->detailed['sha'] = $status;

        // accept = ok
        return ($status == 'accept');
    }

    /**
     * Set the environment.
     *
     * @param string[optional] $environment The environment to operate in, possible values are: prod, test.
     */
    public function setEnvironment($environment = 'prod')
    {
        // possible values
        $possibleValues = array('prod', 'test');

        // validate
        if(!in_array($environment, $possibleValues)) throw new Exception('Invalid environment. Allowed environments are: '. implode(', ', $possibleValues) .'.');

        // set
        $this->environment = (string) $environment;
    }

    /**
     * Set the request method.
     *
     * @param string[optional] $method The method used to submit data back to us, possible values are: get, post.
     */
    public function setMethod($method = 'get')
    {
        // possible values
        $possibleValues = array('get', 'post');

        // validate
        if(!in_array($method, $possibleValues)) throw new Exception('Invalid request method. Allowed methods are: '. implode(', ', $possibleValues) .'.');

        // set
        $this->method = (string) $method;
    }

    /**
     * Set the SHA verification method.
     *
     * @param string[optional] $verification The method to use for the sha-verification string, possible values are: main, each.
     */
    public function setVerification($verification = 'each')
    {
        // possible values
        $possibleValues = array('main', 'each');

        // validate
        if(!in_array($verification, $possibleValues)) throw new Exception('Invalid verification method. Allowed methods are: '. implode(', ', $possibleValues) .'.');

        // set
        $this->verification = (string) $verification;
    }

    /**
     * Set a parameter.
     *
     * @param string $key   The name of the parameter.
     * @param string $value The value.
     */
    public function setParameter($key, $value)
    {
        // redefine and convert to uppercase
        $key = mb_strtoupper((string) $key);
        $value = (string) $value;

        // validate maximum length
        if(isset($this->parametersMaximumLength[$key]) && mb_strlen($value) > $this->parametersMaximumLength[$key]) throw new Exception('Value for '. $key .' too long. Maximum-length is: '. $this->parametersMaximumLength[$key]);

        // set parameters
        $this->parametersIn[$key] = $value;
    }

    /**
     * Set a bunch of parameters.
     *
     * @param array $parameters The parameters as key/value-pairs.
     */
    public function setParameters($parameters)
    {
        foreach ((array) $parameters as $key => $value) {
            $this->setParameter($key, $value);
        }
    }

    /**
     * Set SHA-IN.
     *
     * @param string $SHAIn The SHA-in-string.
     */
    public function setSHAIn($SHAIn)
    {
        $this->SHAIn = $SHAIn;
    }

    /**
     * Set SHA-OUT.
     *
     * @param string $SHAOut The SHA-out-string.
     */
    public function setSHAOut($SHAOut)
    {
        $this->SHAOut = $SHAOut;
    }

    /**
     * Set the encryption-method to use, possible value are: SHA1, SHA256, SHA512.
     *
     * @param string $digest
     * @throws \InvalidArgumentException
     */
    public function setDigest($digest)
    {
        if (!in_array($digest, $this->allowedSHA)) {
            throw new \InvalidArgumentException(
                'Invalid digest.  Allowed values are: '. implode(', ', $this->allowedSHA) .'.'
            );
        }
        $this->SHA = $digest;
    }

}
