<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_gastos'])) {

        // Instiate blog post object
        $post = new Controller_Gasto($GLOBALS['db']);
        $p = new controller_bodega($GLOBALS['db']);

        // GET ID
        //se puede cambiar por el id_bodega (decir a compañeero para ver quer le parece)
        $post->id_gastos = isset($_GET['id_gastos']) ? $_GET['id_gastos'] : die();


        if (!empty($post->buscar_id_gastos($post->id_gastos))) {
            echo json_encode(
                array('message' => 'No existe datos sobre la bodega N°' . $post->id_producto)
            );
        } else {
            if ($post->Read_single_gasto()) {
                $post_item = array(
                    'id_gastos' => $post->id_gastos,
                    'descripcion_gastos' => $post->descripcion_gastos,
                    'valor_gastos' => $post->valor_gastos,
                    'estado' => $post->estado,
                    'fecha' => $post->fecha,
                    'nombre_bodega' =>$post->nombre_bodega,
                    'id_bodega' => $post->id_bodega
                );
                //Make JSON

                print_r(json_encode($post_item));
            } else {
                echo json_encode(
                    array('message' => 'No se encontro los gastos del codigo: '.$post->id_gastos )
                );
            }
        }
    } else {
        $post =  new Controller_Gasto($GLOBALS['db']);
        $result = $post->Read_Gasto_no_pagado();
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_gastos' => $id_gastos,
                    'descripcion_gastos' => $descripcion_gastos,
                    'valor_gastos' => $valor_gastos,
                    'estado' => $estado,
                    'fecha' => $fecha,
                    'nombre_bodega' =>$nombre_bodega,
                    'id_bodega' => $id_bodega
                );

                array_push($posts_arr['data'], $post_item);
            }

            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(

                array('message' => 'No existe gastos')
            );
        }
    }
}



//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
?>