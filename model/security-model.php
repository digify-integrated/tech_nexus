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

    public function formatEmail($email) {
        $parts = explode("@", $email);
        $username = $parts[0];
        $domain = $parts[1];
      
        $maskedUsername = substr($username, 0, 1) . str_repeat("*", strlen($username) - 1);
      
        return $maskedUsername . "@" . $domain;
    }

    public function getErrorDetails($type) {
        switch ($type){
            case 'password reset token invalid':
                $response[] = array(
                    'TITLE' => 'Password Reset Token Invalid',
                    'MESSAGE' => 'The password reset token is invalid. Please initiate the password reset process again to receive a new password reset link.'
                );
            break;
            case 'password reset token expired':
                $response[] = array(
                    'TITLE' => 'Password Reset Token Expired',
                    'MESSAGE' => 'The password reset token has expired. Please initiate the password reset process again to receive a new password reset link.'
                );
            break;
            case 'email verification token expired':
                $response[] = array(
                    'TITLE' => 'Email Verification Token Expired',
                    'MESSAGE' => 'The email verification token has expired. Please initiate the email verification process again to receive a new email verification token.'
                );
            break;
            case 'invalid user':
                $response[] = array(
                    'TITLE' => 'Invalid User',
                    'MESSAGE' => 'The user account is invalid or does not exist. Please check your credentials and try again.'
                );
            break;
        }

        return $response;
    }
}
?>