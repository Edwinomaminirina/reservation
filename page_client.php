
<?php
session_start();
?>

<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=reservation', 'root', '');
}catch (Exception $exception){
    die('erreur'.$exception->getMessage());
}
include 'fonction.php';

if (isset($_POST['modifier_reservation'])){
    formulaire_modification_reservation($bdd);
}
else{
    if (isset($_SESSION['prenom']) and isset($_SESSION['id'])){
        formulaire_reservation($bdd);
        bouton_deconnexion();
    }
}
?>

