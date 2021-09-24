<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Trabajador.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $validador = true;
    $post = new Controller_Trabajador($GLOBALS['db']);
    $post->rut_trabajador=$GLOBALS['data']->rut_trabajador;
    $post->nombre_trabajador=$GLOBALS['data']->nombre_trabajador;
    $post->fecha_contratacion=$GLOBALS['data']->fecha_contratacion;
    $post->usuario=$GLOBALS['data']->usuario;
    $post->contraseña=$GLOBALS['data']->contraseña;
    $post->id_tipo_trabajador=1;

    if ($post->validateDate($post->fecha_contratacion)==false) {
        $validador = false;
        echo json_encode(
            array('Error' => "asd")
        );
    }
    //validacion de rut mas completa
    if ($post->rut_trabajador==null) {
        $validador = false;
        echo json_encode(
            array('Error' => "ingrese el rut de trabajador")
        );
    }else {
        if ($post->Validator_run($post->rut_trabajador)==false) {
            $validador = false;
            echo json_encode(
                array('Error' => "Error no se rut mal ingresado")
            );
        }
    }
    

    if ($post->Validacion_parametros($post->nombre_trabajador)==false) {
        $validador = false;
        echo json_encode(
            array('Error' => "ingrese nombre de trabajador")
        );
    }
    if ($post->Validacion_parametros($post->usuario)==false) {
        $validador = false;
        echo json_encode(
            array('Error' => "ingrese el usuario de trabajador")
        );
    }
    if ($post->Validacion_parametros($post->contraseña)==false) {
        $validador = false;
        echo json_encode(
            array('Error' => "ingrese la contraseña del trabajador")
        );
    }
    if ($validador==true) {
        if ($post->create_trabajador()) {
            echo json_encode(
                array('message' => 'Se registro el usuario')
            );
        } else {
            echo json_encode(
                array('message' => 'No se registro el usuario')
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

?>