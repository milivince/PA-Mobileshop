<?php
ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/PA-Mobileshop/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include './includes/head.php';
include './includes/navigation.php';
if (isset($_GET['add']) || isset($_GET['edit'])) {
    $brandQuery = $db->query("SELECT * FROM Brand ORDER BY brand");
    $parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");

    $title = ((isset($_POST['title']) && $_POST['title'] != '') ? sanitize($_POST['title']) : '');
    $brand = ((isset($_POST['brand']) && $_POST['brand'] != '') ? sanitize($_POST['brand']) : '');
    $parent = ((isset($_POST['parent']) && $_POST['parent'] != '') ? sanitize($_POST['parent']) : '');
    $category = ((isset($_POST['child']) && $_POST['child'] != '') ? sanitize($_POST['child']) : '');

    if (isset($_GET['edit'])) {
        $edit_id = (int) $_GET['edit'];
        $productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
        $product = mysqli_fetch_assoc($productResults);
        $category = ((isset($_POST['child']) && !empty($_POST['child'])) ? sanitize($_POST['child']) : $product['categories']);
        $title = ((isset($_POST['title']) && !empty($_POST['title'])) ? sanitize($_POST['title']) : $product['title']);
        $brand = ((isset($_POST['brand']) && !empty($_POST['brand'])) ? sanitize($_POST['brand']) : $product['brand']);
        $parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
        $parentResult = mysqli_fetch_assoc($parentQ);
        $parent = ((isset($_POST['parent']) && !empty($_POST['parent'])) ? sanitize($_POST['parent']) : $parentResult['parent']);
    }

    if ($_POST) {
       
        $price = sanitize($_POST['price']);
        $list_price = sanitize($_POST['list_price']);
        $sizes = sanitize($_POST['sizes']);
        $description = sanitize($_POST['description']);
        $dbpath = '';
        $errors = array();
        if (!empty($_POST['sizes'])) {
            $sizeString = sanitize($_POST['sizes']);
            $sizeString = rtrim($sizeString, ',');
            echo $sizeString;
            $sizesArray = explode(',', $sizeString); //exploser la chaine par le délimitant de virgule
            $sArray = array();
            $qArray = array();
            foreach ($sizesArray as $ss) {
                $s = explode(':', $ss);
                $sArray[] = $s[0];
                $qArray[] = $s[1];
            }
        } else {
            ($sizesArray = array());
        }
        $required = array('title', 'brand', 'price', 'parent', 'child', 'sizes'); //input obligatoire
        foreach ($required as $field) {
            if ($_POST[$field] == '') {
                $errors[] = 'Champs avec * obligatoires';
                break;
            }
        }

        if (!empty($_FILES)) {
            if ($photo['name'] != '' && $photo['type'] != '') {
                //var_dump($_FILES);
                $photo = $_FILES['photo'];
                // var_dump($photo);
                $name = $photo['name'];
                //var_dump($name);
                $nameArray = explode('.', $name);
                $fileName = $nameArray[0];
                $fileExt = $nameArray[1];
                $mime = explode('/', $photo['type']);
                $mimeType = $mime[0];
                $mimeExt = $mime[1];
                $tmpLoc = $photo['tmp_name'];
                $fileSize = $photo['size'];

                $allowed = array('png', 'jpg', 'jpeg', 'gif');
                $uploadName = md5(microtime()) . '.' . $fileExt;
                $uploadPath = BASEURL . './images/products/' . $uploadName;
                $dbpath = '/PA-Mobileshop/images/products/' . $uploadName;
                if ($mimeType != 'image') {
                    $errors[] .= 'Le ficheir doit etre de type image !';
                }
                if (!in_array($fileExt, $allowed)) {
                    $errors[] .= 'Les images doient etre de type png, jpg, jpeg ou gif';
                }
                if ($fileSize > 15000000) {
                    $errors[] .= 'le ficheir doit etre inférieur à 15 MO.';
                }
                if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
                    $errors[] .= 'File extension does not match the file.';
                }
            }
        }
        if (!empty($errors)) {
            echo display_errors($errors);
        } else {

            //mise à jour du ficheir et insertion dans la BDD
            move_uploaded_file($tmpLoc, $uploadPath);
            $insertSql = "INSERT INTO products(`title`,`price`,`list_price`, `brand`, `categories`, `image`,`description`,`memory`) "
                    . "VALUES ('$title','$price','$list_price','$brand','$categories','$dbpath', '$description','$sizes')";
            $db->query($insertSql);
            header('Location: products.php');
        }
    }
    ?>
    <h2 class="text-center"><?= ((isset($_GET['edit'])) ? ' Modifier' : 'Ajouter'); ?> un produit</h2><hr>
    <form action="products.php?add=<?= ((isset($_GET['edit'])) ? 'edit=' . $edit_id : 'add=1'); ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group col-md-3">
            <label for="title">Nom *:</label>
            <input type="text" name="title" class="form-control" id="title" value="<?= $title; ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="brand">Marque *:</label>
            <select class="form-control" id="brand" name="brand">
                <option value=""<?php echo (($brand == '') ? ' selectionné' : ''); ?>></option>
                <?php while ($b = mysqli_fetch_assoc($brandQuery)): ?>
                    <option value="<?php echo $b['id']; ?>"<?php echo (( $brand == $b['id']) ? ' selectionné' : ''); ?>><?php echo $b['brand']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="parent">Categorie *:</label>
            <select class="form-control" id="parent" name="parent">
                <option value=""<?php echo (( $brand == $b['id']) ? ' selectionné' : ''); ?>></option>
                <?php while ($p = mysqli_fetch_assoc($parentQuery)): ?>
                    <option value="<?php echo $p['id']; ?>"<?php echo (($parent == $p['id']) ? ' selectionné' : ''); ?>><?php echo $p['category']; ?></option>
                <?php endwhile; ?>

            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="child">Sous-Categorie *:</label>
            <select class="form-control" id="child" name="child"></select>
        </div>

        <div class="form-group col-md-3">
            <label for="price">Prix *:</label>
            <input type="text" id="price" name="price" class="form-control" value="<?= ((isset($_POST['price'])) ? sanitize($_POST['price']) : ''); ?>">
        </div>

        <div class="form-group col-md-3">
            <label for="price">Prix à la sortie:</label>
            <input type="text" id="list_price" name="list_price" class="form-control" value="<?= ((isset($_POST['list_price'])) ? sanitize($_POST['list_price']) : ''); ?>">
        </div>

        <div class="form-group col-md-3">
            <label>Quantités et taille mémoire* :</label>
            <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Quantités et taille mémoire</button>
        </div>

        <div class="form-group col-md-3">
            <label for="sizes">Quantités et mémoire dispo :</label>
            <input type="text" id="sizes" class="form-control" name="sizes"  value="<?= ((isset($_POST['sizes'])) ? $_POST['sizes'] : ''); ?>" readonly>
        </div>

        <div class="form-group col-md-6">
            <label for="photo">Photo du produit:</label>
            <input type="file" id="photo" class="form-control" name="photo">
        </div>

        <div class="form-group col-md-6">
            <label for="description">Description* :</label>
            <textarea id="description" class="form-control" name="description" rows="6"><?= ((isset($_POST['description'])) ? sanitize($_POST['description']) : ''); ?></textarea>
        </div>
        <div class="form-group pull-right">
            <a href="products.php" class="btn btn-default">Cancel</a>
            <input type="submit" value="<?= ((isset($_GET['edit'])) ? 'Modifier' : 'Ajouter'); ?> le produit" class="btn btn-success ">
        </div>
        <div class="clearfix"></div>

    </form>

    <!-- Modal -->
    <div class="modal fade " id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" area-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="sizesModalLabel">Quantités et tailles mémoires</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <?php for ($i = 1; $i <= 12; $i++) : ?>
                            <div class="form-group col-md-4">
                                <label for="size<?= $i; ?>">Mémoire:</label>
                                <input type="text" name="size <?= $i; ?>" id="size<?= $i; ?>" value="<?= ((!empty($sArray[$i - 1])) ? $sArray[$i - 1] : ''); ?>" class="form-control"> 
                            </div>
                            <div class="form-group col-md-2">
                                <label for="qty<?= $i; ?>">Quantité:</label>
                                <input type="number" name="qty <?= $i; ?>" id="qty<?= $i; ?>" value="<?= ((!empty($qArray[$i - 1])) ? $qArray[$i - 1] : ''); ?>" min="0" class="form-control"> 
                            </div>

                            <div></div>
                        <?php endfor; ?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle'); return false;">Sauver modifs</button>
                </div>
            </div>
        </div>
    </div>

    <?php
}
else {
    $sql = "SELECT * FROM products WHERE deleted !=1";
    $presults = $db->query($sql);
    if (isset($_GET['featured'])) {
        $id = (int) $_GET['id'];
        $featured = (int) $_GET['featured'];
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
        <?php
        while ($product = mysqli_fetch_assoc($presults)):
            $childID = $product['categories'];
            $catSql = "SELECT * FROM categories WHERE id = '$childID'";
            $result = $db->query($catSql);
            $child = mysqli_fetch_assoc($result);
            $parentID = $child['parent'];
            $pSql = "SELECT * FROM categories WHERE id='$parentID'";
            $presult = $db->query($pSql);
            $parent = mysqli_fetch_assoc($presult);
            $category = $parent['category'] . '~' . $child['category'];
            ?>

            <tr>
                <td>
                    <a href="products.php?edit= <?php echo $product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="products.php?delete= <?php echo $product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
                </td>
                <td><?php echo $product['title']; ?></td>
                <td><?php echo money($product['price']); ?></td>
                <td><?php echo $category; ?></td>
                <td><a href="products.php?featured=<?php echo (($product['featured'] == 0) ? '1' : '0'); ?>&id=<?php echo $product['id']; ?>" class="btn btn-xs btn-default"> 
                        <span class="glyphicon glyphicon-<?php echo(($product['featured'] == 1) ? 'minus' : 'plus'); ?>"></span>

                    </a>
                    &nbsp <?php echo (($product['featured'] == 1) ? 'Produit à la une ' : ''); ?>;
                </td>
                <td>0</td>            
            </tr>

        <?php endwhile; ?>

    </tbody>

    </table>



    <?php
}
include './includes/footer.php';
?>
    <script>
    jQuery('document').ready(function(){
        get_child_option('<?= $category; ?>');
    });
    </script>
