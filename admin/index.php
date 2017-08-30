<?php
ob_start();
require_once '../core/init.php';
if (!is_logged_in()) {
    header('Location : login.php');
}

include './includes/head.php';
include './includes/navigation.php';
echo $_SESSION['MSUser'];
?>
Admin HOME
<?php
include './includes/footer.php';
ob_end_flush();
?>
