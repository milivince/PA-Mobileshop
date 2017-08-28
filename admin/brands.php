<?php
require_once '../core/init.php';
include './includes/head.php';
include './includes/navigation.php';
// get brands from DB
$sql = "SELECT * FROM Brand ORDER BY brand";
$results = $db->query($sql);
$errors = array();

//Modifier la Marque
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = (int) $_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sql2 = "SELECT  FROM Brand WHERE id = '$edit_id'";
    $edit_result = $db->query($sql2);
    $eBrand = mysqli_fetch_assoc($edit_result);
}


//Supprimer Marque
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "DELETE FROM Brand WHERE id='$delete_id'";
    $db->query($sql);
    header('Location: brands.php');
}

//Si on confirme l'ajout
if (isset($_POST['add_submit'])) {
    $brand = sanitize($_POST['brand']);
    //ajout de blancs
    if ($_POST['brand'] == '') {
        $errors[] .= ' Ajoutez une marque ! ';
    }
    //Verification de l'existance de la marque dans la DB
    $sql = "SELECT * FROM Brand WHERE brand = '$brand'";
    if (isset($_GET['edit'])) {
    $sql = "SELECT * FROM Brand WHERE brand = '$brand' AND id != '$edit_id'";    
    }
    $result = $db->query($sql);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $errors[] .= $brand . ' : Cette marque existe, choissez un autre nom de marque !';
    }

    //Affichage des erreurs
    if (!empty($errors)) {
        echo display_errors($errors);
    } else {
        //Ajout de la marque dans la DB   
        $sql = "INSERT INTO Brand (brand) VALUES ('$brand') ";
        if (isset($_GET['edit'])) {
    $sql = "UPDATE Brand SET brand = '$brand' WHERE id = '$edit_id'";    
    }
        $db->query($sql);
        header('Location: brands.php');
    }
}
?>

<h2 class="text-center">Brands</h2>
<hr>
<!-- Brands Forms -->

<div class="text-center">
    <form class="form-inline" action="brands.php<?php echo ((isset($_GET['edit'])) ? '?edit = ' . $edit_id : ''); ?>" method="post">
        <div class="form-group">
<?php
$brand_value = '';
if (isset($_GET['edit'])) {
    $brand_value = $eBrand['brand'];
} else {
    if (isset($_POST['brand'])) {
        $brand_value = sanitize($_POST['brand']);
    }
}
?>
            <label for="brand"><?php echo ((isset($_GET['edit'])) ? ' Modifier ' : ' Ajouter '); ?> Marque :</label>
            <input type="text" name="brand" id="brand" class="form-control" value="<?php echo $brand_value; ?>"> <!--condition vrai ou faux pour le contenue de l'ajout --> 
<?php if (isset($_GET['edit'])): ?>
                <a href="brands.php" class="btn btn-default">Cancel</a>
            <?php endif; ?>
            <input type="submit" name="add_submit" value="<?php echo ((isset($_GET['edit'])) ? ' Modifier ' : ' Ajouter '); ?> Marque" class="btn btn-success">

        </div>
    </form>
</div>
<hr>

<table class="table table-auto table-bordered table-striped table-condensed ">
    <thead>
    <th></th>
    <th>Brand</th>
    <th></th>

</thead>
<tbody>
<?php while ($brand = mysqli_fetch_assoc($results)): ?>
        <tr>
            <td><a href="brands.php?edit=<?php echo $brand['id']; ?>" class="btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
            <td><?php echo $brand['brand']; ?></td>
            <td><a href="brands.php?delete=<?php echo $brand['id']; ?>" class="btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>           
        </tr>
<?php endwhile; ?>
</tbody>
</table>
<?php
include './includes/footer.php';
?>
