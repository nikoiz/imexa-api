<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Producto.php';
include_once '../../Controller/Controller_detalle_compra.php';
include_once '../../Controller/Controller_bodega_has_producto.php';
include_once '../../Controller/controller_bodega.php';
include_once '../../Controller/Controller_Factura_Compra.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validador = true;
    $post = new Controller_Producto($GLOBALS['db']);
    $pos = new Controller_detalle_compra($GLOBALS['db']);
    $po = new  Controller_bodega_has_producto($GLOBALS['db']);
    $b = new controller_bodega($GLOBALS['db']);
    $factura = new Controller_Factura_Compra($GLOBALS['db']);

    //datos de producto
    $post->nombre_producto = $GLOBALS['data']->nombre_producto;
    $post->valor_producto = $GLOBALS['data']->valor_producto;

    //datos de detalle compra
    $pos->descripcion_compra_producto = $GLOBALS['data']->descripcion_compra_producto;
    $pos->cantidad_compra_producto = $GLOBALS['data']->cantidad_compra_producto;
    $pos->valor = $GLOBALS['data']->valor;
    $pos->id_compra; // se sacara la cual sera la ultima de factura compra => ya sacada
    $pos->producto_id_producto; //se sacara al hacer ingreso del producto 

    //bodega_has_producto
    $b->id_bodega = $GLOBALS['data']->id_bodega;
    $po->id_producto; //se consigue mas abajo
    $po->cantidad_total = $GLOBALS['data']->cantidad_compra_producto; //preguntar ** 

    if ($post->validador_nombre($post->nombre_producto) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $post->validador_nombre($post->nombre_producto))
        );
    }
    if (empty($pos->descripcion_compra_producto)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese una descripcion del producto")
        );
    }
    if ($pos->Validador_cantidad_compra_producto($pos->cantidad_compra_producto) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $pos->Validador_cantidad_compra_producto($pos->cantidad_compra_producto))
        );
    }

    if ($pos->Validador_valor_compra_producto($pos->valor) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $pos->Validador_valor_compra_producto($pos->valor))
        );
    }
    if ($post->Validador_valor_producto($post->valor_producto) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $post->Validador_valor_producto($post->valor_producto))
        );
    }
    if (empty($b->id_bodega)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese una bodega")
        );
    } else {
        if ($b->buscar_id_bodega($b->id_bodega) != false) {
            $validador = false;
            echo json_encode(
                array('message' => "No existe la bodega")
            );
        }
    }


    if ($validador == true) {
        if ($post->create_producto()) {


            //buscar el ultimo id para el bodega has prod.
            $id_producto = $post->buscar_el_ultimo_id();
            $po->id_producto = $id_producto;
            //buscar id_compra
            $pos->id_compra = $factura->buscar_el_ultimo_id_de_factura_compra();
            //buscar el ultimo id del producto para el detalle
            $pos->producto_id_producto = $id_producto;


            if ($po->create_bodega_has_producto($b->id_bodega, $po->id_producto, $po->cantidad_total) == false) {
                echo json_encode(
                    array('message' => 'Error en ingreso de datos teniendo en cuenta el codigo del producto y el codigo de la bodega')
                );
            } else {
                //agregar el producto a al detalle compra
                if ($pos->create_detalle_compra($pos->descripcion_compra_producto, $pos->cantidad_compra_producto, $pos->valor, $pos->id_compra, $pos->producto_id_producto) == false) {
                    echo json_encode(
                        array('message' => 'Error no se pudo hacer el detalle del producto: ' . $post->nombre_producto)
                    );
                } else {
                    echo json_encode(
                        array('message' => 'Post Created')
                    );
                }
            }
        } else {
            echo json_encode(
                array('message' => 'Post not created')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') { // no lo necesita
    if (isset($_GET['id'])) {
    } else {
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') { // no lo necesita


}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') { //se
    
    $validador = true;
    $post = new Controller_Producto($GLOBALS['db']);
    $pos = new Controller_detalle_compra($GLOBALS['db']);
    $po = new  Controller_bodega_has_producto($GLOBALS['db']);
    $b = new controller_bodega($GLOBALS['db']);
    $factura = new Controller_Factura_Compra($GLOBALS['db']);

    //datos de producto
    $post->id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : die();
    $post->nombre_producto = $GLOBALS['data']->nombre_producto;
    $post->valor_producto = $GLOBALS['data']->valor_producto;

    //datos de detalle compra
    $pos->id_detalle_compra = $GLOBALS['data']->id_detalle_compra;
    $pos->descripcion_compra_producto = $GLOBALS['data']->descripcion_compra_producto;
    $pos->cantidad_compra_producto = $GLOBALS['data']->cantidad_compra_producto;
    $pos->valor = $GLOBALS['data']->valor;
    $pos->id_compra= $GLOBALS['data']->id_compra; // se sacara la cual sera la ultima de factura compra => ya sacada
    $pos->producto_id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : die();; //se sacara al hacer ingreso del producto 

    //bodega_has_producto
    $b->id_bodega = $GLOBALS['data']->id_bodega;

    $po->cantidad_total = $GLOBALS['data']->cantidad_compra_producto; //preguntar ** 

    if ($post->buscar_id_producto($post->id_producto)) {
        $validador = false;
        echo json_encode(
            array('message' => 'id no encontrado')
        );
    }

    if ($post->validador_nombre($post->nombre_producto) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $post->validador_nombre($post->nombre_producto))
        );
    }
    if (empty($pos->descripcion_compra_producto)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese una descripcion del producto")
        );
    }
    if ($pos->Validador_cantidad_compra_producto($pos->cantidad_compra_producto) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $pos->Validador_cantidad_compra_producto($pos->cantidad_compra_producto))
        );
    }
    if ($pos->Validador_valor_compra_producto($pos->valor) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $pos->Validador_valor_compra_producto($pos->valor))
        );
    }
    if ($post->Validador_valor_producto($post->valor_producto) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $post->Validador_valor_producto($post->valor_producto))
        );
    }
    if (empty($b->id_bodega)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese una bodega")
        );
    } else {
        if ($b->buscar_id_bodega($b->id_bodega) != false) {
            $validador = false;
            echo json_encode(
                array('message' => "No existe la bodega")
            );
        }
    }
    if ($pos->buscar_id_detalle_compra($pos->id_detalle_compra)!=false) {
        $validador = false;
        echo json_encode(
            array('message' => "No existe la bodega")
        );
    }

    
    if ($validador == true) {
        if ($post->update_producto()) {
            
            if ($po->update_bodega_has_producto($b->id_bodega, $post->id_producto, $cantidad_total)) {
                
                
                if ($pos->update_detalle_compra($pos->id_detalle_compra, $pos->descripcion_compra_producto, $pos->cantidad_compra_producto, $pos->valor, $pos->id_compra, $pos->producto_id_producto)) {
                    echo json_encode(
                        array('message' => 'Post Update')
                    );
                }
            } else {
                echo json_encode(
                    array('message' => 'Post not Update')
                );
            }
        } else {
            echo json_encode(
                array('message' => 'Post not Update')
            );
        }
    }
    
    
    
    
    
    
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
