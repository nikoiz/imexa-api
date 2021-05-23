<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
//se usuran 3 innvantario - producto - inventario/bodega
include_once '../../Controller/Controller_Producto.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = new Controller_Producto($GLOBALS['db']);
    $post->nombre_producto = $GLOBALS['data']->nombre_producto;
    $post->valor_producto = $GLOBALS['data']->valor_producto;

    //$validador=true;

    if ($post->create_producto()) {
        echo json_encode(
            array('message' => 'Post Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Post not created')
        );
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
                array('message' => 'No existe datos sobre la bodega N°' . $post->id_producto)
            );
        } else {
            if ($post->read_single()) {
                $post_item = array(
                    'id_producto' => $post->id_producto,
                    'valor_producto' => $post->valor_producto,
                    'nombre_producto' => $post->nombre_producto
                );

                //Make JSON

                print_r(json_encode($post_item));
            } else {
                echo json_encode(
                    array('message' => 'No Posts Found')
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

                array('message' => 'No Posts Found')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Instiate blog post object
    $post = new Controller_Producto($GLOBALS['db']);


    // GET ID
    $post->id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : die();

    if (!empty($post->buscar_id_producto($post->id_producto))) {
        echo json_encode(
            array('message' => 'no se encontro la bodega para eliminar')
        );
    } else {
        if ($post->delete_single_producto()) {
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
    $post = new Controller_Producto($GLOBALS['db']);

    // Get  raw posted data

    // GET ID
    $post->id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : die();
    $post->valor_producto = $GLOBALS['data']->valor_producto;
    $post->nombre_producto = $GLOBALS['data']->nombre_producto;

    $validador = true;

    if ($post->buscar_nombre_producto($post->nombre_producto) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Existe mombre del Producto')
        );
    }
    if ($validador == true) {
        if ($post->update_producto()) {
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
