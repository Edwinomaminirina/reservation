<?php
function formulaire_personnel(){
    ?>
    <form method="post" action="traitement.php" class="container">
        <h1>Pour inserer de nouveau personnel</h1>
        <label for="prenom">Nom</label><br>
        <input type="text" name="prenom" id="prenom" class="form-control"><br>
        <label for="Tel">Tel</label><br>
        <input type="number" name="Tel" id="Tel" class="form-control"><br>
        <label>Fonction</label><br>
        <select name="fonction" class="form-select">
            <option value="chauffeur">Chauffeur</option>
            <option value="bagagiste">bagagiste</option>
            <option value="caissiere">caissiere</option>
            <option value="mecanicien">mecanicien</option>
            <option value="responsable_reservation">responsable reservation</option>
        </select><br>
        <input type="submit" name="inserer_personnel" value="Se connecter" class="btn btn-primary">
    </form>
    <?php
}
function creercompte($bdd){
    if (isset($_POST['prenom']) and isset($_POST['Tel'])){
        $nouveau_compte = $bdd->prepare('INSERT INTO client(prenoms_client, tel_client) 
            VALUES (:prenom, :tel)');
        $nouveau_compte->execute(array('prenom'=>htmlspecialchars($_POST['prenom']),
            'tel'=>htmlspecialchars($_POST['Tel'])));
        $nouveau_compte->closeCursor();
        ?>
        <form method="post" action="traitement.php">
            <input type="hidden" name="prenom" value="<?php echo $_POST['prenom']?>">
            <input type="hidden" name="Tel" value="<?php echo $_POST['Tel']?>">
            <input type="submit" name="acceder_compte" value="Acceder au compte">
        </form>
        <?php
    }
    else {header('location:index.php');}
}
function log_in($bdd){
    $compte_log_in = $bdd->prepare('SELECT * FROM client WHERE prenoms_client=:prenom ');
    $compte_log_in->execute(array('prenom'=>htmlspecialchars($_POST['prenom'])));
    while ($donne=$compte_log_in->fetch()){
        if ($donne['tel_client']==$_POST['Tel']){
            session_start();
            $_SESSION['id']=$donne['Id_client'];
            $_SESSION['prenom']=$_POST['prenom'];
            $_SESSION['telephone']=$_POST['Tel'];
            header('Location:page_client.php');
        }
    }

}
function log_in_personnel($bdd){
    if ($_POST['prenom']=='admin' and $_POST['Tel']=='0343444334'){
        session_start();
        $_SESSION['admin']=true;
        header('Location:page_personnel.php');
    }else{
        $compte_log_in = $bdd->prepare('SELECT * FROM personnel WHERE User_personnel=:prenom ');
        $compte_log_in->execute(array('prenom'=>htmlspecialchars($_POST['prenom'])));
        while ($donne=$compte_log_in->fetch()){
            if ($donne['Tel_personnel']==$_POST['Tel']){
                session_start();
                $_SESSION['prenom']=$_POST['prenom'];
                $_SESSION['telephone']=$_POST['Tel'];
                header('Location:page_personnel.php');
            }
        }
    }


}
function inserer_personnel($bdd){
    $nouveau_compte = $bdd->prepare('INSERT INTO personnel(User_personnel, Tel_personnel,Fonction) 
            VALUES (:prenom, :tel, :fonction)');
    $nouveau_compte->execute(array('prenom'=>htmlspecialchars($_POST['prenom']),
        'tel'=>$_POST['Tel'],
        'fonction'=>htmlspecialchars($_POST['fonction'])));
    header('Location:page_personnel.php');
}
function reserver($bdd){
    if (isset($_POST['type_voiture'])){
        if ($_POST['type_voiture']==15){
            $numero_place = $_POST['15_place'];
        }
        if ($_POST['type_voiture']==19){
            $numero_place = $_POST['19_place'];
        }
    }
    $reservation = $bdd->prepare('INSERT INTO service(id_client, Cooperative, choix_vehicule, Choix_place, Depart, Date_depart, Arrive) 
                                    VALUES (:id_client,:cooperative, :choix_vehicule, :choix_place, 
                                            :depart, :date_depart, :arrive)');
    $reservation->execute(array(
        'id_client'=>$_SESSION['id'],
        'cooperative'=>$_POST['cooperative'],
        'choix_vehicule'=>$_POST['type_voiture'],
        'choix_place'=>$numero_place,
        'depart'=>$_POST['depart'],
        'date_depart'=>$_POST['date_de_depart'],
        'arrive'=>$_POST['arrive']
    ));
    header('Location:page_client.php');
}
function modifier_reservation($bdd){
    if (isset($_POST['type_voiture'])){
        if ($_POST['type_voiture']==15){
            $numero_place = $_POST['15_place'];
        }
        if ($_POST['type_voiture']==19){
            $numero_place = $_POST['19_place'];
        }
    }
    $reservation = $bdd->prepare('UPDATE service SET Cooperative=:cooperative, choix_vehicule=:choix_vehicule, Choix_place=:choix_place, Depart=:depart, Date_depart=:date_depart, Arrive=:arrive ');
    $reservation->execute(array(
        'cooperative'=>$_POST['cooperative'],
        'choix_vehicule'=>$_POST['type_voiture'],
        'choix_place'=>$numero_place,
        'depart'=>$_POST['depart'],
        'date_depart'=>$_POST['date_de_depart'],
        'arrive'=>$_POST['arrive']
    ));
    header('Location:page_client.php');
}
function modifier_personnel($bdd){
    $personnel = $bdd->prepare('UPDATE personnel SET User_personnel=:User_personnel, Tel_personnel=:Tel_personnel, Fonction=:Fonction WHERE Id_personnel=:id');
    $personnel->execute(array(
        'User_personnel'=>htmlspecialchars($_POST['prenom']),
        'Tel_personnel'=>htmlspecialchars($_POST['Tel']),
        'Fonction'=>htmlspecialchars($_POST['fonction']),
        'id'=>htmlspecialchars($_POST['id'])
    ));
    header('Location:page_personnel.php');
}
function supprimer_reservation($bdd){
    $supprimer = $bdd->prepare('DELETE FROM service WHERE Id_service=:id');
    $supprimer->execute(array('id'=>$_POST['id']));
    header('location:page_client.php');
}
function supprimer_personnel($bdd){
    $supprimer = $bdd->prepare('DELETE FROM personnel WHERE Id_personnel=:id');
    $supprimer->execute(array('id'=>$_POST['id']));
    header('location:page_personnel.php');
}
function recup_nom_client($bdd, $id_client){
    $nom_client = $bdd->prepare('SELECT prenoms_client FROM client WHERE id_client=:id');
    $nom_client->execute(array('id'=>$id_client));
    $nom= $nom_client->fetch();
    return $nom['prenoms_client'];
}
function affichage_reservation($bdd){
    if (isset($_SESSION['id'])){
        $afficher_reservation = $bdd->prepare('SELECT * FROM service WHERE Id_client=:id');
        $afficher_reservation->execute(array('id'=>$_SESSION['id']));
        ?>
        <div style="display: grid; grid-template-columns: repeat(9, 1fr)" class="text-center">
            <span class="btn-primary">id reservation</span>
            <span class="btn-primary">Cooperative</span>
            <span class="btn-primary">Nom client</span>
            <span class="btn-primary">choix vehicule(nbr place)</span>
            <span class="btn-primary">place</span>
            <span class="btn-primary">Depart</span>
            <span class="btn-primary">Arrivé</span>
            <span class="btn-primary">Date de depart</span>
            <span class="btn-primary"></span>
            <?php
            while ($donne=$afficher_reservation->fetch()){
                ?>
                <span><?php echo $donne['Id_service']?></span>
                <span><?php echo $donne['Cooperative']?></span>
                <span><?php echo recup_nom_client($bdd, $donne['id_client'])?></span>
                <span><?php echo $donne['choix_vehicule']?></span>
                <span><?php echo $donne['Choix_place']?></span>
                <span><?php echo $donne['Depart']?></span>
                <span><?php echo $donne['Arrive']?></span>
                <span><?php echo $donne['Date_depart']?></span>
                <div class="">

                    <form method="post" action="page_client.php">
                        <input type="hidden" name="id" value="<?php echo $donne['Id_service']?>">
                        <input type="submit" name="modifier_reservation" value="Modifer" class="btn btn-success">
                    </form>
                    <form method="post" action="traitement.php">
                        <input type="hidden" name="id" value="<?php echo $donne['Id_service']?>">
                        <input type="submit" name="supprimer" value="Supprimer" class="btn-danger btn">
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }


}
function affichage_reservation_personnel($bdd){
        $afficher_reservation = $bdd->query('SELECT * FROM service');
        ?>
        <div style="display: grid; grid-template-columns: repeat(8, 1fr)" class="text-center">
            <span class="btn-primary">id reservation</span>
            <span class="btn-primary">Cooperative</span>
            <span class="btn-primary">Nom client</span>
            <span class="btn-primary">choix vehicule(nbr place)</span>
            <span class="btn-primary">place</span>
            <span class="btn-primary">Depart</span>
            <span class="btn-primary">Arrivé</span>
            <span class="btn-primary">Date de depart</span>

            <?php
            while ($donne=$afficher_reservation->fetch()){
                ?>
                <span><?php echo $donne['Id_service']?></span>
                <span><?php echo $donne['Cooperative']?></span>
                <span><?php echo recup_nom_client($bdd, $donne['id_client'])?></span>
                <span><?php echo $donne['choix_vehicule']?></span>
                <span><?php echo $donne['Choix_place']?></span>
                <span><?php echo $donne['Depart']?></span>
                <span><?php echo $donne['Arrive']?></span>
                <span><?php echo $donne['Date_depart']?></span>
                <?php
            }
            ?>
        </div>
        <?php
}
function affichage_personnel($bdd){
        $afficher_reservation = $bdd->query('SELECT * FROM personnel ');
        ?>
        <h1>Liste des personnels</h1>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr)" class="text-center">
            <span class="btn-primary">id personnel</span>
            <span class="btn-primary">Nom personnel</span>
            <span class="btn-primary">Fonction</span>
            <span class="btn-primary">telephone</span>
            <span class="btn-primary"></span>
            <?php
            while ($donne=$afficher_reservation->fetch()){
                ?>
                <span><?php echo $donne['Id_personnel']?></span>
                <span><?php echo $donne['User_personnel']?></span>
                <span><?php echo $donne['Fonction']?></span>
                <span><?php echo $donne['Tel_personnel']?></span>
                <div>
                    <form method="post" action="page_personnel.php">
                        <input type="hidden" name="id" value="<?php echo $donne['Id_personnel']?>">
                        <input type="submit" name="modifier_personnel" value="Modifer" class="btn btn-success">
                    </form>
                    <form method="post" action="traitement.php">
                        <input type="hidden" name="id" value="<?php echo $donne['Id_personnel']?>">
                        <input type="submit" name="supprimer_personnel" value="Supprimer" class="btn btn-danger">
                    </form>

                </div>
                <?php
            }
            ?>
        </div>
        <?php

}
function formulaire_modification_reservation($bdd){
    $afficher_reservation = $bdd->prepare('SELECT * FROM service WHERE Id_service=:id');
    $afficher_reservation->execute(array('id'=>$_POST['id']));
    $reservation = $afficher_reservation->fetch();
    ?>
    <html>
    <head>
        <title>
            page de reservation
        </title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <form method="post" action="traitement.php">
        <input type="hidden" name="id" value="<?php echo $_SESSION['id'];?>"
        <label for="cooperative">cooperative</label><br>
        <select name="cooperative" id="cooperative">
            <option value="<?php echo $reservation['Cooperative']?>"><?php echo $reservation['Cooperative']?></option>
            <option value="sotrate">Sotrate</option>
            <option value="soa_trans">Soa trans</option>
        </select><br>
        <div class="choix_place">
            <label for="15_place">15 places</label>
            <input type="radio" name="type_voiture" id="15_place" <?php if ($reservation['choix_vehicule']==15){echo 'checked';}?> value="15">
            <input type="radio" name="type_voiture" id="19_place" value="19" <?php if ($reservation['choix_vehicule']==15){echo 'checked';}?>>
            <label for="19_place">19 places</label><br>
            <div class="place_15">
                <label for="choix_place">Choix place</label><br>
                <label for="p15">choix dans les 15 places</label><br>
                <select name="15_place" id="p15">
                    <option value="<?php echo $reservation['Choix_place']?>"><?php echo $reservation['Choix_place']?></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                </select>
            </div> <br>
            <label for="p19">choix dans les 19 places</label>
            <div class="place_19">
                <select name="19_place" id="p19">
                    <option value="<?php echo $reservation['Choix_place']?>"><?php echo $reservation['Choix_place']?></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                </select>
            </div>
        </div>
        <label for="depart">Départ</label><br>
        <select name="depart" id="depart">
            <option value="<?php echo $reservation['Depart']?>"><?php echo $reservation['Depart']?></option>
            <option value="tana">Tana</option>
            <option value="antsirabe">Antsirabe</option>
        </select>
        <label for="date_depart">Date de depart</label><br>
        <input type="date" name="date_de_depart" id="date_depart" value="<?php echo $reservation['Date_depart']?>"><br>
        <label for="arrive">Arrivée</label><br>
        <select name="arrive" id="arrive">
            <option value="<?php echo $reservation['Arrive']?>"><?php echo $reservation['Arrive']?></option>
            <option value="tana">Tana</option>
            <option value="antsirabe">Antsirabe</option>
        </select>
        <input type="submit" name="modifier_reserver" value="Reserver">
    </form>

    </body>
    </html>
    <?php
}
function formulaire_reservation($bdd){
    ?>
    <html>
    <head>
        <title>
            page de reservation
        </title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="asset/css/bootstrap.css">
    </head>
    <body style="font-size: 20px">
    <form method="post" action="traitement.php" class="container">
        <h3 class="text-primary">Remplisser le formulaire de reservation</h3>
        <input type="hidden" name="id" value="<?php echo $_SESSION['id'];?>"
        <label for="cooperative" class="">cooperative</label>
        <select name="cooperative" id="cooperative" class="form-select" >
            <option value="sotrate">Sotrate</option>
            <option value="soa_trans">Soa trans</option>
        </select>
        <div class="choix_place">
            <label for="choix_place">Choix place</label><br>
            <label for="15_place">15 places</label>
            <input type="radio" name="type_voiture" id="15_place" checked value="15">
            <input type="radio" name="type_voiture" id="19_place" value="19">
            <label for="19_place">19 places</label><br>
            <div class="place_15">

                <label for="p15">choix dans les 15 places</label>
                <select name="15_place" class="form-select" id="p15">
                    <option value="" ></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                </select>
            </div> <br>
            <div class="place_19">
                <label for="p19">choix dans les 19 places</label>
                <select name="19_place" class="form-select" id="p19">
                    <option value=""></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                </select>
            </div>
        </div>
        <label for="depart">Départ</label><br>
        <select name="depart" class="form-select" id="depart">
            <option value="tana">Tana</option>
            <option value="antsirabe">Antsirabe</option>
        </select>
        <label for="date_depart">Date de depart</label><br>
        <input type="date" name="date_de_depart" class="form-control" id="date_depart"><br>
        <label for="arrive">Arrivée</label><br>
        <select name="arrive" id="arrive"class="form-select">
            <option value="tana">Tana</option>
            <option value="antsirabe">Antsirabe</option>
        </select>
        <input type="submit" name="reserver" value="Reserver" class="btn-primary btn">
    </form>
    <?php affichage_reservation($bdd);?>
    </body>
    </html>
    <?php
}
function formulaire_modifier_personnel($bdd){
    $compte_log_in = $bdd->prepare('SELECT * FROM personnel WHERE Id_personnel=:id ');
    $compte_log_in->execute(array('id'=>htmlspecialchars($_POST['id'])));
    $donne=$compte_log_in->fetch();
    ?>
    <form method="post" action="traitement.php">
        <h1>Pour le personnel</h1>
        <label for="prenom">Nom</label><br>
        <input type="text" name="prenom" id="prenom" value="<?php echo $donne['User_personnel']?>"><br>
        <label for="Tel">Tel</label><br>
        <input type="number" name="Tel" id="Tel" value="<?php echo $donne['Tel_personnel']?>"><br>
        <select name="fonction">
            <option value="<?php echo $donne['Fonction']?>"><?php echo $donne['Fonction']?></option>
            <option value="chauffeur">Chauffeur</option>
            <option value="bagagiste">bagagiste</option>
            <option value="caissiere">caissiere</option>
            <option value="mecanicien">mecanicien</option>
            <option value="responsable_reservation">responsable reservation</option>
        </select><br>
        <input type="hidden" name="id" value="<?php echo $donne['Id_personnel']?>">
        <input type="submit" name="enregistrer_modification_personnel" value="Enregistrer">
    </form>
    <?php
}

function deconexion(){
    session_start();
    session_abort();
    session_destroy();
    header('location:index.php');
}
function bouton_deconnexion(){
    ?>
    <form method="post" action="traitement.php">
        <input type="submit" name="deconnexion" value="Deconnexion" class="btn btn-danger">
    </form>
    <?php
}