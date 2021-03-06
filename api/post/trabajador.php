<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Trabajador.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validador = true;
    $post = new Controller_Trabajador($GLOBALS['db']);
    $post->rut_trabajador = $GLOBALS['data']->rut_trabajador;
    $post->nombre_trabajador = $GLOBALS['data']->nombre_trabajador;
    $post->fecha_contratacion = $GLOBALS['data']->fecha_contratacion;
    $post->valor_dia = $GLOBALS['data']->valor_dia;
    $post->sueldo = null;
    $post->usuario = "";
    $post->contraseña = "";
    $post->id_tipo_trabajador = 2;

    if ($post->validateDate($post->fecha_contratacion) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => "Fecha mal ingresada")
        );
    }

    if ($post->Validator_run($post->rut_trabajador) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => "Error no se rut mal ingresado")
        );
    }

    if ($post->Validacion_parametros($post->nombre_trabajador) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => "ingrese nombre de trabajador")
        );
    }

    if ($post->Buscar_tipo_trabajador($post->id_tipo_trabajador) != false) {
        $validador = false;
        echo json_encode(
            array('Error' => "no se establecio el tipo de trabajador")
        );
    }
    if ($post->Validacion_parametros($post->valor_dia) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => "ingrese un valor del dia")
        );
    } else {
        if (is_numeric($post->valor_dia)==false) {
            $validador = false;
            echo json_encode(
                array('Error' => "ingrese solo numeros para el valor dia")
            );
        }
    }

    if ($validador==true) {
        if ($post->create_trabajador()) {
            echo json_encode(
                array('message' => 'Se creo el trabajador')
            );
        } else {
            echo json_encode(
                array('message' => 'No se creo el trabajador')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['rut_trabajador'])) {
        $validador = true;
        $post = new Controller_Trabajador($GLOBALS['db']);
        $post->rut_trabajador = isset($_GET['rut_trabajador']) ? $_GET['rut_trabajador'] : die();

        if (!empty($post->rut_trabajador)) {
            if ($post->Validator_run($post->rut_trabajador) == false) {
                $validador = false;
                echo json_encode(
                    array('message' => 'Error ingrese un rut trabajador')
                );
            } else {
                if ($post->Read_single_trabajador()) {
                    $post_item = array(
                        'rut_trabajador' => $post->rut_trabajador,
                        'nombre_trabajador' => $post->nombre_trabajador,
                        'fecha_contratacion' => $post->fecha_contratacion,
                        'valor_dia' => $post->valor_dia,
                        'sueldo' => $post->sueldo,
                        'usuario' => $post->usuario,
                        'contraseña' => $post->contraseña,
                        'id_tipo_trabajador' => $post->id_tipo_trabajador
                    );
                    //Make JSON

                    print_r(json_encode($post_item));
                } else {
                    echo json_encode(
                        array('message' => 'No se encontro al trabajador con el rut: '.$post->rut_trabajador)
                    );
                }
            }
        } else {
            echo json_encode(
                array('message' => 'Ingrese rut del trabajador')
            );
        }
    } else {
        $post = new Controller_Trabajador($GLOBALS['db']);
        $result = $post->Read_trabajador();
        // Get row count
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'rut_trabajador' => $rut_trabajador,
                    'nombre_trabajador' => $nombre_trabajador,
                    'fecha_contratacion' => $fecha_contratacion,
                    'valor_dia' => $valor_dia,
                    'sueldo' => $sueldo,
                    'usuario' => $usuario,
                    'contraseña' => $contraseña,
                    'id_tipo_trabajador' => $id_tipo_trabajador
                );

                array_push($posts_arr['data'], $post_item);
            }

            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(

                array('message' => 'No existen trabajadores')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $post = new Controller_Trabajador($GLOBALS['db']);


    // GET ID
    $post->rut_trabajador = isset($_GET['rut_trabajador']) ? $_GET['rut_trabajador'] : die();

    if (!empty($post->Buscar_rut_trabajador($post->rut_trabajador))) {
        echo json_encode(
            array('message' => 'no se encontro el trabajador para eliminar')
        );
    } else {
        if ($post->delete_single_trabajador()) {
            echo json_encode(
                array('message' => 'Se elimino trabajadores')
            );
        } else {
            echo json_encode(
                array('message' => 'No se elimino trabajadores')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $validador = true;
    $post = new Controller_Trabajador($GLOBALS['db']);
    $post->rut_trabajador = $GLOBALS['data']->rut_trabajador;
    $post->valor_dia = $GLOBALS['data']->valor_dia;



    if ($post->Validator_run($post->rut_trabajador) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => "Error rut mal ingresado")
        );
    }else {
        if ($post->Buscar_rut_trabajador($post->rut_trabajador)==true) {
            $validador = false;
            echo json_encode(
                array('Error' => "Error no se encontro el rut del trabajdor")
            );
        }
    }
    if ($post->Validacion_parametros($post->valor_dia) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => "ingrese un valor del dia")
        );
    } else {
        if (is_numeric($post->valor_dia)==false) {
            $validador = false;
            echo json_encode(
                array('Error' => "ingrese solo numeros para el valor dia")
            );
        }
    }
    if ($validador == true) {
        if ($post->update_trabajador()) {
            echo json_encode(
                array('message' => 'Se actualizo trabajador')
            );
        } else {
            echo json_encode(
                array('message' => 'No se actualizo trabajador')
            );
        }
    }
}
