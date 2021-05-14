<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');


include_once '../../config/conexion.php';
include_once '../../Controller/controller_bodega.php';
include_once '../../Controller/Controller_Producto.php';
include_once '../../Controller/Controller_Proveedor.php';
include_once '../../Controller/Controller_Gasto.php';
include_once '../../Controller/Controller_Factura_Compra.php';
include_once '../../Controller/Controller_Trabajador.php';
// Instantiate DB & connect
$database = new conexion();
$db = $database->connect();


Leer_bodega_single();
//Leer_producto_single();
//Leer_proveedor_single();
//Leer_gastos_single();
//Inicio_session();
function Leer_factura_compra_single()
{

    // Instiate blog post object
    $post = new Controller_Factura_Compra($GLOBALS['db']);

    // GET ID
    $post->id_gfolio_facturaastos = isset($_GET['folio_factura']) ? $_GET['folio_factura'] : die();


    if (!empty($post->buscar_folio_factura($post->folio_factura))) {
        echo json_encode(
            array('message' => 'No existe datos sobre la factura N°' . $post->folio_factura)
        );
    } else {
        if ($post->Read_single_Factura_Compra()) {
            $post_item = array(
                'id_gastos'=>$post->id_gastos,
                'descripcion_gastos' => $post->descripcion_gastos,
                'valor_gastos' => $post->valor_gastos,
                'estado' => $post->estado,
                'bodega_id_bodega' => $post->bodega_id_bodega,                  
            );
            //Make JSON

            print_r(json_encode($post_item));
        } else {
            echo json_encode(
                array('message' => 'No Posts Found')
            );
        }
    }
}
function Inicio_session()
{

    // Instiate blog post object
    $post = new Controller_Trabajador($GLOBALS['db']);

    // GET ID
    $post->usuario = isset($_GET['usuario']) ? $_GET['usuario'] : die();
    $post->contraseña = isset($_GET['contraseña']) ? $_GET['contraseña'] : die();



    if ($post->Inicio_sesion($post->usuario,$post->contraseña)==false) {
        echo json_encode(
            array('message' => 'usuario no encontrado')
        );
        //header("Location: ../index.php?error=$error");
    }else {
        echo json_encode(
            array('message' => 'usuario encontrado')
        );
         session_start();
         $_SESSION["usuario"] = $post->usuario;
         $_SESSION["rut_trabajador"] = $post->contraseña;
         //establecer por medio de api o dejar que el mismo php de backend redireccione
         //header("Location: ../view/Menu_profesora.php");
    }
}
function Leer_gastos_single()
{

    // Instiate blog post object
    $post = new Controller_Gasto($GLOBALS['db']);

    // GET ID
    $post->id_gastos = isset($_GET['id_gastos']) ? $_GET['id_gastos'] : die();


    if (!empty($post->buscar_id_gastos($post->id_gastos))) {
        echo json_encode(
            array('message' => 'No existe datos sobre la bodega N°' . $post->id_producto)
        );
    } else {
        if ($post->Read_single_gasto()) {
            $post_item = array(
                'id_gastos'=>$post->id_gastos,
                'descripcion_gastos' => $post->descripcion_gastos,
                'valor_gastos' => $post->valor_gastos,
                'estado' => $post->estado,
                'bodega_id_bodega' => $post->bodega_id_bodega,                  
            );
            //Make JSON

            print_r(json_encode($post_item));
        } else {
            echo json_encode(
                array('message' => 'No Posts Found')
            );
        }
    }
}
function Leer_proveedor_single()
{
    // Instiate blog post object
    $post = new Controller_Proveedor($GLOBALS['db']);

    // GET ID
    $post->rut_proveedor = isset($_GET['rut_proveedor']) ? $_GET['rut_proveedor'] : die();

    if ($post->Validator_run($post->rut_proveedor) == true) {
        if (!empty($post->buscar_rut_proveedor($post->rut_proveedor))) {
            echo json_encode(
                array('message' => 'No existe datos del provedor')
            );
        } else {
            if ($post->Read_single_proveedor()) {
                $post_item = array(
                    'rut_proveedor' => $post->rut_proveedor,
                    'nombre_proveedor' => $post->nombre_proveedor
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
        echo json_encode(
            array('message' => 'Error no se rut mal ingresado')
        );
    }

    
}

function Leer_producto_single()
{

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
}

function Leer_bodega_single()
{
    // Instiate blog post object
    $post = new controller_bodega($GLOBALS['db']);

    // GET ID
    $post->numero_bodega = isset($_GET['numero_bodega']) ? $_GET['numero_bodega'] : die();


    if (!empty($post->buscar_numero($post->numero_bodega))) {
        echo json_encode(
            array('message' => 'No existe datos sobre la bodega N°' . $post->numero_bodega)
        );
    } else {
        if ($post->read_single()) {
            $post_item = array(
                'id_bodega' => $post->id_bodega,
                'numero_bodega' => $post->numero_bodega,
                'nombre_bodega' => $post->nombre_bodega
            );

            //Make JSON

            print_r(json_encode($post_item));
        } else {
            echo json_encode(
                array('message' => 'No Posts Found')
            );
        }
    }
}

