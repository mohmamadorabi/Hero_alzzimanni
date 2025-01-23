<?php
require_once 'config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $available_times = get_available_times($date);
    echo json_encode($available_times);
}
?>