<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
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

// Instiate blog post object
update_bodega();
//Update_producto();
//Update_provedor();
//Update_gasto();
function Update_gasto()
{
    $post = new Controller_Gasto($GLOBALS['db']);
    $buscar= new controller_bodega($GLOBALS['db']);
    // Get  raw posted data
    
    $post->id_gastos = $GLOBALS['data']->id_gastos;
    $post->descripcion_gastos = $GLOBALS['data']->descripcion_gastos;
    $post->valor_gastos = $GLOBALS['data']->valor_gastos;
    $post->estado = $GLOBALS['data']->estado;
    $post->bodega_id_bodega = $GLOBALS['data']->bodega_id_bodega;
    
    $validador=true;
    
    if ($post->Validador_id_gastos($post->id_gastos)==false) {
        echo json_encode(
            array('Error' => 'Falta el id de gasto')
        );
        $validador=false;
    }
    if ($post->Validador_descripcion_gastos($post->descripcion_gastos)==false) {
        echo json_encode(
            array('Error' => 'Falta la descripcion de gastos')
        );
        $validador=false;
    }
    if (!$post->Validador_valor_gastos($post->valor_gastos)=="") {
        echo json_encode(
            array('Error' =>$post->Validador_valor_gastos($post->valor_gastos) )
        );
        $validador=false;
    }
    if ($post->Validador_estado($post->estado)==false) {
        echo json_encode(
            array('Error' => 'Falta establecer el estado')
        );
        $validador=false;
    }
    if (!$post->Validador_bodega_id_bodega($post->bodega_id_bodega)=="") {
        echo json_encode(
            array('Error' =>$post->Validador_bodega_id_bodega($post->bodega_id_bodega))
        );
        $validador=false;
    }else {
        if ($buscar->buscar_id_bodega($post->bodega_id_bodega)==true) {
            echo json_encode(
                array('Error' => 'No se encontro el id de la bodega')
            );
            $validador=false;
        }
        
    }
    if ($validador==true) {
        if ($post->update_gasto()) {
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
function Update_provedor()
{
    $post = new Controller_Proveedor($GLOBALS['db']);

    // Get  raw posted data
    
    $post->rut_proveedor = $GLOBALS['data']->rut_proveedor;
    $post->nombre_proveedor = $GLOBALS['data']->nombre_proveedor;
    
    $validador=true;

    if ($post->Validator_run($post->rut_proveedor) == false) {
        $validador=false;
        echo json_encode(
            array('message' => 'Rut del Proveedor mal ingresado')
        );
    }
    if ($validador==true) {
        if ($post->update_proveedor()) {
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
function Update_producto()
{
    $post = new Controller_Producto($GLOBALS['db']);

    // Get  raw posted data
    
    $post->id_producto = $GLOBALS['data']->id_producto;
    $post->valor_producto = $GLOBALS['data']->valor_producto;
    $post->nombre_producto = $GLOBALS['data']->nombre_producto;
    
    $validador=true;
    
    if ($post->buscar_nombre_producto($post->nombre_producto)==false) {
        $validador=false;
        echo json_encode(
            array('message' => 'Existe mombre del Producto')
        );
    }  
    if ($validador==true) {
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

function update_bodega()
{
    $post = new controller_bodega($GLOBALS['db']);

    // Get  raw posted data
    
    $post->id_bodega = $GLOBALS['data']->id_bodega;
    $post->numero_bodega = $GLOBALS['data']->numero_bodega;
    $post->nombre_bodega = $GLOBALS['data']->nombre_bodega;
    
    $validador=true;
    
    if ($post->buscar_nombre($post->nombre_bodega)==false) {
        $validador=false;
        echo json_encode(
            array('message' => 'Existe mombre de la bodega')
        );
    }  

    if ($post->buscar_numero_comprobar_datos($post->numero_bodega,$post->nombre_bodega)==false) {
        $validador=false;
        echo json_encode(
            array('message' => 'Existe numero de la bodega')
        );
    }
    if ($validador==true) {
        if ($post->update()) {
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
