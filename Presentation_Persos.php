<!DOCTYPE HTML>
<html>
<head>

<meta charset="utf-8" />

<link rel="stylesheet" href="style2.css"/>

</head>

<body>
<?php   
try {
    $db = new PDO('mysql:host=localhost;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));     

    $sql = file_get_contents('Gacha_BDD.sql');

    $qr = $db->exec($sql); // CREATE DB IF NOT EXIST
    }catch (Exception $e)
    {
    die('Erreur : ' . $e->getMessage());
    echo $e->getMessage();
    echo "<p>Nous allons importer la Base de Données existante...</p>";
    }  
         
    require("Liste_Persos.php");

?>

 <audio autoplay controls loop  style="display: none;">
  <source src="Valkyrie_Anatomia.mp3" type="audio/mpeg">
  </audio>

<h3>Toutes les statistiques présentées ici sont les valeurs moyennes pour chaque personnage, 
<br/>vous pouvez donc obtenir aléatoirement des statistiques légèrement meilleure ou moins bonne.</h3>
<h4 style="margin-left: 20px;">Vous pouvez aussi créer un nouveau personnage <a href="Creation.php">ici...</a></h4>
<form action="index.php" method="post">
<input type="submit" value="Retourner au jeu"/>
</form>

<?php
echo "<div class=\"container\">";

echo "<h4>Liste des personnages <span style=\"color: gold;\">5 étoiles</span> (1% de chance) : </h4>";

foreach($liste_5_stars as $key => $value){
    ?>
        <div class="div_presentation"> 
        <img src=<?= $key.".png" ?> />
        </div>
            <p class="infos_presentation"><span style="text-align  center;"> <?= $key ?> </span>
                <br/> Points de vie :  <?= $liste_5_stars[$key]['PVM'] ?>
                <br/> Attaque : <?= $liste_5_stars[$key]['ATK'] ?>
                <br/> Défense : <?= $liste_5_stars[$key]['DEF'] ?>
                <br/> Element : <?= $liste_5_stars[$key]['ELMT'] ?>
            </p> <?php
} ?>
</div>
<?php


echo "<div class=\"container\">";

echo "<h4>Liste des personnages <span style=\"color: silver;\">4 étoiles</span> (9% de chances) : </h4>";

foreach($liste_4_stars as $key => $value){
    ?>
        <div class="div_presentation"> 
        <img src=<?= $key.".png" ?> />
        </div>
            <p class="infos_presentation"><span style="text-align  center;"> <?= $key ?> </span>
                <br/> Points de vie :  <?= $liste_4_stars[$key]['PVM'] ?>
                <br/> Attaque : <?= $liste_4_stars[$key]['ATK'] ?>
                <br/> Défense : <?= $liste_4_stars[$key]['DEF'] ?>
                <br/> Element : <?= $liste_4_stars[$key]['ELMT'] ?>
            </p> <?php
} ?>
</div>
<?php

echo "<div class=\"container\">";

echo "<h4>Liste des personnages <span style=\"color: rgb(206, 65, 9);\">3 étoiles</span> (90% de chances) : </h4>";

foreach($list as $key => $value){
    ?>
        <div class="div_presentation"> 
        <img src=<?= $key.".png" ?> />
        </div>
            <p class="infos_presentation"><span style="text-align  center;"> <?= $key ?> </span>
                <br/> Points de vie :  <?= $list[$key]['PVM'] ?>
                <br/> Attaque : <?= $list[$key]['ATK'] ?>
                <br/> Défense : <?= $list[$key]['DEF'] ?>
                <br/> Element : <?= $list[$key]['ELMT'] ?>
            </p> <?php
} ?>
</div>

<form action="index.php" method="post">
<input type="submit" value="Retourner au jeu"/>
</form>

</body>
</html>