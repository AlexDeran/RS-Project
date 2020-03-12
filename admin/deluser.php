<?php
session_start();
$pdo =new PDO('mysql:host=localhost; dbname=rs; charset=utf8','root','');

//on vérifie que la connexion s'effectue correctement
if(!$pdo){
    echo "Erreur de connexion à la base de données.";
} 
else {
    //on setup pour les erreurs a retirer quand publication
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

$error="Un probléme est survenu, veuillez réessayer";

if(isset($_SESSION['statut']) && !empty($_SESSION['statut']) && $_SESSION['statut'] == "admin" && isset($_SESSION['nom']) && !empty($_SESSION['nom'])):

    if(isset($_POST['dropdownsuppr']) && !empty($_POST['dropdownsuppr'])):

        $personne = $_POST["dropdownsuppr"];

        $deluser = $pdo->prepare("DELETE FROM utilisateurs WHERE nom = :personne ");
        $deluser->bindParam(':personne',$personne,PDO::PARAM_STR);
        $deluser->execute();


        $delbelonging = $pdo ->prepare("DELETE FROM appartenance WHERE utilisateurs_nom = :personne ");
        $delbelonging->bindParam(':personne',$personne,PDO::PARAM_STR);
        $delbelonging ->execute();

    endif;

    else:
        header("Location:admincontent.php");
        
endif;

if($_SESSION['statut'] == "salaries"):
    header("Location:../polesalaries/actualitessal.php");
endif;

if(!isset($_SESSION['statut']) || !isset($_SESSION['nom']) || empty($_SESSION['statut'] || $_SESSION['nom'])):
    header("Location:../connexion/connexion.html");
endif;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Supprimer Utilisateur</title>
    <link rel="shortcut icon" type="image/png" href="../favicon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
</head>
<body id="fakehome">
<div class="row">
    <div class="col s12 m6">
        <div class="cardacc">
            <div class="card-content">
                <i><span id ="greenRS">R</span><span id="blackrest">égies</span><span id ="greenRS"> S</span><span id="blackrest">ervices</span></i>
                <br>
                <br>
                <?php if($deluser && $delbelonging) { ?>
                    <p id="dct" style="text-align:center;">Utilisateur <?= $personne ?> Supprimé !</p>
                    <br>
                    <div class="card-action">
                        <button id="val" type="submit"class="waves-effect waves-light btn hoverable"  onclick="self.location.href='admincontent.php'">Retour sur Admin</button>
                    </div>
                <?php } else {echo $error;?>
                    <br>
                    <div class="card-action">
                        <button id="suppr" type="submit"class="waves-effect waves-light btn hoverable"  onclick="self.location.href='admincontent.php'">Réessayer</button> 
                    </div>
                <?php }?>
            </div>
        </div>
    </div>  
</div>  

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script></body>
</html>