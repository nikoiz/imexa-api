<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Factura_Venta.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['rut_cliente'])) {
        $post = new Controller_Factura_Venta($GLOBALS['db']);
        $post->rut_cliente = isset($_GET['rut_cliente']) ? $_GET['rut_cliente'] : die();
        if (!empty($post->rut_cliente)) {
            if ($result = $post->Read_single_factura_no_pagadas()) {
                $num = $result->rowCount();
                if ($num > 0) {
                    // Post array
                    $posts_arr = array();
                    $posts_arr['data'] = array();
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $post_item = array(
                            'id_venta' => $post->id_venta,
                            'fecha_venta' => $post->fecha_venta,
                            'valor_venta' => $post->valor_venta,
                            'estado' => $post->estado,
                            'id_tipo_venta' => $post->id_tipo_venta,
                            'rut_cliente' => $post->rut_cliente,
                            'recursiva_id' => $post->recursiva_id,
                            'id_tipo_f_venta' => $post->id_tipo_f_venta
                        );
                        array_push($posts_arr['data'], $post_item);
                    }
                    //echo json_encode($posts_arr);
                } else {
                    // No posts
                    echo json_encode(
                        array('message' => 'No existen facturas de ventas')
                    );
                }
                //listar el detalle y el valor total
                if ($post->Suma_facturas_Npagadas_cliente()!=null) {
                    $total = $post->Suma_facturas_Npagadas_cliente();
                }else {
                    echo json_encode(
                        array('message' => 'No se encontraron facturas no pagadas del cliente: '.$post->rut_cliente)
                    );
                }


                $detalle_completo = array(
                    "Facturas" => array(
                        $posts_arr
                        ),
                    "Total" =>array($total) 
                    );
    
                echo json_encode($detalle_completo);
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
            
            if ($post->Suma_facturas_Npagadas()!=null) {
                $total = $post->Suma_facturas_Npagadas();
            }else {
                echo json_encode(
                    array('message' => 'No existe un total de facturas compras no pagadas')
                );
            }
            $detalle_completo = array(
                "Facturas" => array(
                    $posts_arr
                    ),
                "Total" => $total
                );

            echo json_encode($detalle_completo);
        } else {
            // No posts
            echo json_encode(
                array('message' => 'No existen facturas ventas')
            );
        }
    }
}

?>