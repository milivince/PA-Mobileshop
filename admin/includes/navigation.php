<!-- Bar de navigation -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <a href="/PA-Mobileshop/admin/index.php" class="navbar-brand">Mobileshop Admin</a>
        <ul class="nav navbar-nav">
            <li><a href="brands.php">Marques</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="products.php">Produits</a></li>
            <?php if (has_permission('admin')): ?>
                <li><a href="users.php">Utilisateurs</a></li>
            <?php endif; ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Bonjour <?= $user_data['first'];?> ! 
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="change_password.php">Modifier mot de passe</a></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                </ul>
            </li>
            <!-- <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category'] ?><span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#"><?php echo $child['category'] ?></a></li>
                </ul> -->

            </li>
        </ul>
    </div>
</nav>