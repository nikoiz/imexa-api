<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Cliente.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validador = true;
    $post = new Controller_Cliente($GLOBALS['db']);

    $post->rut_cliente = $GLOBALS['data']->rut_cliente;
    $post->nombre_cliente = $GLOBALS['data']->nombre_cliente;

    if (empty($post->rut_cliente)) {
        $validador = false;
        echo json_encode(
            array('Error' => "Error ingrese un rut")
        );
    } else {
        if ($post->Validator_run($post->rut_cliente) == false) {
            $validador = false;
            echo json_encode(
                array('Error' => "Error no se rut mal ingresado")
            );
        }else {
            //busacar otro rut igual al cliente si es asi que no se cree
            if ($post->buscar_rut_cliente($post->rut_cliente)==false) {
                $validador = false;
                echo json_encode(
                    array('message' => 'ya existe el cliente')
                );
            }

        }
    }
    if (empty($post->nombre_cliente)) {
        $validador = false;
        echo json_encode(
            array('Error' => "Error ingrese el nombre")
        );
    }
    if ($validador == true) {
        if ($post->create_cliente()) {
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
    if (isset($_GET['rut_cliente'])) {
        $post = new Controller_Cliente($GLOBALS['db']);
        $post->rut_cliente = isset($_GET['rut_cliente']) ? $_GET['rut_cliente'] : die();

        if (!empty($post->rut_cliente)) {
            if ($post->Validator_run($post->rut_cliente) == false) {
                $validador = false;
                echo json_encode(
                    array('message' => 'Error rut mal ingresado')
                );
            } else {
                if ($post->Read_single_cliente()) {
                    $post_item = array(
                        'rut_cliente' => $post->rut_cliente,
                        'nombre_cliente ' => $post->nombre_cliente
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
            echo json_encode(
                array('message' => 'Ingrese rut del cliente')
            );
        }
    } else {

        $post = new Controller_Cliente($GLOBALS['db']);
        $result = $post->read_cliente();
        // Get row count
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'rut_cliente' => $rut_cliente,
                    'nombre_cliente ' => $nombre_cliente
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
    $post = new Controller_Cliente($GLOBALS['db']);

    $post->rut_cliente = isset($_GET['rut_cliente']) ? $_GET['rut_cliente'] : die();
    
    if (!empty($post->buscar_rut_cliente($post->rut_cliente))) {
        echo json_encode(
            array('message' => 'no se encontro la gasto para eliminar')
        );
    } else {
        if ($post->delete_single_cliente()) {
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
    $validador = true;
    $post = new Controller_Cliente($GLOBALS['db']);

    $post->rut_cliente = $GLOBALS['data']->rut_cliente;
    $post->nombre_cliente = $GLOBALS['data']->nombre_cliente;

    if (empty($post->rut_cliente)) {
        $validador = false;
        echo json_encode(
            array('Error' => "Error ingrese un rut")
        );
    } else {
        if ($post->Validator_run($post->rut_cliente) == false) {
            $validador = false;
            echo json_encode(
                array('Error' => "Error no se rut mal ingresado")
            );
        }
    }
    if (empty($post->nombre_cliente)) {
        $validador = false;
        echo json_encode(
            array('Error' => "Error ingrese el nombre")
        );
    }
    if ($validador==true) {
        if ($post->update_cliente()) {
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
