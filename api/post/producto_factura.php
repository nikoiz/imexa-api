<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Producto.php';
include_once '../../Controller/Controller_detalle_compra.php';
include_once '../../Controller/Controller_bodega_has_producto.php';
include_once '../../Controller/controller_bodega.php';
include_once '../../Controller/Controller_Factura_Compra.php';
include_once '../../Controller/Controller_detalle_inventario.php';
include_once '../../Controller/Controller_Inventario.php';


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
    $di = new Controller_detalle_inventario($GLOBALS['db']);
    $i = new Controller_Inventario($GLOBALS['db']);

    //datos de producto

    /*
    do {
        $numero_random = rand();
    } while ($post->buscar_random_id($numero_random)==true);
    */

    //busqeuda con el id no random

    do {
        $numero_random = $post->obtener_el_ultimo_id();
        $numero_random += 1;
    } while ($post->buscar_random_id($numero_random) == false);

    $post->id_producto = $numero_random; //se obtendra y retornara +1

    $post->nombre_producto = $GLOBALS['data']->nombre_producto;
    $post->valor_producto = $GLOBALS['data']->valor_producto;

    //datos de detalle compra
    $pos->descripcion_compra_producto = $GLOBALS['data']->descripcion_compra_producto;
    $pos->cantidad_compra_producto = $GLOBALS['data']->cantidad_compra_producto;
    $pos->valor = $GLOBALS['data']->valor;
    $pos->id_compra; // se sacara la cual sera la ultima de factura compra => ya sacada
    $pos->producto_id_producto = $post->id_producto; //se sacara al hacer ingreso del producto 

    //bodega_has_producto
    $b->id_bodega = $GLOBALS['data']->id_bodega;
    $po->cantidad_total = $GLOBALS['data']->cantidad_compra_producto; //preguntar ** 
    //buscar el ultimo id para el bodega has prod.(esta malo )
    $po->id_producto = $post->id_producto;
    // al buscar el id genera el error
    //buscar el nombre del producto
    //buscar el ultimo id y sumarlo ponerlo al producto 

    //inventario
    $i->id_inventario = 1;
    $fecha = date('Y-m-d');


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
    if ($i->Busacar_id_inventario($id_inventario) != false) {
        $validador = false;
        echo json_encode(
            array('message' => "No existe el inventario")
        );
    }









    if ($validador == true) {
        if ($post->create_producto_factura()) {
            //buscar id_compra
            $pos->id_compra = $factura->buscar_el_ultimo_id_de_factura_compra();

            //error o mal dato

            //buscar el ultimo id del producto para el detalle
            $pos->producto_id_producto = $post->id_producto;

            echo json_encode(
                array('asd' => "$pos->producto_id_producto ")
            );

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



                    //crear el detalle del inventario 
                    //comprobar que ese detalle si ya existe  sumarlo

                    $nombre = $di->buscardor_igual_producto($post->nombre_producto, $post->valor_producto);

                    if ($nombre == null) { //2 medios en buscar 
                        echo json_encode(
                            array('message' => "no se encontro, se creara uno nuevo")
                        );
                        //crear el producot al inventario
                        if ($di->create_detalle_inventario($post->nombre_producto, $pos->cantidad_compra_producto, $post->valor_producto, $i->id_inventario, $b->id_bodega, $post->id_producto, $fecha) == false) {
                            echo json_encode(
                                array('message' => 'no se pudo crear el detalle del inventario')
                            );
                        }
                    } else {

                        echo json_encode(
                            array('message' => "Producto encontrado")
                        );

                        if ($di->buscardor_igual_producto_id($post->nombre_producto, $post->valor_producto) != null) {

                            $di->id_detalle_inventario = $di->buscardor_igual_producto_id($post->nombre_producto, $post->valor_producto);
                        } else {
                            echo json_encode(
                                array('message' => "Error: Codigo no encontrado")
                            );
                        }

                        if ($di->buscardor_igual_producto_cantidad($post->nombre_producto, $post->valor_producto) != null) {
                            $cantidad_d_i = $di->buscardor_igual_producto_cantidad($post->nombre_producto, $post->valor_producto);
                        } else {
                            echo json_encode(
                                array('message' => "Error: Cantidad no encontrada")
                            );
                        }

                        $cantidad_d_i = $cantidad_d_i + $pos->cantidad_compra_producto;

                        if ($di->Sumar_mismo_producto($di->id_detalle_inventario, $cantidad_d_i, $fecha) == false) {
                            echo json_encode(
                                array('message' => 'Error: No se pudo actualizar el detalle del inventario')
                            );
                        } else {
                            if ($di->valor_total() != null) {
                                $valor_total = $di->valor_total();
                            } else {
                                echo json_encode(
                                    array('message' => "Error: No se pudo obtener el valo total")
                                );
                            }
                            if ($di->cantidad_total() != null) {
                                $cantidades_total = $di->cantidad_total();
                            } else {
                                echo json_encode(
                                    array('message' => "Error: No se pudo obtener la cantidad total")
                                );
                            }
                        }
                    }



                    $total_inventario = $valor_total * $cantidades_total;

                    $i->actualizar_valor($total_inventario, 1);

                    echo json_encode(
                        array('message' => 'Se creo el producto factura')
                    );
                }
            }
        } else {
            echo json_encode(
                array('message' => 'No se creo la factura del producto')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') { // no lo necesita   no o esta retornando el nombre
    $di = new Controller_detalle_inventario($GLOBALS['db']);
    $di->nombre_producto = isset($_GET['nombre_producto']) ? $_GET['nombre_producto'] : die();
    $di->valor = isset($_GET['valor']) ? $_GET['valor'] : die();

    if ($di->Read_single_inventario_only($di->nombre_producto, $di->valor)) {
        $post_item = array(
            'nombre_producto' => $di->nombre_producto,
            'id_detalle_inventario' => $di->id_detalle_inventario
        );
        //Make JSON

        print_r(json_encode($post_item));
    }else {
        echo json_encode(
            array('message' => 'No se encontro datos del producto')
        );
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
    $pos->id_compra = $GLOBALS['data']->id_compra; // se sacara la cual sera la ultima de factura compra => ya sacada
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
    if ($pos->buscar_id_detalle_compra($pos->id_detalle_compra) != false) {
        $validador = false;
        echo json_encode(
            array('message' => "No existe el detalle compra")
        );
    }


    if ($validador == true) {
        if ($post->update_producto()) {

            if ($po->update_bodega_has_producto($b->id_bodega, $post->id_producto, $cantidad_total)) {


                if ($pos->update_detalle_compra($pos->id_detalle_compra, $pos->descripcion_compra_producto, $pos->cantidad_compra_producto, $pos->valor, $pos->id_compra, $pos->producto_id_producto)) {
                    echo json_encode(
                        array('message' => 'Se actualizo la factura de la compra')
                    );
                }
            } else {
                echo json_encode(
                    array('message' => 'No se actualizo la factura de la compra ')
                );
            }
        } else {
            echo json_encode(
                array('message' => 'No se actualizo la factura de la compra')
            );
        }
    }
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
