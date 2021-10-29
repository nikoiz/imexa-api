<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/controller_bodega.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));



if ($_SERVER['REQUEST_METHOD'] == 'GET'){
if (isset($_GET['nombre_producto'])){
    $post = new controller_bodega($GLOBALS['db']);
    $post->nombre_producto = isset($_GET['nombre_producto']) ? $_GET['nombre_producto'] : die();

        $result = $post->buscar_bodeganombre_producto();
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_bodega' => $id_bodega,
                    'nombre_bodega' => $nombre_bodega
                    
                );

                array_push($posts_arr['data'], $post_item);
            }

            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(
                array('message' => 'No existen bodegas')
            );
        }
    }else{
        
    }

}
?>