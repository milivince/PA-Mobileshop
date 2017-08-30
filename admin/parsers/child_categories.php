<!-- Selectionne la catégorie mère et proposéles produits correspondant ! -->
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PA-Mobileshop/core/init.php';
$parentID = (int) $_POST['parentID']; //vient de l'envoi par la fonction ajax dans le footer
$selected = sanitize($_POST['selected']);
$childQuery = $db->query("SELECT * FROM categories WHERE parent = '$parentID' ORDER BY category");
ob_start();
?>
<option value=""></option>
<?php while ($child = mysqli_fetch_assoc($childQuery)):
    ?>
<option value="<?php echo $child['id']; ?>" <?= (($selected == $child['id'])? ' selectionné' :'')?>><?php echo $child['category']; ?></option>
<?php endwhile; ?>
<?php
echo ob_get_clean();
?>