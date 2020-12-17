

<form  method="post">
  <div>
    <input name="name" placeholder="Your name" required />
  </div>
  <div>
    <canvas id="signature" width="300" height="100"></canvas>
  </div>
  <div>
    <input type="hidden" name="signature" />
  </div>
  <button type="submit">Send</button>
  <form>


<?php

if(isset($_POST['signature'])){
$img = $_POST['signature'];
$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img));
file_put_contents('signature.png', $data);
}
?>


  
<script>
    var canvas = document.getElementById('signature');
var ctx = canvas.getContext("2d");
var drawing = false;
var prevX, prevY;
var currX, currY;
var signature = document.getElementsByName('signature')[0];

canvas.addEventListener("mousemove", draw);
canvas.addEventListener("mouseup", stop);
canvas.addEventListener("mousedown", start);

function start() {
  drawing = true;
}

function stop() {
  drawing = false;
  prevX = prevY = null;
  signature.value = canvas.toDataURL();
}

function draw(e) {
  if (!drawing) {
    return;
  }
  currX = e.clientX - canvas.offsetLeft;
  currY = e.clientY - canvas.offsetTop;
  if (!prevX && !prevY) {
    prevX = currX;
    prevY = currY;
  }

  ctx.beginPath();
  ctx.moveTo(prevX, prevY);
  ctx.lineTo(currX, currY);
  ctx.strokeStyle = 'black';
  ctx.lineWidth = 2;
  ctx.stroke();
  ctx.closePath();

  prevX = currX;
  prevY = currY;
}

function onSubmit(e) {
  console.log({
    'signature': signature.value,
  });
}
</script>



</body>
</html>