<?php
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/PA-Mobileshop/core/init.php';
include './includes/head.php';
$email = ((isset($_POST['email'])) ? sanitize($_POST['email']) : '');
$email = trim($email);
$password = ((isset($_POST['password'])) ? sanitize($_POST['password']) : '');
$password = trim($password);

$errors = array();


?>
<style>
    body{
        background-color: #31b0d5;
    }

</style>

<div id="login-form">
    <div>
        <?php
        if ($_POST) {
            //validation du formulaire
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $errors[] = "Ajoutez un email et un mot de passe !";
            }

            //email valide
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Entrez un mail valide';
            }

            //mdp plus de 6 caractère
            if (strlen($password) < 6) {
                $errors[] = 'Mot de passe doit contenir plus de 6 caractères';
            }
            //vérification si l'email existe dans la BDD
            $query = $db->query("SELECT * FROM users WHERE email = '$email'");
            $user = mysqli_fetch_assoc($query);
            $userCount = mysqli_num_rows($query);
            if ($userCount < 1) {
                $errors[] = 'Email n\'est pas dans la base de donnée ';
            }
            
            if(!password_verify($password, $user['password'])){
                $errors[]='Le mot de passe ne correspont pas ! Recommencez !';
            }

            //vérification des erreurs
            if (!empty($errors)) {
                echo display_errors($errors);
            }else{
                //connexion utilisateur
                $user_id = $user['id'];
                login($user_id);
                
            }
        }
        ?>
    </div>
    <h2 class="text-center">Login</h2><hr>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="email">Email : </label>
            <input type="email" name="email" id="email" class="form-control" value="<?= $email; ?>">
        </div>
        <div class="form-group">
            <label for="password">Mot de passe : </label>
            <input type="password" name="password" id="password" class="form-control" value="<?= $password; ?>">
        </div>
        <div class="form-group">
            <input type="submit" value="Connexion" class="btn btn-primary">
        </div>

    </form>
    <p class="text-right"><a href="/PA-Mobileshop/index.php" alt="home">Visit Site</a></p>
</div>
<?php
    ob_end_flush();
include './includes/footer.php';
?>