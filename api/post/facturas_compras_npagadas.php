<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Factura_Compra.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));



if ($_SERVER['REQUEST_METHOD'] == 'GET') { //se hara el get de todas la entidades
    if (isset($_GET['rut_proveedor'])) {
        // Instiate blog post object
        $post = new Controller_Factura_Compra($GLOBALS['db']);

        // GET ID
        $post->rut_proveedor = isset($_GET['rut_proveedor']) ? $_GET['rut_proveedor'] : die();


            if ($result = $post->Read_single_Factura_Compra_no_pagados()) {
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


            if ($post->Suma_facturas_Npagadas_proveedor()!=null) {
                $total = $post->Suma_facturas_Npagadas_proveedor();
            }
            $detalle_completo = array(
                "Facturas" => array(
                    $posts_arr
                    ),
                "Total" => array($total)
                );

                print_r(json_encode($detalle_completo));

        } 
            } else {
                echo json_encode(
                    array('message' => 'No se encontro el codigo de la factura compra')
                );
            }
        
    } else {
        //para poner todas la facturas
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

            //obtner el total de todas la facturas no pagadas
            
            if ($post->Suma_facturas_Npagadas()!=null) {
                $total = $post->Suma_facturas_Npagadas();
            }

            $detalle_completo = array(
                "Facturas" => array(
                    $posts_arr
                    ),
                "Total" =>array($total) 
                );

                print_r(json_encode($detalle_completo));

        }
    }
}
