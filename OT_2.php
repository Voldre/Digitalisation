
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


if(isset($data['SIGNATURE_PROPRIO_DEBUT']) && isset($data['SIGNATURE_INTERVENANT_DEBUT']))
{ 
    echo "<br/><form action=\"OT_3.php\"> <p><input type=\"submit\" value =\"Page suivante\" > </p></form>";
}

require("Signatures.php");
?>

</body>
</html>