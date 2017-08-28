<?php
ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/PA-Mobileshop/core/init.php';
include './includes/head.php';
include './includes/navigation.php';
if(isset($_GET['add'])){
    $brandQuery = $db->query("SELECT * FROM Brand ORDER BY brand");
    $parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
    
    ?>
<h2 class="text-center">Ajouter un nouveau produit</h2><hr>
<form action="products.php?add=1" method="POST" enctype="multipart/form-data">
    <div class="form-group col-md-3">
        <label for="title">Nom *:</label>
        <input type="text" name="title" class="form-control" id="title" value="<?php echo ((isset($_POST['title']))?sanitize($_POST['title']): ''); ?>">
    </div>
    <div class="form-group col-md-3">
        <label for="brand">Marque *:</label>
        <select class="form-control" id="brand" name="brand">
            <option value=""<?php echo ((isset($_POST['brand']) && $_POST['brand'] == '')?' selectionné' : ''); ?>></option>
            <?php while($brand= mysqli_fetch_assoc($brandQuery)): ?>
            <option value="<?php echo $brand['id'];?>"<?php echo ((isset($_POST['brand']) && $_POST['brand'] == $brand['id'])?' selectionné' : ''); ?>><?php echo $brand['brand'];?></option>
            <?php endwhile;?>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="parent">Categorie *:</label>
        <select class="form-control" id="parent" name="parent">
            <option value=""<?php echo ((isset($_POST['prent']) && $_POST['parent'] == '')?' selectionné' : ''); ?>></option>
            <?php while($parent= mysqli_fetch_assoc($parentQuery)): ?>
            <option value="<?php echo $parent['id'];?>"<?php echo ((isset($_POST['parent']) && $_POST['parent'] == $parent['id'])?' selectionné' : ''); ?>><?php echo $parent['category'];?></option>
            <?php endwhile;?>
            
        </select>
    </div>
    
</form>
<?php
    
}
else{
$sql = "SELECT * FROM products WHERE deleted !=1";
$presults = $db->query($sql);
if(isset($_GET['featured'])){
    $id = (int)$_GET['id'];
    $featured = (int)$_GET['featured'];
    $featuredSql = "UPDATE products SET featured = '$featured' WHERE id ='$id'";
    $db->query($featuredSql);
    header('Location: products.php');
}
 ob_end_flush();
?>
<h2 class="text-center">Produits</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Ajout de produits</a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condensed table-striped">
    <thead>
        <th></th>
        <th>Produit</th>
        <th>Prix</th>
        <th>Categorie</th>
        <th>A la une</th>
        <th>Vendu</th>
    </thead>
    <tbody>
        <?php while($product = mysqli_fetch_assoc($presults)): 
        $childID = $product['categories'];
        $catSql = "SELECT * FROM categories WHERE id = '$childID'";
        $result = $db->query($catSql);
        $child = mysqli_fetch_assoc($result);
        $parentID = $child['parent'];
        $pSql = "SELECT * FROM categories WHERE id='$parentID'";
        $presult = $db->query($pSql);
        $parent = mysqli_fetch_assoc($presult);
        $category = $parent['category'].'~'.$child['category'];
            ?>
        
        <tr>
            <td>
                <a href="products.php?edit= <?php echo $product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="products.php?delete= <?php echo $product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
            </td>
            <td><?php echo $product['title'];?></td>
            <td><?php echo money($product['price']);?></td>
            <td><?php echo $category;?></td>
            <td><a href="products.php?featured=<?php echo (($product['featured'] == 0)?'1' : '0'); ?>&id=<?php echo $product['id'];?>" class="btn btn-xs btn-default"> 
                        <span class="glyphicon glyphicon-<?php echo(($product['featured']==1)?'minus' : 'plus');?>"></span>
                        
                </a>
                &nbsp <?php echo (($product['featured'] == 1)?'Produit à la une ' : '');  ?>;
            </td>
            <td>0</td>            
        </tr>
        
        <?php endwhile;?>
        
    </tbody>

</table>



<?php }
include './includes/footer.php';
?>
