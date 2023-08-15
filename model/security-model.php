<?php
/**
* Class SecurityModel
*
* The SecurityModel class handles security-related operations and interactions. 
*/
class SecurityModel {
    # -------------------------------------------------------------
    #
    # Function: encryptData
    # Description: Encrypts the given text using AES-256-CBC encryption.
    #
    # Parameters:
    # - $plainText (string): The text to encrypt.
    #
    # Returns:
    # - A encrypted text.
    #
    # -------------------------------------------------------------
    public function encryptData($plainText) {
        if (empty(trim($plainText))) return false;
    
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $ciphertext = openssl_encrypt(trim($plainText), 'aes-256-cbc', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
        
        if (!$ciphertext) return false;
        
        return rawurlencode(base64_encode($iv . $ciphertext));
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: decryptData
    # Description: Decrypts the given text using AES-256-CBC decryption.
    #
    # Parameters:
    # - $plainText (string): The encrypted text to decrypt.
    #
    # Returns:
    # - A decrypted text.
    #
    # -------------------------------------------------------------
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
        
        $plainText = openssl_decrypt($ciphertext, 'aes-256-cbc', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
        
        if (!$plainText) {
            return false;
        }
        
        return $plainText;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: formatEmail
    # Description: Formats the email address by masking the username.
    #
    # Parameters:
    # - $email (string): The encrypted text to decrypt.
    #
    # Returns:
    # - The formatted email address with the masked username.
    #
    # -------------------------------------------------------------
    public function formatEmail($email) {
        $parts = explode('@', $email);
        $username = $parts[0];
        $domain = $parts[1];
    
        $maskedUsername = $this->maskUsername($username);
    
        return $maskedUsername . '@' . $domain;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: maskUsername
    # Description: Masks the username by replacing all characters except the first with asterisks.
    #
    # Parameters:
    # - $username (string): The username to mask.
    #
    # Returns:
    # - The masked username.
    #
    # -------------------------------------------------------------
    private function maskUsername($username) {
        $firstChar = substr($username, 0, 1);
        $maskedUsername = $firstChar . str_repeat('*', strlen($username) - 1);
        return $maskedUsername;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getErrorDetails
    # Description: Retrieves the error details based on the error type.
    #
    # Parameters:
    # - $type (string): The error typek.
    #
    # Returns:
    # - The error details containing the title and message.
    #
    # -------------------------------------------------------------
    public function getErrorDetails($type) {
        $response = [];

        switch ($type) {
            case 'password reset token invalid':
                $response = [
                    'TITLE' => 'Password Reset Token Invalid',
                    'MESSAGE' => 'The password reset token is invalid. Please initiate the password reset process again to receive a new password reset link.'
                ];
                break;
            case 'password reset token expired':
                $response = [
                    'TITLE' => 'Password Reset Token Expired',
                    'MESSAGE' => 'The password reset token has expired. Please initiate the password reset process again to receive a new password reset link.'
                ];
                break;
            case 'email verification token expired':
                $response = [
                    'TITLE' => 'Email Verification Token Expired',
                    'MESSAGE' => 'The email verification token has expired. Please initiate the email verification process again to receive a new email verification token.'
                ];
                break;
            case 'invalid user':
                $response = [
                    'TITLE' => 'Invalid User',
                    'MESSAGE' => 'The user account is invalid or does not exist. Please check your credentials and try again.'
                ];
                break;
            case 'otp expired':
                $response = [
                    'TITLE' => 'One-Time Password (OTP) Expired',
                    'MESSAGE' => 'The OTP has expired. Please initiate the login process again to receive a new One-Time Password (OTP).'
                ];
                break;
            case 'invalid otp':
                $response = [
                    'TITLE' => 'Invalid One-Time Password (OTP)',
                    'MESSAGE' => 'The One-Time Password (OTP) you entered is invalid or you have exceeded the maximum number of attempts. Please initiate the login process again to receive a new One-Time Password (OTP).'
                ];
                break;
            default:
                $response = [
                    'TITLE' => 'Unknown Error',
                    'MESSAGE' => 'An unknown error occurred.'
                ];
                break;
        }
        
        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateFileName
    # Description: 
    # Generates a random file name of specified length.
    #
    # Parameters: 
    # - $minLength (int): The minimum length of the generated token. Default is 4.
    # - $maxLength (int): The maximum length of the generated token. Default is 4.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function generateFileName($minLength = 4, $maxLength = 4) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characterCount = strlen($characters);
        $fileName = '';
    
        $length = mt_rand($minLength, $maxLength);
        
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = random_int(0, $characterCount - 1);
            $fileName .= $characters[$randomIndex];
        }
    
        return $fileName;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: directoryChecker
    # Description: 
    # Checks the directory if it exists and create if not exist.
    #
    # Parameters: 
    # - $directory (string): The directory.
    #
    # Returns: Bool
    #
    # -------------------------------------------------------------
    public function directoryChecker($directory) {
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                $error = error_get_last();
                return 'Error creating directory: ' . ($error ? $error['message'] : 'Unknown error');
            }
        }
        else if (!is_writable($directory)) {
            return 'Directory exists, but is not writable';
        }
    
        return true;
    }
    # -------------------------------------------------------------
}
?>