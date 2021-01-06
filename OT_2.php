
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

Refresh obligatoire dans le code PHP (où on veut) :   echo "<meta http-equiv='refresh' content='0'>";


*/

session_start();

require("header.php");

require("OT_2_content.php");
?>
<!-- Accès à la dernière page -->


<?php

echo "<br/><form action=\"OT_1.php\"> <p><input type=\"submit\" value =\"Page précédente\" > </p></form>";


    // MàJ Janvier 2021 : Texte qui précise de revenir APRES L'INTERVENTION

if(isset($data['SIGNATURE_PROPRIO_DEBUT']) && isset($data['SIGNATURE_INTERVENANT_DEBUT']))
{ 

    $reponse = $db->prepare('SELECT GXP_CAHIER_ROUTE_COMPLET, GXP_CAHIER_ROUTE_OBSERV FROM OT   WHERE  ID = ? ');
    $reponse->execute(array($_SESSION['OT_ID']));
    $data = $reponse->fetch();
    $reponse->closeCursor();

    if($data['GXP_CAHIER_ROUTE_COMPLET'] == 0 & strlen($data['GXP_CAHIER_ROUTE_OBSERV']) <= 6){
        // Si la page 3 n'a pas encore été saisie
        // Et que les 2 signatures sont saisies (ligne 38)
            // Alors, on dit "Tout est OK, revenez plus tard"
        echo"<br/><label><b>Les signatures ont bien été prises en compte.</b>
            <br/>Vous pouvez à présent effectuer les différentes opérations de l'Ordre de Travail.
            <br/><b>Après intervention</b>, vous pourrez continuer la saisie sur la page suivante.</label><br/>";
        
            echo "<form action=\"index.php\"><input type=\"submit\" value =\"Retourner à l'accueil\" ></form>";
        }
 // Et dans tous les cas, on propose la page suivante, comme ça on force pas le mec à relancer la page s'il veut enchaîner
    echo "<br/><form action=\"OT_3.php\"> <p><input type=\"submit\" value =\"Page suivante\" > </p></form>";
}

require("Signatures.php");
?>

</body>
</html>