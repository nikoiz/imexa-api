<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');

include_once '../../config/conexion.php';
include_once '../../Controller/controller_bodega.php';
include_once '../../Controller/Controller_Producto.php';
include_once '../../Controller/Controller_Proveedor.php';
include_once '../../Controller/Controller_Gasto.php';
include_once '../../Controller/Controller_Factura_Compra.php';

// Instantiate DB & connect
$database = new conexion();
$db = $database->connect();


//Leer_producto();
Leer_bodega();
//Leer_proveedor();
//Leer_gastos();
//Leer_factura_compra();
function Leer_factura_compra()
{
    $post =  new Controller_Factura_Compra($GLOBALS['db']);
    $result=$post->Read_Factura_Compra();
    $num = $result->rowCount();

    if ($num > 0) {
        // Post array
        $posts_arr = array();
        $posts_arr['data'] = array();
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $post_item = array(
                'folio_factura' => $folio_factura,
                'total_factura' => $total_factura                 
            );
            array_push($posts_arr['data'], $post_item);
        }
    
        echo json_encode($posts_arr);
    
    }else {
        // No posts
        echo json_encode(
    
            array('message' => 'No Posts Found')
        );
    }
}
function Leer_gastos()
{
    $post =  new Controller_Gasto($GLOBALS['db']);
    $result=$post->Read_Gasto();
    $num = $result->rowCount();

    if ($num > 0) {
        // Post array
        $posts_arr = array();
        $posts_arr['data'] = array();
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $post_item = array(
                'id_gastos'=>$id_gastos,
                'descripcion_gastos' => $descripcion_gastos,
                'valor_gastos' => $valor_gastos,
                'estado' => $estado,
                'bodega_id_bodega' => $bodega_id_bodega,                  
            );
    
            array_push($posts_arr['data'], $post_item);
    
        }
    
        echo json_encode($posts_arr);
    
    }else {
        // No posts
        echo json_encode(
    
            array('message' => 'No Posts Found')
        );
    }
}
function Leer_proveedor()
{
    $post =  new Controller_Proveedor($GLOBALS['db']);
    $result=$post->Read_proveedor();
    $num = $result->rowCount();

    if ($num > 0) {
        // Post array
        $posts_arr = array();
        $posts_arr['data'] = array();
    
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $post_item = array(
                'rut_proveedor' => $rut_proveedor,
                'nombre_proveedor' => $nombre_proveedor                 
            );
    
            array_push($posts_arr['data'], $post_item);
    
        }
    
        echo json_encode($posts_arr);
    
    }else {
        // No posts
        echo json_encode(
    
            array('message' => 'No Posts Found')
        );
    }
}
function Leer_producto()
{
    $post =  new Controller_Producto($GLOBALS['db']);
    $result=$post->Read_producto();
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
                'valor_producto' => $valor_producto                 
            );
    
            array_push($posts_arr['data'], $post_item);
    
        }
    
        echo json_encode($posts_arr);
    
    }else {
        // No posts
        echo json_encode(
    
            array('message' => 'No Posts Found')
        );
    }

}


function Leer_bodega()
{
// Instiate blog post object
$post =  new controller_bodega($GLOBALS['db']);

// Blog post query
$result = $post->read();
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
            'id_bodega' => $id_bodega,
            'numero_bodega' => $numero_bodega,
            'nombre_bodega' => $nombre_bodega                 
        );

        array_push($posts_arr['data'], $post_item);

    }

    echo json_encode($posts_arr);

}else {
    // No posts
    echo json_encode(

        array('message' => 'No Posts Found')
    );
}
}



/*
*/

?>