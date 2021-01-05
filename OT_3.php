<?php

/*
    // Réutilisation de la liste de tous les paramètres SQL existant ($liste_parametres)
                    // $key pas utile
        foreach($liste_parametres as $value){
                                        
                **********************************************************************************************************
                    // IL FAUT FEINTER LA REQUETE SQL EN METTANT LA VARIABLE DIRECTEMENT DANS LE TEXTE DE LA REQUETE!
                        // SI ON PASSE PAR execute(), avec  SET ? = ?, cela nous retournera une erreur!
                        // Donc faut feinter en faisant '... SET'.$variable.' = ? ...'       ! ! !
                            // Cela marche parfaitement ainsi full optimisation du UPDATE par micro étape
                  ******************************************************************************************************

        $requete = $db->prepare('UPDATE OT SET '.$value.' = ? WHERE  ID = ?');
                    // le UPDATE tient sur une ligne, car on fait un update par valeur! Pas SET x = ?, y = ?, z = ? , .......
                        //ex : SET GXP_PARAM_MODIF = $_POST['GXP_PARAM_MODIF'] WHERE OT = 12345
        $requete->execute(array($_POST[$value], $_SESSION['OT_ID']));
        $requete->closeCursor();

            // SET'.$value.' = ?   et  => execute( array( $_POST[$value] ) ) !
        }
    $_SESSION['message'][] = "<p>L'OT a bien été complété avec les questions saisies!</p>";
    }

    echo "<meta http-equiv='refresh' content='0'>"; // Refresh pour afficher $_SESSION['message']

    <input type="text" name="variable" value=<?php echo "\"".$data['GXP_CAHIER_ROUTE_OBSERV']."\""; // Même si null ?> >
                                <!-- On code du HTML, donc echo un tete va retirer les "", ex : $data[truc] = Ceci est un test
                                On aura value=Ceci est un test. Seul "Ceci" sera pris en compte, car pas de "".
*/


session_start();

require("header.php");

require("OT_3_content.php");

require_once("Signatures.php");


echo "<br/><form action=\"OT_2.php\"> <p><input type=\"submit\" value =\"Page précédente\" > </p></form>";  

?>  




    <!-- UNE FOIS QUE TOUT EST VALIDE : -->
<?php                                                       
if(isset($data['GXP_IMPACT']) && isset($data['GXP_ALL_DONE']) ){

    if($data['GXP_CAHIER_ROUTE_COMPLET'] != 0 || strlen($data['GXP_CAHIER_ROUTE_OBSERV']) > 6)
    echo "<br/><form action=\"OT_4.php\"> <p><input type=\"submit\" value =\"Page suivante\" > </p></form>";
}
?>

</div>

</body>
</html>
