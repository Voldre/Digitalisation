<?php

// Ctrl + F5 pour rafraichir la page avec les propriétés CSS

// https://stackoverflow.com/questions/42935656/create-an-html-form-with-digital-electronic-signature-using-php
  // Think to delete the Onsubmit() part, and the onsubmit= into the <form>

// Préremplir en gris une zone de texte : placeholder="123456..."

/*  MàJ  Janvier 2021 :
- bouton "ouvrir OT" ouvre par défaut sur la dernière page écrite
- Intégration Abode Sign ?
- Nouveaux tests et confirmation
- Amélioration du rendu de l'impression (saut de page)
*/



session_start();

$_SESSION['OT_ID'] = -1;
$_SESSION['message'] = array();

include("header.php");
?>

<div class="main">

<h1>Formulaire "Sécurité et Qualité" d'un OT</h1>

<h4>Remplissez le formulaire ci-dessous pour créer ou ouvrir la partie "Sécurité et Qualité" d'un OT</h4>


<form method="post">

    <div class="left">
    <h3>Génération un OT</h3>

    <label>Numéro de l'OT-<input type="text" name="numero" placeholder="123456..."/></label>
    <label>Désignation de l'OT :<input type="text" name="designation" placeholder= "Préventif..."/></label>
    <br/>
    <p class="center"><input type="submit" name="valide" value="Générer"/></p>
    </div>

</form>
    <br/>
<form action="index.php" method="post">

    <div class="right">
    <h3>Ouverture d'un OT</h3>
    
    <label>Numéro de l'OT-<input type="text" name="numero" placeholder="123456..."/></label>
    <br/>
    <p class="center"><input type="submit" name="valide" value="Ouvrir"/></p>
    </div>

</form>


<?php           // Une fois le formulaire validé

if(isset($_POST["valide"])){
    $numero_OT = (int) $_POST['numero'];

    if($numero_OT != null and $numero_OT != 0){
    }else{
        echo "<p class=\"red\">Erreur, le numéro d'OT n'est pas valide.</p>";
        $_POST["valide"] = "Erreur";
    } 

    //echo gettype($numero_OT).$numero_OT;

    if($_POST["valide"] == "Générer"){    // Si on veut créer un OT

        $_POST['designation'] = htmlspecialchars($_POST['designation']);

        if(strlen( $_POST['designation'] ) < 5 ){
            echo "<p class=\"red\">Erreur, la désignation de l'OT est trop courte.</p>";
        }
        else{                                                   // Par défaut, impact GXP à OUI
            $requete = $db->prepare('INSERT INTO OT(ID,DESIGNATION,GXP_IMPACT) VALUES(?,?,1)');
            try{
            $requete->execute(array($numero_OT,$_POST['designation']));
            
            echo "<p>La partie \"Sécurité et Qualité\" de l'OT a bien été générée!</p>";

            }catch (Exception $e)
            {
            //echo $e->getMessage(); // SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicata du champ '   ' pour la clef 'PRIMARY'

            echo "<p class=\"red\">Cet OT existe déjà, veuillez utiliser le formulaire d'ouverture d'OT.</p>";
            }  

            $requete->closeCursor();
        }

    }else if($_POST["valide"] == "Ouvrir"){ // Si on veut ouvrir un OT

        $reponse = $db->prepare('SELECT COUNT(*) AS Count FROM OT WHERE ID = ?');
        $reponse->execute(array($numero_OT));

        $data = $reponse->fetch();
        //print_r($data);

        if($data["Count"] != 1){
            echo "<p class=\"red\">Erreur, cet OT ne possède pas encore de partie \"Sécurité et Qualité\", veuillez d'abord en générer un.</p>";
        }
        else{
            // Si tout est OK, on se rend dans l'OT...

           echo "Entrer dans l'OT...";

           $_SESSION['OT_ID'] = $numero_OT;


           // MàJ Janvier 2021 : Entrée différente selon l'avancement (redirige par défaut sur la dernière page)

           $reponse = $db->prepare('SELECT GXP_IMPACT, GXP_ALL_DONE, GXP_CAHIER_ROUTE_COMPLET AS GXP_CRC,
                                     GXP_CAHIER_ROUTE_OBSERV AS GXP_CRO,    SIGNATURE_PROPRIO_DEBUT AS SPD,
                                     SIGNATURE_INTERVENANT_DEBUT AS SID     FROM OT   WHERE  ID = ? ');

           $reponse->execute(array($_SESSION['OT_ID']));

           $data = $reponse->fetch();
           $reponse->closeCursor();

            // Si cela, Page 4
            if(isset($data['GXP_IMPACT']) && isset($data['GXP_ALL_DONE']) && 
                    ($data['GXP_CRC'] == 1 || strlen($data['GXP_CRO']) > 6) ){
                header("Location: OT_4.php");
            }   // Sinon, si cela, Page 3
            else if(isset($data['SPD']) && isset($data['SID'])){
                header("Location: OT_3.php");
            }   
            else{ // Sinon

                $requete = $db->prepare('SELECT Type, COUNT(*) AS Count FROM OT_RP_EPI, Risques_Precautions_EPI WHERE ID_RP_EPI = ID AND ID_OT = ? GROUP BY Type');
                $requete->execute(array( $_SESSION['OT_ID']));
                $nb_categories = 0;
                while($data = $requete->fetch()){
                $nb_categories++;  }
                $requete->closeCursor();
                    // Si cela, Page 2
                if($nb_categories >= 3){
                    header("Location: OT_2.php");
                }
                else{ // Si rien, Page 1
                    header("Location: OT_1.php");
                } 
            } // Si Ni Page 3 ou 4
      } // Si "OT existe"
    } // Si "Ouvrir"
}

?>


<?php /*

<h2>Interface administrateur</h2>

<h4>Ajout d'un risque, d'une précaution ou d'un EPI</h4>

<form method="post">
                      <!-- Require force à ce que le nom soit saisi-->
<label>Nom:<input type="text" name="nom" require/></label>
<label>Type:<select name="type">
  <option value="risques">Risques</option>
  <option value="precautions">Précautions</option>
  <option value="epi">EPI</option>
  </select></label>
<label>Cible:<select name="cible">
  <option value="Proprio_Inter">Propriétaire de l'équipement et intervenants</option>
  <option value="Inter">Intervenants</option>
  </select></label>

<input type="submit" name="new" value="Créer"/>

</form>

<?php

if(isset($_POST['new'])){

  $_POST['nom'] = htmlspecialchars($_POST['nom']);
  
  if(strlen($_POST['nom']) > 3){
  $requete = $db->prepare('INSERT INTO Risques_Precautions_EPI(NOM,type,cible) VALUES(?,?,?)');
  $requete->execute(array($_POST['nom'],$_POST['type'],$_POST['cible']));

  $requete->closeCursor();

  echo "<p>Le paramètre a bien été ajouté!</p>";
  }
  else{ echo "<p class=\"red\">Erreur : le nom du paramètre est trop court.</p>"; }
}

?>
*/ ?>
</div>

</body>
</html>