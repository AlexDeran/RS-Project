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

$error="Une erreur est survenue, veuillez réessayez";

if(isset($_SESSION['statut']) && !empty($_SESSION['statut']) && $_SESSION['statut'] == "admin" && isset($_SESSION['nom']) && !empty($_SESSION['nom'])):

    if(isset($_POST["dropdownsupprc"]) && !empty($_POST["dropdownsupprc"])):
        
        $content = $_POST["dropdownsupprc"];

        $delcontent = $pdo->prepare("DELETE FROM contenu WHERE nom_contenu = :content");
        $delcontent->bindParam(':content',$content,PDO::PARAM_STR);
        $delcontent->execute();

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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Supprimer Ressources</title>
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
                <?php if($delcontent) { ?>
                    <p id="dct" style="text-align:center;">Contenu <?=$content ?> Supprimé !</p>
                    <br>
                    <div class="card-action">
                        <button id="val" type="submit"class="waves-effect waves-light btn hoverable"  onclick="self.location.href='admincontent.php'">Retour sur Admin</button>
                        <button id="val" type="submit"class="waves-effect waves-light btn hoverable"  onclick="self.location.href='../polesalaries/actualitessal.php'">Continuez vers Pôle Salariés</button>
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