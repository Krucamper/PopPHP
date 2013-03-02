<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/nicksagona/PopPHP
 * @category   Pop
 * @package    Pop_Auth
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Auth;

use Pop\Validator;
use Pop\I18n\I18n;

/**
 * Auth class
 *
 * @category   Pop
 * @package    Pop_Auth
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.2.2
 */
class Auth
{

    /**
     * Constant for user is valid result
     * @var int
     */
    const USER_IS_VALID = 1;

    /**
     * Constant for user not found result
     * @var int
     */
    const USER_NOT_FOUND = 2;

    /**
     * Constant for user is blocked result
     * @var int
     */
    const USER_IS_BLOCKED = 3;

    /**
     * Constant for password incorrect result
     * @var int
     */
    const PASSWORD_INCORRECT = 4;

    /**
     * Constant for login attempts exceeded result
     * @var int
     */
    const ATTEMPTS_EXCEEDED = 5;

    /**
     * Constant for IP address blocked result
     * @var int
     */
    const IP_BLOCKED = 6;

    /**
     * Constant for IP address blocked result
     * @var int
     */
    const IP_NOT_ALLOWED = 7;

    /**
     * Constant to trigger using no encryption
     * @var int
     */
    const ENCRYPT_NONE = 0;

    /**
     * Constant to trigger using md5() encryption
     * @var int
     */
    const ENCRYPT_MD5 = 1;

    /**
     * Constant to trigger using sha1() encryption
     * @var int
     */
    const ENCRYPT_SHA1 = 2;

    /**
     * Constant to trigger using crypt() encryption
     * @var int
     */
    const ENCRYPT_CRYPT = 3;

    /**
     * Array of validator objects
     * @var array
     */
    protected $validators = array(
        'allowedIps'     => null,
        'allowedSubnets' => null,
        'blockedIps'     => null,
        'blockedSubnets' => null,
        'attempts'       => null
    );

    /**
     * Auth adapter object
     * @var mixed
     */
    protected $adapter = null;

    /**
     * Encryption method to use
     * @var int
     */
    protected $encryption = 0;

    /**
     * Encryption salt
     * @var string
     */
    protected $salt = null;

    /**
     * Current number of login attempts
     * @var int
     */
    protected $attempts = 0;

    /**
     * Current IP address
     * @var string
     */
    protected $ip = null;

    /**
     * Current subnet
     * @var array
     */
    protected $subnet = null;

    /**
     * Authentication result
     * @var int
     */
    protected $result = 0;

    /**
     * User validation result from authentication
     * @var boolean
     */
    protected $isValid = false;

    /**
     * Constructor
     *
     * Instantiate the auth object
     *
     * @param Adapter\AdapterInterface $adapter
     * @param int                      $encryption
     * @param string                   $salt
     * @return \Pop\Auth\Auth
     */
    public function __construct(Adapter\AdapterInterface $adapter, $encryption = 0, $salt = null)
    {
        $this->adapter = $adapter;
        $this->start = time();
        $this->setEncryption($encryption);
        $this->salt = $salt;
    }

    /**
     * Static method to instantiate the auth object and return itself
     * to facilitate chaining methods together.
     *
     * @param Adapter\AdapterInterface $adapter
     * @param int                      $encryption
     * @param string                   $salt
     * @return \Pop\Auth\Auth
     */
    public static function factory(Adapter\AdapterInterface $adapter, $encryption = 0, $salt = null)
    {
        return new self($adapter, $encryption, $salt);
    }

    /**
     * Method to get a validator
     *
     * @param  string $name
     * @return mixed
     */
    public function getValidator($name)
    {
        return $this->validators[$name];
    }

    /**
     * Method to get the current number of login attempts
     *
     * @return int
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * Method to get the encryption
     *
     * @return int
     */
    public function getEncryption()
    {
        return $this->encryption;
    }

    /**
     * Method to get the salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Method to get the authentication result
     *
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Method to get the authentication result message
     *
     * @return string
     */
    public function getResultMessage()
    {
        $msg = null;

        switch ($this->result) {
            case self::USER_IS_VALID:
                $msg = I18n::factory()->__('The user is valid.');
                break;
            case self::USER_NOT_FOUND:
                $msg = I18n::factory()->__('The user was not found.');
                break;
            case self::USER_IS_BLOCKED:
                $msg = I18n::factory()->__('The user is blocked.');
                break;
            case self::PASSWORD_INCORRECT:
                $msg = I18n::factory()->__('The password was incorrect.');
                break;
            case self::ATTEMPTS_EXCEEDED:
                $msg = I18n::factory()->__(
                    'The allowed login attempts (%1) have been exceeded.',
                    $this->validators['attempts']->getValue()
                );
                break;
            case self::IP_BLOCKED:
                $msg = I18n::factory()->__('That IP address is blocked.');
                break;
            case self::IP_NOT_ALLOWED:
                $msg = I18n::factory()->__('That IP address is not allowed.');
                break;
        }

        return $msg;
    }

    /**
     * Method to set the encryption
     *
     * @param  int $encryption
     * @return \Pop\Auth\Auth
     */
    public function setEncryption($encryption = 0)
    {
        $enc = (int)$encryption;
        if (($enc >= 0) && ($enc <= 3)) {
            $this->encryption = $enc;
        }

        return $this;
    }

    /**
     * Method to set the encryption
     *
     * @param  string $salt
     * @return \Pop\Auth\Auth
     */
    public function setSalt($salt = null)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Method to set the number of attempts allowed
     *
     * @param  int $attempts
     * @return \Pop\Auth\Auth
     */
    public function setAttemptLimit($attempts = 0)
    {
        if ($attempts == 0) {
            $this->validators['attempts'] = null;
        } else {
            $this->validators['attempts'] = new Validator\LessThan($attempts);
        }
        return $this;
    }

    /**
     * Method to set the number of attempts allowed
     *
     * @param  int $attempts
     * @return \Pop\Auth\Auth
     */
    public function setAttempts($attempts = 0)
    {
        $this->attempts = (int)$attempts;
        return $this;
    }

    /**
     * Method to set the block IP addresses
     *
     * @param  string|array $ips
     * @return \Pop\Auth\Auth
     */
    public function setBlockedIps($ips = null)
    {
        if (null === $ips) {
            $this->validators['blockedIps'] = null;
        } else {
            $validIps = $this->filterIps($ips);
            if (count($validIps) > 0) {
                $this->validators['blockedIps'] = new Validator\Excluded($validIps);
            }
        }
        return $this;
    }

    /**
     * Method to set the block subnets
     *
     * @param  string|array $subnets
     * @return \Pop\Auth\Auth
     */
    public function setBlockedSubnets($subnets = null)
    {
        if (null === $subnets) {
            $this->validators['blockedSubnets'] = null;
        } else {
            $validSubnets = $this->filterSubnets($subnets);
            if (count($validSubnets) > 0) {
                $this->validators['blockedSubnets'] = new Validator\Excluded($validSubnets);
            }
        }
        return $this;
    }

    /**
     * Method to set the allowed IP addresses
     *
     * @param  string|array $ips
     * @return \Pop\Auth\Auth
     */
    public function setAllowedIps($ips = null)
    {
        if (null === $ips) {
            $this->validators['allowedIps'] = null;
        } else {
            $validIps = $this->filterIps($ips);
            if (count($validIps) > 0) {
                $this->validators['allowedIps'] = new Validator\Included($validIps);
            }
        }
        return $this;
    }

    /**
     * Method to set the allowed subnets
     *
     * @param  string|array $subnets
     * @return \Pop\Auth\Auth
     */
    public function setAllowedSubnets($subnets = null)
    {
        if (null === $subnets) {
            $this->validators['allowedSubnets'] = null;
        } else {
            $validSubnets = $this->filterSubnets($subnets);
            if (count($validSubnets) > 0) {
                $this->validators['allowedSubnets'] = new Validator\Included($validSubnets);
            }
        }
        return $this;
    }

    /**
     * Method to authenticate a user
     *
     * @param  string $username
     * @param  string $password
     * @return int
     */
    public function authenticate($username, $password)
    {
        $this->result = 0;

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $this->ip = $_SERVER['REMOTE_ADDR'];
            $this->subnet = substr($this->ip, 0, strrpos($this->ip, '.'));
        }

        $this->processValidators();

        if ($this->result == 0) {
            $this->result = $this->adapter->authenticate($username, $this->encryptPassword($password));
        }

        $this->isValid = ($this->result == 1) ? true : false;

        return $this->result;
    }

    /**
     * Method to determine if the user is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * Method to filter the ip addresses to confirm their validity
     *
     * @param  string|array $ips
     * @return array
     */
    protected function filterIps($ips)
    {
        $validIps = array();

        if (!is_array($ips)) {
            $ips = array($ips);
        }

        foreach ($ips as $ip) {
            $ip = trim($ip);
            if ((Validator\Ipv4::factory()->evaluate($ip)) ||
                (Validator\Ipv6::factory()->evaluate($ip))) {
                $validIps[] = $ip;
            }
        }

        return $validIps;
    }

    /**
     * Method to filter the subnets to confirm their validity
     *
     * @param  string|array $subnets
     * @return array
     */
    protected function filterSubnets($subnets)
    {
        $validSubnets = array();

        if (!is_array($subnets)) {
            $subnets = array($subnets);
        }

        foreach ($subnets as $subnet) {
            if (Validator\Subnet::factory()->evaluate($subnet)) {
                $validSubnets[] = $subnet;
            }
        }

        return $validSubnets;
    }

    /**
     * Method to encrypt the password
     *
     * @param  string $pwd
     * @throws Exception
     * @return string
     */
    protected function encryptPassword($pwd)
    {
        $encrypted = $pwd;

        if ($this->encryption == self::ENCRYPT_MD5) {
            $encrypted = md5($pwd);
        } else if ($this->encryption == self::ENCRYPT_SHA1) {
            $encrypted = sha1($pwd);
        } else if ($this->encryption == self::ENCRYPT_CRYPT) {
            if (null === $this->salt) {
                throw new \Pop\Auth\Exception('Error: The encryption salt was not set.');
            }
            $encrypted = crypt($pwd, $this->salt);
        }

        return $encrypted;
    }

    /**
     * Method to process the validators
     *
     * @return void
     */
    protected function processValidators()
    {
        foreach ($this->validators as $name => $validator) {
            if (null !== $validator) {
                switch ($name) {
                    case 'allowedIps':
                        if ((null !== $this->ip) && (!$validator->evaluate($this->ip))) {
                            $this->result = self::IP_NOT_ALLOWED;
                        }
                        break;
                    case 'allowedSubnets':
                        if ((null !== $this->subnet) && (!$validator->evaluate($this->subnet))) {
                            $this->result = self::IP_NOT_ALLOWED;
                        }
                        break;
                    case 'blockedIps':
                        if ((null !== $this->ip) && (!$validator->evaluate($this->ip))) {
                            $this->result = self::IP_BLOCKED;
                        }
                        break;
                    case 'blockedSubnets':
                        if ((null !== $this->subnet) && (!$validator->evaluate($this->subnet))) {
                            $this->result = self::IP_BLOCKED;
                        }
                        break;
                    case 'attempts':
                        if (!$validator->evaluate($this->attempts)) {
                            $this->result = self::ATTEMPTS_EXCEEDED;
                        }
                        break;
                }
            }
        }

        $this->attempts++;
    }

}
