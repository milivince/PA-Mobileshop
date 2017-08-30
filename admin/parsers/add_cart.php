<?php

require_once $_SERVER['DOCUMENT_ROOT'] .'/PA-Mobileshop/core/init.php';
$product_id = sanitize($_POST['$product_id']);
$size = sanitize($_POST['$size']);
$available = sanitize($_POST['$available']);
$quantity = sanitize($_POST['$quantity']);
$item = array();
$item[] = array(
    'id' => $product_id,
    'size' => $size,
    'quantity' => $quantity,
    );

    $domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST'] : false);
    $query = $db->query("SELECT * FROM products WHERE id = '$product_id'");
    $product = mysqli_fetch_assoc($query);
    $_SESSION['success_flash'] = $product['title']. ' ajouté au panier.';
    
    //vérification que le cookie du panier existe
    if($cart_id !=''){
        $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
        $cart = mysqli_fetch_assoc($cartQ);
        $previous_items = json_decode($cart['items'],true);
        $item_match = 0;
        $new_items = array();
        foreach($previous_items as $pitem){ //pour selectionner un article à la fois et pas acheter plus d'articles que dispo
            if($item[0]['id'] == $pitem['id'] && $item['0']['size']){
                $pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
                if($pitem['quantity'] > $available){
                    $pitem['quantity'] = $available;
                }
                $item_match = 1;
            }
            $new_items[] = $pitem;
        }
        if($item_match != 1 ){
            //ajout le nouvel article avant le dernier dans la colonne article
            $new_items = array_merge($item,$previous_items); 
        }
        
        $items_json = json_encode($new_items);
        $cart_expire = date("Y-m-d H:i:s", strtotime("+30 jours"));
        $db->query("UPDATE cart SET items = '{$items_json}', expire_date = '{$cart_expire}' WHERE id = '{$cart_id}'");
        setcookie(CART_COOKIE,'',1,"/",$domain,FALSE);
        setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,FALSE);
        
    }else{
        //ajout le panier à la bdd et définir le cookie
        $items_json = json_encode($item);
        $cart_expire = date("Y-m-d H:i:s", strtotime("+30 jours"));
        $db->query("INSERT INTO cart (items,expire_date) VALUES ('{$items_json}','{$cart_expire}')");
        $cart_id = $db->insert_id;
        setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
        
    }
?>