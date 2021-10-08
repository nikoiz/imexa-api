<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Producto.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $post =  new Controller_Producto($GLOBALS['db']);

    // Blog post query
    $result = $post->Read_producto_id_ultima();
    // Get row count
    $num = $result->rowCount();
    // Check if any posts


    if ($num > 0) {
        // Post array
        $posts_arr = array();
        $posts_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $post_item = array(
                'id_producto' => $id_producto
            );

            array_push($posts_arr['data'], $post_item);
        }

        echo json_encode($posts_arr);
    } else {
        // No posts
        echo json_encode(

            array('message' => 'No existe codigo de producto')
        );
    }

}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
?>