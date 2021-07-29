<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_metodo_pago_compra.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_tipo_compra'])) {
        $validador = false;
        $post = new Controller_metodo_pago_compra($GLOBALS['db']);
        $post->id_tipo_compra = isset($_GET['id_tipo_compra']) ? $_GET['id_tipo_compra'] : die();
        if (empty($post->id_tipo_compra)) {
            $validador = false;
            echo json_encode(
                array('message' => 'Error ingrese un rut trabajador')
            );
        }
        if ($validador==true) {
            if ($post->Read_single_metodo_pago_compra()) {
                $post_item = array(
                    'id_tipo_compra' => $post->id_tipo_compra,
                    'descripcion_compra ' => $post->descripcion_compra
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
        $post = new Controller_metodo_pago_compra($GLOBALS['db']);
        $result = $post->read_metodo_pago_compra();
        // Get row count
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_tipo_compra' => $id_tipo_compra,
                    'descripcion_compra ' => $descripcion_compra
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
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
