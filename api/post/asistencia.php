<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Asistencia.php';
include_once '../../Controller/Controller_Trabajador.php';

$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $post = new Controller_Asistencia($GLOBALS['db']);
    $t = new Controller_Trabajador($GLOBALS['db']);
    $post->fecha = $fecha = date('Y-m-d');
    $post->cantidad_dias_fallados = 0;
    $post->rut_trabajador = $GLOBALS['data']->rut_trabajador;
    $post->id_detalle_asistencia = null;
    $validador = true;

    if ($post->Validacion_parametro($post->rut_trabajador) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => 'ingrese el rut del trabajador')
        );
    } else {
        if ($post->Validator_run($post->rut_trabajador) == false) {
            $validador = false;
            echo json_encode(
                array('Error' => 'rut mal ingresado')
            );
        } else {
            //buscar el trabajador
            if (!empty($t->Buscar_rut_trabajador($post->rut_trabajador))) {
                $validador = false;
                echo json_encode(
                    array('Error' => 'rut no existe')
                );
            }
        }
    }
    if ($validador == true) {
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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_asistencia'])) {
        // Instiate blog post object
        $post = new Controller_Asistencia($GLOBALS['db']);

        // GET ID
        //se puede cambiar por el id_bodega (decir a compañeero para ver quer le parece)
        $post->id_asistencia = isset($_GET['id_asistencia']) ? $_GET['id_asistencia'] : die();


        if (!empty($post->buscar_id_asistencia($post->id_asistencia))) {
            echo json_encode(
                array('message' => 'No existe datos sobre la asistencia')
            );
        } else {
            if ($post->Read_single_asistencia()) {
                $post_item = array(
                    'id_asistencia' => $post->id_asistencia,
                    'fecha' => $post->fecha,
                    'cantidad_dias_fallados' => $post->cantidad_dias_fallados,
                    'rut_trabajador' => $post->rut_trabajador,
                    'id_detalle_asistencia' => $post->id_detalle_asistencia
                );
                //Make JSON

                print_r(json_encode($post_item));
            } else {
                echo json_encode(
                    array('message' => 'No Posts Found')
                );
            }
        }
    } else {
        $post = new Controller_Asistencia($GLOBALS['db']);
        $result = $post->Read_Asistencia();
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_asistencia' => $id_asistencia,
                    'fecha' => $fecha,
                    'cantidad_dias_fallados' => $cantidad_dias_fallados,
                    'rut_trabajador' => $rut_trabajador,
                    'id_detalle_asistencia' => $id_detalle_asistencia
                );

                array_push($posts_arr['data'], $post_item);
            }

            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(

                array('message' => 'No Posts Found')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Instiate blog post object
    $post = new Controller_Asistencia($GLOBALS['db']);


    // GET ID
    $post->id_asistencia = isset($_GET['id_asistencia']) ? $_GET['id_asistencia'] : die();

    if (!empty($post->buscar_id_asistencia($post->id_asistencia))) {
        echo json_encode(
            array('message' => 'no se encontro la gasto para eliminar')
        );
    } else {
        if ($post->Delete_asistencia()) {
            echo json_encode(
                array('message' => 'Post deleted')
            );
        } else {
            echo json_encode(
                array('message' => 'Post not deleted')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $post = new Controller_Asistencia($GLOBALS['db']);
    $post->fecha = $fecha = date('Y-m-d');
    $post->cantidad_dias_fallados = 0;
    $post->rut_trabajador = $GLOBALS['data']->rut_trabajador;
    $post->id_detalle_asistencia = null;
    $validador = true;

    if ($post->Validacion_parametro($post->rut_trabajador) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => 'ingrese el rut del trabajador')
        );
    } else {
        if ($post->Validator_run($post->rut_trabajador) == false) {
            $validador = false;
            echo json_encode(
                array('Error' => 'rut mal ingresado')
            );
        }
    }
    if ($validador == true) {
        if ($post->Update_asistencia()) {
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
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
