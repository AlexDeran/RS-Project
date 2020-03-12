<?php
session_start();
//DB login

$pdo =new PDO('mysql:host=localhost; dbname=rs; charset=utf8','root','');

//ACCESS RIGHTS VERIFICATION

if(isset($_SESSION['statut']) && !empty($_SESSION['statut']) && $_SESSION['statut'] == "admin" && isset($_SESSION['nom']) && !empty($_SESSION['nom'])):

//SQL QUERIES

    //QUERIES TO DETERMINE USER'S STATUS

    $queryadmin = $pdo->query('SELECT utilisateurs.id, utilisateurs.nom FROM utilisateurs WHERE utilisateurs.statut = "admin"');
    if($queryadmin):
        $admins = $queryadmin->fetchAll(PDO::FETCH_ASSOC);
    else:
        $admins=false;
    endif;

    $querysal = $pdo->query('SELECT utilisateurs.nom FROM utilisateurs WHERE utilisateurs.statut = "salaries"');
    if($querysal):
        $Sal = $querysal->fetchAll(PDO::FETCH_ASSOC);
    else:
        $Sal=false;
    endif;

    // WORK RELATED CATEGORIES QUERIES 

    $queryCateRS = $pdo->query('SELECT * FROM catRS');
    if($queryCateRS):
        $CateRS = $queryCateRS->fetchAll(PDO::FETCH_ASSOC);
    else:
        $CateRS=false;
    endif;

    $queryCatePE = $pdo->query('SELECT * FROM catPE');
    if($queryCatePE):
        $CatePE = $queryCatePE->fetchAll(PDO::FETCH_ASSOC);
    else:
        $CatePE=false;
    endif;

    $queryCateA = $pdo->query('SELECT * FROM catAutre');
    if($queryCateA):
        $CateA = $queryCateA->fetchAll(PDO::FETCH_ASSOC);
    else:
        $CateA=false;
    endif;
   
    //DOCUMENTS CATEGORIES QUERIES

    $querycontentactu = $pdo->query('SELECT * FROM contenu WHERE cat_Doc = "Actualités"');
    if($querycontentactu):
        $contentactu = $querycontentactu->fetchAll(PDO::FETCH_ASSOC);
    else:
        $contentactu=false;
    endif;

    $querycontentDURS = $pdo->query('SELECT * FROM contenu WHERE cat_Doc = "Documents Utiles" && cat_DocsUtiles = "Régies Services"');
    if($querycontentDURS):
        $contentDURS = $querycontentDURS->fetchAll(PDO::FETCH_ASSOC);
    else:
        $contentDURS=false;
    endif;

    $querycontentDUS = $pdo->query('SELECT * FROM contenu WHERE cat_Doc = "Documents Utiles" && cat_DocsUtiles = "Stages"');
    if($querycontentDUS):
        $contentDUS = $querycontentDUS->fetchAll(PDO::FETCH_ASSOC);
    else:
        $contentDUS=false;
    endif;

    $querycontentDUTRE = $pdo->query('SELECT * FROM contenu WHERE cat_Doc = "Documents Utiles" && cat_DocsUtiles = "Techniques recherche emploi"');
    if($querycontentDUTRE):
        $contentDUTRE = $querycontentDUTRE->fetchAll(PDO::FETCH_ASSOC);
    else:
        $contentDUTRE=false;
    endif;

endif;

//ACCESS RIGHTS VERIFICATION

if(!isset($_SESSION['statut']) || !isset($_SESSION['nom']) || empty($_SESSION['statut'] || $_SESSION['nom'])):
    header("Location:../connexion/connexion.html");
endif;


if($_SESSION['statut'] == "salaries"):
    header("Location:../polesalaries/actualitessal.php");
endif;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
    <title>Admin</title>
    <link rel="shortcut icon" type="image/png" href="../favicon.png">
    <script src="../js/textboxio/textboxio.js"></script>
</head>
<body id="ad">

<!--SIDENAV-->

    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="background">
                    <img class="responsive-image" src="../img/bg.jpg">
                </div>
                <span><a class="waves-effect" href="https://www.facebook.com/R%C3%A9gies-Services-1962520857136604/"target="_blank"><img src="../img/fb.png" style="width:30%;" class="img-fluid"></a><p class="white-text">Notre Facebook</p>
                <span class="white-text name"><b><?=$_SESSION['nom']?></b></span>
                <span class="white-text"><strong>Page Administration<strong></span>
            </div>
        </li>
        <li><a class="waves-effect" href="../polesalaries/actualitessal.php"><i class="material-icons">comment</i>Actualités</a></li>
        <li><a  class="waves-effect" href="../polesalaries/docsutiles.php"><i class="material-icons">description</i>Documents Utiles</a></li>
        <li><div class="divider"></div></li>
        <li><a class="waves-effect" href="../connexion/logout.php"><i class="material-icons">exit_to_app</i>Déconnexion</a></li>
        <br>
        <br>
        <img id="logobar" src="../img/rstransparent.png">
    </ul>

    <!--ADMIN PAGE-->

    <ul class="collapsible">
        <li>

        <!-- USER GESTION-->

            <div class="collapsible-header"><i class="material-icons">person</i>Gestion des Utilisateurs</div>
            <div class="collapsible-body">

            <!--CREATION-->

                <h2 id="ac" class="retroshadow">Création</h2>
                <form id="createuser" action="createuser.php" class="form" role="form" method="POST">

                <!--NAME,ID & PASSWORD-->

                    <p><strong>Nom</strong></p>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">person</i>
                        <input id="name" name="nom" type="text" class="validate" required>
                        <label for="name">Prénom puis Nom</label>
                    </div>
                    <br>
                    <p><strong>Identifiant</strong></p>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">account_circle</i>
                        <input id="user" name="user" type="text" class="validate" required>
                        <label for="user">Identifiant</label>
                    </div>
                    <br>
                    <p><strong>Mot de passe</strong></p>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">lock</i>
                        <input id="mdp" name="password" type="password" class="validate" required>
                        <label for="mdp">Mot de Passe</label>
                    </div>
                    <br>

                    <!--STATUS & GROUP-->

                    <p><strong>Quel statut pour le nouvel utilisateur ?</strong></p>
                    <br>
                    <div class="form-inline" required>
                        <label>       
                            <input class="with-gap" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="admin">
                            <span id="radio">Admin</span>
                        </label>
                    
                        <label>
                            <input class="with-gap" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="salaries">
                            <span id="radio">Salarié</span>
                        </label>   
                    </div>
                    <br>
                    <br>
                    <p><strong>Quel Groupe si salarié ?</strong> 
                    <br>
                    <br> 
                        <div class="form-inline">
                                <label>       
                                    <input class="with-gap" type="radio" name="radiogpe" id="inlineCheckbox1" value="Entretien Ménager">
                                    <span id="radio">Entretien Ménager</span>
                                </label>

                                <label>       
                                    <input class="with-gap" type="radio" name="radiogpe" id="inlineCheckbox1" value="Espaces Verts">
                                    <span id="radio">Espaces Verts</span>
                                </label>

                                <label>       
                                    <input class="with-gap" type="radio" name="radiogpe" id="inlineCheckbox1" value="Polyvalence">
                                    <span id="radio">Polyvalence</span>
                                </label>

                                <label>       
                                    <input class="with-gap" type="radio" name="radiogpe" id="inlineCheckbox1" value="Autre">
                                    <span id="radio">Autre</span>
                                </label>
                            </p>
                        </div>
                    </p>
                    <br>
                    <br>
                    
                    <!--VALIDATION BUTTON-->

                    <button id="val" type="submit" class="waves-effect waves-light btn hoverable">Créer
                        <i class="material-icons right">person_add</i>
                    </button>
                </form>
                <br>
                <hr>

                <!--MODIFICATION-->

                <h2 id="ac" class="retroshadow">Modification</h2>
                <p> Choisissez la personne dont les infos doivent être modifiées : </p>
                <br>
                <p>Renseigner impérativement tous les champs même si ils ne changent pas ! </p>
                <br>
                <form id="moduser" action="moduser.php" class="form" role="form" method="POST">
                    <select name="dropdownmod"> 
                        <?php foreach($Sal as $salaries):?>
                            <option><a class="dropdown-item" href=><?=$salaries['nom']?></a></option>
                        <?php endforeach;?>
                    </select>
                    <br>
                    <br>
                    <p><strong>Nouveau Nom</strong></p>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">person</i>
                        <input id="modname" name="nom" type="text" class="validate">
                        <label for="modname">Prénom puis Nom</label>
                    </div>
                    <br>
                    <p><strong> Nouvel Identifiant</strong></p>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">account_circle</i>
                        <input id="modusername" name="user" type="text" class="validate">
                        <label for="modusername">Identifiant</label>
                    </div>
                    <br>
                    <p><strong> Nouveau Mot de Passe </strong></p>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">lock</i>
                        <input id="mdphash" name="pwd" type="password" class="validate">
                        <label for="mdphash">Mot de Passe</label>
                    </div>
                    <br>
                    <p><strong> Nouveau Statut pour l'utilisateur ?</strong></p>
                    <br>
                    <div class="form-inline">
                        <label>      
                            <input class="with-gap" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="admin">
                            <span id="radio">Admin</span>
                        </label>

                        <label>
                            <input class="with-gap" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="salaries">
                            <span id="radio">Salarié</span>
                        </label> 
                    </div>
                    <br>
                    <br>
                    <p><strong>Nouveau Groupe ?</strong>  
                    <br>
                    <br> 
                    <div class="form-inline">
                        <label>       
                            <input class="with-gap" type="radio" name="radiogpe" id="inlineCheckbox1" value="Entretien Ménager">
                            <span id="radio">Entretien Ménager</span>
                        </label>

                        <label>       
                            <input class="with-gap" type="radio" name="radiogpe" id="inlineCheckbox1" value="Espaces Verts">
                            <span id="radio">Espaces Verts</span>
                        </label>

                        <label>       
                            <input class="with-gap" type="radio" name="radiogpe" id="inlineCheckbox1" value="Polyvalence">
                            <span id="radio">Polyvalence</span>
                        </label>
                        <label>       
                            <input class="with-gap" type="radio" name="radiogpe" id="inlineCheckbox1" value="Autre">
                            <span id="radio">Autre</span>
                        </label>
                    </div>
                    <br>
                    <br>
                    <button style="background-color:orange;" type="submit" class="waves-effect waves-light btn hoverable">Modifier
                        <i class="material-icons right">send</i>
                    </button>
                </form>
                <br>
                <hr>

                <!--USER DELETION-->

                <h2 id="ac" class="retroshadow">Suppression</h2>
                <p> Choisissez la personne dont les infos doivent être supprimées : </p>
                <form id="deluser" action="deluser.php" class="form" role="form" method="POST">
                    <div class="input-field col s12">
                        <select name="dropdownsuppr">
                            <option value="" disabled >Choissisez la personne à supprimer</option>
                            <?php foreach($Sal as $salaries):?>
                                <option><a class="dropdown-item" href=><?=$salaries['nom']?></a></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <br>
                    <button id="suppr" type="submit" class="waves-effect waves-light btn hoverable">Supprimer
                        <i class="material-icons right">clear</i>
                    </button>
                </form>  
            </div>
        </li>

        <!--DOCUMENTS DISSEMINATION-->

        <li>
            <div class="collapsible-header">
                <i class="material-icons">wifi</i>Diffusion de Documents
            </div>

            <!--RESSOURCES IMPORTATION-->

            <div class="collapsible-body">
                <h2 id="ac" class="retroshadow">Importation de Ressources</h2>
                <br>

                <!--FILE CREATION & IMPORTATION-->

                <form action="sendfile.php" method="POST" class="form" role="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <p><strong>Nom de la ressource</strong></p>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">description</i>
                            <input name="nom" id="salcontent" type="text" class="validate">
                            <label for="salcontent">Nom de la ressource</label>
                        </div>
                        <br>
                        <br>
                        <p><strong>Choissisez le fichier à importer</strong></p>
                        <br>
                        <div class="file-field input-field">
                            <div id="val" class="btn">
                                <i class="material-icons right">attach_file</i>
                                <span>Parcourir</span>
                                <input type="file" name="sendfile">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="Selectionner un fichier">
                            </div>
                        </div>
                        <small id="importfilerestriction" class="form-text text-muted">Taille maximale du fichier à uploader : 5 Mo.</small>
                        <br>
                        <small id="importfilerestriction" class="form-text text-muted">Extensions de fichiers supportées : jpg, jpeg, gif, png, pdf, svg et docx.</small>
                        <br>
                        <br>
                        <br>

                        <!-- DOCUMENTS CATEGORIES-->

                        <p><strong>Quelle catégorie de document ?</strong>  
                        <br>
                        <br>
                        <div class="form-inline">
                            <label>       
                                <input class="with-gap" type="radio" name="radiocatdoc" id="inlineCheckbox1" value="Actualités">
                                <span id="radio">Actualités</span>
                            </label>
                            <label>       
                                <input class="with-gap" type="radio" name="radiocatdoc" id="inlineCheckbox1" value="Documents Utiles">
                                <span id="radio">Documents Utiles</span>
                            </label>
                        </div>
                        </p>
                        <br>
                        <br>
                        <br>
                        <p><strong>Si Documents Utiles, de quelle sous catégorie est elle ?</strong>  
                        <br>
                        <br>
                        <div class="form-inline">
                            <label>       
                                <input class="with-gap" type="radio" name="radiocatdu" id="inlineCheckbox1" value="Régies Services">
                                <span id="radio">Régies Services</span>
                            </label>
                        
                            <label>       
                                <input class="with-gap" type="radio" name="radiocatdu" id="inlineCheckbox1" value="Stages">
                                <span id="radio">Stages</span>
                            </label>
                        
                            <label>       
                                <input class="with-gap" type="radio" name="radiocatdu" id="inlineCheckbox1" value="Techniques recherche emploi">
                                <span id="radio">Techniques recherche emploi</span>
                            </label>
                        </div>
                        </p>
                        <br>
                        <br>

                        <!-- SEND TO ONE GROUP ONLY POSSIBILITY-->

                        <p><strong>A quel groupe l'envoyer ?</strong></p>
                        <select name="catrssend">
                            <?php foreach($CateRS as $catRS):?>
                                    <option><a class="dropdown-item" href=><?=$catRS['nom']?></a></option>
                            <?php endforeach;?>
                        </select>
                        <br>
                        <br>

                        <!-- WORK CATEGORY ATTACHMENT-->

                        <p><strong> Quelle catégorie concerne t-il ?</strong></p>
                        <select name="catpesend">
                            <optgroup label="Groupes Globaux">
                                <?php foreach($CateA as $catA):?>
                                    <option><a class="dropdown-item" href=><?=$catA['nom']?></a></option>
                                <?php endforeach;?>
                            </optgroup>
                            <optgroup label="Groupes d'activités">
                                <?php foreach($CatePE as $catPE):?>
                                        <option><a class="dropdown-item" href=><?=$catPE['nom']?></a></option>
                                <?php endforeach;?>
                            </optgroup>
                        </select>
                        <br>
                        <br>

                        <!--TEXTBOX-->

                        <p><strong>Votre Message</strong></p>
                        <br>
                        <textarea id="mytextarea" name="textareasend" placeholder="Votre message ici"></textarea>
                    </div>
                    <br>
                    <button id="val" type="submit"class="waves-effect waves-light btn hoverable">Envoyer
                        <i class="material-icons right">send</i>
                    </button>
                </form>
                
                <!--RESSOURCES DELETION-->

                <h3 id="as" class="retroshadow">Suppression de ressources</h3>
                <p> Choisissez l'Actualité qui doit être supprimé : </p>
                <form id="delcontent" action="delcontent.php" class="form" role="form" method="POST">
                    <div class="input-field col s12">
                        <select class="icons" name="dropdownsupprc">
                            <option disabled selected>Choissisez le contenu à supprimer</option>
                            <optgroup label="Actualités">
                                <?php foreach($contentactu as $contenu):?>
                                    <option data-icon="../uploads/actu/<?=$contenu['img']?>" class="left"><a class="dropdown-item" href="bdd.php?id=<?=$contenu['id']?>"><?=$contenu['nom_contenu']?></a></option>        
                                <?php endforeach;?>
                            </optgroup>
                            <optgroup label="Documents Utiles">
                                <optgroup label="Régies Services">
                                <?php foreach($contentDURS as $contenuRS):?>
                                    <option data-icon="../uploads/DU/<?=$contenuRS['img']?>" class="left"><a class="dropdown-item" href="bdd.php?id=<?=$contenuRS['id']?>"><?=$contenuRS['nom_contenu']?></a></option>        
                                <?php endforeach;?>
                                </optgroup>
                                <optgroup label="Stages">
                                <?php foreach($contentDUS as $contenuS):?>
                                    <option data-icon="../uploads/DU/<?=$contenuRS['img']?>" class="left"><a class="dropdown-item" href="bdd.php?id=<?=$contenuS['id']?>"><?=$contenuS['nom_contenu']?></a></option>        
                                <?php endforeach;?>
                                </optgroup>
                                <optgroup label="Recherche d'Emploi">
                                <?php foreach($contentDUTRE as $contenuTRE):?>
                                    <option data-icon="../uploads/DU/<?=$contenuTRE['img']?>" class="left"><a class="dropdown-item" href="bdd.php?id=<?=$contenuTRE['id']?>"><?=$contenuTRE['nom_contenu']?></a></option>        
                                <?php endforeach;?>
                                </optgroup>
                            </optgroup>
                        </select> 
                    </div>
                    <br>  
                    <button id="suppr" type="submit" class="waves-effect waves-light btn hoverable">Supprimer
                        <i class="material-icons right">clear</i>
                    </button>
                </form>
            </div>      
        </li>
    </ul>  
<script type="text/javascript">
    var editor = textboxio.replace('#mytextarea');
</script>
<script src ="../js/init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</html>