<?php


// Ctrl + F5 pour rafraichir la page avec les propriétés CSS

// https://stackoverflow.com/questions/42935656/create-an-html-form-with-digital-electronic-signature-using-php
  // Think to delete the Onsubmit() part, and the onsubmit= into the <form>

session_start();

$_SESSION['OT_ID'] = -1;
$_SESSION['message'] = array();

include("header.php");
?>

<h1>Formulaire de création d'OT</h1>

<h4>Remplissez le formulaire ci-dessous pour créer ou ouvrir la partie sécurité d'un OT</h4>


<form method="post">

    <div class="left">
    <h3>Création d'un OT</h3>

    <label>Numéro de l'OT-<input type="text" name="numero" placeholder="123456..."/></label>
    <label>Désignation de l'OT :<input type="text" name="designation" placeholder= "Préventif..."/></label>
    <br/>
    <p class="center"><input type="submit" name="valide" value="Créer"/></p>
    </div>

</form>
    <br/>
<form action="index.php" method="post">

    <div class="right">
    <h3>Ouverture d'un OT</h3>
    
    <label>Numéro de l'OT-<input type="text" name="numero"/></label>
    <br/>
    <p class="center"><input type="submit" name="valide" value="Ouvrir"/></p>
    </div>

</form>


<?php           // Une fois le formulaire validé

if(isset($_POST["valide"])){

    if($_POST['numero'] != "" and $_POST['numero'] != "0"){
        $numero_OT = $_POST['numero'];
    }else{
        echo "<p class=\"red\">Erreur, le numéro d'OT n'est pas valide.</p>";
        $_POST["valide"] = "Erreur";
    } 

    //echo gettype($numero_OT).$numero_OT;

    if($_POST["valide"] == "Créer"){    // Si on veut créer un OT

        $_POST['designation'] = htmlspecialchars($_POST['designation']);

        if(strlen( $_POST['designation'] ) < 5 ){
            echo "<p class=\"red\">Erreur, la désignation de l'OT est trop courte.</p>";
        }
        else{
            $requete = $db->prepare('INSERT INTO OT(ID,DESIGNATION) VALUES(?,?)');
            try{
            $requete->execute(array($numero_OT,$_POST['designation']));
            
            echo "<p>L'OT a bien été créé!</p>";

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
            echo "<p class=\"red\">Erreur, cet OT n'existe pas, veuillez d'abord en créer un.</p>";
        }
        else{
            // Si tout est OK, on se rend dans l'OT...
           echo "Entrer dans l'OT...";

           $_SESSION['OT_ID'] = $numero_OT;

            header("Location: OT.php");
        }
    }
}

?>




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

</body>
</html>