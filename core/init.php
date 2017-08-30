<?php
ob_start();

$db = mysqli_connect('lamp-edu.condorcet.be', 'usr10197', 'mysqlcondorcet', 'vincent_militello_mobileshop');
if(mysqli_connect_errno()) {
    echo 'Erreur de connexion à la base de donnée, erruers: '.mysqli_connect_error();
    die();
    
}
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/PA-Mobileshop/config.php';
require_once BASEURL.'/helpers/helpers.php';

$cart_id = '';
if(isset($_COOKIE[CART_COOKIE])){
    $cart_id = sanitize($_COOKIE[CART_COOKIE]);
}

if(isset($_SESSION['MSUser'])){
    $user_id = $_SESSION['MSUser'];
    $query= $db->query("SELECT * FROM users WHERE id = '$user_id'");
    $user_data = mysqli_fetch_assoc($query);
    $fn = explode(' ', $user_data['full_name']);
    $user_data['first']= $fn[0];
    $user_data['last']= $fn[1];
    
}
if(isset($_SESSION['success_flash'])){
    echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
    unset($_SESSION['success_flash']);
        
}

if(isset($_SESSION['error_flash'])){
    echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
    unset($_SESSION['error_flash']);
       
}

    ob_end_flush();
 ?>

