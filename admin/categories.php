<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/PA-Mobileshop/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include './includes/head.php';
include './includes/navigation.php';

?>

<h2 class="text-center">Categories</h2><hr>


<?php
include './includes/footer.php';
?>
