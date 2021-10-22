<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_detalle_inventario.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_bodega'])) {

        $post = new Controller_detalle_inventario($GLOBALS['db']);
        $post->id_bodega = isset($_GET['id_bodega']) ? $_GET['id_bodega'] : die();
        $result = $post->Read_single_detalle_invetario_por_bodega($post->id_bodega);
        // Get row count
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(

                    'nombre_producto' => $nombre_producto,
                    'cantidad_producto' => $cantidad_producto,
                    'valor' => $valor
                );
                array_push($posts_arr['data'], $post_item);
            }
            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(
                array('message' => 'No se encontro el producto: '.$post->nombre_producto)
            );
        }

       
    } else {
        $post = new Controller_detalle_inventario($GLOBALS['db']);
        $result = $post->Read_producto_detalle_invetario();
        // Get row count
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(


                    'id_detalle_inventario' => $id_detalle_inventario,
                    'nombre_producto' => $nombre_producto,
                    'cantidad_producto' => $cantidad_producto,
                    'valor' => $valor,
                    'fecha_inventario' => $fecha_inventario,
                    'id_inventario' => $id_inventario,
                    'id_bodega' => $id_bodega,
                    'id_producto' => $id_producto
                );
                array_push($posts_arr['data'], $post_item);
            }
            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(
                array('message' => 'No exite detalle de inventario')
            );
        }
    }
}
