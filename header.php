<?php 
            // Vérification de la présence de la BDD, création de le BDD si elle n'existe pas.
            try {
                $db = new PDO('mysql:host=localhost;', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));     
            
                $sql = file_get_contents('OT_BDD.sql');
            
                $qr = $db->exec($sql); // CREATE DB IF NOT EXIST
                }catch (Exception $e)
                {
                die('Erreur : ' . $e->getMessage());
                echo $e->getMessage();
                echo "<p>Nous allons importer la Base de Données existante...</p>";
                }    ?>
             
<!DOCTYPE HTML>
<html>
<head>

<meta charset="utf-8" />

<link rel="stylesheet" href="style.css"/>

</head>

<body>
    
<div class="main">
    <form action="index.php"> <p>

<?php if(basename($_SERVER['PHP_SELF']) != "index.php"){ ?>
<input type="submit" value ="Retourner à l'accueil" > 

    <?php
        if(isset($_SESSION['OT_ID'])){
            echo "OT N°".$_SESSION['OT_ID'];
        }
        
        if(!isset($_SESSION['message'])){
            $_SESSION['message'] = array();
        }
        $_SESSION['message'][] = ("<label>Page ".substr(basename($_SERVER['PHP_SELF']),-5,-4)."/4</label>"); // Reset pour les futures notifications 

        echo "<h5 class=\"float\">";
        foreach($_SESSION['message'] as $value){
            echo $value; // Pour afficher les "notifications"
        }
        echo "</h5>";

        $_SESSION['message'] = array();
    }

        ?>
    </p></form>
