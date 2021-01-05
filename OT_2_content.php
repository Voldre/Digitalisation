<?php

// Récupération des signatures déjà faites :
          
$requete = $db->prepare('SELECT SIGNATURE_PROPRIO_DEBUT, SIGNATURE_INTERVENANT_DEBUT,
                                DATE_FORMAT(SIGNATURE_PROPRIO_DEBUT_DATE, "%d/%m/%Y à %T") AS SPDD,
                                DATE_FORMAT(SIGNATURE_INTERVENANT_DEBUT_DATE, "%d/%m/%Y à %T") AS SIDD
                         FROM OT WHERE ID = ?');
$requete->execute(array($_SESSION['OT_ID']));

$data = $requete->fetch();
$requete->closeCursor();

//print_r($data);

?>          <!-- Obliger de déclarer une signature-->
        <canvas class="none" id="signature3" width="0" height="0"></canvas>


<!-- PARTIE SIGNATURE DE DEBUT -->

<div class="liste0">
<p>Signature avant intervention : Le propriétaire de l'équipement ou son représentant s'est assuré que toutes les mesures <br/>
 de sécurité et de bio sécurité on été prises (cadres ci-dessus complétés) afin de maîtriser les risques de l'intervention <br/> 
 et met le système à disposition pour intervention. L'intervenant responsable de l'intervention a pris connaissance <br/> 
 des mesures de sécurité et de bio sécurité définies pour l'autorisation d'intervention et s'engage à les respecter.<br/><br/>
--> Les réponses précédentes ne pourront plus être modifié après signature.</p>
</div>

<div class="short">

<h4>Propriétaire de l'équipement ou son représentant :</h4>
<?php
if(isset($data['SIGNATURE_PROPRIO_DEBUT'])){
echo "<p>Nom : ".$data['SIGNATURE_PROPRIO_DEBUT']."</p>";
echo "<p>Date : ".$data['SPDD']."</p>";
echo "<p> Signature :</p>";
echo "<img src=\"".$_SESSION['OT_ID']."_signature_proprio_debut.png\" />";

echo "<canvas class=\"none\" id=\"signature1\" width=\"0\" height=\"0\"></canvas>";
 // BLOC DE SIGNATURE OBLIGATOIRE SINON TOUT PLANTE CAR IL N'EST PAS DECLARE 
}
else{?> <!-- Line height pour retirer l'espace en trop -->
<form style="line-height: 0%" method="post">
<p>Nom :<input type="text" name="nom_proprio_debut"/></p> 
<div>
<p> Signature :</p>
<canvas id="signature1" width="300" height="100"></canvas>
</div>
<!-- L'ID du Canvas et le nom du hidden doivent être identique! -->
<input type="hidden" name="signature1" />
<a href="OT_2.php">Effacer</a>
<input type="submit" name="valide1" value="valider la signature du propriétaire">
</form>

<?php 
} ?>
      
<h4>Intervenant ou donneur d'ordre :</h4>
<?php
if(isset($data['SIGNATURE_INTERVENANT_DEBUT'])){
echo "<p>Nom : ".$data['SIGNATURE_INTERVENANT_DEBUT']."</p>";
echo "<p>Date : ".$data['SIDD']."</p>";
echo "<p> Signature :</p>";
echo "<img src=\"".$_SESSION['OT_ID']."_signature_intervenant_debut.png\" />";

echo "<canvas class=\"none\" id=\"signature2\" width=\"0\" height=\"0\"></canvas>";
// BLOC DE SIGNATURE OBLIGATOIRE SINON TOUT PLANTE CAR IL N'EST PAS DECLARE 
}
else{?>
<form style="line-height: 0%" method="post">
<p>Nom :<input type="text" name="nom_intervenant_debut"/></p>
<div>
<p> Signature :</p>
<canvas id="signature2" width="300" height="100"></canvas>
</div>
<!-- L'ID du Canvas et le nom du hidden doivent être identique! -->
<input type="hidden" name="signature2" />
<a href="OT_2.php">Effacer</a>
<input type="submit"  name="valide2" value="valider la signature de l'intervenant">
</form>

<?php 
} ?>

</div>