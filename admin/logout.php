<?php
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/PA-Mobileshop/core/init.php';
unset($_SESSION['MSUser']);
header('Location: login.php');

?>


<?php
ob_end_flush();
?>