<?php

function signature($num,$database){

switch($num){
case 1 :
$poste_signataire= "proprio_debut";
break;
case 2 :
$poste_signataire = "intervenant_debut";
break;
case 3 :
$poste_signataire = "sqo";
break;
case 4 :
$poste_signataire = "intervenant_fin";
break;
case 5 :
$poste_signataire = "proprio_fin";
}

// ENREGISTREMENT DES SIGNATURES
if(isset($_POST['valide'.$num])){
                    // $num modulo 3, car SQO = 3 = "nouvelle signature, signature3" 
                    // et après : INTERVENANT_FIN (4) prend dans OT_3 la signature1, donc 4 modulo 3 = 1
                                // Par déduction, PROPRIO_FIN prend la signature2, donc case 5 donnera : 5 modulo 3 = 2
                                // Ainsi, comme on a simultanément que jusqu'à 3 signatures, on peut faire case 1 à 5 et modulo 3
    $sign =   $_POST['signature'.($num %3)];  // $num Modulo 3
    $nom = $_POST['nom_'.$poste_signataire];
    $nom = htmlspecialchars($nom);
    if(strlen($nom) > 4)  // 5 caractères minimum, ce qui est logique : Nom + Prénom normalement
    {                              // La signature est ajoutée pareil, NUMERO_signature_NomDuPoste.png
    $img = $sign; 
    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img));
    file_put_contents($_SESSION['OT_ID'].'_signature_'.$poste_signataire.'.png', $data);
    $reponse = $database->prepare('UPDATE OT SET SIGNATURE_'.strtoupper($poste_signataire).' = ? , 
                                    SIGNATURE_'.strtoupper($poste_signataire).'_DATE = NOW() WHERE  ID = ? ');
                                                // SIGNATURE_SQO_DATE par exemple ! :)
    $reponse->execute(array( $nom, $_SESSION['OT_ID']));
    $reponse->closeCursor();

    echo "<p>La signature de \"".$nom."\" a bien été ajoutée.</P>";
    echo "<meta http-equiv='refresh' content='0'>"; // Obliger de refresh / reset car ça se fait mal
    }
    else{ echo "<p class=\"red\">Erreur : Le nom de la personne saisi est trop court.</p>"; }
}
}
    // Test des signatures pour les 5 à faire
for($i=1; $i <= 5; $i++){
if(isset($_POST['valide'.$i])){
    signature($i,$db); //$db est une variable globale!
}
}

?>


<script>
// Fonctions Javascript pour les deux à trois signatures simultanées

var canvas1 = document.getElementById('signature1');
var canvas2 = document.getElementById('signature2');
var canvas3 = document.getElementById('signature3');
var ctx1 = canvas1.getContext("2d");
var ctx2 = canvas2.getContext("2d");
var ctx3 = canvas3.getContext("2d");
var drawing = false;
var prevX, prevY;
var currX, currY;
var signature1 = document.getElementsByName('signature1')[0];
var signature2 = document.getElementsByName('signature2')[0]
var signature3 = document.getElementsByName('signature3')[0];

canvas1.addEventListener("mousemove", draw1)
canvas1.addEventListener("mouseup", stop1);
canvas1.addEventListener("mousedown", start)
canvas2.addEventListener("mousemove", draw2)
canvas2.addEventListener("mouseup", stop2)
canvas2.addEventListener("mousedown", start);
canvas3.addEventListener("mousemove", draw3);
canvas3.addEventListener("mouseup", stop3);
canvas3.addEventListener("mousedown", start);

function start() {
drawing = true;
}

function stop1() {
drawing = false;
prevX = prevY = null;
signature1.value = canvas1.toDataURL(); 
}
function stop2() {
drawing = false;
prevX = prevY = null;
signature2.value = canvas2.toDataURL(); 
}
function stop3() {
drawing = false;
prevX = prevY = null;
signature2.value = canvas3.toDataURL(); 
}

function draw1(e) {
if (!drawing) {
return;
}
currX = e.clientX - canvas1.offsetLeft;
currY = e.clientY - canvas1.offsetTop;

if (!prevX && !prevY) {
prevX = currX;
prevY = currY;
}

ctx1.beginPath();
ctx1.moveTo(prevX, prevY);
ctx1.lineTo(currX, currY);
ctx1.strokeStyle = 'black';
ctx1.lineWidth = 2;
ctx1.stroke();
ctx1.closePath();

prevX = currX;
prevY = currY;
}

function draw2(e) {
if (!drawing) {
return;
}
currX = e.clientX - canvas2.offsetLeft;
currY = e.clientY - canvas2.offsetTop;

if (!prevX && !prevY) {
prevX = currX;
prevY = currY;
}

ctx2.beginPath();
ctx2.moveTo(prevX, prevY);
ctx2.lineTo(currX, currY);
ctx2.strokeStyle = 'black';
ctx2.lineWidth = 2;
ctx2.stroke();
ctx2.closePath();

prevX = currX;
prevY = currY;
}

function draw3(e) {
if (!drawing) {
return;
}
currX = e.clientX - canvas3.offsetLeft;
currY = e.clientY - canvas3.offsetTop;

if (!prevX && !prevY) {
prevX = currX;
prevY = currY;
}

ctx3.beginPath();
ctx3.moveTo(prevX, prevY);
ctx3.lineTo(currX, currY);
ctx3.strokeStyle = 'black';
ctx3.lineWidth = 2;
ctx3.stroke();
ctx3.closePath();

prevX = currX;
prevY = currY;
}
</script>