<?php

// Récupération des signatures déjà faites :
          
$requete = $db->prepare('SELECT SIGNATURE_PROPRIO_FIN, SIGNATURE_INTERVENANT_FIN, SIGNATURE_SQO,
                                DATE_FORMAT(SIGNATURE_PROPRIO_FIN_DATE, "%d/%m/%Y à %T") AS SPFD,
                                DATE_FORMAT(SIGNATURE_INTERVENANT_FIN_DATE, "%d/%m/%Y à %T") AS SIFD,
                                DATE_FORMAT(SIGNATURE_SQO_DATE, "%d:%m/%Y à %T") AS SSD,
                         GXP_PARAM_MODIF, GXP_PIECES_MODIF, GXP_PROG_MODIF, GXP_CABLAGE_MODIF, GXP_ALL_DONE,
                         GXP_CONTROLE, GXP_FONCTIONNEL, GXP_CAHIER_ROUTE_COMPLET, GXP_CAHIER_ROUTE_OBSERV, GXP_IMPACT
                         FROM OT WHERE ID = ?');


$requete->execute(array($_SESSION['OT_ID']));

$data = $requete->fetch();
$requete->closeCursor();

//print_r($data);

function yes_no($my_data,$name){
    if($my_data == 1){ $valueOui = 'checked'; $valueNon = null; }
          else if($my_data == 0){ $valueOui = null; $valueNon = 'checked'; }
          else{ $valueOui= null; $valueNon = null; } // Si la case n'a jamais été cochée
    ?>  <label>
        <input type="radio" name=<?=$name?> value=1 <?=$valueOui?> > Oui
        <input type="radio" name=<?= $name?> value=0 <?=$valueNon?> > Non
        </label>
    <?php
}

?>

<h3>Après intervention et avant redémarrage</h3>

<h5><b>L'intervenant responsable de l'intervention s'engage sur la réalisation des interventions<br/>
demandées / prévues et sur le respect des consignes et mesures définies préalablement.</h5>

<h4>Impact qualité de l'intervention si équipement GXP</h4>


<form action="OT_3.php" method="post">

<div class="justify">

<?php

$liste_parametres = array("Un paramètre d'équipement a-t-il été modifié ? . . ." => "GXP_PARAM_MODIF",
                    "Les pièces critiques ont-elles été changées par un code article différent ? . . ." => "GXP_PIECES_MODIF",
                    "Un programme a-t-il été modifié ? . . ." => "GXP_PROG_MODIF",
                    "Un câblage a-t-il été modifié ? . . ." => "GXP_CABLAGE_MODIF",
                    "Toutes les opérations non facultatives mentionnées dans l'ordre de travail sont réalisées ?" => "GXP_ALL_DONE",
                    "Un contrôle métrologique à prévoir : . . ." => "GXP_CONTROLE",
                    "L'équipement est-il fonctionnel : . . ." => "GXP_FONCTIONNEL",
                    "Le cahier de route est complété : . . ." => "GXP_CAHIER_ROUTE_COMPLET");

foreach($liste_parametres as $key => $value){   // Enorme simplification des longues lignes, + extraction des NOMS DE PARAMETRES
    
    echo "<p>".$key; 
    yes_no($data[$value],$value);
    echo "</p>";
}
?>

<p>Si non, observation : . . .  <!-- Différent car il s'agit d'un input type text -->
<input type="text" name="GXP_CAHIER_ROUTE_OBSERV" value=<?php echo "\"".$data['GXP_CAHIER_ROUTE_OBSERV']."\""; // Même si null ?> >
</p>                                                    <!-- On code du HTML, donc echo un tete va retirer les "", ex : $data[truc] = Ceci est un test
                                                                                Dans le input type="text", on aura value=Ceci est un test
                                                                                Autrement dit, il lira juste value="Ceci", car pas de guillemet
                                                                                Donc faut les rajouter, pour faire value="Ceci est un test"
                                                                                D'où les " \" ", on ressort un " avec le \.
                                                                -->


<p>
    Sur la base des informations renseignées dans la zone "Impact qualité de l'intervention" ci-dessus,<br/>
    l'intervention a-t-elle un impact sur l'état qualifié de l'équipement? . . . 
    <?php yes_no($data['GXP_IMPACT'],'GXP_IMPACT'); ?>
</p>

    <p><b>Si la réponse est oui, informer le SQO.</b></p>

<?php
    if(isset($data['SIGNATURE_PROPRIO_FIN']) OR isset($data['SIGNATURE_INTERVENANT_FIN']) OR isset($data['SIGNATURE_SQO']) ){
    echo "<p class=\"red\">Les réponses ne peuvent plus être mises à jour, le document a déjà été signé par au moins une personne.</p>";
}else{   ?>
<input type="submit" name="questions" value="Mettre à jour toutes les réponses">  
<?php   }   ?>

</div>

</form>


<?php 

    // TRAITEMENT DES QUESTIONS

if(isset($_POST['questions'])){

    $_POST['GXP_CAHIER_ROUTE_OBSERV'] = htmlspecialchars($_POST['GXP_CAHIER_ROUTE_OBSERV']);

    // Remplissage de la table OT

    if($_POST['GXP_CAHIER_ROUTE_COMPLET'] == 0 && strlen($_POST['GXP_CAHIER_ROUTE_OBSERV']) < 6){
        $_SESSION['message'][] = "<p class=\"red\">Attention, le motif d'observation concernant le cahier de route est trop court, incomplet.</p>";
    }
    else{
        
        if($_POST['GXP_CAHIER_ROUTE_COMPLET'] == 1){
            $_POST['GXP_CAHIER_ROUTE_OBSERV'] = null;
        }

    // Réutilisation de la liste de tous les paramètres ($liste_parametres)
                    // $key pas utile
        foreach($liste_parametres as $value){
                                        

                    // IL FAUT FEINTER LA REQUETE SQL EN METTANT LA VARIABLE DIRECTEMENT DANS LE TEXTE DE LA REQUETE!
                        // SI ON PASSE PAR execute(), avec  SET ? = ?, cela nous retournera une erreur!
                        // Donc faut feinter en faisant '... SET'.$variable.' = ? ...'       ! ! !
                            // Cela marche parfaitement ainsi full optimisation du UPDATE par micro étape

        $requete = $db->prepare('UPDATE OT SET '.$value.' = ? WHERE  ID = ?');
                    // le UPDATE tient sur une ligne, car on fait un update par valeur! Pas SET x = ?, y = ?, z = ? , .......
        //echo $value." => ".$_POST[$value];
                        //ex : SET GXP_PARAM_MODIF = $_POST['GXP_PARAM_MODIF'] WHERE OT = 12345
        $requete->execute(array($_POST[$value], $_SESSION['OT_ID']));
        $requete->closeCursor();
        }
    }

    // Update de la case "GXP_CAHIER_ROUTE_OBSERVATION"

    $requete = $db->prepare('UPDATE OT SET GXP_CAHIER_ROUTE_OBSERV = ? WHERE  ID = ?');
    $requete->execute(array($_POST['GXP_CAHIER_ROUTE_OBSERV'], $_SESSION['OT_ID']));
    $requete->closeCursor();



    // Update de la case "GXP_IMPACT" car elle est plus loin dans le formulaire

    $requete = $db->prepare('UPDATE OT SET GXP_IMPACT = ? WHERE  ID = ?');
    $requete->execute(array($_POST['GXP_IMPACT'], $_SESSION['OT_ID']));
    $requete->closeCursor();


    $_SESSION['message'][] = "<p>L'OT a bien été complété avec les questions saisies!</p>";
    echo "<meta http-equiv='refresh' content='0'>"; // Refresh pour afficher $_SESSION['message']
}
