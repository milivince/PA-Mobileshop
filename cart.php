<?php
require_once 'core/init.php';
include './includes/head.php';
include './includes/navigation.php';
include './includes/headerfull.php';
if ($cart_id != '') {
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($results['items'], true);
    var_dump($items);

    $i = 1;
    $sub_total = 0;
    $item_count = 0;
}
?>

<div class="col-md-12">
    <div class="row">
        <h2 class="text-center">Mon Panier</h2><hr>        
        <?php if ($cart_id == '') : ?>
            <div class="bg-danger">
                <p class="text-center text-danger">Le panier est vide</p>
            </div>
        <?php else : ?>
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                <th>#</th>
                <th>Article</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Taille Mémoire</th>
                <th>Sous total</th>                
                </thead>
                <tbody>
                    <?php
                    foreach ($items as $item) {
                        $product_id = $item['id'];
                        $productQ = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
                        $product = mysqli_fetch_assoc($productQ);
                        $sArray = explode(',', $product['memory']);
                        foreach ($sArray as $sizeString) {
                            $s = explode(':', $sizeString);
                            if ($s[0] == $item['size']) {
                                $available = $s[1];
                            }
                        }
                        ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $product['title']; ?></td>
                            <td><?= money($product['price']); ?></td>
                            <td><?= $item['quantity']; ?></td>
                            <td><?= $item['size']; ?></td>
                            <td><?= money($item['quantity'] * $product['price']); ?></td>
                        </tr>
                        <?php
                        $i++;
                        $item_count += $item['quantity'];
                        $sub_total += ($product['price'] * $item['quantity']);
                    }
                    $tax = TAXRATE * $sub_total;
                    $tax = number_format($tax, 2);
                    $grand_total = $tax + $sub_total;
                    ?>
                </tbody>
            </table>

            <table class="table table-bordered table-condensed text-right">
                <legend>Totaux : </legend><hr>

                <thead class="totals-table-header">
                <th>Total articles</th>
                <th>Sous total (HTVA)</th>
                <th>Tax</th>
                <th>Total (TVAC)</th>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $item_count; ?></td>
                        <td><?= money($sub_total); ?></td>
                        <td><?= money($tax); ?></td>
                        <td class="bg-success"><?= money($grand_total); ?></td>                        
                    </tr>
                </tbody>
            </table>
            <!-- Bouton d'achat -->
            <button type="button" class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#checkoutModal">
                <span class="glyphicon glyphicon-shopping-cart"> Acheter >></span>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
                <div class="modal-dialog-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="checkoutModalLabel">Adresse de livraison : </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <form action="thankyou.php" method="post" id="payment-form">
                                    <span class="bg-danger" id="payment-errors"></span>
                                    <div id="step1" style="display:block">
                                        <div class="form-group col-md-6">
                                            <label for="full_name">Nom + Prénom :</label>
                                            <input class="form-control" id="full_name" name="full_name" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">Email:</label>
                                            <input class="form-control" id="email" name="email" type="email">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="street">Adresse (1):</label>
                                            <input class="form-control" id="street" name="street" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="street2">Adresse (2):</label>
                                            <input class="form-control" id="street2" name="street2" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="city">Ville:</label>
                                            <input class="form-control" id="city" name="city" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="zip_code">Code Postal:</label>
                                            <input class="form-control" id="zip_code" name="zip_code" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="country">Pays:</label>
                                            <input class="form-control" id="country" name="country" type="text">

                                        </div>
                                    </div>
                                    <div id="step2" style="display:none">
                                        <div class="form-groupe col-md-3">
                                            <label for="name">Titulaire de la carte :</label>
                                            <input type="text" id="name" class="form-control">

                                        </div>
                                        <div class="form-groupe col-md-3">
                                            <label for="number">Numéro de carte :</label>
                                            <input type="text" id="number" class="form-control">

                                        </div>

                                        <div class="form-groupe col-md-2">
                                            <label for="cvc">CVC :</label>
                                            <input type="text" id="cvc" class="form-control">

                                        </div>
                                        <div class="form-groupe col-md-2">
                                            <label for="exp-month">Mois validité :</label>
                                            <select id="exp-month" class="form-control">
                                                <option value=""></option>
                                                <?php for ($i = 1; $i < 13; $i++): ?>
                                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                                <?php endfor; ?>
                                            </select>

                                        </div>
                                        <div class="form-groupe col-md-2">
                                            <label for="exp-year">Année Validité :</label>
                                            <select id="exp-year" class="form-control">
                                                <option value=""></option>
                                                <?php $yr = date("Y"); ?>
                                                <?php for ($i = 0; $i < 11; $i++): ?>
                                                    <option value="<?= $yr + $i; ?>"><?= $yr + $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                        <button type="button" class="btn btn-primary" onclick="check_address()" id="next_button">Suivant >> </button>
                                        <button type="button" class="btn btn-primary" onclick="back_address()" id="back_button" style="display: none;"> << Précédent</button>
                                        <button type="submit" class="btn btn-primary" id="checkout_button" style="display: none;">Acheter >> </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<script>

    function back_address() {
        jQuery('#payment-errors').html("");
        jQuery('#step1').css("display", "block");
        jQuery('#step2').css("display", "none");
        jQuery('#next_button').css("display", "inline-block");
        jQuery('#back_button').css("display", "none");
        jQuery('#checkout_button').css("display", "none");
        jQuery('#checkoutModalLabel').html("Adresse de livraison :");

    }
    function check_address() {
        var data = {
            'full_name': jQuery('#full_name').val(),
            'email': jQuery('#email').val(),
            'street': jQuery('#street').val(),
            'street2': jQuery('#street2').val(),
            'city': jQuery('#city').val(),
            'zip_code': jQuery('#zip_code').val(),
            'country': jQuery('#country').val()
        };

        jQuery.ajax({
            url: '/PA-Mobileshop/admin/parsers/check_address.php',
            method: 'POST',
            data: data,
            success: function (data) {
                if (data.trim() != 'envoi') {
                    jQuery('#payment-errors').html(data);

                }
                if (data.trim() == 'envoi') {
                    jQuery('#payment-errors').html("");
                    jQuery('#step1').css("display", "none");
                    jQuery('#step2').css("display", "block");
                    jQuery('#next_button').css("display", "none");
                    jQuery('#back_button').css("display", "inline-block");
                    jQuery('#checkout_button').css("display", "inline-block");
                    jQuery('#checkoutModalLabel').html("Entrez vos données bancaires");

                }
            },
            error: function () {
                alert("Quelque chose ne va pas !");
            },
        });
    }
</script>

<?php
include './includes/footer.php';
?>