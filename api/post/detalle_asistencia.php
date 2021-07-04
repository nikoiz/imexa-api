<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
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

    $post->falta_laboral = $GLOBALS['data']->falta_laboral;
    $validador = true;

    if (empty($post->falta_laboral)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese una descripcion del producto")
        );
    }
    if ($validador == true) {
        if ($post->Create_detalle_asistencia()) {
            echo json_encode(
                array('message' => 'Post Created')
            );
        } else {
            echo json_encode(
                array('message' => 'Post not created')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_detalle_asistencia'])) {
        // Instiate blog post object
        $post = new Controller_detalle_asistencia($GLOBALS['db']);


        // GET ID
        //se puede cambiar por el id_bodega (decir a compañeero para ver quer le parece)
        $post->id_detalle_asistencia = isset($_GET['id_detalle_asistencia']) ? $_GET['id_detalle_asistencia'] : die();


        if (!empty($post->buscar_id_detalle_asistencia($post->id_detalle_asistencia))) {
            echo json_encode(
                array('message' => 'No se encontro el detalle de la asistencia')
            );
        } else {
            if ($post->Read_single_detalle_asistencia()) {
                $post_item = array(
                    'id_detalle_asistencia' => $post->id_detalle_asistencia,
                    'falta_laboral' => $post->falta_laboral
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
                    'falta_laboral' => $falta_laboral
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
    $post = new Controller_detalle_asistencia($GLOBALS['db']);
    $validador == true;

    // GET ID
    $post->id_detalle_asistencia = isset($_GET['id_detalle_asistencia']) ? $_GET['id_detalle_asistencia'] : die();
    if (!is_numeric($post->id_detalle_asistencia)) {
        $validador == false;
        echo json_encode(
            array('message' => 'ingrese solo numeros')
        );
    }else {
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
    $post = new Controller_detalle_asistencia($GLOBALS['db']);
    $a = new Controller_Asistencia($GLOBALS['db']);
    $t = new Controller_Trabajador($GLOBALS['db']);

    $post->id_detalle_asistencia = $GLOBALS['data']->id_detalle_asistencia;
    $post->falta_laboral = $GLOBALS['data']->falta_laboral;
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

    if (empty($post->falta_laboral)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese una descripcion del producto")
        );
    }
    if ($validador == true) {
        if ($post->Update_detalle_asistencia()) {
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
