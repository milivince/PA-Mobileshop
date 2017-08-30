<?php
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/PA-Mobileshop/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include './includes/head.php';
$hashed = $user_data['password'];
$old_password = ((isset($_POST['old_password'])) ? sanitize($_POST['old_password']) : '');
$old_password = trim($old_password);
$password = ((isset($_POST['password'])) ? sanitize($_POST['password']) : '');
$password = trim($password);
$confirm = ((isset($_POST['confirm'])) ? sanitize($_POST['confirm']) : '');
$confirm = trim($confirm);
$new_hashed = password_hash($password, PASSWORD_DEFAULT);
$user_id = $user_data['id'];


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
            if (empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])) {
                $errors[] = "Completez les champs !";
            }

            //mdp plus de 6 caractère
            if (strlen($password) < 6) {
                $errors[] = 'Mot de passe doit contenir plus de 6 caractères';
            }

            //vérif nouveau pwd est le meme que le confirmer
            if($password != $confirm){
                $errors[]= 'Le nouveau mot de passe et la 2nd vérification ne sont pas identique ! ';
            }
           
            
            if(!password_verify($old_password, $hashed)){
                $errors[]='L\'ancien mot de passe ne correspont pas à celui dans la base de données';
            }

            //vérification des erreurs
            if (!empty($errors)) {
                echo display_errors($errors);
            }else{
                //changement de mot de passe
                $db->query("UPDATE users SET password = '$new_hashed' WHERE id = 'user_id'");
                $_SESSION['success_flash']= 'Mise à jour du mot de passe réussie !';
                header('Location: index.php');
                
            }
        }
        ?>
    </div>
    <h2 class="text-center">Modification de mot de passe</h2><hr>
    <form action="change_password.php" method="post">
        <div class="form-group">
            <label for="email">Ancien mot de passe : </label>
            <input type="password" name="old_password" id="old_password" class="form-control" value="<?= $old_password; ?>">
        </div>
        <div class="form-group">
            <label for="password">Nouveau Mot de passe : </label>
            <input type="password" name="password" id="password" class="form-control" value="<?= $password; ?>">
        </div>
        <div class="form-group">
            <label for="confirm">Confirmer Mot de passe : </label>
            <input type="password" name="confirm" id="confirm" class="form-control" value="<?= $confirm; ?>">
        </div>
        <div class="form-group">
            <a href="index.php" class="btn btn-default">Cancel</a>
            <input type="submit" value="Connexion" class="btn btn-primary">
        </div>

    </form>
    <p class="text-right"><a href="/PA-Mobileshop/index.php" alt="home">Visit Site</a></p>
</div>
<?php
    ob_end_flush();
include './includes/footer.php';
?>