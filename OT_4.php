<?php

session_start();

require("header.php");


require("OT_4_content.php");


echo "<br/><form action=\"OT_3.php\"> <p><input type=\"submit\" value =\"Page précédente\" > </p></form>";

require_once("Signatures.php");
?>

    <!-- UNE FOIS QUE TOUT EST VALIDE : -->
    <?php                                                                                       // SQO : Pas besoin (== 0) OU est présent
if(isset($data['SIGNATURE_PROPRIO_FIN']) && isset($data['SIGNATURE_INTERVENANT_FIN']) && (!$isSQOneed OR  isset($data['SIGNATURE_SQO']) ) ){

    echo "<form action=\"OT_EXTRACTION.php\"> <p><input type=\"submit\" value =\"Exporter l'OT au format PDF\" > </p></form>";  
}
?>

</div>
</body>
</html>
