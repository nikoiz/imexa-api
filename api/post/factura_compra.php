<?php
//falta cosas
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Factura_Compra.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
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
   } // Instiate blog post object
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

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    if (isset($_GET['folio_factura'])){
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
    }else{
        $post =  new Controller_Factura_Compra($GLOBALS['db']);
        $result=$post->Read_Factura_Compra();
        $num = $result->rowCount();
    
        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();
        
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'folio_factura' => $folio_factura,
                    'total_factura' => $total_factura                 
                );
                array_push($posts_arr['data'], $post_item);
            }
        
            echo json_encode($posts_arr);
        
        }else {
            // No posts
            echo json_encode(
        
                array('message' => 'No Posts Found')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $post = new Controller_Factura_Compra($GLOBALS['db']);
    // GET ID
    $post->folio_factura = isset($_GET['folio_factura']) ? $_GET['folio_factura'] : die();
    if (!empty($post->buscar_folio_factura($post->folio_factura))) {
        echo json_encode(
            array('message' => 'no se encontro la gasto para eliminar')
        );
    } else {
        if ($post->delete_Factura_Compra()) {
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
    $post = new Controller_Factura_Compra($GLOBALS['db']);
    // Get  raw posted data

    // GET ID
    //$post->folio_factura = isset($_GET['folio_factura']) ? $_GET['folio_factura'] : die();
    $post->folio_factura = $GLOBALS['data']->folio_factura;
    $post->total_factura = $GLOBALS['data']->total_factura;
    /*
    problema al poner un ej: un 6 que se mande pero que valide que los otros nuemors
    */
    //

    if (!$post->Validador_total_factura($post->total_factura) == "") {
        $validador = false; 
        echo json_encode(
            array('Error' => $post->Validador_total_factura($post->total_factura))
        );
    }
    
    if ($validador==true) {
        if ($post->update_Factura_Compra()) {
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

?>