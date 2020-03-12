<?php

// on reprend la session déja existante en cours de l'utilisateur pour connaitre son identité lors des verifiactions.

session_start();

//on commence une nouvelle connexion à notre base de données

$pdo =new PDO('mysql:host=localhost; dbname=rs; charset=utf8','root','');

$success= "L'envoi a bien été effectué !";

/*isset() =  Détermine si une variable est définie et est différente de NULL.
empty() = Détermine si une variable est vide.
$_SESSION = une variable superglobale qui contient un tableau contenant de nombreuses informations sur l'utilisateur de la session en cours
Ici le isset verifie que $_SESSION est définie et le empty verifie si elle est non vide.
On vérifie aussi que $_SESSION[statut] soit bien égal à "admin" sinon on lui refusera l'accés à la page .
*/

if(isset($_SESSION['statut']) && !empty($_SESSION['statut']) && $_SESSION['statut'] == "admin" && isset($_SESSION['nom']) && !empty($_SESSION['nom'])):

// Aprés on vérifie que les données transmises par la page précédante via un formulaire rempli par l'utilisateur ne soit pas vides ou non définie.

    if(isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['radiocatdoc']) && !empty($_POST['radiocatdoc']) && isset($_FILES['sendfile']) && !empty($_FILES['sendfile'])):
        
        // On fait pareil en se qui concerne le fichier joint

        if (isset($_FILES['sendfile']) AND ($_FILES['sendfile']['error'] == 0)):

            // Et en fonction du choix de l'utilisateur on choisi un répertoire de destination pour le fichier.

            switch($_POST["radiocatdoc"]):

                case"Actualités":
                $uploaddir = '../uploads/actu/';
                break;

                case"Documents Utiles":
                $uploaddir = '../uploads/DU/';
                break;
                
            endswitch;

            //on determine une taille maximale pour notre fichier

            $uploadfile = $uploaddir . basename($_FILES['sendfile']['name']);
            $maxsize = 5000000;

            // si le fichier envoyé est superieur à la taille définie il sera réfusé.

            if ($_FILES['sendfile']['size'] < $maxsize): 
                
                // Ensuite on determine les extensions de fichiers que l'on autorise

                $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'pdf', 'svg', 'docx');

                //1. strrchr renvoie l'extension avec le point (« . »).

                //2. substr(chaine,1) ignore le premier caractère de chaine.

                //3. strtolower met l'extension en minuscules.

                // On verifie l'extension du fichier envoyé.

                $extension_upload = strtolower(  substr(  strrchr($_FILES['sendfile']['name'], '.')  ,1)  );

                if (in_array($extension_upload,$extensions_valides)):

                    // si elle est bonne on déplace notre fichier du dossier temporaire au dossier de destination défini plus haut.

                    $movefile = move_uploaded_file($_FILES['sendfile']['tmp_name'], $uploaddir . basename($_FILES['sendfile']['name']));
                    
                   // C'est seulement  à aprtir de maintenant que l'on s'occupe des données a rentrer dans notre BDD.

                    if ($movefile):
                        switch($_FILES['sendfile']['type']):

                            // En fonction du type/extension du document on procéde de maniére differente pour stocker les infomations du document dans la BDD.
                            
                            //ici les fichiers PDF

                            case "application/pdf":

                                // On attribue des variables aux données venant de la page précédente pour pouvoir les rentrer dans la Base De Données.

                                //htmlspecialchars() previent des failles XSS en empéchant de rentrer du code dans un champ de formulaire par exemple.

                                $nomarticle = htmlspecialchars($_POST["nom"],ENT_QUOTES);
                                if(!isset($_POST['textareasend'])){$_POST['textareasend']="Aucun message";}
                                $msg = $_POST['textareasend'];
                                $date = date ('y,m,j');
                                $filename = $_FILES['sendfile']['name'];
                                $pdfimg = 'pdflogo.png';
                                $catrs = $_POST["catrssend"];
                                $catpe = $_POST["catpesend"];
                                $catdoc= $_POST["radiocatdoc"];
                                if(!isset($_POST["radiocatdu"])){$_POST["radiocatdu"]="Aucune";}
                                $catdu = $_POST["radiocatdu"];

                                //on prépare notre requete pour inserer les données dans la BDD

                                $createcontent = $pdo->prepare("INSERT INTO contenu (nom_contenu, cat_Doc, cat_DocsUtiles, nom_catRS, nom_catPE, date_publication,texte,img,pieces_jointes) 
                                VALUES (:nom ,:catdoc, :catdu,:catrs,:catpe,:dated,:msg,:pdfimg,:filenamed)");
                                
                                //bindParam() evite de rentrer les variables en les liant avec une chaine de caractére dans les requetes afin d'eviter toute perte de données si on nous vole nos requetes.
                                
                                $createcontent->bindParam(':nom',$nomarticle,PDO::PARAM_STR);
                                $createcontent->bindParam(':catdoc',$catdoc,PDO::PARAM_STR);
                                $createcontent->bindParam(':catdu',$catdu,PDO::PARAM_STR);
                                $createcontent->bindParam(':catrs',$catrs,PDO::PARAM_STR);
                                $createcontent->bindParam(':catpe',$catpe,PDO::PARAM_STR);
                                $createcontent->bindParam(':dated',$date,PDO::PARAM_STR);
                                $createcontent->bindParam(':msg', $msg,PDO::PARAM_STR);
                                $createcontent->bindParam(':pdfimg', $pdfimg,PDO::PARAM_STR);
                                $createcontent->bindParam(':filenamed', $filename,PDO::PARAM_STR);

                                //on exécute notre requête

                                $createcontent->execute();
                            break;

                            //ici les fichiers Word

                            case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
                                $nomarticle = htmlspecialchars($_POST["nom"],ENT_QUOTES);
                                if(!isset($_POST['textareasend'])){$_POST['textareasend']="Aucun message";}
                                $msg = $_POST['textareasend'];
                                $date = date ('y,m,j');
                                $filename = $_FILES['sendfile']['name'];
                                $catrs = $_POST["catrssend"];
                                $catpe = $_POST["catpesend"];
                                $catdoc= $_POST["radiocatdoc"];
                                $dcimg = 'docx.png';
                                if(!isset($_POST["radiocatdu"])){$_POST["radiocatdu"]="Aucune";}
                                $catdu = $_POST["radiocatdu"];
                                $createcontent = $pdo->prepare("INSERT INTO contenu (nom_contenu, cat_Doc, cat_DocsUtiles, nom_catRS, nom_catPE, date_publication,texte,img,pieces_jointes) 
                                VALUES (:nom ,:catdoc, :catdu,:catrs,:catpe,:dated,:msg,:dcimg,:filenamed)");
                                $createcontent->bindParam(':nom',$nomarticle,PDO::PARAM_STR);
                                $createcontent->bindParam(':catdoc',$catdoc,PDO::PARAM_STR);
                                $createcontent->bindParam(':catdu',$catdu,PDO::PARAM_STR);
                                $createcontent->bindParam(':catrs',$catrs,PDO::PARAM_STR);
                                $createcontent->bindParam(':catpe',$catpe,PDO::PARAM_STR);
                                $createcontent->bindParam(':dated',$date,PDO::PARAM_STR);
                                $createcontent->bindParam(':msg', $msg,PDO::PARAM_STR);
                                $createcontent->bindParam(':dcimg', $dcimg,PDO::PARAM_STR);
                                $createcontent->bindParam(':filenamed', $filename,PDO::PARAM_STR);
                                $createcontent->execute();
                            break;

                            // ici les extensions par défaut, c'est a dire toute les extensions d'images autorisées plus haut.

                            default:
                                $nomarticle = htmlspecialchars($_POST["nom"],ENT_QUOTES);
                                if(!isset($_POST['textareasend'])){$_POST['textareasend']="Aucun message";}
                                $msg = $_POST['textareasend'];
                                $date = date ('y,m,j');
                                $filename = $_FILES['sendfile']['name'];
                                $catrs = $_POST["catrssend"];
                                $catpe = $_POST["catpesend"];
                                $catdoc= $_POST["radiocatdoc"];
                                if(!isset($_POST["radiocatdu"])){$_POST["radiocatdu"]="Aucune";}
                                $catdu = $_POST["radiocatdu"];
                                $createcontent = $pdo->prepare("INSERT INTO contenu (nom_contenu, cat_Doc, cat_DocsUtiles, nom_catRS, nom_catPE, date_publication,texte,img,pieces_jointes) VALUES (:nom ,:catdoc, :catdu,:catrs,:catpe,:dated,:msg,:filenamed,:filenamed)");
                                $createcontent->bindParam(':nom',$nomarticle,PDO::PARAM_STR);
                                $createcontent->bindParam(':catdoc',$catdoc,PDO::PARAM_STR);
                                $createcontent->bindParam(':catdu',$catdu,PDO::PARAM_STR);
                                $createcontent->bindParam(':catrs',$catrs,PDO::PARAM_STR);
                                $createcontent->bindParam(':catpe',$catpe,PDO::PARAM_STR);
                                $createcontent->bindParam(':dated',$date,PDO::PARAM_STR);
                                $createcontent->bindParam(':msg', $msg,PDO::PARAM_STR);
                                $createcontent->bindParam(':filenamed', $filename,PDO::PARAM_STR);
                                $createcontent->execute();
                            break;

                        endswitch;

                    endif;

                endif;
                
            endif;

        endif;
        
    endif;

    else:
    $erreur="Le fichier n'a pas pu être importer. 
    Le fichier est soit supérieur à 5 MO, soit son extension n'est pas valide ou alors le fichier à déja été importé précédemment. 
    Veuillez vérifier et réessayer si ce n'est pas le cas.";
                
endif;

    // on determine que si certains champs n'ont pas été remplis ont redirige vers la page preécédente.

    if(!isset($_POST['nom']) || empty($_POST['nom']) || !isset($_POST['radiocatdoc']) || empty($_POST['radiocatdoc']) || !isset($_FILES) || empty($_FILES)):
       header("Location: admincontent.php");
    endif;

// on créer un message d'erreur

$erreur="Le fichier n'a pas pu être importer. 
    Le fichier est soit supérieur à 5 MO, soit son extension n'est pas valide ou alors le fichier à déja été importé précédemment. 
    Veuillez vérifier et réessayer si ce n'est pas le cas.";

    //on vérifie le statut de l'utilisateur si il est salarié il n'a pas le droit de consulter la page et est redirigé.

if($_SESSION['statut'] == "salaries"):
    header("Location:../polesalaries/actualitessal.php");
endif;

// de même pour une session qui expire il sera déconnecté et renvoyé à l'écran de connexion pour se reconnecté.

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
    <title>Envoi de fichier</title>
    <link rel="shortcut icon" type="image/png" href="../favicon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
</head>
<body>
<body id="fakehome">
<div class="row">
    <div class="col s12 m6">
        <div class="cardacc">
            <div class="card-content">
                <i><span id ="greenRS">R</span><span id="blackrest">égies</span><span id ="greenRS"> S</span><span id="blackrest">ervices</span></i>
                <br>
                <br>
                <p id="dct"> <?php if(isset($movefile) AND $movefile){
                    echo $success;?>
                </p>
                <br>
                <br>
                <div class="card-action">
                    <button id="val" type="submit"class="waves-effect waves-light btn hoverable"  onclick="self.location.href='admincontent.php'">Retournez sur Admin</button>
                    <button id="val" type="submit"class="waves-effect waves-light btn hoverable"  onclick="self.location.href='../polesalaries/actualitessal.php'">Continuez vers Pôle Salariés</button>
                </div>
                <?php } else{ ?>
                <p id="dct">
                    <?=$erreur;?>
                </p>
                <br>
                <div class="card-action">
                    <button id="suppr" type="submit"class="waves-effect waves-light btn hoverable" onclick="self.location.href='admincontent.php'">Réessayer</button>
                </div>
               <?php
                }
                ?>
            </div>
        </div>
    </div>  
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script></body>
</html>