<?php
class SecurityModel {
    public function encryptData($plaintext) {
        if (empty(trim($plaintext))) return false;
    
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $ciphertext = openssl_encrypt(trim($plaintext), 'aes-256-cbc', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
        
        if (!$ciphertext) return false;
        
        return rawurlencode(base64_encode($iv . $ciphertext));
    }
    
    public function decryptData($ciphertext) {
        $ciphertext = base64_decode(rawurldecode($ciphertext));
    
        if (!$ciphertext) {
            return false;
        }
        
        $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    
        if (strlen($ciphertext) < $iv_length) {
            return false;
        }
        
        $iv = substr($ciphertext, 0, $iv_length);
        $ciphertext = substr($ciphertext, $iv_length);
        
        $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
        
        if (!$plaintext) {
            return false;
        }
        
        return $plaintext;
    }
}
?>