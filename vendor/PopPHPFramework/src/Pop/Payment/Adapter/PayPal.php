<?php
/**
 * Pop PHP Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.TXT.
 * It is also available through the world-wide-web at this URL:
 * http://www.popphp.org/LICENSE.TXT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@popphp.org so we can send you a copy immediately.
 *
 * @category   Pop
 * @package    Pop_Payment
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Payment\Adapter;

use Pop\Curl\Curl;

/**
 * This is the PayPal adapter for the Payment component.
 *
 * @category   Pop
 * @package    Pop_Payment
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    1.1.1
 */
class PayPal extends AbstractAdapter
{

    /**
     * API username
     * @var string
     */
    protected $apiUsername = null;

    /**
     * API password
     * @var string
     */
    protected $apiPassword = null;

    /**
     * API signature
     * @var string
     */
    protected $signature = null;

    /**
     * Test URL
     * @var string
     */
    protected $testUrl = 'https://api-3t.sandbox.paypal.com/nvp';

    /**
     * Live URL
     * @var string
     */
    protected $liveUrl = 'https://api-3t.paypal.com/nvp';

    /**
     * Transaction data
     * @var array
     */
    protected $transaction = array(
        'USER'             => null,
        'PWD'              => null,
        'SIGNATURE'        => null,
        'METHOD'           => 'DoDirectPayment',
        'VERSION'          => '84.0',
        'PAYMENTACTION'    => 'Sale',
        'CREDITCARDTYPE'   => null,
        'RECURRING'        => null,
        'AMT'              => null,
        'CURRENCYCODE'     => 'USD',
        'ACCT'             => null,
        'EXPDATE'          => null,
        'CVV2'             => null,
        'INVNUM'           => null,
        'DESC'             => null,
        'FIRSTNAME'        => null,
        'LASTNAME'         => null,
        'COMPANY'          => null,
        'STREET'           => null,
        'CITY'             => null,
        'STATE'            => null,
        'ZIP'              => null,
        'COUNTRYCODE'      => 'US',
        'SHIPTOPHONENUM'   => null,
        'FAX'              => null,
        'EMAIL'            => null,
        'IPADDRESS'        => null,
        'SHIPTOFNAME'      => null,
        'SHIPTOLNAME'      => null,
        'SHIPTOCOMPANY'    => null,
        'SHIPTOSTREET'     => null,
        'SHIPTOCITY'       => null,
        'SHIPTOSTATE'      => null,
        'SHIPTOZIP'        => null,
        'SHIPTOCOUNTRY'    => null,
        'TAXAMT'           => null,
        'SHIPPINGAMT'      => null,
        'RETURNFMFDETAILS' => 1
    );

    /**
     * Transaction fields for normalization purposes
     * @var array
     */
    protected $fields = array(
        'amount'          => 'AMT',
        'cardNum'         => 'ACCT',
        'expDate'         => 'EXPDATE',
        'ccv'             => 'CVV2',
        'firstName'       => 'FIRSTNAME',
        'lastName'        => 'LASTNAME',
        'company'         => 'COMPANY',
        'address'         => 'STREET',
        'city'            => 'CITY',
        'state'           => 'STATE',
        'zip'             => 'ZIP',
        'country'         => 'COUNTRYCODE',
        'phone'           => 'SHIPTOPHONENUM',
        'fax'             => 'FAX',
        'email'           => 'EMAIL',
        'shipToFirstName' => 'SHIPTOFNAME',
        'shipToLastName'  => 'SHIPTOLNAME',
        'shipToCompany'   => 'SHIPTOCOMPANY',
        'shipToAddress'   => 'SHIPTOSTREET',
        'shipToCity'      => 'SHIPTOCITY',
        'shipToState'     => 'SHIPTOSTATE',
        'shipToZip'       => 'SHIPTOZIP',
        'shipToCountry'   => 'SHIPTOCOUNTRY'
    );

    /**
     * Required fields
     * @var array
     */
    protected $requiredFields = array(
        'USER',
        'PWD',
        'SIGNATURE',
        'METHOD',
        'ACCT',
        'EXPDATE',
        'CVV2',
        'AMT',
        'FIRSTNAME',
        'LASTNAME',
        'STREET',
        'CITY',
        'STATE',
        'ZIP',
        'COUNTRYCODE',
        'IPADDRESS'
    );

    /**
     * Constructor
     *
     * Method to instantiate an PayPal payment adapter object
     *
     * @param  string  $apiUser
     * @param  string  $apiPass
     * @param  string  $sign
     * @param  boolean $test
     * @return \Pop\Payment\Adapter\PayPal
     */
    public function __construct($apiUser, $apiPass, $sign, $test = false)
    {
        $this->apiUsername = $apiUser;
        $this->apiPassword = $apiPass;
        $this->signature = $sign;
        $this->transaction['USER'] = $apiUser;
        $this->transaction['PWD'] = $apiPass;
        $this->transaction['SIGNATURE'] = $sign;
        $this->test = $test;
    }

    /**
     * Send transaction
     *
     * @param  boolean $verifyPeer
     * @throws Exception
     * @return void
     */
    public function send($verifyPeer = true)
    {
        if (null === $this->transaction['IPADDRESS']) {
            $this->transaction['IPADDRESS'] = $_SERVER['REMOTE_ADDR'];
        }

        if (!$this->validate()) {
            throw new Exception('The required transaction data has not been set.');
        }

        $url = ($this->test) ? $this->testUrl : $this->liveUrl;
        $options = array(
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $this->buildPostString(),
            CURLOPT_HEADER         => false,
            CURLOPT_RETURNTRANSFER => true
        );

        if (!$verifyPeer) {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }

        $curl = new Curl($options);
        $this->response = $curl->execute();
        $this->responseCodes = $this->parseResponseCodes();

        if (stripos($this->responseCodes['ACK'], 'Success') !== false) {
            $this->approved = true;
            $this->message = 'The transaction has been approved.';
        } else {
            if (isset($this->responseCodes['L_SHORTMESSAGE0']) && (stripos($this->responseCodes['L_SHORTMESSAGE0'], 'Decline') !== false)) {
                $this->declined = true;
            }
            if (isset($this->responseCodes['L_SEVERITYCODE0']) && (stripos($this->responseCodes['L_SEVERITYCODE0'], 'Error') !== false)) {
                $this->error = true;
            }
            if (isset($this->responseCodes['L_LONGMESSAGE0'])) {
                $this->message = $this->responseCodes['L_LONGMESSAGE0'];
            }
        }
    }

    /**
     * Build the POST string
     *
     * @return string
     */
    protected function buildPostString()
    {
        $post = $this->transaction;

        $post['ACCT'] = $this->filterCardNum($post['ACCT']);
        $post['EXPDATE'] = $this->filterExpDate($post['EXPDATE'], 6);

        if ((null !== $post['SHIPTOFNAME']) || (null !== $post['SHIPTOLNAME'])) {
            $post['SHIPTONAME'] = $post['SHIPTOFNAME'] . ' ' . $post['SHIPTOLNAME'];
            unset($post['SHIPTOFNAME']);
            unset($post['SHIPTOLNAME']);
        }

        return http_build_query($post);
    }

    /**
     * Parse the response codes
     *
     * @return void
     */
    protected function parseResponseCodes()
    {
        $responseCodes = explode('&', $this->response);
        $codes = array();

        foreach ($responseCodes as $key => $value) {
            $value = urldecode($value);
            $valueAry = explode('=', $value);
            $codes[$valueAry[0]] = (!empty($valueAry[1])) ? $valueAry[1] : null;
        }

        return $codes;
    }

}
