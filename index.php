
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    //se puede poner en cualquier lado /por mi parte la pondre arriba
    /*
    session_start();
    if (!isset($_SESSION["usuario"])) {
        header("Location: ../index.php");
    }
    
    */
    

    
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>

    <?php
       // include './Controller/Conexion.php';
        echo "mensaje desde esta pagina";
        ?>
        <h1>hoal</h1>
        <script src="Controller/js/test.js" ></script>
    </body>
    <p>Este es un mensaje de pruerba para el pagina</p>
    <p>Este es un mensaje de pruerba para el pagina</p>
    <br>
    <br>


</html>


<?php 
function url_actual(){
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      $url = "https://"; 
    }else{
      $url = "http://"; 
    }
    echo $url . $_SERVER['HTTP_HOST'] .  $_SERVER['REQUEST_URI'];
   }
   $op=url_actual();
   echo json_encode(
    array('message' => $op)
);
?>

<?php

function url_actua(){
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      $url = "https://"; 
    }else{
      $url = "http://"; 
    }
    echo $url . $_SERVER['HTTP_HOST'] .  $_SERVER['REQUEST_URI'];
   }
  
  url_actua();
  // Salida: http://midominio.com/pagina/index.php?user=pepito

?>