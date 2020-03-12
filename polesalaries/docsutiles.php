<?php
session_start();
//Connexion a BDD
$pdo =new PDO('mysql:host=localhost; dbname=rs; charset=utf8','root','');

if(isset($_SESSION['statut']) && isset($_SESSION['nom']) && !empty($_SESSION['statut'] && $_SESSION['nom'])):

    // Setup Erreurs
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $docsuseRS = $pdo->prepare("SELECT * FROM contenu WHERE cat_Doc ='Documents Utiles' AND cat_DocsUtiles = 'Régies Services'");
    $docsuseRS->execute();
    $docsRS = $docsuseRS->fetchAll(PDO::FETCH_ASSOC);

    $docsuseST = $pdo->prepare("SELECT * FROM contenu WHERE cat_Doc ='Documents Utiles' AND cat_DocsUtiles = 'Stages'");
    $docsuseST->execute();
    $docsST = $docsuseST->fetchAll(PDO::FETCH_ASSOC);

    $docsuseTRE = $pdo->prepare("SELECT * FROM contenu WHERE cat_Doc ='Documents Utiles' AND cat_DocsUtiles = 'Techniques recherche emploi'");
    $docsuseTRE->execute();
    $docsTRE = $docsuseTRE->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
    <title>Mes Documents Utiles</title>
    <link rel="shortcut icon" type="image/png" href="../favicon.png">

</head>
<body id="ad">
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="background">
                    <img class="responsive-image" src="../img/bg.jpg">
                </div>
                <a class="sidenav-close" href="#!"><i class="material-icons small right">clear</i></a>
                <span><a class="waves-effect" href="https://www.facebook.com/R%C3%A9gies-Services-1962520857136604/" target="_blank"><img src="../img/fb.png" style="width:30%;" class="img-fluid"></a><p class="white-text">Notre Facebook</p></span>
                <span class="white-text name"><b><?=$_SESSION['nom']?></b></span>
                <?php if($_SESSION['statut']=="salaries"){?><p class ="white-text">Groupe <?=$_SESSION['groupe']?> ! <?php } ?></p>
                <span class="white-text"><strong>Documents Utiles</strong></span>
            </div>
        </li>
        <?php if($_SESSION['statut'] == 'admin'){?><li><a  class="waves-effect" href="../admin/admincontent.php"><i class="material-icons">lock</i>Admin</a></li><?php } ?>
        <li><a class="waves-effect" href="actualitessal.php"><i class="material-icons">comment</i>Actualités</a></li>
        <li><div class="divider"></div></li>
        <li><a class="waves-effect" href="../connexion/logout.php"><i class="material-icons">exit_to_app</i>Déconnexion</a></li>
        <br>
        <br>
        <img id="logobar" src="../img/rstransparent.png">
    </ul>
    <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons medium">menu</i><span>Menu</span></a>
</div>
<div class="containers">
        <h1 id="tsdu" class="retroshadow">Documents Utiles</h1>
        <p> Bienvenue sur la page Documents Utiles !</p>
        <br>
        <p>Sur cette page, vous trouverez, comme son nom l'indique, des documents qui vous seront utiles dans la vie quotidienne et que vous pourrez télécharger pour les emmener partout avec vous ! </p>
        <br>
        <p>Cliquez sur le nom d'un fichier pour le télécharger !</p>
        <br>
        <br>
</div>
     <!-- foreach incomming -->
     <div class="container">
        <br>
        <h2 id="tsdu" class="retroshadow">Régies Services</h2>
        <div class="containerul">
            <ul>  
                <?php foreach($docsRS as $docsutiles):?>
                    <li><i style="font-size:medium;"class="material-icons prefix">get_app</i><a href="../uploads/DU/<?= $docsutiles['pieces_jointes'] ?>"download><?=$docsutiles["nom_contenu"]?></a></li>
                    <br>
                <?php endforeach;?>   
            </ul>
        <br>
        </div>
    </div>
    <div class="container">
        <br>
        <h2 id="tsdu" class="retroshadow">Stages</h2>
        <div class="containerul">
            <ul>  
                <?php foreach($docsST as $docsutiles):?>
                    <li><i style="font-size:medium;"class="material-icons prefix">get_app</i><a href="../uploads/DU/<?= $docsutiles['pieces_jointes'] ?>"download><?=$docsutiles["nom_contenu"]?></a></li>
                    <br>
                <?php endforeach;?>   
            </ul>
        <br>
        </div>
    </div>
    <div class="container">
        <br>
        <h2 id="tre" class="retroshadow">Recherche d'emploi</h2>
        <div class="containerul">
            <ul>  
                <?php foreach($docsTRE as $docsutiles):?>
                    <li><i style="font-size:medium;"class="material-icons prefix">get_app</i><a href="../uploads/DU/<?= $docsutiles['pieces_jointes'] ?>"download><?=$docsutiles["nom_contenu"]?></a></li>
                    <br>
                <?php endforeach;?>   
            </ul>
        <br>
        </div>
    </div> 



</body>
<script src ="../js/init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</html>