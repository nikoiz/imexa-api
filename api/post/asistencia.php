<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Asistencia.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $post= new Controller_Asistencia($GLOBALS['db']);
    $post->fecha =$fecha = date('Y-m-d');
    $post->cantidad_dias_fallados =0;
    $post->rut_trabajador= $GLOBALS['data']->rut_trabajador;
    $post->id_detalle_asistencia = null;
    $validador=true;

    if ($post->Validacion_parametro($post->rut_trabajador)==false) {
        $validador=false;
        echo json_encode(
            array('Error' => 'ingrese el rut del trabajador')
        );
    }else {
        if ($post->Validator_run($post->rut_trabajador)==false) {
            $validador=false;
            echo json_encode(
                array('Error' => 'rut mal ingresado')
            );
        }
    }

    if ($validador==true) {
        if ($post->Create_asistencia()) {
            echo json_encode(
                array('message' => 'Post Update')
            );
        } else {
            echo json_encode(
                array('message' => 'Post not Update')
            );
        }
    }  





    
}

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
if (isset($_GET['id'])){

    }else{
    
    }

}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
?>