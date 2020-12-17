
<?php

//Pas "in_array()" quand on recherche des KEY, in_array = des valeurs! Donc "array_key_exists()"

/*
    ATTRIBUTION D'UN CHECK PAR DEFAUT SELON LA VALEUR ENREGISTREE PRECEDEMMENT :

                        'check' entre apostrophe évite de mettre ça en gros string
                        pour bien sortir un <input type=... name=... value=.... checked >

    <?php if($data['Autoris'] == 1){ $valueOui = 'checked'; $valueNon = null; }
          else if($data['Autoris'] == 0){ $valueOui = null; $valueNon = 'checked'; }
          else{ $valueOui= ""; $valueNon = ""; } // Si la case n'a jamais été cochée
    ?> 
        <input type="radio" name="AUTORISATIONS_PARTICULIERES" value=1 <?=$valueOui?> > Oui
        <input type="radio" name="AUTORISATIONS_PARTICULIERES" value=0 <?=$valueNon?> > Non


*/

session_start();

require("header.php");


echo "<form action=\"index.php\"> <input type=\"submit\" value =\"Retourner à l'accueil\" > </form>";

$liste_RP_EPI = array();
$liste_sur_OT_RP_EPI = array(); // A bien définir au cas où l'OT soit nouveau donc aucun RP_EPI sur l'OT

$reponse = $db->query('SELECT * FROM Risques_Precautions_EPI');


while($data = $reponse->fetch() ){
$liste_RP_EPI[$data['ID']] = $data;
}
$reponse->closeCursor();
        
        // Récupération des ID utilisés par l'OT
$requete = $db->prepare('SELECT ID_RP_EPI FROM OT_RP_EPI WHERE ID_OT = ?');
$requete->execute(array( $_SESSION['OT_ID']));

while($data = $requete->fetch() ){
$liste_sur_OT_RP_EPI[] = $data['ID_RP_EPI'];
}


$requete->closeCursor();
        

  echo "<div class=\"float\">";
  if(isset($_SESSION['message'])){
    foreach($_SESSION['message'] as $value){
        echo $value; // Pour afficher les "notifications"
    }
    echo "</div>";
    $_SESSION['message'] = array(); // Reset pour les futures notifications 
  }
?>



<div clas="cartouche">
<h3>N°<?= $_SESSION['OT_ID']?></h3>

<?php $reponse = $db->prepare('SELECT DESIGNATION FROM OT WHERE ID = ?');
        $reponse->execute(array($_SESSION['OT_ID']));
        $data = $reponse->fetch();
        $reponse->closeCursor(); ?>
<h4>Ordre de travail : <?= $data['DESIGNATION'] ?></h4>

</div>  <!-- Mettre la désignation dans un formulaire? -->


<h4>N° d'infirmerie : 1010 &nbsp;&nbsp;&nbsp; N° pompier : 18  &nbsp;&nbsp;&nbsp; 
    Poste surveillance : 34452 &nbsp;&nbsp;&nbsp;   Accueil : 112 &nbsp;&nbsp;&nbsp;  SAMU : 15</h4>



    <!--SAISIE DES DIFFERENTS PARAMETRES DE SECURITE-->

        <div class="main">

<h3>Evaluation sécurité pour autorisation d'intervention</h3>

<form action="OT.php" method="post"> <!-- Ajout de action pour bien MàJ les cases cochées -->

<?php       // SAISIE DES QUESTIONS LISTE

$liste_tableau = ["Proprio_Inter","Inter"];


foreach($liste_tableau as $cible_global){

echo "<div class=\"liste0\">";
    if($cible_global == "Proprio_Inter"){
        echo "<h4>Propriétaire de l'équipement et intervenants</h4>";
    } else if($cible_global =="Inter"){
        echo "<h4>Intervenants</h4>";
    }

    $liste_types = array("risques" => "risque","precautions" => "precaution","epi" => "epi");

    foreach($liste_types as $key_type => $value_type){ // Optimisaton pour 1 boucle pour chaque type
   ?>
    <div class="liste">
        <h4><b><?= strtoupper($key_type) ?></b></h4>
        <?php
            // Si on est dans la case EPI
        if($key_type == "EPI"){
            if($cible_global == "Proprio_Inter"){ echo "<h5>- - Chaussures de sécurité obligatoires - -</h5>";
            } else { echo "<h5>- - Vêtements couvrants obligatoires - -</h5>"; } // Pour les 2 entêtes de "EPI"
        }
                                // <=> ID
        foreach($liste_RP_EPI as $key => $value){   // Optimisation pour 1 boucle pour chaque paramètre (check)
            foreach($value as $key2 => $value2){    // <=> "nom champ, valeur champ"
                if($key2 == "Type" && $value2 == $key_type && $liste_RP_EPI[$key]['Cible'] == $cible_global){
        
                        // Si l'ID actuel est DANS LISTE d'ID rattachés à l'OT, alors
                        if( in_array($key,$liste_sur_OT_RP_EPI) ){ 
                            echo "<p><label><input type=\"checkbox\" name=$key value=1 checked >"; // checked by default!
                        } else{ echo "<p><label><input type=\"checkbox\" name=$key value=1 >"; 
                        }
                        echo $liste_RP_EPI[$key]['NOM']."</label></p>";
                    }
                }
            } 
    echo "</div>"; // de <div class="liste">
          } // Fin du foreach des 3 types 

 echo "</div><br/><br/>"; // Saut de ligne pour la transition des 2 tableaux (des 2 cibles)

} // Fin bloc foreach de cible_global 


    // SAISIE DES QUESTIONS SEPAREES
    
$liste_reponses_OT = array();


$reponse = $db->prepare('SELECT AUTORISATIONS_PARTICULIERES AS Autoris, PLAN_PREV AS PP, RISQUES_BIOLOGIQUES AS RB,
                        RISQUES_BIOLOGIQUES_DESIGNATION AS RBD, RISQUES_BIOLOGIQUES_ACCES AS RBA
                             FROM OT WHERE ID = ?');
$reponse->execute(array($_SESSION['OT_ID']));

$data = $reponse->fetch();
$reponse->closeCursor();

     //print_r($data);


    
?>

<h3>Autorisations particulières :
    <?php if($data['Autoris'] == 1){ $valueOui = 'checked'; $valueNon = null; }
          else if($data['Autoris'] == 0){ $valueOui = null; $valueNon = 'checked'; }
          else{ $valueOui= null; $valueNon = null; } // Si la case n'a jamais été cochée
    ?> 
        <input type="radio" name="AUTORISATIONS_PARTICULIERES" value=1 <?=$valueOui?> > Oui
        <input type="radio" name="AUTORISATIONS_PARTICULIERES" value=0 <?=$valueNon?> > Non
</h3>

<p> Si oui :</p>
    <?php if($data['PP'] == "particulier"){ $valueOui = 'checked'; $valueNon = null; }
            else if($data['PP'] == "annuel"){ $valueOui = null; $valueNon = 'checked'; }
            else{ $valueOui= null; $valueNon = null; } // Si la case n'a jamais été cochée
        ?> 
<label>
    Plan de prévention particulier....<input type="radio" name="PLAN_PREV" value="particulier"<?=$valueOui?> >
    Plan de prévention annuel....<input type="radio" name="PLAN_PREV" value="annuel" <?=$valueNon?> >
</label>

<br/><br/>

<h3>Risques biologiques :</h3>
    <?php if($data['RB'] == 1){ $valueOui = 'checked'; $valueNon = null; }
            else if($data['RB'] == 0){ $valueOui = null; $valueNon = 'checked'; }
            else{ $valueOui= null; $valueNon = null; } // Si la case n'a jamais été cochée
    ?> 
    <label>Autre bâtiment visité dans la journée (risque de contamination croisée) :<input type="radio" name="RISQUES_BIOLOGIQUES" value=1 <?=$valueOui?> >Oui  
                                                                                <input type="radio" name="RISQUES_BIOLOGIQUES" value=0 <?=$valueNon?> >Non </br>
       Si oui, lequel :<input type="text" name="RISQUES_BIOLOGIQUES_DESIGNATION" value=<?php $data['RBD']; // Même si null ?> >.

       <?php if($data['RBA'] == 1){ $valueOui = 'checked'; $valueNon = null; }
            else if($data['RBA'] == 0){ $valueOui = null; $valueNon = 'checked'; }
            else{ $valueOui= ""; $valueNon = ""; } // Si la case n'a jamais été cochée
        ?> 
        Accès autorisé :<input type="radio" name="RISQUES_BIOLOGIQUES_ACCES" value=1 <?=$valueOui?> >Oui  
                        <input type="radio" name="RISQUES_BIOLOGIQUES_ACCES" value=0 <?=$valueNon?> >Non </br>
    </label>

<br/><br/>

<input type="submit" name="questions" value="Mettre à jour toutes les réponses">

</form> 



<?php  
        
// QUESTIONS

if(isset($_POST['questions'])){

    // Remplissage de la table OT

    if(!isset($_POST['AUTORISATIONS_PARTICULIERES']) && !isset($_POST['RISQUES_BIOLOGIQUES'])){
        $_SESSION['message'][] = "<p class=\red\">Erreur, l'autorisation particulière ou le risque biologique n'a pas été coché (oui ou non)</p>";
    }
    else{
        if($_POST['AUTORISATIONS_PARTICULIERES'] == 0){
            $_POST['PLAN_PREV'] = null;
        }

        if($_POST['RISQUES_BIOLOGIQUES'] == 0){
            $_POST['RISQUES_BIOLOGIQUES_DESIGNATION'] = null;
            $_POST['RISQUES_BIOLOGIQUES_ACCES'] = null;
        }
        else{
            if(strlen($_POST['RISQUES_BIOLOGIQUES_DESIGNATION']) < 5){
                $_POST['RISQUES_BIOLOGIQUES_DESIGNATION'] = "A définir";
            }
        }

        $requete = $db->prepare('UPDATE OT SET AUTORISATIONS_PARTICULIERES = ?, PLAN_PREV = ?, RISQUES_BIOLOGIQUES = ? ,
                            RISQUES_BIOLOGIQUES_DESIGNATION = ? , RISQUES_BIOLOGIQUES_ACCES = ? WHERE  ID = ?');

        $requete->execute(array($_POST['AUTORISATIONS_PARTICULIERES'],  $_POST['PLAN_PREV'],    $_POST['RISQUES_BIOLOGIQUES'],
                            $_POST['RISQUES_BIOLOGIQUES_DESIGNATION'], $_POST['RISQUES_BIOLOGIQUES_ACCES'], $_SESSION['OT_ID']));
        $requete->closeCursor();

        $_SESSION['message'][] = "<p>L'OT a bien été complété avec les questions saisies!</p>";
    }

    // Remplissage de la table OT_RP_EPI

                // On vide la table pour supprimer les cases éventuellements décochés
    $reponse = $db->prepare('DELETE FROM OT_RP_EPI WHERE ID_OT = ?');
    $reponse->execute(array($_SESSION['OT_ID']));
    $reponse->closeCursor();

    
    // On insère toutes les cases cochés dans la table

    foreach($_POST as $key => $value){
        if( (int) $key != 0){ // Si la key en nombre est différente de 0, c'est qu'il s'agit bien d'un nombre

            $requete = $db->prepare('INSERT INTO OT_RP_EPI(ID_OT, ID_RP_EPI) VALUES(?,?)');
            $requete->execute(array( $_SESSION['OT_ID'], $key )); // On envoi les ID en int
            $requete->closeCursor();

        }
    }

        // Si une case "Aucun Truc" a été coché, on retire les liaisons qui auraient pu être cochées
            // Ex : je coche "Aucune précaution" pour EPI, et "Masque adapté" par erreur, alors "Masque adapté" 
            // ne sera pas ajouté à la liste. Car "Aucune précaution" est prioritaire et a été coché

        $liste_aucun = array(1000 => "risques", 1001 => "precautions", 1002 => "epi");
            // Liste des 3 catégories et définition du numéro ID correspondant à l'absence "no_type".
            
            // Optimisation du code en réduisant les 3 requêtes No_risques, No_precautions et No_epi en une seule :
        foreach($liste_aucun as $key => $value){
            //Pas "in_array()" quand on recherche des KEY, in_array = des valeurs! Donc "array_key_exists()"
            if(array_key_exists($key,$_POST)){ // Si dans les valeurs des ID envoyés, 1000, 10001 ou/et 1002 apparaissent :
                $_SESSION['message'][] = "<p>Vous avez cochée la case \"Aucun(e) $value\", aucun(e) $value ne sera donc saisi dans l'OT.</p>";
                $requete = $db->prepare("DELETE T1 FROM OT_RP_EPI As T1, Risques_Precautions_EPI As T2
                                            WHERE ID_OT = ? AND ID_RP_EPI = T2.ID
                                                AND T2.Type = ?      AND  ID_RP_EPI != ?");
                $requete->execute(array($_SESSION['OT_ID'], $value, $key)); // On supprime tous les ID du type 
                $requete->closeCursor();                               // sauf la case "Aucun de ce type", normal
            }
        }

    //  print_r($_POST);

    // Vérification qu'au moins une case a été cochée par catégorie

    $isCheck = true;
    $nb_categories = 0;

    $requete = $db->prepare('SELECT Type, COUNT(*) AS Count FROM OT_RP_EPI, Risques_Precautions_EPI WHERE ID_RP_EPI = ID AND ID_OT = ? GROUP BY Type');
        // COUNT(COUNT(*)): "Je compte le nombre de groupement fait
            // * = Les items,   COUNT(*) = Je compte les items par groupe (car GROUP BY)
            // Donc COUNT(COUNT(*)) = Je compte les groupes
        


    $requete->execute(array( $_SESSION['OT_ID']));


    while($data = $requete->fetch()){
        $nb_categories++;    
        }
    if($nb_categories < 3){
        $isCheck = false;
    }

    if(!$isCheck){ // Si une catégorie contient 0 élément coché
        
        $_SESSION['message'][] = "<p class=\"red\">Vous n'avez pas cochée une seule case dans ".(3-$nb_categories)." catégorie(s) parmi \"Risques\", \"Precautions\" et \"EPI\".</p>";

        // On vide la table car la saisie comporte des erreurs
        $reponse = $db->prepare('DELETE FROM OT_RP_EPI WHERE ID_OT = ?');
        $reponse->execute(array($_SESSION['OT_ID']));
        $reponse->closeCursor();

        $_SESSION['message'][]  = "<p class=\"red\">Veuillez ressaisir vos données dans le formulaire en cochant au moins une case par catégorie (Risques, Précautions, EPI).</p>";
    }
    else{ // Si toutes les catégories possèdent au moins un paramètre (de coché), alors
        $_SESSION['message'][] = "<p>L'OT a bien été associé à tous les risques, précautions et EPI sélectionnés.</p>";
    }

    echo "<meta http-equiv='refresh' content='0'>"; // Obliger de refresh / reset car ça se fait mal
}



    ?>      </div>  <!-- Fin du bloc de questions, passage au bloc de signatures -->
        <div class="main">

        <h3>Signatures de la partie Evaluation sécurité</h3>

    <?php

// SIGNATURE

    $isCheck1 = false;
    $isCheck2 = false;

    // Si les questions ont été remplis (donc AUTORISATIONS_PARTICULIERES et RISQUES_BIOLOGIQUES != 0)
        // et que l'OT est associé à au moins 3 Risques/Precautions/EPI, alors :

    $reponse = $db->prepare('SELECT AUTORISATIONS_PARTICULIERES AS autorisation, RISQUES_BIOLOGIQUES AS risques 
                                FROM OT WHERE ID = ?');
        $reponse->execute(array($_SESSION['OT_ID']));

    $data = $reponse->fetch();

    if(isset($data['autorisation']) && isset($data['risques'])){
        $isCheck1 = true;
    }
    $reponse->closeCursor();


    $reponse = $db->prepare('SELECT COUNT(*) as Count FROM OT_RP_EPI WHERE ID_OT = ?'); 
    $reponse->execute(array($_SESSION['OT_ID']));

    $data = $reponse->fetch();

    if($data['Count'] >= 3){
        $isCheck2 = true;
    }
    $reponse->closeCursor();



    if($isCheck1 && $isCheck2){ // Si tout est OK, on ajoute le formulaire des signatures
        
        require("Signatures.php");
    
    }
    else{
        echo "<p>Vous débloquerez la partie signature de l'OT lorsque vous aurez répondu à toutes les questions et remplie chaque catégorie.</p>";
    }
    

  // ENREGISTREMENT DES SIGNATURES

if(isset($_POST['signature1'])){
    $_POST['nom_proprio'] = htmlspecialchars($_POST['nom_proprio']);
    if(strlen($_POST['nom_proprio']) > 4)
    {
    $img = $_POST['signature1'];
    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img));
    file_put_contents($_SESSION['OT_ID'].'_signature_proprio_debut.png', $data);

    $reponse = $db->prepare('UPDATE OT SET SIGNATURE_PROPRIO_DEBUT = ? , SIGNATURE_PROPRIO_DEBUT_DATE = NOW() WHERE  ID = ? ');
    $reponse->execute(array( $_POST['nom_proprio'], $_SESSION['OT_ID']));
    $reponse->closeCursor();

    echo "<p>La signature de \"".$_POST['nom_proprio']."\" a bien été ajoutée.</P>";
    }
    else{ echo "<p class=\"red\">Erreur : Le nom du propriétaire saisi est trop court.</p>"; }
}

if(isset($_POST['signature2'])){
    $_POST['nom_intervenant'] = htmlspecialchars($_POST['nom_intervenant']);
    if(strlen($_POST['nom_intervenant']) > 4)
    {
    $img = $_POST['signature2'];
    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img));
    file_put_contents($_SESSION['OT_ID'].'_signature_intervenant.png', $data);

    $reponse = $db->prepare('UPDATE OT SET SIGNATURE_INTERVENANT = ? , SIGNATURE_INTERVENANT_DATE = NOW() WHERE  ID = ? ');
    $reponse->execute(array( $_POST['nom_intervenant'], $_SESSION['OT_ID']));
    $reponse->closeCursor();
    
    echo "<p>La signature de \"".$_POST['nom_intervenant']."\" a bien été ajoutée.</P>";
    }
    // AJOUTER LES DATES AVEC NOW()

    else{ echo "<p class=\"red\">Erreur : Le nom du propriétaire saisi est trop court.</p>"; }
}

?>

    </div> <!-- Fin du deuxième div main (pour les signatures) -->

</body>
</html>