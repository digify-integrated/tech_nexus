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
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkDate
    # Description: Checks the date with different format
    #
    # Parameters:
    # - $p_email (string): The email address of the user.
    #
    # Returns:
    # - An array containing the user details.
    #
    # -------------------------------------------------------------
    public function checkDate($type, $date, $time, $format, $modify, $system_date, $current_time){
        if($type == 'default'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify);
            }
            else{
                return $system_date;
            }
        }
        else if($type == 'empty'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify);
            }
            else{
                return null;
            }
        }
        else if($type == 'attendance empty'){
            if(!empty($date) && $date != ' '){
                return $this->format_date($format, $date, $modify);
            }
            else{
                return null;
            }
        }
        else if($type == 'summary'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify);
            }
            else{
                return '--';
            }
        }
        else if($type == 'na'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify);
            }
            else{
                return 'N/A';
            }
        }
        else if($type == 'complete'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify) . ' ' . $time;
            }
            else{
                return 'N/A';
            }
        }
        else if($type == 'encoded'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify) . ' ' . $time;
            }
            else{
                return 'N/A';
            }
        }
        else if($type == 'date time'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify) . ' ' . $time;
            }
            else{
                return 'N/A';
            }
        }
        else if($type == 'default time'){
            if(!empty($date)){
                return $time;
            }
            else{
                return $current_time;
            }
        }
    }
    # -------------------------------------------------------------
}
?>