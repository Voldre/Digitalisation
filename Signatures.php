  <!-- PARTIE SIGNATURE DE DEBUT -->

  <?php // Récupération des signatures déjà faites :
          
        $requete = $db->prepare('SELECT SIGNATURE_PROPRIO_DEBUT, SIGNATURE_INTERVENANT FROM OT WHERE ID = ?');
        $requete->execute(array($_SESSION['OT_ID']));

        $data = $requete->fetch();
  ?>

  <form method="post">
        
        <h4>Propriétaire de l'équipement ou son représentant :</h4>
        
        
        <?php
            if(isset($data['SIGNATURE_PROPRIO_DEBUT'])){
            echo "<p>Nom : ".$data['SIGNATURE_PROPRIO_DEBUT']."</p>";
            }
            else{?>
            <p>Nom :<input type="text" name="nom_proprio"/></p> <?php
            } ?>
        <div><p> Signature :</p>
            <?php
            if(isset($data['SIGNATURE_PROPRIO_DEBUT'])){
                echo "<img src=\"".$_SESSION['OT_ID']."_signature_proprio_debut.png\" />";
            }
            else{?>
                <canvas id="signature1" width="300" height="100"></canvas>
            <?php } ?>
        </div>
        <div><!-- L'ID du Canvas et le nom du hidden doivent être identique! -->
            <input type="hidden" name="signature1" />
        </div>
        
        <?php
            if(!isset($data['SIGNATURE_PROPRIO_DEBUT'])){ ?>
        <input type="submit" value="valider la signature du propriétaire">
                <?php } ?>
        </form>
        
        <form method="post">
        
        <h4>Intervenant ou donneur d'ordre :</h4>
        
        <p>Nom :<input type="text" name="nom_intervenant"/></p>
        
        <div><p> Signature :</p>
            <?php
            if(isset($data['SIGNATURE_INTERVENANT'])){
                echo "<img src=\"".$_SESSION['OT_ID']."_signature_intervenant.png\" />";
            }
            else{?>
            <canvas id="signature2" width="300" height="100"></canvas>
            <?php } ?>
        </div>
        <div><!-- L'ID du Canvas et le nom du hidden doivent être identique! -->
            <input type="hidden" name="signature2" /> 
        </div>
        
        <input type="submit"  value="valider la signature de l'intervenant">
        
        </form>


        <script>
        // Fonctions Javascript pour la signature

var canvas1 = document.getElementById('signature1');
var canvas2 = document.getElementById('signature2');
var ctx1 = canvas1.getContext("2d");
var ctx2 = canvas2.getContext("2d");
var drawing = false;
var prevX, prevY;
var currX, currY;
var signature1 = document.getElementsByName('signature1')[0];
var signature2 = document.getElementsByName('signature2')[0];

canvas1.addEventListener("mousemove", draw1);
canvas1.addEventListener("mouseup", stop1);
canvas1.addEventListener("mousedown", start);
canvas2.addEventListener("mousemove", draw2);
canvas2.addEventListener("mouseup", stop2);
canvas2.addEventListener("mousedown", start);

function start() {
  drawing = true;
}

function stop1() {
  drawing = false;
  prevX = prevY = null;
  signature1.value = canvas1.toDataURL(); 
}
function stop2() {
  drawing = false;
  prevX = prevY = null;
  signature2.value = canvas2.toDataURL(); 
}

function draw1(e) {
  if (!drawing) {
    return;
  }
  currX = e.clientX - canvas1.offsetLeft;
  currY = e.clientY - canvas1.offsetTop;

  if (!prevX && !prevY) {
    prevX = currX;
    prevY = currY;
  }

  ctx1.beginPath();
  ctx1.moveTo(prevX, prevY);
  ctx1.lineTo(currX, currY);
  ctx1.strokeStyle = 'black';
  ctx1.lineWidth = 2;
  ctx1.stroke();
  ctx1.closePath();

  prevX = currX;
  prevY = currY;
}

function draw2(e) {
  if (!drawing) {
    return;
  }
  currX = e.clientX - canvas2.offsetLeft;
  currY = e.clientY - canvas2.offsetTop;

  if (!prevX && !prevY) {
    prevX = currX;
    prevY = currY;
  }

  ctx2.beginPath();
  ctx2.moveTo(prevX, prevY);
  ctx2.lineTo(currX, currY);
  ctx2.strokeStyle = 'black';
  ctx2.lineWidth = 2;
  ctx2.stroke();
  ctx2.closePath();

  prevX = currX;
  prevY = currY;
}
        </script>