<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/PA-Mobileshop/core/init.php';
$name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$zip_code = sanitize($_POST['zip_code']);
$country = sanitize($_POST['country']);
$errors = array();
$required = array(
    'full_name' =>'Nom + Prenom',
    'email' =>'Email',
    'street' =>'Adresse',
    'city' =>'Ville',
    'zip_code' =>'Code postal',
    'country' =>'Pays',    
);

//vérifier les champs obligatoire
foreach ($required as $f => $d) {
    if(empty($_POST[$f]) || $_POST[$f] == ''){
        $errors[] = $d. ' est vide !';
    }
}

//vérifier email
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errors[] = 'Ajoutez une adresse email valide !';
}

if(!empty($errors)){
    echo display_errors($errors);
}else{
    echo 'envoi';
}


?>