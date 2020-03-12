<?php
if(isset($_POST['user']) && !empty($_POST['user']) && isset($_POST['password']) && !empty($_POST['password']))
{
// les champs sont bien posté et pas vide, on sécurise les données entrées par le membre:
$user = htmlspecialchars($_POST['user'], ENT_QUOTES, "ISO-8859-1"); // le htmlspecialchars() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
$password = htmlspecialchars($_POST['password'], ENT_QUOTES, "ISO-8859-1");
}
//on se connecte à la base de données:
$pdo =new PDO('mysql:host=localhost; dbname=rs; charset=utf8','root','');

//on vérifie que la connexion s'effectue correctement
if(!$pdo){
    echo "Erreur de connexion à la base de données.";
} 

else{

$stat = $pdo->prepare ("SELECT statut FROM utilisateurs WHERE utilisateurs.identifiant = :user ");
$stat->bindParam(':user',$user,PDO::PARAM_STR);
$stat->execute();
$statu = $stat->fetch();
$statut= $statu ['statut'];

$req = $pdo->prepare("SELECT mdphash FROM utilisateurs WHERE utilisateurs.identifiant = :user "); 
$req->bindParam(':user',$user,PDO::PARAM_STR);
$req->execute();
$hash = $req->fetch();
$texthash = $hash ['mdphash'];
$pwd = password_verify($password,$texthash);

$nam = $pdo->prepare("SELECT nom FROM utilisateurs WHERE utilisateurs.identifiant = :user ");
$nam->bindParam(':user',$user,PDO::PARAM_STR);
$nam->execute();
$name = $nam->fetch();
$nom = $name['nom'];



if ($pwd && $statut === 'admin')
    {
    session_start();
    $_SESSION['user'] = $user;
    $_SESSION['nom'] = $nom;
    $_SESSION['statut'] = $statut;
    header("Location:../admin/admincontent.php");
    }

elseif ($pwd && $statut === 'salaries'){
    session_start();
    $gpe = $pdo->prepare("SELECT catRS_nom FROM appartenance WHERE appartenance.utilisateurs_nom = :nom ");
    $gpe->bindParam(':nom',$nom,PDO::PARAM_STR);
    $gpe->execute();
    $group = $gpe->fetch();
    $groupe = $group['catRS_nom'];

    $_SESSION['user'] = $user;
    $_SESSION['nom'] = $nom;
    $_SESSION['statut'] = $statut;
    $_SESSION['groupe'] = $groupe;
    header("Location:../polesalaries/actualitessal.php");
    }
    
    else {echo "
        <div class='card-panel red accent-3'> 
        Identifiant ou/et mot de passe incorrect(s). Veuillez réessayer en faisant bien attention aux majuscules et minuscules.
        </div>";
        include 'connexion.html';
    }
}
