<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
//se usuran 3 innvantario - producto - inventario/bodega
include_once '../../Controller/Controller_Inventario.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validador = true;
    $post = new Controller_Inventario($GLOBALS['db']);
    $post->valor_inventario = $GLOBALS['data']->valor_inventario;


    if ($post->Validacion_parametros($post->valor_inventario) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => "ingrese el valor del invantario")
        );
    } else {
        if (!is_numeric($post->valor_inventario)) {
            $validador = false;
            echo json_encode(
                array('Error' => "ingrese solo valores numericos")
            );
        } else {
            if (!$post->valor_inventario > 0) {
                $validador = false;
                echo json_encode(
                    array('Error' => "ingrese un valor mayor a 0 del valor de inventario")
                );
            }
        }
    }
    if ($validador == true) {
        if ($post->create_invantario()) {
            echo json_encode(
                array('message' => 'Se creo el inventario')
            );
        } else {
            echo json_encode(
                array('message' => 'No se creo el inventario')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //establesco medio en donde al leer el inventario si no existe se creara automaticameente y ejecutara la orden nuevamente para mostrar datos
    $validador = true;
    do {
        if (isset($_GET['id_inventario'])) {
            $post = new Controller_Inventario($GLOBALS['db']);
            $post->id_inventario = isset($_GET['id_inventario']) ? $_GET['id_inventario'] : die();

            if (!empty($post->id_inventario)) {
                if (empty($post->id_inventario)) {
                    $validador = false;
                    echo json_encode(
                        array('message' => 'Error ingrese un rut trabajador')
                    );
                } else {
                    if ($post->Read_single_inventario()) {
                        $post_item = array(
                            'id_inventario' => $post->id_inventario,
                            'valor_inventario' => $post->valor_inventario
                        );
                        //Make JSON

                        print_r(json_encode($post_item));
                    } else {
                        echo json_encode(
                            array('message' => 'No se encontro inventario')
                        );
                        $validador = false;
                    }
                }
            } else {
                echo json_encode(
                    array('message' => 'Ingrese un inventario a busacar')
                );
            }
        } else {
            $post = new Controller_Inventario($GLOBALS['db']);
            $result = $post->Read_inventario();
            // Get row count
            $num = $result->rowCount();

            if ($num > 0) {
                // Post array
                $posts_arr = array();
                $posts_arr['data'] = array();

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $post_item = array(
                        'id_inventario' => $id_inventario,
                        'valor_inventario' => $valor_inventario
                    );

                    array_push($posts_arr['data'], $post_item);
                }

                echo json_encode($posts_arr);
                $validador = true;
            } else {
                // No posts
                /*
            echo json_encode(

                array('message' => 'No Posts Found')
            );
            */
                $validador = false;
            }
        }
        if ($validador == false) {
            if ($post->invantario_por_defecto() == true) {
            }
        }
    } while ($validador != true);
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $post = new Controller_Inventario($GLOBALS['db']);


    // GET ID
    $post->id_inventario = isset($_GET['id_inventario']) ? $_GET['id_inventario'] : die();

    if (!empty($post->Busacar_id_inventario($post->id_inventario))) {
        echo json_encode(
            array('message' => 'no se encontro el inventario a eliminar')
        );
    } else {
        if ($post->delete_single_inventario()) {
            echo json_encode(
                array('message' => 'Se elimino el inventario')
            );
        } else {
            echo json_encode(
                array('message' => 'No se elimino inventario')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $validador = true;
    $post = new Controller_Inventario($GLOBALS['db']);
    $post->valor_inventario = $GLOBALS['data']->valor_inventario;
    $post->id_inventario = $GLOBALS['data']->id_inventario;

    if ($post->Busacar_id_inventario($post->id_inventario) == true) {
        $validador = false;
        echo json_encode(
            array('Error' => "No se encuentra el coddigo identificativo del inventario")
        );
    }
    if ($post->Validacion_parametros($post->valor_inventario) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => "ingrese el valor del invantario")
        );
    } else {
        if (!is_numeric($post->valor_inventario)) {
            $validador = false;
            echo json_encode(
                array('Error' => "ingrese solo valores numericos")
            );
        } else {
            if (!$post->valor_inventario > 0) {
                $validador = false;
                echo json_encode(
                    array('Error' => "ingrese un valor mayor a 0 del valor de inventario")
                );
            }
        }
    }


    if ($validador == true) {
        if ($post->update_inventario()) {
            echo json_encode(
                array('message' => 'Se actualizo el inventario')
            );
        } else {
            echo json_encode(
                array('message' => 'No se actualizo el inventaria')
            );
        }
    }
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
