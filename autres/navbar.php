<nav class="navbar navbar-default"style="background:#004C02;">
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <a type="button" class="btn btn-light" href="http://localhost/php/rs/accueil.php" style="background-color:beige;color:black;">Accueil</a>
        <a type="button" class="btn btn-light" href ="http://localhost/php/rs/docsutiles.php"style="background-color:beige;color:black;">Documents Utiles</a>
        <a type="button" class="btn btn-light" href ="http://localhost/php/rs/logout.php"style="background-color:beige;color:black;">Déconnexion</a>
    </div>
    <br>
    <p style = "color:white">Vous êtes connecté ! Bonjour <?=$_SESSION['nom']?> ! </p>
</nav>