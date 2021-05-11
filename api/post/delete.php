<?php

header('Access-Control-Allow-Origin: http://localhost:8080');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');



include_once '../../config/conexion.php';
include_once '../../Controller/controller_bodega.php';
include_once '../../Controller/Controller_Producto.php';
include_once '../../Controller/Controller_Proveedor.php';
include_once '../../Controller/Controller_Gasto.php';
include_once '../../Controller/Controller_Factura_Compra.php';

// Instantiate DB & connect
$database = new conexion();
$db = $database->connect();

$data = json_decode(file_get_contents("php://input"));

Delete_bodega();
//Delete_producto();
//Delete_proveedor();
//Delete_gastos();
function Delete_gastos()
{
    // Instiate blog post object
    $post = new Controller_Gasto($GLOBALS['db']);


    // GET ID
    $post->id_gastos = isset($_GET['id_gastos']) ? $_GET['id_gastos'] : die();

    if (!empty($post->buscar_id_gastos($post->id_gastos))) {
        echo json_encode(
            array('message' => 'no se encontro la gasto para eliminar')
        );
    } else {
        if ($post->delete_single_gasto()) {
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
function Delete_proveedor()
{

    // Instiate blog post object
    $post = new Controller_Proveedor($GLOBALS['db']);


    // GET ID
    $post->rut_proveedor = isset($_GET['rut_proveedor']) ? $_GET['rut_proveedor'] : die();

    if ($post->Validator_run($post->rut_proveedor) == true) {
        if (!empty($post->buscar_rut_proveedor($post->rut_proveedor))) {
            echo json_encode(
                array('message' => 'no se encontro proveedor con el rut: ' . $post->rut_proveedor)
            );
        } else {
            if ($post->delete_single_proveedor()) {
                echo json_encode(
                    array('message' => 'Post deleted')
                );
            } else {
                echo json_encode(
                    array('message' => 'Post not deleted')
                );
            }
        }
    } else {
        echo json_encode(
            array('message' => 'Error no se rut mal ingresado')
        );
    }

    
}

function Delete_producto()
{
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

function Delete_bodega()
{
    $validator=true;
    // Instiate blog post object
    $post = new controller_bodega($GLOBALS['db']);


    // GET ID
    $post->id_bodega = isset($_GET['id_bodega']) ? $_GET['id_bodega'] : die();



    if (!empty($post->buscar_id_bodega($post->id_bodega))) {
        echo json_encode(
            array('message' => 'no se encontro la bodega para eliminar')
        );
    } else {
        if (empty($post->buscar_referncias_tablas($post->id_bodega))) {
            $validator=false;
            echo json_encode(
                array('message' => 'no se puede eliminar esta bodega ya que esta relaciona a un gasto existente')
            );
        }

        if ($validator==true) {
            if ($post->delete_single()) {
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
}
