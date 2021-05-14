<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Trabajador.php';
$database = new conexion();
$db = $database->connect();



 // Instiate blog post object
 $post = new Controller_Trabajador($GLOBALS['db']);

 // GET ID
 $post->usuario = isset($_GET['usuario']) ? $_GET['usuario'] : die();
 $post->contraseña = isset($_GET['contraseña']) ? $_GET['contraseña'] : die();



 if ($post->Inicio_sesion($post->usuario,$post->contraseña)==false) {
     echo json_encode(
         array('message' => 'usuario no encontrado')
     );
     //header("Location: ../index.php?error=$error");
 }else {
     echo json_encode(
         array('message' => 'usuario encontrado')
     );
      session_start();
      $_SESSION["usuario"] = $post->usuario;
      $_SESSION["rut_trabajador"] = $post->contraseña;
      //establecer por medio de api o dejar que el mismo php de backend redireccione
      //header("Location: ../view/Menu_profesora.php");
 }




?>