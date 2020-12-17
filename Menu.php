
<?php
            // Vérification de la présence de la BDD, création de le BDD si elle n'existe pas.
try {
    $db = new PDO('mysql:host=localhost;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));     

    $sql = file_get_contents('Gacha_BDD.sql');

    $qr = $db->exec($sql); // CREATE DB IF NOT EXIST
    }catch (Exception $e)
    {
    die('Erreur : ' . $e->getMessage());
    echo $e->getMessage();
    echo "<p>Nous allons importer la Base de Données existante...</p>";
    }    ?>
 
<div class="menu">      <!-- Renvoi vers Intro.PHP -->
    <form method="GET">
        <input type="submit" name="new_game" value="Lancer une nouvelle partie ? " class="main_button"/>
    </form>

    <?php  
    echo "<p>Gacha : LTE</p><h5>Site réalisé par Voldre (12/10/2020 --> )</h5>";

                // Renvoi vers identification.PHP

    if (isset($_SESSION['id']) && isset($_SESSION['login']) ){
            echo "<h5>Bonjour " , strtolower($_SESSION['login']) , " !</h5>";
            ?>
            <form action="identification.php" method="post"> 
            <input type="submit" name="Sauvegarder" value="Sauvegarder"/>
            <input type="submit" name="Deconnexion" value="Deconnexion"/>

            </form>
            <?php
        }
        else{
            ?>
            <form action="identification.php" method="post"> 
            <input type="submit" name="Inscription" value="Inscription"/>
            <input type="submit" name="Ma_Connexion" value="Connexion"/>

            </form>
        <?php
        }


    if(isset($_SESSION['argent'])){
    echo "Argent : ".$_SESSION['argent']." Gold.";
    }
    ?>           <!-- Renvoi vers Intro.PHP -->

    <br/><p>Invocations :</p>
    <form action="Intro.php" method="GET">
        <input type="hidden" name="summon" value="1"/>
        <input type="submit" name="summon1" value="Lancer 1 invocation (2G)"/><br/><br/>
    </form>
    <form action="Intro.php" method="GET">
        <input type="hidden" name="summon" value="10"/>
        <input type="submit" name="summon10" value="Lancer 10 invocations (18G)"/>
    </form>

    <form action="Intro.php" method="GET">
        <input type="submit" name="free" value="Délivrer un personnage"/>
        <a style="font-size: 35px; float: right;" href="Presentation_Persos.php"><img src="question_mark.png" width="100"/></a>

    </form>
</div>