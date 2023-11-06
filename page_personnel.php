<?php
session_start();?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>reservation</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="asset/css/bootstrap.css">
    </head>
    <body>
<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=reservation', 'root', '');
}catch (Exception $exception){
    die('erreur'.$exception->getMessage());
}
    include 'Fonction.php';
    if (isset($_SESSION['admin'])){
        if (isset($_POST['modifier_personnel'])){
            formulaire_modifier_personnel($bdd);
        }else{
            formulaire_personnel();
            affichage_personnel($bdd);
            bouton_deconnexion();
        }
    }
    else{
        ?><h1>Liste des reservations</h1><?php
        affichage_reservation_personnel($bdd);
        bouton_deconnexion();
    }
    ?>
    </body>
</html>
