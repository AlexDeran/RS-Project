
<!--SOURCES (aka DA BIBLE) :---> "https://openclassrooms.com/fr/courses/2091901-protegez-vous-efficacement-contre-les-failles-web";

<?php

# verif droits de session

    session_start();


    if (isset($_SESSION['statut']) AND $_SESSION['statut'] == "administrateur") {

    echo "Le code secret est 351633135153";

    }


    else {

        echo "Vous n'avez pas le droit d'être ici !";

    }





# faille XSS

    $pseudo = htmlspecialchars($_POST['pseudo']);

        echo "Bonjour ".$pseudo." !";





# faille include

    if (empty($page)) {

    $page = "accueil";

    // On limite l'inclusion aux fichiers.php en ajoutant dynamiquement l'extension

    // On supprime également d'éventuels espaces

    $page = trim($page.".php");


    }


    // On évite les caractères qui permettent de naviguer dans les répertoires

    $page = str_replace("../","protect",$page);

    $page = str_replace(";","protect",$page);

    $page = str_replace("%","protect",$page);


    // On interdit l'inclusion de dossiers protégés par htaccess

    if (preg_match("admin",$page)) {

    echo "Vous n'avez pas accès à ce répertoire";

    }


    else {


        // On vérifie que la page est bien sur le serveur

        if (file_exists($page) && $page != 'index.php') {

        include("./".$page); 

        }


        else {

            echo "Page inexistante !";

        }

    }






# faille upload

    // Varibale d'erreur par soucis de lisibilité

    $error = false;

    //file rename

    $file = $_FILES["MY_FILE"];

    $actualName = $file['tmp_name'];

    $newName = bin2hex(random_bytes(32));

    //choix du folder d'upload bien faire un chmod sur le dossier pour permettre l'ecriture et la lecture + htaccess

    $path = "/upload";

    // On crée un tableau avec les extensions autorisées

    $legalExtensions = array("JPG", "PNG", "GIF", "TXT", "PDF");


    // On récupère l'extension du fichier soumis et on vérifie qu'elle soit dans notre tableau

    $extension = pathinfo($file['MY_FILE'], PATHINFO_EXTENSION);


    if (in_array($extension, $legalExtensions)) {

        move_uploaded_file($actualName, $path.'/'.$newName.'.'.$extension);

    }  
    
    // Ce qui donnerait bien en forme

    // Varibale d'erreur par soucis de lisibilité

    // Evite d'imbriquer trop de if/else, on pourrait aisément s'en passer

    $error = false;


    // On définis nos constantes

    $newName = bin2hex(random_bytes(32));

    $path = "/upload";

    $legalExtensions = array("JPG", "PNG", "GIF", "TXT");

    $legalSize = "10000000"; // 10000000 Octets = 10 MO


    // On récupères les infos

    $file = $_FILES["MY_FILE"];

    $actualName = $file['tmp_name'];

    $actualSize = $file['size'];

    $extension = pathinfo($file['MY_FILE'], PATHINFO_EXTENSION);


    // On s'assure que le fichier n'est pas vide

    if ($actualName == 0 || $actualSize == 0) {

    $error = true;

    }


    // On vérifie qu'un fichier portant le même nom n'est pas présent sur le serveur

    if (file_exists($path.'/'.$newName.'.'.$extension)) {

    $error = true;

    }


    // On effectue nos vérifications réglementaires

    if (!$error) {

        if ($actualSize < $legalSize) {

            if (in_array($extension, $legalExtensions)) {

                move_uploaded_file($actualName, $path.'/'.$newName.'.'.$extension);

            }

        }

    }


    else {

    // On supprime le fichier du serveur

    @unlink($path.'/'.$newName.'.'.$extension);

    echo "Une erreur s'est produite";

    }

    // si image ou audio verif si pas de caractéres suspicieux ou du code caché dedans 

    // [...]

    $handle = fopen($nom, 'r');


    if ($handle) {


        while (!feof($handle) AND $erreur == 0) {


            $buffer = fgets($handle);


            switch (true) {

                case strstr($buffer,'<'):

                $error = true;

                break;


                case strstr($buffer,'>'):

                $erreur += 1;

                break;


                case strstr($buffer,';'):

                $erreur += 1;

                break;


                case strstr($buffer,'&'):

                $erreur += 1;

                break;


                case strstr($buffer,'?'):

                $erreur += 1;

                break;

            }

        }

    }

    fclose($handle);

    // [...]

    /*htaccess à coupler avec au dessus ? pas compris ^^

    deny from all

    <Files ~ “^w+.(gif|jpg|png|txt)$”>

    order deny,allow

    allow from all

    </Files>*/






# injection SQL

    // On récupère les variables envoyées par le formulaire

    $login = $_POST['login'];

    $password = $_POST['password'];


    // Connexion à la BDD en PDO

    try { $bdd = new PDO('mysql:host=localhost;dbname=bdd','root',''); }

    catch (Exeption $e) { die('Erreur : ' .$e->getMessage())  or die(print_r($bdd->errorInfo())); }


    // Requête SQL sécurisée

    $req = $bdd->prepare("SELECT * FROM utilisateurs WHERE login= ? AND password= ?");

    $req->execute(array($login, $password));







# faille CSRF "Cross site request forgery" (on peut pas s'en protéger totalement car applications de la faille quasi infinie :/ )

    // Authentification par jeton (token) 


        // --> ne pas utiliser 'uniqid()' <-- mauvaise idée car même si génère identifiant unique basé sur le temps en microsecondes valeur pas impossible à deviner.


        /* utiliser plutôt -->  <?php */ $token = bin2hex(random_bytes(32)); //?'>


            /* D'après la documentation PHP :


            */random_bytes(); // génére des pseudo-bytes sécurisés cryptographiquement à partir d'une source aléatoire.


        // Ici sert à pour générer un nombre (pseudo) aléatoire plus solide qu'en utilisant microtime()



        // Exemple de formulaire protégé par token


            // On démarre la session en début de chaque page
            
            session_start();
            
            
            //On enregistre notre token
            
            $token = bin2hex(random_bytes(32));
            
            
            $_SESSION['token'] = $token;
            
            
            ?>
            
            
            <!DOCTYPE html>
            
            <html>
            
            <head>
            
                    <meta charset="utf-8"/>
            
            <!--[if lt IE 9]>
            
                <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            
            <![endif]-->
            
                <link rel="stylesheet" href="test.css" />
            
                <title>Mon Site</title>
            
            </head>
            
            <body>
            
                <form>
            
                    <!-- Pseudo de la personne à supprimer -->
            
                    <input type="text" name="pseudo" id="pseudo" />
            
                    <input type="submit" value="valider" />
            
                    <!-- Notre token de vérification, bien caché -->
            
                    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
            
                </form>
            
            </body>
            
            </html>
            
    <?php

        // page PHP qui se chargerait de générer le token s’il n'existe pas déjà

        session_start();

        //On vérifie que tous les jetons sont là

        if (isset($_SESSION['token']) AND isset($_POST['token']) AND !empty($_SESSION['token']) AND !empty($_POST['token'])) {

            // On vérifie que les deux correspondent

            if ($_SESSION['token'] == $_POST['token']) {

                        // Vérification terminée

                        // On peut supprimer l'utilisateur
            }
        }

        else {

            // Les token ne correspondent pas

            // On ne supprime pas

            echo "Erreur de vérification";

        }

        /* Si on voulait faire encore mieux, on pourrait rajouter une variable de session qui enregistre l'heure de la création du token. 
        
            On met ensuite en place un système qui vérifie que le token n'a pas été créé il y a trop longtemps. 
        
            En général le délai d'expiration des token est de 10 minutes. 
        
            Il est également possible d'utiliser les jetons hors formulaire en les faisant passer dans l'URL comme ci-dessous.

            www.monsite.com/index.php?profile=mon_ennemi&action=supprimer&token22f2f68d45fe0baea8d064bdd4604391ba95752b4df6c85f478c56207addebb9 */


        /* Autres techniques en complément du token :

            Demande de confirmation

                Bon pour cette technique pas besoin d'épiloguer. 
                
                Il s'agit simplement de demander à l'administrateur de confirmer l'action avec un pop-up de confirmation ou même mieux, une confirmation par mot de passe. 
                
                Ainsi, on réduit encore plus le risque de suppression involontaire.
            
            Un petit captcha
            
                Une autre technique consiste à demander à l'administrateur de valider l'action en remplissant un captcha. 
                
                C'est tout bête et très efficace, mais pas très adapté si l'action est répétitive... */



        /* Idées reçues sur la protection :

            Beaucoup de gens rivalisent d'ingéniosité pour contrer cette faille. 
            
            Mais bien souvent, les petites bidouilles ne protègent rien du tout. 
            
            Voilà ce qui, contrairement aux idées reçues, ne vous protègera pas :

                Vérification par cookie ultra secret

                Oublier la méthode GET et n'utiliser que POST

                Ajouter plein d'étapes

                Utiliser de l'URL rewriting */







# Faille CRLF (Carriage Return Line Feed)



    /* Permet d'effectuer un retour chariot dans un champ du type input ou textarea.

    Elle est le plus souvent utilisée pour récupérer le mot de passe de quelqu'un, grâce à la fonction mail() de la page "mot de passe oublié.

    Il nous suffit de connaitre l'adresse mail de la victime et d'utiliser la faille pour nous mettre en copie de ce mail. */



    // S'en protéger en supprimant les retours à la ligne lors du traitement :

        // On récupère la valeur du input

        $chaine_utilisateur = $_POST['mail'];

        // On supprime les retour à la ligne

        $chaine_secure = str_replace(array("\n","\r",PHP_EOL),'',$chaine_utilisateur);



    // Vous pouvez également vérifier que la chaine de caractères entrée par l'utilisateur est bien une adresse mail en utilisant un filtre par exemple :

        $email = $_POST['mail'];


        if(filter_var($email, FILTER_VALIDATE_EMAIL)){

            // Valide

        }


        else {

            // Non valide

        }







# Attaque par force brute (Bruteforce)

    // Different des autres puisque pas une faille mais une longue attaque donc pas de moyens de protection vs une faille mais plus des moyens de rendre ce genre d'attaques inefficace  

        /* Késako ? ==> hacker créer meme formulaire que ton site. Puis avec un script essaye tout les combinaisons possibles de mdp. 
        
        OU ALORS ==> hacker se base sur d'énormes dictionnaires contenant des millions / milliards de mdp régulièrement utilisés.

        */





    // RALENTIR LE PROCESSUS
        
        //Ne rend pas impossible l'attaque par force brute mais la ralentit trés fortement (Si fortement qu'il faudrait des milliers d'années avant de trouver un mot de passe basique) :
            
            // LE PRINCIPE : Trés SIMPLE : Comme son nom l'indique juste ralentir le processus d'éxecution du formulaire en rajoutant une pause d'une seconde ^^ 

                ?>

                <html>

                <form method="post" action="connexion.php">
            
                <input type="text" name="pseudo">
            
                <input type="password" name="password">
            
                <input type="submit" value="connexion">
            
                </form>
            
                </html>
                
                
                <?php 
                
                
                if(isset($_POST['pseudo']) AND isset($_POST['password'])) {
                
                    
                
                    sleep(1); // Une pause de 1 sec
                
                    
                
                    //if... ==> Vérification des identifiants/password etc..
            
            
                }

                
            /*  Après soyons francs, il est possible d'exécuter plusieurs formulaires à la fois et le pirate pourra se servir d'un réseau botnet. 

            
            BOTNET ==> réseau d'ordinateurs infectés par le pirate. Bien que ces ordinateurs continuent à fonctionner normalement, 
            
            le pirate peut s'en servir pour exécuter des programmes à l'insu de leur utilisateur.

            
            Et là notre technique s'avère beaucoup moins efficace que prévue. C'est donc une bonne technique, mais elle ne vaut pas grand choses si elle est utilisée seule. 

            De plus, elle comporte certains inconvénients sur le plan technique. Bien qu'il ne s'agisse que d'une petit seconde, cela va monopoliser un thread d'apache pendant une seconde.

            Sur un site qui possède un faible trafic, cela ne posera pas trop de problème. 

            Mais si le serveur doit gérer simultanément plusieurs centaines de connexion, vous comprendrez alors à quel point cette technique ruine les performances du serveur. */





    // LE BANNISSEMENT D'IP (technique plus complexe mais bcp plus efficace)

        // EN GROS : Limiter le nombre de tentative par personne et par jour.


         
        //!\\ IL NE FAUT PAS BLOQUER le compte visé par l'attaque, MAIS EMPECHER le hacker de continuer à brutaliser notre formulaire. //!\\



            /* L'astuce consiste donc à créer une table qu'on appellera connexion et dans laquelle on enregistrera toutes les tentatives ratées de connexions au site.
            
            On y enregistrera simplement l'IP de la personne. Au-delà d'un certain nombre de tentatives, l'accès au compte avec cet IP devient impossible pendant un certain temps.*/

            
            
        // Un exemple avec au-delà de 10 tentatives ratées ==> l'IP est bannie jusqu'au lendemain. 

            
            // On créer déja notre script PHP enregistrer les connexions ratées


                // Pour simplifier voilà le password

                $password = "zero";


                // On se connecte à la bdd

                try { $bdd = new PDO('mysql:host=localhost;dbname=bdd','root',''); }

                catch (Exeption $e) { die('Erreur : ' .$e->getMessage())  or die(print_r($bdd->errorInfo())); }


                // On récupère l'IP du visiteur

                $ip = $_SERVER['REMOTE_ADDR'];

                

                // On regarde s'il est autorisé à se connecter

                $recherche = $bdd->prepare('SELECT * FROM connexion WHERE ip = ?');

                $recherche->execute(array($ip));

                $count = $recherche->rowCount();


                // Si l'ip a essayé de se connecter moins de 10 fois ce jour là

                if ($count < 10)


                    // Vérification classique du password

                    if ($_POST['password'] == $password) {

                        echo "Bravo vous êtes connecté";

                        }


                    else {

                        // On enregistre la tentative échouée pour cette ip

                        $req = $bdd->prepare('INSERT INTO connexion(ip) VALUES(:ip)');

                        $req->execute(array('ip' => $ip));

                        

                        echo "Mot de passe incorrecte";

                        }


                // Si la personne a déja essayé de se connecter 10 fois ce jour là

                else {

                echo "Désolé vous êtes banni jusqu'à demain";

                }





        // On peut aussi utiliser un CRON ==> petit programme qui se situe côté serveur et qui peut entre autre exécuter des actions à des heures précises.

            
            /* Il suffit donc dans le cas au dessus de créer un cron qui vide notre table connexion tous les jours à une heure précise et fixe.

            Voici un tuto pour apprendre à créer un CRON : http://www.commentcamarche.net/contents/1134-linux-ordonnancement-des-taches */



            //!\\ SI VOUS ETES HERBERGE SUR UN SERVEUR QUI NE VOUS APPARTIENT PAS ET/OU ETES EN MUTUALISE L'UTILISATION DU CRON PEUT ETRE PLUS COMPLIQUE. //!\\





        //Fail2ban 
                
            /* Fail2ban se décrit lui même dans sa doc offcielle comme :

            "Lisant des fichiers de log comme /var/log/pwdfail ou /var/log/apache/error_log et bannissant les adresses IP qui ont obtenu un trop grand nombre d'échecs lors de l'authentification. 

            Il met à jour les règles du pare-feu pour rejeter cette adresse IP. Ces règles peuvent êtres définies par l'utilisateur. 

            Fail2ban peut lire plusieurs fichiers de log comme ceux de sshd ou du serveur Apache. */
            

           /* Bien que ça soit un peu radical, cette solution est très efficace. 
           
           N'hésitez pas à la mettre en place, quitte à ajouter un service de récupération pour les utilisateur vraiment étourdis (avec une vérification humaine, pas un script.*/


     // La Vérification par CAPTCHA
     
     /*Cette dernière technique est très simple. 
     
     Elle consiste à insérer un captcha de vérification dans vos formulaires. Et c'est très efficace ! 
     
     C'est presque imparable pour être certain qu'on a à faire à un humain et non à un script.

     Pour un exemple de CAPTCHA , go voir celui de GOOGLE qui est en plus d'etre trés efficace est plutôt ok niveau design : */ "https://www.google.com/recaptcha/intro/v3beta.html";






# Les variables de Session

    // Le vol de session

        //Avant toute chose il faut savoir que lorsque vous vous connectez sur un site, une session unique est créée. Un ID unique est alors attribué à cette session : le PHPSESSID . 

        //C'est cet ID qui va permettre au serveur de vous reconnaitre. Et cela pose certains problèmes de sécurité:

        /*Imaginez un cas ou l'ordi d'utilisateur est infecté par un hacker: 

                    Quand l'utilisateur se connecte à notre site et navigue, le hacker va fouiner dans les données de navigation.
                    Il en profite pour récuperer le PHPSESSID de la personne et une fois sur le site le hacker remplace son PHPSESSID par celui de l'utilisateur.
                    Le serveur ne faisant pas la difference entre les 2, il reconnait le hacker comme l'utilisateur et le connecte.
                
                    Voici un schéma pour mieux comprendre :
        */
                    "https://sdz-upload.s3.amazonaws.com/prod/upload/sch%C3%A9ma%2021.jpg";


        //Pour s'en protéger, il faut définir l'utilisateur PRECISEMENT :

            //Cependant, il est tout simplement impossible d'être sûr à 100% de qui est réellement connecté puisque l'on peut pas faire confiance aux donées renvoyées par l'utilisateur ^^


            /*Pour cela, il va en fait falloir créer un système de communication entre le client et le serveur.
            On va en fait mettre en place un système de "tickets". Le serveur génère un ticket qu'il garde en mémoire. Il le stock ensuite dans les cookies de l'utilisateur. 
            A chaque fois que l'utilisateur demande une nouvelle page, le serveur vérifie qu'ils ont bien le même ticket avant d'en générer un nouveau pour la page suivante.

                        Exemple:    
            
                        1. L'utilisateur se connecte : début de la session

                        2. Il se rend sur la page "index.php".

                        3. Le serveur génère un ticket qui a pour valeur "ticket001"

                        4. Il l'enregistre simultanément dans une variable de session et dans les cookies de l'utilisateur

                        5. L'utilisateur change de page

                        6. Le serveur vérifie qu'ils ont tous les deux le même ticket

                        7. Il génère un nouveau ticket qui a pour valeur "ticket002" (par exemple)


            Si le hacker vole la session, il se retrouvera avec un ticket aléatoire et donc forcément différent de celui de l'utilisateur. 
            Lorsqu'il essaye de se rendre sur une page, le serveur réalise que les tickets sont différents et la session est détruite.

            Voici un aperçu du code de tout ça en code : */


                session_start();

                $cookie_name = "ticket";

                // On génère quelque chose d'aléatoire

                $ticket = session_id().microtime().rand(0,9999999999);

                // on hash pour avoir quelque chose de propre qui aura toujours la même forme

                $ticket = hash('sha512', $ticket);


                // On enregistre des deux cotés

                setcookie($cookie_name, $ticket, time() + (60 * 20)); // Expire au bout de 20 min

                $_SESSION['ticket'] = $ticket;


            //Viens ensuite les vérifs :


                # Pensez à ajouter des isset() dans le if() !

                # Je les ai volontairement retirés par soucis de lisibilité du code

                    

                session_start();


                if ($_COOKIE['ticket'] == $_SESSION['ticket'])

                {

                    // C'est reparti pour un tour

                    $ticket = session_id().microtime().rand(0,9999999999);

                    $ticket = hash('sha512', $ticket);

                    $_COOKIE['ticket'] = $ticket;

                    $_SESSION['ticket'] = $ticket;

                }

                else

                {

                    // On détruit la session

                    $_SESSION = array();

                    session_destroy();

                    header('location:index.php');

                }

            //Pensez également à vérifier que le navigateur de l'utilisateur accepte les cookies, sans quoi le script ne pourra pas fonctionner correctement.

            setcookie($name, $value, $time);

            if(!isset($_COOKIE[$name])) {

                // Le navigateur ne semble pas accepter les cookies

            }


            // Par soucis de sécurité on préférera un nom très peu explicite plutôt que "ticket" pour le ticket, cela attirera moins l'oeil du hacker :

                //Vous admetterez que ceci :

                    "gt_e : 5a2fb5772e35641024303c5a79163a63f9670cabb534d13737826a [...]";

                //est beaucoup moins explicite que ceci :
                
                    "ticket : 5a2fb5772e35641024303c5a79163a63f9670cabb534d13737826a [...]";


        /* ATTENTION //!\\ //!\\  Comme vous l'avez peut-être remarqué, ce système possède également une faille. Il ne peut fonctionner que si les deux personnes sont connectées en même temps. 
        Vous savez aussi bien que moi qu'une session ne se détruit automatiquement qu'après plusieurs minutes. 
        Donc si l'utilisateur se contente de quitter son ordinateur sans se déconnecter, le hacker n’aura qu'à prendre le relais en récupérant le dernier ticket attribué à l'utilisateur. 
        C'est pourquoi il est extrêmement important de sensibiliser les utilisateurs sur l'importance de se déconnecter manuellement après chaque session. 
        Vous pouvez également instaurer un système de déconnexion automatique après plusieurs minutes d’inactivité. //!\\ //!\\ FIN D'ATTENTION */



        /*La solution ultime consiste à passer en HTTPS avec un chiffrement SSL/TLS ce qui rendrait impossible tout type d'écoute. 
        Mais cette solution est couteuse et donc pas accessible à tous*/




    //L'empoisonnement de session

        //C'est exactement le même problème que pour les injections SQL. 
        //A partir du moment où vous autorisez l'utilisateur à envoyer des données qui seront stockées dans des variables de session, il en profitera pour injecter toute sorte de choses.
            // Voir les injections SQL et le htmlspecialchars() (plus haut) qui devrait suffire à sécuriser tout ça.




# Les Buffer Overflows ("dépassement de mémoire tampon")


    /* La faille ne se trouve pas dans le code, mais bien dans le langage lui-même ! (==> AH !) Enfin, il résulte d'une mauvaise utilisation du langage C qui gère le PHP. 
    Car oui, PHP est en fait construit en librairies C.*/

    // CEPENDANT bien que les Buffer Overflow soient vraiment complexes a sécuriser dans les applications en C, elle ne le sont pas en PHP.
    // MAIS Les développeurs ne touche jamais au code C derriére le PHP, donc c'est pas à nous de s'occuper de la sécurité à ce niveau la.
    // EN REVANCHE, on doit avoir les connaissances du ou , pourquoi et comment ont lieu ses attaques !



    // Meilleure métaphore du monde pour expliquer les Buffer Overflows :

            /* Bah si on peut pas les sécuriser nous même pas les Buffer Overflows on s'en fout un peu non ?

            //!\\NOPE. GROSSE ERREUR.//!\\

            Admettons que vous avez fermé votre porte blindée à double tour, construit des murs solides, clôturé votre jardin, mais laissé la fenêtre du premier étage grande ouverte.
            Alors peut-être que personne n'essaiera jamais de grimper jusque-là par manque de connaissance en escalade,
            mais le jour ou un Yamakasi réussira à rentrer, c'est toute la sécurité de votre maison qui sera compromise. 

            Bon je m'arrete la mais j'espère que vous avez saisi l'idée ^^ */



    // C'est bien les métaphores et tout la, mais d'ailleurs qu'es ce que c'est les Buffer Overflows ?


            /* Le principe des BO est simple. 

            Déja pour commencer il est bon de savoir que chaque donnée gérée par l'ordinateur est stockée temporairement dans un tampon avant d'être traitée;
            Et que chaque tampon contient une série de caractères.
            Dans le cas du langage C, des tampons sont systématiquement utilisés pour réguler les Entrées-Sorties du programme (Ex : la lecture ou l'écriture dans un fichier).
            A chaque fois qu'une entrée utilisateur doit être stockée, un espace est alloué par l'ordinateur. */

            // OK d'accord mais du coup elle est ou la faille ?

                /* J'y viens. Le principe du Buffer Overflow est simple : écrire plus de données que le tampon ne peut en contenir. 
                Le tampon va donc littéralement "déborder". 
                Cela aura pour effet d'écraser des parties du code de l'application et permettra au pirate d'injecter des données utiles pour l'exploitation.
                Lorsque le bug se produit, le comportement de l'ordinateur devient imprévisible. Il en résulte souvent un blocage du programme, voire de tout le système. 

                 Voilà une liste non-exhaustive de ce qu'il pourra faire avec votre serveur :

                    Injection de code (SQL ou non)

                    Exécution de code arbitraire (Le pirate peut alors prendre le contrôle total du serveur)

                    Attaque par déni de service (ou DOS) qui rendrait votre serveur indisponible

                    Utilisation de votre serveur pour d'autres attaques*/



            //Il faut quand même savoir que ce type d'attaque requiert une bonne connaissance du fonctionnement d'un ordinateur et de trés solides bases en programmation;
            //Ce n'est pas à la portée de tout le monde.


     //!\\ CECI N'EST QU'UNE REPRÉSENTATION IMAGÉE DE CE QU'EST UN BUFFER OVERFLOW, LE BUT ÉTANT QUE L'EXPLICATION RESTE ACCESSIBLE À TOUS.  //!\\

     //!\\ LE BUT N'ETAIT PAS ICI DE VOUS EXPLIQUER PRÉCISEMENT COMMENT FONCTIONNE CE BUG,MAIS D'EXPLIQUER À TOUS SON FONCTIONNEMENT GÉNÉRAL. //!\\


    /* Pour plus d'informations détaillées si vous souhaiter creuser le sujet : 

        Sécurité Info : https://www.securiteinfo.com/attaques/hacking/buff.shtml;

        CCM :https://www.commentcamarche.com/contents/49-attaques-par-debordement-de-tampon-buffer-overflow;

        Wiki: https://fr.wikipedia.org/wiki/D%C3%A9passement_de_tampon;

        ET SURTOUT N'OUBLIEZ PAS QUE GOOGLE EST VOTRE AMI DANS CE GENRE DE SITUATION !*/
  
    
    //Et du coup comment on se protége contre cette faille ?

        // Il n'y a pas de fonction miracle malheureusement.
        // En fait pour s'en protéger du mieux possible, il va falloir être très rigoureux dans sa façon de développer. 
        //!\\ NE COMPTEZ PAS SUR PHP POUR GARDER VOTRE APPLICATION EN SÉCURITÉ //!\\
        // De nouvelles failles seront surement découvertes un jour et c'est à vous de savoir proteger votre code.


        /* Voici plusieurs points à surveiller en permanence quand vous créer de nouveaux éléments sur votre application PHP :


            - Veillez à toujours avoir PHP à jour sur votre serveur et à ne pas utiliser de fonctions obsolètes. 
                C'est extrêmement important, de nombreux bug sont fixés à chaque mise à jour, et ce n'est pas pour rien qu'elles sont là.
                Si une nouvelle version est proposée, mettez votre serveur à jour. Si on vous dit d'utiliser PDO pour vos requêtes SQL, faites-le !

            - Restez au courant des dernières alertes. Votre serveur est peut-être à la pointe de la sécurité aujourd'hui, cela ne garantis pas pour autant qu'il le sera demain. 
                De nouvelles failles sont découvertes tous les jours, vous vous devez d'être au courant. 
                Des organismes sont spécialisés sur le sujet et regroupent tous les nouveaux dangers potentiels.

            - Rappelez-vous que les données externes au programme ne viennent pas forcément directement de l'utilisateur.
                Un flux RSS peut être un vecteur d'attaque s'il est correctement utilisé. 
                Votre programme récupère des données du flux, à priori pas de danger ça vient d'une source sure, vous faites confiance. 
                Grave erreur. 
                Pareil si vous utilisez des bases de données externes. Vous ne maitrisez pas ce qui n'est pas sur votre serveur. 
                Si vous ne vérifiez pas ce que le service externe vous envoie et que celui-ci est corrompu, votre sécurité l'est également.

            - Maitrisez le contenu de toutes vos variables. N’autorisez que ce qui est nécessaire. 
                Mieux-vaut trop de restrictions que pas assez ! N'oubliez pas le premier commandement de la sécurité informatique : ne faites jamais confiance ! LIMITEZ TOUT !
                   
                    La longueur de la chaine avec strlen()

                    Utilisez htmlspecialchars() pour éviter les injections

                    N’autorisez que les caractères dont vous avez besoin. Par exemple pour une variable qui contiendra un âge, n’autorisez que les chiffres compris entre 0 et 120

                    Virez les caractères spéciaux si vous n'en avez pas besoin

                    N'autorisez les majuscules que si nécessaire. Vous pouvez les gérer avec une expression régulière ou les fonctions strtolower() et strtoupper()

                    Ne faites pas confiance aux listes déroulantes, checklist, checkbox et autres input prédéfinis en HTML. Ça se modifie coté client */



    // EN GROS : DEVENEZ DE VRAI PARANOS. Même si les majuscules ne posent à priori aucun soucis de sécurité, ne les autorisez que si nécessaire.
        // Partez du principe que si vous laissez des libertés à vos utilisateurs, ils l'utiliseront contre vous. Soyez meilleur qu'eux ! 
    
        

# GOOD LUCK & HAVE FUN EVERYONE !

            ?>