<?php
ob_start();
require_once '../core/init.php';
$id = $_POST['id'];
$id = (int) $id; //securité pour ne pas passer autre chose que l'id
$sql = "SELECT * FROM products WHERE id='$id'";
$result = $db->query($sql);
$product = mysqli_fetch_assoc($result);
$brand_id = $product['brand'];
$sql = " SELECT brand FROM Brand WHERE id='$brand_id' ";
$brand_query = $db->query($sql);
$brand = mysqli_fetch_assoc($brand_query);
$sizestring = $product['memory'];
$sizestring = rtrim($sizestring, ',');
$size_array = explode(',', $sizestring);

ob_end_flush();
?>
<?php ob_start(); ?>
<div class="modal fade details-1" id="details-modal" tabindex=-"-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" onclick="closeModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-center"><?php echo $product['title'];?></h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <span id="modal_errors" class="bg-danger"></span>
                        <div class="col-sm-6">
                            <div class="center-block">
                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="details img-responsive">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <p><?php echo nl2br($product['description']); ?></p>
                            <hr>
                            <p>Prix : <?php echo money($product['price']); ?></p>
                            <p>Marque : <?php echo $brand['brand']; ?></p>
                            <form action="add_cart.php" method="post">
                                <input type="hidden" name="product_id" id="product_id" value="<?= $id; ?>">
                                <input type="hidden" name="available" id="available" value="">

                                <div class="form-group">
                                    <div class="col-xs-3">
                                        <label for="quantity">Quantity : </label>
                                        <input type="number" class="form-control" id="quantity" name="quantity">
                                    </div><br>
                                    <div class="col-xs-9">&nbsp;</div>
                                </div>

                                <div class="form-group">
                                    <div>
                                        <label for="size">Size : </label>
                                        <select name="size" id="size" class="form-control">
                                            <option value=""></option>
                                            <?php
                                            foreach ($size_array as $string) {
                                                $string_array = explode(':', $string);
                                                $size = $string_array[0];
                                                $available = $string_array[1];
                                                var_dump($available);
                                                echo '<option value=" ' . $size . '" data-available="'.$available.'">' . $size . '(' . $available . ' Dispo)</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default"  onclick="closeModal()">Close</button>
                <button class="btn btn-warning" onclick="add_to_cart(); return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Add To Cart</button>
            </div>
        </div>
    </div>
</div>
<script>

    jQuery('#size').change(function(){
        var available = jQuery('#size option:selected').data("available");
        jQuery('#available').val(available);
    });
    function closeModal() {
        jQuery('#details-modal').modal('hide');
        setTimeout(function () {
            jQuery('#details-modal').remove();
            jQuery('.modal-backdrop').remove();

        }, 500);
    }
</script>
<?php
ob_end_flush();
echo ob_get_clean();
?>