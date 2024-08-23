<?php
  #require('config/_required_php_file.php');
    #echo $securityModel->decryptData('BDbouHuj17TD3SsMGK%2Fyca0xxaKLmtjTP0r%2B4I8DVoM%3D');

    // Set initial variables
/*$start_date = new DateTime('2022-01-01'); // starting date
$term = 36; // number of payments in months
$payment_frequency = 'monthly'; // monthly payments
$repayment = 1000; // repayment amount
$escalation_rate = 0.05; // 5% escalation rate

// Initialize arrays for storing repayment schedule
$dates = array();
$amounts = array();

// Calculate repayment schedule
for ($i = 0; $i < $term; $i++) {
    // Add starting date to array
    $dates[] = $start_date->format('Y-m-d');

    // Add repayment amount to array
    $amounts[] = $repayment;

    // Increase repayment amount by escalation rate every 12 months
    if (($i + 1) % 12 == 0) {
        $repayment *= (1 + $escalation_rate);
    }

    // Add 1 month to starting date
    $start_date->add(new DateInterval('P1M'));
}

// Display repayment schedule
echo "<table border='1'>\n";
echo "<tr><th>Date</th><th>Repayment Amount</th></tr>\n";
for ($i = 0; $i < $term; $i++) {
    echo "<tr><td>". $dates[$i]. "</td><td>". number_format($amounts[$i], 2). "</td></tr>\n";
}
echo "</table>\n";*/

echo file_get_contents('email-template/default-email.html');
?>