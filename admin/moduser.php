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

$error="L'utilisateur existe déja ou l'identifiant et/ou le mot de passe existe(nt) déja.";


if(isset($_SESSION['statut']) && !empty($_SESSION['statut']) && $_SESSION['statut'] == "admin" && isset($_SESSION['nom']) && !empty($_SESSION['nom'])):

    if(isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['user']) && !empty($_POST['user']) && isset($_POST['pwd']) && !empty($_POST['pwd']) && isset($_POST['inlineRadioOptions']) && !empty($_POST['inlineRadioOptions']) && isset($_POST['radiogpe']) && !empty($_POST['radiogpe'])):
        
        // on affecte les $_POST à des variables 
        // et on transforme les données des inputs à entrée libre en texte pour plus de sécurité (failles XSS entre autres)

        $personne = $_POST["dropdownmod"];
        $nom = htmlspecialchars($_POST['nom']);
        $user = htmlspecialchars($_POST['user']);
        $pwd= htmlspecialchars($_POST['pwd']);
        $password = PASSWORD_HASH($pwd, PASSWORD_DEFAULT);
        $verifuser = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE nom = :nom OR identifiant = :user OR  mdphash = :pwd");
        $verifuser->bindParam(':nom',$nom,PDO::PARAM_STR);
        $verifuser->bindParam(':user',$user,PDO::PARAM_STR);
        $verifuser->bindParam(':pwd',$password,PDO::PARAM_STR);
        $verifuser->execute();
        $result = $verifuser->fetchColumn();

        if($result > 0){
             $resultat=$error;
        }
        else{
        $statut = $_POST['inlineRadioOptions'];
        $groupe = $_POST['radiogpe'];

        //prep et execution query modif utilisateur dans BDD
        $moduser = $pdo->prepare("UPDATE utilisateurs 
        SET nom = :nom, identifiant = :user, mdphash = :passworded, statut = :statut 
        WHERE utilisateurs.nom =:personne");
        $moduser->bindParam(':nom',$nom,PDO::PARAM_STR);
        $moduser->bindParam(':user',$user,PDO::PARAM_STR);
        $moduser->bindParam(':passworded',$password,PDO::PARAM_STR);
        $moduser->bindParam(':statut',$statut,PDO::PARAM_STR);
        $moduser->bindParam(':personne',$personne,PDO::PARAM_STR);
        $moduser->execute();

        $modbelonging = $pdo ->prepare("UPDATE appartenance SET utilisateurs_nom =:nom, catRS_nom = :groupe WHERE appartenance.utilisateurs_nom = :personne");
        $modbelonging->bindParam(':personne',$personne,PDO::PARAM_STR);
        $modbelonging->bindParam(':nom',$nom,PDO::PARAM_STR);
        $modbelonging->bindParam(':groupe',$groupe,PDO::PARAM_STR);
        $modbelonging ->execute();
        $success="Utilisateur $personne modifié !";
        $resultat=$success;
        }

    endif;

    else:
        header("Location:./admin/admincontent.php");

endif;

$personne = $_POST["dropdownmod"];
$nom = htmlspecialchars($_POST['nom']);
$success="Utilisateur $personne modifié!";


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
    <title>Modifier Utilisateur</title>
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
                <?php if($result==0) { ?>
                    <p id="dct" style="text-align:center"><?=$success?></p>
                    <br>
                    <br>
                    <div class="card-action">
                        <button id="val" type="submit"class="waves-effect waves-light btn hoverable"  onclick="self.location.href='admincontent.php'">Retour sur Admin</button>
                    </div>
                <?php } else {?>
                    <p id="dct" style="text-align:center"><?=$error;?>
                    <br>
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