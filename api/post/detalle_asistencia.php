<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_detalle_asistencia.php';
include_once '../../Controller/Controller_Asistencia.php';
include_once '../../Controller/Controller_Trabajador.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = new Controller_detalle_asistencia($GLOBALS['db']);
    $a = new Controller_Asistencia($GLOBALS['db']);
    $t = new Controller_Trabajador($GLOBALS['db']);

    $post->falla_laboral = $GLOBALS['data']->falla_laboral;
    //se identifiac con un date
    $a->fecha = $GLOBALS['data']->fecha;

    //trabajador
    $t->rut_trabajador = $GLOBALS['data']->rut_trabajador;




    $validador = true;

    if (empty($post->falla_laboral)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese una descripcion del producto")
        );
    }
    if (empty($a->fecha)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese una fecha")
        );
    } else {
        if ($post->validateDate($a->fecha) == false) {
            echo json_encode(
                array('Error' => "fecha mal ingresada")
            );
            $validador = false;
        }
    }
    if (empty($t->rut_trabajador)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese un rut del trabajador")
        );
    } else {
        if ($t->Validator_run($t->rut_trabajador) == false) {
            $validador = false;
            echo json_encode(
                array('Error' => "Error rut mal ingresado")
            );
        }
    }
    if ($validador == true) {
        //obtener el id de la asistencia  por el medio de la fecha y el rut del trabajador


        if ($post->Create_detalle_asistencia()) {
            echo json_encode(
                array('message' => 'Se creo el detalle de la asistencia del trabajador')
            );
        } else {
            echo json_encode(
                array('message' => 'No se creo la asistencia')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $validador = true;
    do {
        $post = new Controller_detalle_asistencia($GLOBALS['db']);
        $result = $post->Read_Detalle_asistencia();
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_detalle_asistencia' => $id_detalle_asistencia,
                    'falla_laboral' => $falla_laboral
                );

                array_push($posts_arr['data'], $post_item);
            }

            echo json_encode($posts_arr);
            $validador = true;
        } else {
            // No posts
            
            echo json_encode(

                array('message' => 'No existe detalles de asistencia')
            );
            
            
            $validador = false;
        }

        if ($validador == false) {
            if ($post->Create_detalle_asistencia_automatico() == true) {
            }
        }
    } while ($validador!=true);
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $post = new Controller_detalle_asistencia($GLOBALS['db']);
    $validador == true;

    // GET ID
    $post->id_detalle_asistencia = isset($_GET['id_detalle_asistencia']) ? $_GET['id_detalle_asistencia'] : die();
    if (!is_numeric($post->id_detalle_asistencia)) {
        $validador == false;
        echo json_encode(
            array('message' => 'ingrese solo numeros')
        );
    } else {
        if (!empty($post->id_detalle_asistencia)) {
            $validador == false;
            echo json_encode(
                array('message' => 'ingrese el detalle de asistencia para eliminar')
            );
        } else {
            if ($post->buscar_id_detalle_asistencia($post->id_detalle_asistencia) == true) {
                $validador == false;
                echo json_encode(
                    array('message' => 'no se encontro el detalle asistencia')
                );
            }
        }
    }

    if ($validador == true) {
        if ($post->delete_single_detalle_asistencia()) {
            echo json_encode(
                array('message' => 'Se elimino el detalle de la asistencia')
            );
        } else {
            echo json_encode(
                array('message' => 'No se elimino el detalle de la asistencia ')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $post = new Controller_detalle_asistencia($GLOBALS['db']);
    $a = new Controller_Asistencia($GLOBALS['db']);
    $t = new Controller_Trabajador($GLOBALS['db']);

    $post->id_detalle_asistencia = $GLOBALS['data']->id_detalle_asistencia;
    $post->falla_laboral = $GLOBALS['data']->falla_laboral;
    $validador = true;

    if (empty($post->id_detalle_asistencia)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese una descripcion del producto")
        );
    }

    if ($post->buscar_id_detalle_asistencia($post->id_detalle_asistencia) == true) {
        $validador = false;
        echo json_encode(
            array('message' => "no se encontro el detalle de la asistencia")
        );
    }

    if (empty($post->falla_laboral)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese una descripcion del producto")
        );
    }
    if ($validador == true) {
        if ($post->Update_detalle_asistencia()) {
            echo json_encode(
                array('message' => 'Se actualizo el detalle de la asistencia')
            );
        } else {
            echo json_encode(
                array('message' => 'No se actualizo el detalle de la asistencia')
            );
        }
    }
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
