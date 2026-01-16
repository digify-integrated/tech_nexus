<?php
if (isset($_GET['path'])) {
    $relativePath = $_GET['path'];
    // Security: Only allow files within the inventory folder
    $baseDir = realpath(__DIR__ . '/inventory/product/');
    $filePath = realpath(__DIR__ . '/' . $relativePath);

    if ($filePath && strpos($filePath, $baseDir) === 0 && file_exists($filePath)) {
        // Clear any previous output
        if (ob_get_level()) ob_end_clean();

        // Set headers to force RAW download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream'); // Treats file as raw data
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Pragma: public');

        readfile($filePath);
        exit;
    }
}
die("Original file not found.");

?>