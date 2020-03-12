<?php
session_start();
//Connexion a BDD
$pdo =new PDO('mysql:host=localhost; dbname=rs; charset=utf8','root','');

if(isset($_SESSION['statut']) && isset($_SESSION['nom']) && !empty($_SESSION['statut'] && $_SESSION['nom'])):

    // Setup Erreurs
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    $queryCateRS = $pdo->query('SELECT catRS.ID, catRS.Nom FROM catRS');
    if($queryCateRS):
        //récupération des colonnes sélectionées
        $CateRS = $queryCateRS->fetchAll(PDO::FETCH_ASSOC);
    else:
        $CateRS=false;
    endif;
    //fin nouvelle requête

    $queryCatePE = $pdo->query('SELECT catPE.ID, catPE.Nom FROM catPE');
    if($queryCatePE):
        $CatePE = $queryCatePE->fetchAll(PDO::FETCH_ASSOC);
    else:
        $CatePE=false;
    endif;

    $queryCateA = $pdo->query('SELECT catAutre.id, catAutre.nom FROM catAutre');
    if($queryCateA):
        $CateA = $queryCateA->fetchAll(PDO::FETCH_ASSOC);
    else:
        $CateA=false;
    endif;

    $salcontent = $pdo->prepare("SELECT * FROM contenu WHERE cat_Doc ='Recherche Emploi' ORDER BY date_publication DESC");
    $salcontent->execute();
    $content = $salcontent->fetchAll(PDO::FETCH_ASSOC);

endif;

//$catcontenu=$_POST["cat"];
//$filtrage = $pdo->prepare("SELECT * FROM content WHERE nom_catPE == $catcontenu");

if(!isset($_SESSION['statut']) || !isset($_SESSION['nom']) || empty($_SESSION['statut'] || $_SESSION['nom']))
    {header("Location:../connexion/connexion.html");}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
    <title>Recherche Emploi</title>
    <link rel="shortcut icon" type="image/png" href="../favicon.png">
</head>
<body id ="ps">
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="background">
                    <img class="responsive-image" src="../img/bg.jpg">
                </div>
                <a class="sidenav-close" href="#!"><i class="material-icons small right">clear</i></a>
                <span><a class="waves-effect" href="https://www.facebook.com/R%C3%A9gies-Services-1962520857136604/"target="_blank"><img src="../img/fb.png" style="width:30%;" class="img-fluid"></a><p class="white-text">Notre Facebook</p></span>
                <span class="white-text name"><b><?=$_SESSION['nom']?></b></span>
<?php if($_SESSION['statut']=="salaries"){?><span class="white-text">Groupe <?=$_SESSION['groupe']?> ! </span>
                <br>
                <?php } ?>
                <span class="white-text"><strong>Recherche Emploi</strong></span>
            </div>
        </li>
        <?php if($_SESSION['statut'] == 'admin'){?><li><a  class="waves-effect" href="../admin/admincontent.php"><i class="material-icons">lock</i>Admin</a></li><?php } ?>
        <li><a class="waves-effect" href="actualitessal.php"><i class="material-icons">comment</i>Actualités</a></li>
        <li><a class="waves-effect" href="docsutiles.php"><i class="material-icons">description</i>Documents Utiles</a></li>
        <li><div class="divider"></div></li>
        <li><a class="waves-effect" href="../connexion/logout.php"><i class="material-icons">exit_to_app</i>Déconnexion</a></li>
        <br>
        <br>
        <img id="logobar" src="../img/rs.gif">
    </ul>
    <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons medium">menu</i><span>Menu</span></a>
    <br>
    <div class="containers">
        <h1 id="ts" class="retroshadow">Recherche Emploi</h1>
        <p> Bienvenue sur la page Recherche Emploi !</p>
        <br>
        <p>Ici vous trouverez tout ce qui concerne les offres d'emplois, de formations ainsi que les coordonnées de forums ou d'information(s) collective(s) parmi tant autres !</p>
        <br>
        <p>Cliquez sur l'image quand il y en a une pour la zoomer !</p>
        <p> Et sur le l'image PDF pour voir le fichier PDF ! </p> 
        <br>
    </div>
        <!--début contenu-->
    <?php foreach($content as $contenu):?>
<?php if($_SESSION['statut'] == 'admin') { ?>
<div class="containers">
    <div class="row">
        <div class="col s12 m7">
            <div id="sc" class="card">
                <strong><span id="stitle"><?=$contenu["nom_contenu"] ?></span></strong>
                <br>
                <br>
                <div class="date"><?= "Date de publication : " .$contenu["date_publication"] ?></div>
                <br>
                <br>
                <div class="cat"><?="Catégorie : " .$contenu["nom_catPE"] ?>
                <br>
                </div>
                <br>
                <br>
                <?php if($contenu["img"]=="pdflogo.png"){?>
                    <div id="saldcico" class="card-image">
                        <a href="../uploads/RE/<?= $contenu["pieces_jointes"]?>"><img id="imgsico" src="../uploads/RE/<?=$contenu["img"] ?>"  class="img-fluid" alt="Responsive image"></a>
                    </div>
                    <p style="line-height:80px;">Fichier PDF</p>
                    <br>
                    <div class="card-content"><?=$contenu["texte"] ?></div>
                    <br>
                <?php } elseif($contenu["img"]=="docx.png"){?>
                    <div id="saldcico" class="card-image">
                        <a href="../uploads/RE/<?= $contenu["pieces_jointes"]?>"><img id="imgsico" src="../uploads/RE/<?=$contenu["img"] ?>"  class="img-fluid" alt="Responsive image"></a>
                    </div>
                    <p style="line-height:80px;">Fichier WORD</p>
                    <br>
                    <div class="card-content"><?=$contenu["texte"] ?></div>
                    <br>
                <?php } else{ ?>
                    <img id="imgs" src="../uploads/RE/<?=$contenu["pieces_jointes"] ?>" class="materialboxed" alt="Responsive image">
                    <div class="card-content">
                        <?=$contenu["texte"] ?><?php } ?>
                    </div>
                <br>
            </div>
        </div>
    </div>
</div>         
<?php } elseif($contenu['nom_catRS']==$_SESSION['groupe']||$contenu['nom_catRS']=='Tous') { ?>
    <div class="containers">
        <div class="row">
            <div class="col s12 m7">
                <div id="sc" class="card">
                    <strong><span id="stitle"><?= $contenu["nom_contenu"] ?></span></strong>
                    <br>
                    <br>
                    <div class="date"><?="Date de publication : " .$contenu["date_publication"] ?></div>
                    <br>
                    <br>
                    <div class="cat"><?=  "Catégorie : " .$contenu["nom_catPE"] ?>
                    <br>
                    </div>
                    <br>
                    <br>
                    <?php if($contenu["img"]=="pdflogo.png"){?>
                        <div id="saldcico" class="card-image">
                            <img id="imgsico" src="../uploads/RE/<?=$contenu["img"] ?>" class="img-fluid" alt="Responsive image">
                        </div>
                        <p style="line-height:80px;">Fichier PDF</p>
                        <br>
                        <div class="card-content"><?=$contenu["texte"] ?></div>
                        <br>
                        <div class="card-action">  
                            <a id ="valdc" class="waves-effect waves-light btn hoverable" href="../uploads/RE/<?= $contenu["pieces_jointes"]?>"><p id=dltdc>Voir le document</p></a> 
                        </div>
                    <?php } else{ ?>
                        <img id="imgs" src="../uploads/RE/<?=$contenu["pieces_jointes"] ?>" class="materialboxed" alt="Responsive image">
                    <div class="card-content">
                        <div><?=$contenu["texte"] ?></div><?php } }?>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>             
<br>
<?php  endforeach;?>    
</body>
<script src="../js/init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</html>