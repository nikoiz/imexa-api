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
    if (isset($_GET['id_venta'])) {
        $post = new Controller_Factura_Venta($GLOBALS['db']);
        $post->id_venta = isset($_GET['id_venta']) ? $_GET['id_venta'] : die();
        if (!empty($post->id_venta)) {
            if ($post->Read_single_factura()) {
                $post_item = array(
                    'id_venta' => $post->id_venta,
                    'fecha_venta' => $post->fecha_venta,
                    'valor_venta' => $post->valor_venta,
                    'estado' => $post->estado,
                    'id_tipo_venta' => $post->id_tipo_venta,
                    'rut_cliente' => $post->rut_cliente,
                    'recursiva_id' => $post->recursiva_id,
                    'id_tipo_f_venta' => $post->id_tipo_f_venta,

                    'id_detalle_venta' => $post->id_detalle_venta,
                    'descripcion_producto' => $post->descripcion_producto,
                    'cantidad_producto' => $post->cantidad_producto,
                    'valor' => $post->valor,
                    'producto_id_producto' => $post->producto_id_producto


                );
                //Make JSON

                print_r(json_encode($post_item));
            } else {
                echo json_encode(
                    array('message' => 'No Posts Found')
                );
            }
        } else {
            echo json_encode(
                array('message' => 'Ingrese rut del cliente')
            );
        }
    } else {
        $post = new Controller_Factura_Venta($GLOBALS['db']);
        $result = $post->Read_Factura_no_pagadas();
        // Get row count
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_venta' => $id_venta,
                    'fecha_venta' => $fecha_venta,
                    'valor_venta' => $valor_venta,
                    'estado' => $estado,
                    'id_tipo_venta' => $id_tipo_venta,
                    'rut_cliente' => $rut_cliente,
                    'recursiva_id' => $recursiva_id,
                    'id_tipo_f_venta' => $id_tipo_f_venta
                );
                array_push($posts_arr['data'], $post_item);
            }
            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(
                array('message' => 'No existen facturas ventas')
            );
        }
    }
}

?>