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



if ($_SERVER['REQUEST_METHOD'] == 'GET') { //se hara el get de todas la entidades
    if (isset($_GET['id_compra'])) {
        // Instiate blog post object
        $post = new Controller_Factura_Compra($GLOBALS['db']);

        // GET ID
        $post->id_compra = isset($_GET['id_compra']) ? $_GET['id_compra'] : die();


        if (!empty($post->buscar_folio_factura($post->folio_factura))) {
            echo json_encode(
                array('message' => 'No existe datos sobre la factura N°' . $post->folio_factura)
            );
        } else {
            if ($post->Read_single_Factura_Compra()) {
                $post_item = array(
                    'id_compra' => $post->id_compra,
                    'fecha_compra' => $post->fecha_compra,
                    'valor_compra' => $post->valor_compra,
                    'estado' => $post->estado,
                    'rut_proveedor' => $post->rut_proveedor,
                    'id_tipo_f_compra' => $post->id_tipo_f_compra,



                    'id_detalle_compra' => $post->id_detalle_compra,
                    'descripcion_compra_producto' => $post->descripcion_compra_producto,
                    'cantidad_compra_producto' => $post->cantidad_compra_producto,
                    'valor' => $post->valor,
                    'producto_id_producto' => $post->producto_id_producto,
                    'id_producto' => $post->id_producto,
                    'nombre_producto' => $post->nombre_producto,
                    'valor_producto' => $post->valor_producto
                );
                //Make JSON

                print_r(json_encode($post_item));
            } else {
                echo json_encode(
                    array('message' => 'No se encontro el codigo de la factura compra')
                );
            }
        }
    } else {
        $post =  new Controller_Factura_Compra($GLOBALS['db']);
        $result = $post->Read_Factura_Compra_no_pagados();
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_compra' => $id_compra,
                    'fecha_compra' => $fecha_compra,
                    'valor_compra' => $valor_compra,
                    'estado' => $estado,
                    'rut_proveedor' => $rut_proveedor,
                    'id_tipo_f_compra' => $id_tipo_f_compra
                );
                array_push($posts_arr['data'], $post_item);
            }

            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(

                array('message' => 'No existen facturas compras')
            );
        }
    }
}


?>