<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


include_once './config/conexion.php';
include_once './Controller/controller_bodega.php';
include_once './Controller/Controller_Producto.php';
include_once './Controller/Controller_Proveedor.php';
include_once './Controller/Controller_Gasto.php';
include_once './Controller/Controller_Factura_Compra.php';

// Instantiate DB & connect
$database = new conexion();
$db = $database->connect();
error_reporting(0);



// Get  raw posted data
$data = json_decode(file_get_contents("php://input"));




//Crear_Bodega();
//Crear_Producto();
//Crear_Proveedor();
//Crear_Gasto();
//Crear_Factura_Compra();
function Crear_Bodega(){
    
    // Instiate blog post object
    $post = new controller_bodega($GLOBALS['db']);
    
    $post->numero_bodega = $GLOBALS['data']->numero_bodega;
    $post->nombre_bodega = $GLOBALS['data']->nombre_bodega;

    $validador=true;

    
    if ($post->buscar_nombre($post->nombre_bodega)==false) {
        $validador=false;
        echo json_encode(
            array('message' => 'Existe mombre de la bodega')
        );
    }
    if ($post->buscar_numero($post->numero_bodega)==false) {
        $validador=false;
        echo json_encode(
            array('message' => 'Existe numero de bodega')
        );
    }

    /*

    if ($validador==true) {
        if ($post->create()) {
            echo json_encode(
                array('message' => 'Post Created')
            );
        } else {
            echo json_encode(
                array('message' => 'Post not created')
            );
        }
    }
    
    */
    
}
function Crear_Producto(){
    $post= new Controller_Producto($GLOBALS['db']);
    $post->nombre_producto= $GLOBALS['data']->nombre_producto;
    $post->valor_producto= $GLOBALS['data']->valor_producto;

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
function Crear_Proveedor(){
    $post= new Controller_Proveedor($GLOBALS['db']);
    $post->nombre_proveedor= $GLOBALS['data']->nombre_proveedor;
    $post->rut_proveedor= $GLOBALS['data']->rut_proveedor;


    if ($post->Validator_run($post->rut_proveedor)==true) {
        if ($post->create_producto()) {
            echo json_encode(
                array('message' => 'Post Created')
            );
        } else {
            echo json_encode(
                array('message' => 'Post not created')
            );
        }
    }else {
        echo json_encode(
            array('message' => 'Error no se rut mal ingresado')
        );
    }
}
function Crear_Gasto()
{
    $validador=true;
    $buscar= new controller_bodega($GLOBALS['db']);
    $post= new Controller_Gasto($GLOBALS['db']);
    $post->descripcion_gastos= $GLOBALS['data']->descripcion_gastos;
    $post->valor_gastos= $GLOBALS['data']->valor_gastos;
    $post->estado= $GLOBALS['data']->estado;
    $post->bodega_id_bodega= $GLOBALS['data']->bodega_id_bodega;

    


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
        if ($post->create_gasto()) {
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
function Crear_Factura_Compra()
{
   // Instiate blog post object
   $post = new Controller_Factura_Compra($GLOBALS['db']);
   $post->folio_factura = $GLOBALS['data']->folio_factura;
   $post->total_factura = $GLOBALS['data']->total_factura;

   $validador=true;

   if ($post->buscar_folio_factura($post->folio_factura)==false) {
       $validador=false;
       echo json_encode(
           array('message' => 'Existe numero de la factura compra')
       );
   }
   if (!$post->Validador_total_factura($post->total_factura)=="") {
       $validador=false;
       echo json_encode(
           array('Error' =>$post->Validador_total_factura($post->total_factura))
       );
   }
   

   if ($validador==true) {
       if ($post->create_Factura_Compra()) {
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

/*
$folio_factura;
$total_factura;


*/
?>