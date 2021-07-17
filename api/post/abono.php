<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Abono.php';
include_once '../../Controller/Controller_Factura_Venta.php';
include_once '../../Controller/Controller_Cliente.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $validador = true;
    $post = new Controller_Abono($GLOBALS['db']);
    $fv = new Controller_Factura_Venta($GLOBALS['db']);

    $post->id_abono = $GLOBALS['data']->id_abono;
    $post->valor_abono = $GLOBALS['data']->valor_abono;
    $post->fecha_abono = $fecha = date('Y-m-d'); //se establece como directa 
    $post->id_venta = $GLOBALS['data']->id_venta;


    if ($post->Validacion_parametro($post->id_abono) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una codigo de venta')
        );
    } else {
        if ($post->buscar_id_abono($post->id_abono) == true) {
            $validador = false;
            echo json_encode(
                array('message' => 'Existe numero de la factura venta')
            );
        }
    }
    if ($post->Validador_de_valor_abono($post->valor_abono) == true) {
        $validador = false;
        echo json_encode(
            array('message' => $post->Validador_de_valor_abono($post->valor_abono))
        );
    }
    if ($post->Validacion_parametro($post->id_venta) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una codigo de venta')
        );
    } else {
        if ($fv->buscar_id_venta($post->id_venta) == true) {
            $validador = false;
            echo json_encode(
                array('message' => 'Existe numero de la factura venta')
            );
        }
    }

    if ($validador == true) {
        if ($post->create_abono()) {
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

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
if (isset($_GET['rut_cliente'])){
    $post = new Controller_Abono($GLOBALS['db']);
    $cl = new Controller_Cliente($GLOBALS['db']);
    $cl->rut_cliente = isset($_GET['rut_cliente']) ? $_GET['rut_cliente'] : die();
    if (!empty($post->rut_cliente)) {
            if ($post->Read_single_abono()) {
                $post_item = array(
                    'id_abono' => $post->id_abono,
                    'valor_abono ' => $post->valor_abono,
                    'fecha_abono' =>$post->fecha_abono,
                    'id_venta'=>$post->id_venta
                );
                //Make JSON
                //por medio del rut cliente hacer el tan el single y despues mostar el total sumado de los abonos
                $total_abono=$post->obtener_valor_total($post->rut_cliente);
                print_r(json_encode($post_item));
                print_r(json_encode($total_abono));
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
    }else{
        $post = new Controller_Abono($GLOBALS['db']);
        $result = $post->Read_Abono();
        // Get row count
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_abono' => $id_abono,
                    'valor_abono ' => $valor_abono,
                    'fecha_abono' =>$fecha_abono,
                    'id_venta'=>$id_venta
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

if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    /*
    ver si como actualizar el valor
    si es necesario el actualizar 
    */
    // 
    $validador = true;
    $post = new Controller_Abono($GLOBALS['db']);
    $fv = new Controller_Factura_Venta($GLOBALS['db']);

    $post->id_abono = $GLOBALS['data']->id_abono;
    $post->valor_abono = $GLOBALS['data']->valor_abono;
    $post->fecha_abono = $fecha = date('Y-m-d'); //se establece como directa 
    $post->id_venta = $GLOBALS['data']->id_venta;


    if ($post->Validacion_parametro($post->id_abono) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una codigo de venta')
        );
    } else {
        if ($post->buscar_id_abono($post->id_abono) == true) {
            $validador = false;
            echo json_encode(
                array('message' => 'Existe numero de la factura venta')
            );
        }
    }
    if ($post->Validador_de_valor_abono($post->valor_abono) == true) {
        $validador = false;
        echo json_encode(
            array('message' => $post->Validador_de_valor_abono($post->valor_abono))
        );
    }
    if ($post->Validacion_parametro($post->id_venta) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una codigo de venta')
        );
    } else {
        if ($fv->buscar_id_venta($post->id_venta) == true) {
            $validador = false;
            echo json_encode(
                array('message' => 'Existe numero de la factura venta')
            );
        }
    }
    if ($validador==true) {
        if ($post->update_abono()) {
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
?>