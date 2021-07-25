<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Factura_Venta.php';
include_once '../../Controller/Controller_tipo_factura_venta.php';
include_once '../../Controller/Controller_metodo_pago_venta.php';
include_once '../../Controller/Controller_Abono.php';
include_once '../../Controller/Controller_Cliente.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validador = true;
    $post = new Controller_Factura_Venta($GLOBALS['db']);
    $tf = new Controller_tipo_factura_venta($GLOBALS['db']);
    $mpv = new Controller_metodo_pago_compra($GLOBALS['db']);
    $ab = new Controller_Abono($GLOBALS['db']);
    $cl = new Controller_Cliente($GLOBALS['db']);




    $post->id_venta = $GLOBALS['data']->id_venta;
    $post->fecha_venta = $fecha = date('Y-m-d');
    $post->valor_venta = $GLOBALS['data']->valor_venta;
    $post->estado = $GLOBALS['data']->estado;
    $post->id_tipo_venta = $GLOBALS['data']->id_tipo_venta;
    $post->rut_cliente = $GLOBALS['data']->rut_cliente;
    $post->recursiva_id = $post->id_venta;
    $post->id_tipo_f_venta = $GLOBALS['data']->id_tipo_f_venta;
    $ab->valor_abono =  $GLOBALS['data']->valor_abono;

    //comprobar el valor abono mandado, pero antes realizar validacion de parametros de ""



    if ($post->Validacion_parametro($post->id_venta) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una codigo de venta')
        );
    } else {
        if ($post->buscar_id_venta($post->id_venta) == false) {
            $validador = false;
            echo json_encode(
                array('message' => 'Existe numero de la factura venta')
            );
        }
    }
    if ($post->Validador_de_valor_venta($post->valor_venta) == true) {
        $validador = false;
        echo json_encode(
            array('message' => $post->Validador_de_valor_venta($post->valor_venta))
        );
    }
    if ($post->Validacion_parametro($post->estado) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una estado')
        );
    } else {
        if ($post->estado == "Pagado" || $post->estado == "Pendiente") {
        } else {
            $validador = false;
            echo json_encode(
                array('message' => 'favor de ingresar un estado de Pagado o Pendiente')
            );
        }
    }
    if ($post->Validacion_parametro($post->id_tipo_venta) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese un metodo de pago')
        );
    } else {
        if ($mpv->buscar_metodo_pago_venta($post->id_tipo_venta) == false) {
            $validador = false;
            echo json_encode(
                array('message' => 'No existe metodo de pago')
            );
        }
    }

    if ($post->Validacion_parametro($post->rut_cliente) == false) { //validacion mas busqeuda
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese el rut del cliente')
        );
    } else {
        if ($post->Validator_run($post->rut_cliente) == false) {
            $validador = false;
            echo json_encode(
                array('message' => 'Error rut mal ingresado')
            );
        } else {
            //buscar rut en proveedores
            if (!empty($cl->buscar_rut_cliente($post->rut_cliente))) {
                $validador = false;
                echo json_encode(
                    array('message' => 'No existe datos del cliente')
                );
            }
        }
    }
    if ($ab->valor_abono != "") {
        if ($ab->obtener_valor_total($post->rut_cliente) != "") {
            // se obtiene nuevo valor en base a los abonos 
            //si se resta o no
            $post->valor_venta =$post->valor_venta-$ab->obtener_valor_total($post->rut_cliente);
            //actualizar el abono se debe realizar en el update del abono como otro json

        } else {
            
            $validador = false;
            echo json_encode(
                array('message' => 'No se puedo Restar el valor del abono')
            );
            
            //se crea el abono

        }
    }
    if ($post->Validacion_parametro($post->id_tipo_f_venta) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese un tipo de factura')
        );
    } else {
        if ($tf->buscar_tipo_factura_venta($post->id_tipo_f_venta) == false) {
            $validador = false;
            echo json_encode(
                array('message' => 'Error no existe un tipo de factura')
            );
        }
    }


    if ($validador == true) {
        if ($post->create_factura_venta()) {
            echo json_encode(
                array('message' => 'Post Created')
            );
        } else {
            echo json_encode(
                array('message' => 'Post not created')
            );
        }
    }
}

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
        $result = $post->Read_Factura();
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
                array('message' => 'No Posts Found')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $post = new Controller_Factura_Venta($GLOBALS['db']);

    $post->id_venta = isset($_GET['id_venta']) ? $_GET['id_venta'] : die();

    if (!empty($post->buscar_id_venta($post->id_venta))) {
        echo json_encode(
            array('message' => 'no se encontro la venta para eliminar')
        );
    } else {
        //hacer delete de detalle con los productos
        if ($post->delete_single_factura_venta()) {
            echo json_encode(
                array('message' => 'Post deleted')
            );
        } else {
            echo json_encode(
                array('message' => 'Post not deleted')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $validador = true;
    $post = new Controller_Factura_Venta($GLOBALS['db']);




    $post->id_venta = $GLOBALS['data']->id_venta;
    $post->estado = $GLOBALS['data']->estado;

    if ($post->Validacion_parametro($post->id_venta) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una codigo de venta')
        );
    } else {
        if ($post->buscar_id_venta($post->id_compra) == true) {
            $validador = false;
            echo json_encode(
                array('message' => 'Existe numero de la factura venta')
            );
        }
    }
    if ($post->Validacion_parametro($post->estado) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una estado')
        );
    } else {
        if ($post->estado == "Pagado" || $post->estado == "Pendiente") {
        } else {
            $validador = false;
            echo json_encode(
                array('message' => 'favor de ingresar un estado de Pagado o Pendiente')
            );
        }
    }
    
    if ($validador == true) {
        if ($post->update_factura_venta()) {
            echo json_encode(
                array('message' => 'Post Update')
            );
        } else {
            echo json_encode(
                array('message' => 'Post not Update')
            );
        }
    }
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
