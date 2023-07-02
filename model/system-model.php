<?php
/**
* Class SystemModel
*
* The SystemModel class handles system related operations and interactions.
*/
class SystemModel {
    # -------------------------------------------------------------
    #
    # Function: timeElapsedString
    # Description: Retrieves the details of a user based on their email address.
    #
    # Parameters:
    # - $p_email (string): The email address of the user.
    #
    # Returns:
    # - An array containing the user details.
    #
    # -------------------------------------------------------------
    public function timeElapsedString($dateTime) {
        $timestamp = strtotime($dateTime);
        $currentTimestamp = time();

        $diffSeconds = $currentTimestamp - $timestamp;

        $intervals = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        $elapsedTime = 'Just Now';

        foreach ($intervals as $seconds => $label) {
            $count = floor($diffSeconds / $seconds);
            if ($count > 0) {
                if ($count == 1) {
                    $elapsedTime = $count . ' ' . $label . ' ago';
                } else {
                    $elapsedTime = $count . ' ' . $label . 's ago';
                }
                break;
            }
        }

        if ($diffSeconds > 604800) {
            $elapsedTime = date('M j, Y \a\t h:i A', $timestamp);
        }

        return $elapsedTime;
    }
}
?>