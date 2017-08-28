<?php
function display_errors($errors){
    $display = '<ul class="bg-danger">';
    foreach ($errors as $error){
        $display .='<li class="text-danger">'.$error.'</li>';
    }
    $display .='</ul>';
    return $display;
}

function sanitize($dirty){
    return htmlentities($dirty,ENT_QUOTES,"UTF-8"); //sécurité pour l'ajout de la marque pour ne pas entrer des tags html ou des liens vers d'autre site
    
}

function money($number){
    return '€'.number_format($number,2);
}