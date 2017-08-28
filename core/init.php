<?php

$db = mysqli_connect('lamp-edu.condorcet.be', 'usr10197', 'mysqlcondorcet', 'vincent_militello_mobileshop');
if(mysqli_connect_errno()) {
    echo 'Erreur de connexion à la base de donnée, erruers: '.mysqli_connect_error();
    die();
    
}
require_once $_SERVER['DOCUMENT_ROOT'].'/PA-Mobileshop/config.php';
require_once BASEURL.'/helpers/helpers.php';
