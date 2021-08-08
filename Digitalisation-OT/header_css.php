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

<style>

.page_break{
    page-break-before: always;
}


body {
    /*
    background-image: url("background.jpg");
    background-size: cover;
    background-repeat: no-repeat;
    */
}

h1,
label,
h5,
h4,
h2,
h3,
p {
    /* color: rgba(223, 223, 223);*/
    color: black;
}

input[type=checkbox] {
    float: left;
    /* Alignement */
    margin: 5px;
}

a {
    background-color: aliceblue;
    border: 2px black solid;
    padding: 2px;
}

h3 {
    text-decoration: underline;
}

.cartouche {
    text-align: center;
}

.main {
    border: 2px solid rgba(0, 0, 0, 0.265);
    background-color: rgba(23, 23, 180, 0.04);
    margin: 0 auto;
    width: 95%;
    text-align: center;
    padding-bottom: 5px;
    /* Pas plus sinon on peut scroll vers le bas, et signature ne suit pas */
}

img {
    background-color: rgba(200, 200, 200, 0.9);
    border: solid 2px black;
}

h3 {
    font-size: 25px;
    line-height: 80%;
}

h4 {
    font-size: 22px;
    line-height: 75%;
}

p *,
label {
    font-size: 19px;
}

.liste0 {
    border: 2px solid black;
    background-color: rgba(23, 23, 23, 0.01);
    margin: -2px auto;
    width: 95%;
    display: flex;
    justify-content: space-around;
}

.liste {
    margin: 0 auto;
    border: 3px solid black;
    text-align: center;
    background-color: rgba(23, 23, 123, 0.02);
    vertical-align: top;
    /* Fixe les 3 blocs par le haut */
    margin: 3px;
    justify-content: space-around;
    display: inline-flexbox;
    width: 90%;
    line-height: 92%; /* Dans header_css, à l'impression PDF les items RP_EPI sont moins "espacés" */
}

.liste_no_border {
    margin: 0 auto;
    /*border: 3px solid black;*/
    text-align: center;
    background-color: rgba(23, 23, 123, 0.02);
    vertical-align: top;
    /* Fixe les 3 blocs par le haut */
    margin: 3px;
    justify-content: space-around;
    display: inline-flexbox;
    width: 90%;
}

.float {
    float: right;
    border: 2px solid rgba(0, 0, 0, 0.5);
    background-color: rgba(23, 23, 23, 0.35);
    margin-right: 100px;
    padding: -5px;
}

.short * {
    /* ".short *" permet de prendre TOUT ce qui appartient à une div ayant la class "short" */
    line-height: 35%;
}

.justify {
    justify-content: center;
    width: 60%;
    margin: 0 auto;
}

.justify label {
    text-align: right;
    float: right;
}

.red {
    color: darkred;
    font-size: 20px;
}

.center {
    padding-left: 120px;
}

canvas {
    background-color: lightgrey;
    border: 2px solid black;
}

form>* {
    margin: 1px;
}

.none {
    display: none;
}





</style>

</head>

<body>
    
<div class="main">

<!-- MàJ Janvier 2021, le header de l'export ne génère ni de "Page x/4", 
        ni de titre d'OT, ni de bouton "Retourner à l'accueil"         -->