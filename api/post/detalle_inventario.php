<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_detalle_inventario.php';
include_once '../../Controller/controller_bodega.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['nombre_producto'])) {

        $post = new Controller_detalle_inventario($GLOBALS['db']);
        $post->nombre_producto = isset($_GET['nombre_producto']) ? $_GET['nombre_producto'] : die();
        $result = $post->Read_single_detalle_invetario($post->nombre_producto);
        // Get row count
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(

                    'nombre_producto' => $post->nombre_producto,
                    'cantidad_producto' => $post->cantidad_producto,
                    'valor' => $post->valor,
                    'nombre_bodega' => $post->nombre_bodega,
                    'numero_bodega' => $post->numero_bodega,
                    'peso_unitario'=> $post->peso_unitario
                );
                array_push($posts_arr['data'], $post_item);
            }
            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(
                array('message' => 'No se encontro el producto: ' . $post->nombre_producto)
            );
        }
    } else {
        $post = new Controller_detalle_inventario($GLOBALS['db']);
        $result = $post->Read_producto_detalle_invetario();
        // Get row count
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(


                    'id_detalle_inventario' => $id_detalle_inventario,
                    'nombre_producto' => $nombre_producto,
                    'cantidad_producto' => $cantidad_producto,
                    'valor' => $valor,
                    'fecha_inventario' => $fecha_inventario,
                    'id_inventario' => $id_inventario,
                    'id_bodega' => $id_bodega,
                    'id_producto' => $id_producto,
                    'peso_unitario' =>$peso_unitario
                );
                array_push($posts_arr['data'], $post_item);
            }
            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(
                array('message' => 'No exite detalle de inventario')
            );
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    //este metodo se establecera para el uso de la valansa como tal
    $post = new Controller_detalle_inventario($GLOBALS['db']);
    $bdg = new Controller_bodega($GLOBALS['db']);

    $post->id_detalle_inventario = $GLOBALS['data']->id_detalle_inventario;
    $post->nombre_producto = $GLOBALS['data']->nombre_producto;
    $post->cantidad_producto = $GLOBALS['data']->cantidad_producto;
    $post->valor = $GLOBALS['data']->valor;
    $post->fecha_inventario = $fecha = date('Y-m-d');
    $post->id_inventario = 1;
    $post->id_bodega = $GLOBALS['data']->id_bodega;
    $post->peso_unitario=$GLOBALS['data']->peso_unitario;

    $validador = true;

    if (empty($post->id_detalle_inventario)) {
        $validador = false;
        echo json_encode(
            array('Error' => "Ingrese un codigo de inventario")
        );
    } else {
        if ($post->buscar_id_detalle_inventario($post->id_detalle_inventario) == true) {
            $validador = false;
            echo json_encode(
                array('message' => 'No existe codigo del detalle de inventario en cuestion')
            );
        }
    }

    if (empty($post->nombre_producto)) {
        $validador = false;
        echo json_encode(
            array('Error' => "Ingrese un nombre de la bodega")
        );
    } 
    if (empty($post->valor)) {
        $validador = false;
        echo json_encode(
            array('Error' => "Ingrese un valor")
        );
    } else {
        if (!is_numeric($post->valor)) {
            $validador = false;
            echo json_encode(
                array('Error' => "Ingrese un numeros en el valor")
            );
        }
    }
    if (empty($post->id_bodega)) {
        $validador = false;
        echo json_encode(
            array('Error' => "Ingrese un valor")
        );
    } else {
        if ($bdg->buscar_id_bodega($post->id_bodega) == true) {
            $validador = false;
            echo json_encode(
                array('Error' => "No se encontro el codigo de la bodega")
            );
        }
    }
    if (!is_numeric($post->cantidad_producto)) {
        $validador = false;
            echo json_encode(
                array('Error' => "Ingrese un numeros para la cantidad")
            );
    }
    if ($validador==true) {
        if ($post->update_detalle_inventario()) {
            echo json_encode(
                array('message' => 'Se actualizo el producto: '.$post->nombre_producto)
            );
        } else {
            echo json_encode(
                array('message' => 'No se pudo actualizar el producto')
            );
        }
    }
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
