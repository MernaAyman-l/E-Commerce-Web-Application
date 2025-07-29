<?php

class openssl {
    private $cipher_algo = 'AES-256-CBC';
    private $key = 'un0liI7FvY03d1TNi8xiRQTAgkolPtwx';
    private $iv;  // Store IV for future retrieval

    public function encrypt($plaintext) {
        $iv_length = openssl_cipher_iv_length($this->cipher_algo);
        $this->iv = openssl_random_pseudo_bytes($iv_length);  // Generate and store the IV
        $ciphertext = openssl_encrypt($plaintext, $this->cipher_algo, $this->key, 0, $this->iv);
        return base64_encode($this->iv . $ciphertext);  // Append IV with the ciphertext for future use
    }

    public function decrypt($encrypted) {
        $encrypted = base64_decode($encrypted);
        $iv_length = openssl_cipher_iv_length($this->cipher_algo);
        $iv = substr($encrypted, 0, $iv_length);
        $ciphertext = substr($encrypted, $iv_length);
        return openssl_decrypt($ciphertext, $this->cipher_algo, $this->key, 0, $iv);
    }

    public function getIv() {
        return $this->iv;  // Return the IV used during encryption
    }
}


?>
