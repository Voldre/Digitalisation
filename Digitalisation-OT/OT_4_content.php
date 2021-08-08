<?php

// Récupération des signatures déjà faites :
          
$requete = $db->prepare('SELECT SIGNATURE_PROPRIO_FIN, SIGNATURE_INTERVENANT_FIN, SIGNATURE_SQO,
                                DATE_FORMAT(SIGNATURE_PROPRIO_FIN_DATE, "%d/%m/%Y à %T") AS SPFD,
                                DATE_FORMAT(SIGNATURE_INTERVENANT_FIN_DATE, "%d/%m/%Y à %T") AS SIFD,
                                DATE_FORMAT(SIGNATURE_SQO_DATE, "%d:%m/%Y à %T") AS SSD,
                         GXP_PARAM_MODIF, GXP_PIECES_MODIF, GXP_PROG_MODIF, GXP_CABLAGE_MODIF, GXP_ALL_DONE,
                         GXP_FONCTIONNEL, GXP_IMPACT
                         FROM OT WHERE ID = ?');
$requete->execute(array($_SESSION['OT_ID']));

$data = $requete->fetch();
$requete->closeCursor();

?>          <!-- Obliger de déclarer TOUTES les signatures -->

<!-- PARTIE SIGNATURE DE FIN -->

<div class="liste0">

    <div class="liste_no_border">
    <h3>Signatures après intervention</h3>

    <h4>Signature de l'intervenant :</h4>
    <?php
    if(isset($data['SIGNATURE_INTERVENANT_FIN']) && $data['SIGNATURE_INTERVENANT_FIN'] != "" ){
    echo "<p>Nom : ".$data['SIGNATURE_INTERVENANT_FIN']."</p>";
    echo "<p>Date : ".$data['SIFD']."</p>";
    echo "<p> Signature :</p>";
    echo "<img src=\"".$_SESSION['OT_ID']."_signature_intervenant_fin.png\" />";

    echo "<canvas class=\"none\" id=\"signature1\" width=\"0\" height=\"0\"></canvas>";
    // BLOC DE SIGNATURE OBLIGATOIRE SINON TOUT PLANTE CAR IL N'EST PAS DECLARE 

    }
    else{?>
    <form method="post">
    <p>Nom :<input type="text" name="nom_intervenant_fin"/></p> 
    <div>
    <p> Signature :</p>
    <canvas id="signature1" width="300" height="100"></canvas>
    </div>
    <input type="hidden" name="signature1" />
    <a href="OT_4.php">Effacer</a>
    <input type="submit" name="valide4" value="valider la signature de l'intervenant">

    </form>

<?php 
} ?>
      

      <h3>Partie à remplir par le propriétaire :</h3>

<h4>Signature du Propriétaire</h4>
<?php
if(isset($data['SIGNATURE_PROPRIO_FIN'])){
    echo "<p>Nom : ".$data['SIGNATURE_PROPRIO_FIN']."</p>";
    echo "<p>Date : ".$data['SPFD']."</p>";
    echo "<p> Signature :</p>";
    echo "<img src=\"".$_SESSION['OT_ID']."_signature_proprio_fin.png\" />";

    echo "<canvas class=\"none\" id=\"signature2\" width=\"0\" height=\"0\"></canvas>";
    // BLOC DE SIGNATURE OBLIGATOIRE SINON TOUT PLANTE CAR IL N'EST PAS DECLARE 
}
else{?>
    <form method="post">
    <p>Nom :<input type="text" name="nom_proprio_fin"/></p> 
    <div>
    <p> Signature :</p>
    <canvas id="signature2" width="300" height="100"></canvas>
    </div>
    <input type="hidden" name="signature2" />
    <a href="OT_4.php">Effacer</a>
    <input type="submit" name="valide5" value="valider la signature du propriétaire">
    </form>
    <?php 
    } 



    /* Signature SQO requis si :
    L'une des cases "MODIF" est à "Oui"
    ou si la case "ALL DONE" ou "IMPACT" est à "Oui"
    ou si la case "fonctionnel" est à "Non"

    DONC : 
    Si 
      [GXP_PARAM_MODIF, GXP_PIECES_MODIF, GXP_PROG_MODIF, GXP_CABLAGE_MODIF] = "Oui" , OU
       GXP_ALL_DONE, GXP_FONCTIONNEL = "Non", OU
        GXP_IMPACT = "Oui"  ALORS : SQO
    */

if($data['GXP_PARAM_MODIF'] + $data['GXP_PIECES_MODIF'] + $data['GXP_PROG_MODIF'] + $data['GXP_CABLAGE_MODIF'] != 0
    || $data['GXP_ALL_DONE'] * $data['GXP_FONCTIONNEL'] != 1    || $data['GXP_IMPACT'] != 0 ){
    //if($data['GXP_IMPACT'] != 0){


    $isSQOneed = true;

?> 
        </div>
        <div class="liste_no_border">
<h4>Signature du SQO :</h4>
<?php
    if(isset($data['SIGNATURE_SQO'])){
    echo "<p>Nom : ".$data['SIGNATURE_SQO']."</p>";
    echo "<p>Date : ".$data['SSD']."</p>";
    echo "<p> Signature :</p>";
    echo "<img src=\"".$_SESSION['OT_ID']."_signature_sqo.png\" />";

    echo "<canvas class=\"none\" id=\"signature3\" width=\"0\" height=\"0\"></canvas>";
    // BLOC DE SIGNATURE OBLIGATOIRE SINON TOUT PLANTE CAR IL N'EST PAS DECLARE 
    }
    else{?>
        <form method="post">
    <p>Nom :<input type="text" name="nom_sqo"/></p> 
    <div>
    <p> Signature :</p>
    <canvas id="signature3" width="300" height="100"></canvas>
    </div>
    <!-- L'ID du Canvas et le nom du hidden doivent être identique! -->
    <input type="hidden" name="signature3" />
    <a href="OT_4.php">Effacer</a>
    <input type="submit" name="valide3" value="valider la signature du SQO">
        </form>
    <?php 
    }

} else{  echo "<canvas class=\"none\" id=\"signature3\" width=\"0\" height=\"0\"></canvas>";
    $isSQOneed = false ; }

    ?> </div></div> 