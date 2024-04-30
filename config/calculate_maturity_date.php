<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST["start_date"];
    $payment_frequency = $_POST["payment_frequency"];
    $term_type = $_POST["term_type"];
    $term_length = $_POST["term_length"];

    if(empty($payment_frequency)){
        echo null;
        exit;
    }

    if(empty($term_type)){
        echo null;
        exit;
    }

    if($term_length == 0){
        echo null;
        exit;
    }

    if (strtotime($start_date) === false) {
        echo null;
        exit;
    }

    $start_date = new DateTime($start_date);

    switch ($payment_frequency) {
        case 'Monthly':
            $start_date->modify("+" . $term_length . " months");
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
