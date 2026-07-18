<?php
/**
 * Encryption class.
 * This class handles simple OpenSSL AES-256-CBC encryption/decryption.
 */
class Encryption
{
    private $key;
    private $method;

    public function __construct($key = ENCRYPTION_KEY, $method = ENCRYPTION_METHOD)
    {
        $this->key = hash('sha256', $key, true);
        $this->method = $method;
    }

    public function encrypt($value)
    {
        $ivLength = openssl_cipher_iv_length($this->method);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encrypted = openssl_encrypt($value, $this->method, $this->key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($value)
    {
        $data = base64_decode($value);
        $ivLength = openssl_cipher_iv_length($this->method);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, $this->method, $this->key, OPENSSL_RAW_DATA, $iv);
    }
}
?>
