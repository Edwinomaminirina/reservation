<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=reservation', 'root', '');
}catch (Exception $exception){
    die('erreur'.$exception->getMessage());
}
include 'fonction.php';
if (isset($_POST['Valider'])){
    creercompte($bdd);
}
if (isset($_POST['se_connecter'])){
    log_in($bdd);
}
if (isset($_POST['reserver'])){
    session_start();
    reserver($bdd);
}
if (isset($_POST['acceder_compte'])){
    log_in($bdd);
}
if (isset($_POST['modifier_reserver'])){
    modifier_reservation($bdd);
}
if (isset($_POST['supprimer'])){
    supprimer_reservation($bdd);
}
if (isset($_POST['connexion_personel'])){
    log_in_personnel($bdd);
}
if (isset($_POST['inserer_personnel'])){
    inserer_personnel($bdd);
}
if (isset($_POST['enregistrer_modification_personnel'])){
    modifier_personnel($bdd);
}
if (isset($_POST['supprimer_personnel'])){
    supprimer_personnel($bdd);
}
if (isset($_POST['deconnexion'])){
    deconexion();
}