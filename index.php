<!DOCTYPE html>
<html>
<head>
    <title>reservation</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="asset/css/bootstrap.css">
</head>
<body class="container ">
<h1 class="text-primary">Reserver son voyage</h1>
<form method="post" action="traitement.php">
    <h3 class="">Pour les clients</h3>
    <label for="prenom" class="">Prenom</label><br>
    <input type="text" name="prenom" id="prenom" class="form-control-sm"><br>
    <label for="Tel">Tel</label><br>
    <input type="number" name="Tel" id="Tel" class="form-control-sm"><br><br>
    <input type="submit" name="Valider" value="Creer compte" class="btn btn-primary">
    <input type="submit" name="se_connecter" value="Se connecter" class="btn btn-primary">
</form>
<br>
<form method="post" action="traitement.php">
    <h3>Pour le personnel</h3>
    <label for="prenom">Nom</label><br>
    <input type="text" name="prenom" id="prenom"><br>
    <label for="Tel">Tel</label><br>
    <input type="number" name="Tel" id="Tel"><br><br>
    <input type="submit" name="connexion_personel" value="Se connecter" class="btn btn-primary">
</form>
</body>
</html>

