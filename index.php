<?php
require_once 'core/init.php';
include './includes/head.php';
include './includes/navigation.php';
include './includes/headerfull.php';
include './includes/leftbar.php';

$sql = "SELECT * FROM products WHERE featured = 1";
$featured = $db->query($sql);
?>
<!-- Main content -->
<div class="col-md-8">
    <div class="row">
        <h2 class="text-center">Produits à la une </h2> <!-- Feature products -->
       <?php while($product = mysqli_fetch_assoc($featured)) : ?>
       
        <div class="col-md-3 text-center">
            <h4><?php echo $product['title']; ?></h4>
            <img src="<?php echo $product['image'];?>" alt="<?php echo $product['title'] ?>" class="img-thumb"/>
            <p class="list-price text-danger">Prix catalogue:  <?php echo money($product['list_price']);?></p>
            <p class="price">Notre prix : <?php echo money($product['price']);?></p>
            <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $product['id'];?>)">Details</button>

        </div>
        <?php endwhile;?>

    </div>
</div>
<!-- Ordre à de l'importance-->
<?php
include './includes/rightbar.php';
include './includes/footer.php';
?>

