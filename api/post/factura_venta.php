<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Factura_Venta.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $validador=true;
    $post = new Controller_Factura_Venta($GLOBALS['db']);
    $post->folio_factura_venta = $GLOBALS['data']->folio_factura_venta;
    $post->nombre_contribuyente = $GLOBALS['data']->nombre_contribuyente;
    $post->rut_contribuyente = $GLOBALS['data']->rut_contribuyente;
    $post->total_factura = $GLOBALS['data']->total_factura;
    
    if ($post->Validador_nombre_contribuyente($post->nombre_contribuyente)!="") {
        $validador=false;
        echo json_encode(
            array('Error' =>$post->Validador_nombre_contribuyente($post->nombre_contribuyente))
        );
    }
    if ($post->Validador_folio_factura_venta($post->folio_factura_venta)!="") {
        $validador=false;
        echo json_encode(
            array('Error' =>$post->Validador_folio_factura_venta($post->folio_factura_venta))
        );
    }
    if ($post->Validator_run($post->rut_contribuyente)==false) {
        $validador=false;
        echo json_encode(
            array('Error' =>"Error rut mal ingresado")
        );
    }
    if ($validador==true) {
        if ($post->create_Factura_Venta()) {
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
if (isset($_GET['folio_factura_venta'])){
    $post = new Controller_Factura_Venta($GLOBALS['db']);

    // GET ID
    $post->folio_factura_venta = isset($_GET['folio_factura_venta']) ? $_GET['folio_factura_venta'] : die();

    if (!empty($post->buscar_folio_factura_venta($post->folio_factura_venta))) {
        echo json_encode(
            array('message' => 'No existe datos sobre el folio' . $post->folio_factura_venta)
        );
    }else{
        if ($post->Read_single_Factura_Venta()) {
            $post_item = array(
                'folio_factura_venta' => $post->folio_factura_venta ,
                'nombre_contribuyente ' => $post->nombre_contribuyente,
                'rut_contribuyente' => $post->rut_contribuyente,
                'total_factura' => $post->total_factura                
            );
             //Make JSON

             print_r(json_encode($post_item));
        }else {
            echo json_encode(
                array('message' => 'No Posts Found')
            );
        }
    }
    
    }else {
        $post = new Controller_Factura_Venta($GLOBALS['db']);

        // Blog post query
        $result = $post->Read_Factura_Venta();
        // Get row count
        $num = $result->rowCount();
        // Check if any posts


        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'folio_factura_venta' => $folio_factura_venta ,
                    'nombre_contribuyente ' => $nombre_contribuyente,
                    'rut_contribuyente' => $rut_contribuyente,
                    'total_factura' => $total_factura                
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
    $validator=true;
    // Instiate blog post object
    $post = new Controller_Factura_Venta($GLOBALS['db']);


    // GET ID
    $post->folio_factura_venta = isset($_GET['folio_factura_venta']) ? $_GET['folio_factura_venta'] : die();



   if ($post->Validador_folio_factura_venta($post->folio_factura_venta)=="") {
    $validator=false;
    echo json_encode(

        array('message' => $post->Validador_folio_factura_venta($post->folio_factura_venta))
    );
   }
    if ($validator==true) {
        if ($post->delete_Factura_Venta()) {
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

if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $post = new Controller_Factura_Venta($GLOBALS['db']);
    //$post->folio_factura_venta = isset($_GET['folio_factura_venta']) ? $_GET['folio_factura_venta'] : die();
    $post->folio_factura_venta = $GLOBALS['data']->folio_factura_venta;
    $post->nombre_contribuyente = $GLOBALS['data']->nombre_contribuyente;
    $post->rut_contribuyente = $GLOBALS['data']->rut_contribuyente;
    $post->total_factura = $GLOBALS['data']->total_factura;

    $validador=true;

    if ($post->Validador_nombre_contribuyente($post->nombre_contribuyente)!="") {
        $validador=false;
        echo json_encode(
            array('Error' =>$post->Validador_nombre_contribuyente($post->nombre_contribuyente))
        );
    }
    if ($post->Validador_folio_factura_venta($post->folio_factura_venta)!="") {
        $validador=false;
        echo json_encode(
            array('Error' =>$post->Validador_folio_factura_venta($post->folio_factura_venta))
        );
    }
    if ($post->Validator_run($post->rut_contribuyente)==false) {
        $validador=false;
        echo json_encode(
            array('Error' =>"Error rut mal ingresado")
        );
    }

    if ($validador==true) {
        if ($post->update_Factura_Venta()) {
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
header("HTTP/1.1 400 Bad Request");
?>