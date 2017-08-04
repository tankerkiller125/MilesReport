<?php
/**
 * Created by PhpStorm.
 * User: tanke
 * Date: 8/4/2017
 * Time: 17:23
 */

namespace App\Providers;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class SecureHasher implements HasherContract
{
    protected $rounds = 10;

    public function make($value, array $options = [])
    {
        if (function_exists('crypto_pwhash_str')) {
            return crypto_pwhash_str($value, CRYPTO_PWHASH_OPSLIMIT_SENSITIVE, CRYPTO_PWHASH_MEMLIMIT_SENSITIVE);
        } else if (function_exists('\Sodium\crypto_pwhash_str')) {
            \Sodium\crypto_pwhash_str($value, \Sodium\CRYPTO_PWHASH_OPSLIMIT_SENSITIVE, \Sodium\CRYPTO_PWHASH_MEMLIMIT_SENSITIVE);
        } else {
            return password_hash($value, PASSWORD_BCRYPT, [
                'cost' => $this->cost($options),
            ]);
        }
    }

    /**
     * Extract the cost value from the options array.
     *
     * @param  array $options
     * @return int
     */
    protected function cost(array $options = [])
    {
        return isset($options['rounds']) ? $options['rounds'] : $this->rounds;
    }

    public function check($value, $hashedValue, array $options = [])
    {
        if (function_exists('crypto_pwhash_str_verify')) {
            crypto_pwhash_str_verify($hashedValue, $value);
        } else if (function_exists('\Sodium\crypto_pwhash_str_verify')) {
            \Sodium\crypto_pwhash_str_verify($hashedValue, $value);
        } else {
            return password_verify($value, $hashedValue);
        }
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        if (function_exists('crypto_pwhash_str_verify')) {
            return false;
        } else if (function_exists('\Sodium\crypto_pwhash_str_verify')) {
            return false;
        } else {
            return password_needs_rehash($hashedValue, PASSWORD_BCRYPT, [
                'cost' => $this->cost($options),
            ]);
        }
    }

    /**
     * Set the default password work factor.
     *
     * @param  int $rounds
     * @return $this
     */
    public function setRounds($rounds)
    {
        $this->rounds = (int)$rounds;

        return $this;
    }
}