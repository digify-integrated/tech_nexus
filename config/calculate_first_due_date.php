<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST["start_date"];
    $payment_frequency = $_POST["payment_frequency"];
    $number_of_payments = $_POST["number_of_payments"];

    if(empty($payment_frequency)){
        echo null;
        exit;
    }

    if($number_of_payments == 0){
        echo null;
        exit;
    }

    if (strtotime($start_date) === false) {
        exit;
    }

    $start_date = new DateTime($start_date);

    switch ($payment_frequency) {
        case 'Lumpsum':
            $due_date = $start_date;
            break;
        case 'Monthly':
        case 'Quarterly':
        case 'Semi-Annual':
            $monthsToAdd = ($payment_frequency == 'Monthly') ? 1 : (($payment_frequency == 'Quarterly') ? 3 : 6);
            $start_date->modify("+" . $monthsToAdd . " months");
            $due_date = $start_date;
            break;
        default:
            echo null;
            exit;
    }

    // Output the calculated due date
    echo $due_date->format('m/d/Y');
}
?>
