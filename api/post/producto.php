<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
//se usuran 3 innvantario - producto - inventario/bodega
include_once '../../Controller/Controller_Producto.php';
include_once '../../Controller/Controller_bodega_has_producto.php';
include_once '../../Controller/controller_bodega.php';
include_once '../../Controller/Controller_Inventario.php';
include_once '../../Controller/Controller_detalle_inventario.php';

$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validador = true;
    $post = new Controller_Producto($GLOBALS['db']);
    $po = new Controller_bodega_has_producto($GLOBALS['db']);
    $p = new controller_bodega($GLOBALS['db']);
    $di = new Controller_detalle_inventario($GLOBALS['db']);
    $i = new Controller_Inventario($GLOBALS['db']);

    $post->nombre_producto = $GLOBALS['data']->nombre_producto;
    $post->valor_producto = $GLOBALS['data']->valor_producto;
    $cantidad_total = $GLOBALS['data']->cantidad_total;
    $p->id_bodega = $GLOBALS['data']->id_bodega;
    $di->peso_unitario = $GLOBALS['data']->peso_unitario;
    $id_inventario = 1;
    $fecha = date('Y-m-d');
    //$fecha = date('d-m-Y');
    //$validador=true;

    if ($post->validador_nombre($post->nombre_producto) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $post->validador_nombre($post->nombre_producto))
        );
    }
    if ($post->Validador_valor_producto($post->valor_producto) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $post->Validador_valor_producto($post->valor_producto))
        );
    }
    if ($po->Validador_cantidad_total($cantidad_total) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $po->Validador_cantidad_total($cantidad_total))
        );
    }
    if (empty($p->id_bodega)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese una bodega")
        );
    } else {
        if ($p->buscar_id_bodega($p->id_bodega) != false) {
            $validador = false;
            echo json_encode(
                array('message' => "No existe la bodega")
            );
        }
    }
    if (empty($di->peso_unitario)) {
        $validador = false;
        echo json_encode(
            array('message' => "ingrese un peso unitario")
        );
    }


    if ($validador == true) {
        if ($post->create_producto($post->nombre_producto,$post->valor_producto)) {


            $id_producto = $post->obtener_el_ultimo_id(); //manda
            if ($po->create_bodega_has_producto($p->id_bodega, $id_producto, $cantidad_total) == false) { //funciona
                echo json_encode(
                    array('message' => 'Error en ingreso de datos teniendo en cuenta el codigo del producto y el codigo de la bodega')
                );
            } else {

                //buscardor
                $nombre = $di->buscardor_igual_producto($post->nombre_producto, $post->valor_producto,$p->id_bodega);
                if ($nombre == null) {
                    echo json_encode(
                        array('message' => "no se encontro")
                    );

                    if ($di->create_detalle_inventario($post->nombre_producto, $cantidad_total, $post->valor_producto, 1, $p->id_bodega, $id_producto, $fecha,$di->peso_unitario) == false) { //listo
                        echo json_encode(
                            array('message' => 'Error en ingreso para el inventario')
                        );
                    } else {
                        echo json_encode(
                            array('message' => 'Se cre un nuevo producto')
                        );
                    }
                } else {
                    echo json_encode(
                        array('message' => "Producto encontrado")
                    );

                    $di->id_detalle_inventario = $di->buscardor_igual_producto_id($post->nombre_producto, $post->valor_producto,$p->id_bodega);
                    $cantidad_d_i = $di->buscardor_igual_producto_cantidad($post->nombre_producto, $post->valor_producto,$p->id_bodega);

                    $cantidad_d_i = $cantidad_d_i + $cantidad_total;
                    if ($di->Sumar_mismo_producto($di->id_detalle_inventario, $cantidad_d_i, $fecha) == false) {
                        echo json_encode(
                            array('message' => 'no se pudo actualizar el detalle del inventario')
                        );
                    } else {
                        echo json_encode(
                            array('message' => 'Se creo el actualizo el inventario del producto')
                        );
                    }
                }







                $valor_total = $di->valor_total();
                $cantidades_total = $di->cantidad_total();
                $total_inventario = $valor_total * $cantidades_total;


                if ($i->actualizar_valor($valor_total, $total_inventario) == false) { //me falta
                    echo json_encode(
                        array('message' => 'Error al actualizar el valor del inventario')
                    );
                } else {
                    echo json_encode(
                        array('message' => 'Se creo el producto')
                    );
                }
            }
        } else {
            echo json_encode(
                array('message' => 'No se creo el producto')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_producto'])) {
        // Instiate blog post object
        $post = new Controller_Producto($GLOBALS['db']);

        // GET ID
        $post->id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : die();


        if (!empty($post->buscar_id_producto($post->id_producto))) {
            echo json_encode(
                array('message' => 'No existe datos el producto N°' . $post->id_producto)
            );
        } else {
            if ($post->read_single()) {
                $post_item = array(
                    'producto.id_producto' => $post->id_producto,
                    'valor_producto' => $post->valor_producto,
                    'nombre_producto' => $post->nombre_producto,
                    'cantidad_total' => $post->cantidad_total
                );

                //Make JSON

                print_r(json_encode($post_item));
            } else {
                echo json_encode(
                    array('message' => 'No se encontro el producto con el codigo: '.$post->id_producto )
                );
            }
        }
    } else {
        $post =  new Controller_Producto($GLOBALS['db']);
        $result = $post->Read_producto();
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
        } else {
            // No posts
            echo json_encode(

                array('message' => 'No existen productos')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Instiate blog post object
    $post = new Controller_Producto($GLOBALS['db']);
    $po = new Controller_bodega_has_producto($GLOBALS['db']);
    $di = new Controller_detalle_inventario($GLOBALS['db']);

    // GET ID
    $post->id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : die();

    $fecha = date('Y-m-d');


    if (!empty($post->buscar_id_producto($post->id_producto))) {
        echo json_encode(
            array('message' => 'no se encontro la bodega para eliminar')
        );
    } else {
        //obtener cantidad del producto para despues restarla en el inventario
        //cantidad total de ese producto en cuestion
        $po->cantidad_total = $po->buscar_cantidad_producto($post->id_producto);
        //obtenr nombre y valor  por id 
        $post->nombre_producto = $post->Obtener_nombre_producto($post->id_producto);
        $post->valor_producto = $post->Obtener_valor_producto($post->id_producto);
        $po->id_bodega= $post->Obtener_id_bodega($post->id_producto);

        //restarla del inventario
        //se obtien el total del inventario sobre el producto 
        $cantidad_d_i = $di->buscardor_igual_producto_cantidad($post->nombre_producto, $post->valor_producto,$po->id_bodega);
        $cantidad_d_i = $cantidad_d_i - $po->cantidad_total;
        //se obtiene el id
        $di->id_detalle_inventario = $di->buscardor_igual_producto_id($post->nombre_producto, $post->valor_producto,$po->id_bodega);
        

        if ($di->Sumar_mismo_producto($di->id_detalle_inventario, $cantidad_d_i, $fecha) == false) {
            echo json_encode(
                array('message' => 'no se pudo actualizar el detalle del inventario')
            );
        } else {
            if ($po->delete_bodega_has_producto($post->id_producto)) {
                echo json_encode(
                    array('message' => 'Se elimino el producto')
                );
            } else {
                echo json_encode(
                    array('message' => 'No se elimino el producto')
                );
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $post = new Controller_Producto($GLOBALS['db']);
    $po = new Controller_bodega_has_producto($GLOBALS['db']);
    $p = new controller_bodega($GLOBALS['db']);
    // Get  raw posted data

    // GET ID
    $post->id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : die();
    $post->valor_producto = $GLOBALS['data']->valor_producto;
    $post->nombre_producto = $GLOBALS['data']->nombre_producto;
    $cantidad_total = $GLOBALS['data']->cantidad_total;
    $p->id_bodega = $GLOBALS['data']->id_bodega;
    $fecha = date('Y-m-d');
    $validador = true;

    if ($post->buscar_id_producto($post->id_producto) == true) {
        $validador = false;
        echo json_encode(
            array('message' => 'No existe la bodega')
        );
    }
    if ($post->buscar_nombre_producto($post->nombre_producto) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Existe mombre del Producto')
        );
    }
    if ($post->Validador_valor_producto($post->valor_producto) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $post->Validador_valor_producto($post->valor_producto))
        );
    }
    if ($po->Validador_cantidad_total($cantidad_total) != null) {
        $validador = false;
        echo json_encode(
            array('message' => $po->Validador_cantidad_total($cantidad_total))
        );
    }
    if ($validador == true) {
        if ($post->update_producto()) {
            if ($po->update_bodega_has_producto($p->id_bodega, $post->id_producto, $cantidad_total)) {




                $cantidad_d_i = $di->buscardor_igual_producto_cantidad($post->nombre_producto, $post->valor_producto,$p->id_bodega);
                $cantidad_d_i = $cantidad_d_i - $po->cantidad_total;
                //se obtiene el id
                $di->id_detalle_inventario = $di->buscardor_igual_producto_id($post->nombre_producto, $post->valor_producto,$p->id_bodega);


                if ($di->Sumar_mismo_producto($di->id_detalle_inventario, $cantidad_d_i, $fecha) == false) {
                    echo json_encode(
                        array('message' => 'no se pudo actualizar el detalle del inventario')
                    );
                }
                echo json_encode(
                    array('message' => 'Se actualizo el producto')
                );
            } else {
                echo json_encode(
                    array('message' => 'No se actualizo el producto')
                );
            }
        } else {
            echo json_encode(
                array('message' => 'No se actualizo el producto')
            );
        }
    }
}
