<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Producto.php';
include_once '../../Controller/Controller_bodega_has_producto.php';
include_once '../../Controller/controller_bodega.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_producto'])) {
        // Instiate blog post object
        $post = new Controller_Producto($GLOBALS['db']);

        // GET ID
        $post->id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : die();


        if (!empty($post->buscar_id_producto($post->id_producto))) {
            echo json_encode(
                array('message' => 'No existe datos el producto N°' . $post->id_producto)
            );
        } else {
            if ($post->read_single()) {
                $post_item = array(
                    'producto.id_producto' => $post->id_producto,
                    'valor_producto' => $post->valor_producto,
                    'nombre_producto' => $post->nombre_producto,
                    'cantidad_total' => $post->cantidad_total
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
        $post =  new Controller_bodega_has_producto($GLOBALS['db']);

        $result = $post->Read_bodega_has_producto();
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_producto' => $id_producto,
                    'nombre_producto' => $nombre_producto,
                    'valor_producto' => $valor_producto,
                    'cantidad_total' => $cantidad_total
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

if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
?>