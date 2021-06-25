<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';

include_once '../../Controller/Controller_Producto.php';
include_once '../../Controller/Controller_detalle_venta.php';
include_once '../../Controller/controller_bodega.php';
include_once '../../Controller/Controller_Factura_Venta.php';
include_once '../../Controller/Controller_detalle_inventario.php';
include_once '../../Controller/Controller_Inventario.php'; //preguntar si se deve actualizar el total 


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validador = true;
    //mostrar los productos el det.inventario y da ahi hacer el producto con el  
    //del prodiucot en base a ello se trabaja
    //id lo cambie a autoincrement
    //!!!enlasar nomas
    $post = new Controller_Producto($GLOBALS['db']);
    $di = new Controller_detalle_inventario($GLOBALS['db']);
    $dv = new Controller_detalle_venta($GLOBALS['db']);
    $fv = new Controller_Factura_Venta($GLOBALS['db']);

    $fecha = date('Y-m-d');

    //datos del producto
    $post->nombre_producto = $GLOBALS['data']->nombre_producto;
    //busqueda de este en el inv y prod  por el nombre °

    //datos del detalle venta
    $dv->descripcion_producto = $GLOBALS['data']->descripcion_producto;
    $dv->cantidad_producto = $GLOBALS['data']->cantidad_producto;
    $dv->valor; // se sacara obteniedno por la busqueda del producto
    $dv->id_venta; // se sacara abajo
    $dv->producto_id_producto; //se sacara abajo°

    //detalle inventairo
    $di->id_detalle_inventario;

    if ($post->buscar_nombre_producto($post->nombre_producto) == true) {
        $validador = false;
        echo json_encode(
            array('message' => "no se encontro nombre del producto")
        );
    } else {
        if ($di->buscardor_igual_nombre($post->nombre_producto) == false) {
            $validador = false;
            echo json_encode(
                array('message' => "no se encontro nombre del producto")
            );
        } else {
            if ($post->buscar_id_producto_por_nombre($post->nombre_producto) != null) {
                $dv->producto_id_producto = $post->buscar_id_producto_por_nombre($post->nombre_producto);
                //obtner el id del detalle inv.
                
                if ($di->buscardor_id_detalle($post->nombre_producto) == null) {
                    $validador = false;
                    echo json_encode(
                        array('message' => "no se encontro el codigo del producto del inventario")
                    );
                }else {
                    $di->id_detalle_inventario= $di->buscardor_id_detalle($post->nombre_producto);

                }
            } else {
                $validador = false;
                echo json_encode(
                    array('message' => "no se encontro el codigo del producto")
                );
            }
        }
    }

    if ($dv->Validacion_parametro($dv->descripcion_producto) == false) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese la descripcion del producto")
        );
    }
    //valido campo vacio
    if ($dv->Validador_cantidad_venta_producto($dv->cantidad_producto) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $dv->Validador_cantidad_venta_producto($dv->cantidad_producto))
        );
    } else {
        
        //valido si existe cantidad solicitada
        //obtengo cantidad por le nombre
        $cantidad_prod = $di->buscardor_cantidad_producto($post->nombre_producto);
        if ($cantidad_prod < $dv->cantidad_producto) {
            $validador = false;
            echo json_encode(
                array('message' => "cantidad insuficiente para el pedido")
            );
        } else {
            //obtengo el valor 

            if ($valor_producto = $di->buscardor_valor_producto($post->nombre_producto) == null) {
                $validador = false;
                echo json_encode(
                    array('message' => "no se pudo conseguir el valor")
                );
            } else {
                $valor_producto = $di->buscardor_valor_producto($post->nombre_producto);
                $dv->valor = $valor_producto * $dv->cantidad_producto;
                //obteno el nuevo numero del inventario
                $cantidad_prod = $cantidad_prod-  $dv->cantidad_producto;
            }
        }
    }

    if ($validador == true) {
        $dv->id_venta = $fv->buscar_el_ultimo_id_de_factura_venta();

        if ($dv->create_detalle_venta($dv->descripcion_producto, $dv->cantidad_producto, $dv->cantidad_producto, $dv->id_venta, $dv->producto_id_producto)) {
            //actualizar el actualizar el inv.
            if ($di->Sumar_mismo_producto($di->id_detalle_inventario, $cantidad_prod, $fecha) == false) {
                echo json_encode(
                    array('message' => 'no se pudo actualizar el detalle del inventario')
                );
            } else {
                echo json_encode(
                    array('message' => 'Post Created')
                );
            }
        } else {
            echo json_encode(
                array('message' => 'Post not created')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
    } else {
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
