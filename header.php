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

