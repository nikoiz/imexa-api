<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Gasto.php';
include_once '../../Controller/controller_bodega.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validador = true;
    $buscar = new controller_bodega($GLOBALS['db']);
    $post = new Controller_Gasto($GLOBALS['db']);
    $post->descripcion_gastos = $GLOBALS['data']->descripcion_gastos;
    $post->valor_gastos = $GLOBALS['data']->valor_gastos;
    $post->estado = $GLOBALS['data']->estado;
    $post->fecha = $GLOBALS['data']->fecha;
    $post->id_bodega = $GLOBALS['data']->id_bodega;

    if (empty($post->fecha)) {
        $validador = false;
        echo json_encode(
            array('Error' => "ingrese una fecha")
        );
    }else {
        if ($post->validateDate($post->fecha)==false) {
            $validador = false;
            echo json_encode(
                array('Error' => "ingrese una fecha valida")
            );
        }
    }

    if ($post->Validador_descripcion_gastos($post->descripcion_gastos) == false) {
        echo json_encode(
            array('Error' => 'Falta la descripcion de gastos')
        );
        $validador = false;
    }
    if (!$post->Validador_valor_gastos($post->valor_gastos) == "") {
        echo json_encode(
            array('Error' => $post->Validador_valor_gastos($post->valor_gastos))
        );
        $validador = false;
    }
    if ($post->Validador_estado($post->estado) == false) {
        echo json_encode(
            array('Error' => 'Falta establecer el estado')
        );
        $validador = false;
    }
    if (!$post->Validador_bodega_id_bodega($post->id_bodega) == "") {
        echo json_encode(
            array('Error' => $post->Validador_bodega_id_bodega($post->id_bodega))
        );
        $validador = false;
    } else {
        if ($buscar->buscar_id_bodega($post->id_bodega) == true) {
            echo json_encode(
                array('Error' => 'No se encontro el id de la bodega')
            );
            $validador = false;
        }
    }
    if (!$post->Validador_valor_gastos($post->valor_gastos)=="") {
        echo json_encode(
            array('Error' =>$post->Validador_valor_gastos($post->valor_gastos) )
        );
        $validador=false;
    }
    if ($validador == true) {
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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {

        // Instiate blog post object
        $post = new Controller_Gasto($GLOBALS['db']);
        $p = new controller_bodega($GLOBALS['db']);

        // GET ID
        //se puede cambiar por el id_bodega (decir a compañeero para ver quer le parece)
        $post->id_gastos = isset($_GET['id_gastos']) ? $_GET['id_gastos'] : die();


        if (!empty($post->buscar_id_gastos($post->id_gastos))) {
            echo json_encode(
                array('message' => 'No existe datos sobre la bodega N°' . $post->id_producto)
            );
        } else {
            if ($post->Read_single_gasto()) {
                $post_item = array(
                    'id_gastos' => $post->id_gastos,
                    'descripcion_gastos' => $post->descripcion_gastos,
                    'valor_gastos' => $post->valor_gastos,
                    'estado' => $post->estado,
                    'fecha' => $post->fecha,
                    'nombre_bodega' =>$post->nombre_bodega,
                    'id_bodega' => $post->id_bodega,
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
        $post =  new Controller_Gasto($GLOBALS['db']);
        $result = $post->Read_Gasto();
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_gastos' => $id_gastos,
                    'descripcion_gastos' => $descripcion_gastos,
                    'valor_gastos' => $valor_gastos,
                    'estado' => $estado,
                    'fecha' => $fecha,
                    'nombre_bodega' =>$nombre_bodega,
                    'id_bodega' => $id_bodega,
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

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $post = new Controller_Gasto($GLOBALS['db']);
    $buscar= new controller_bodega($GLOBALS['db']);
    // Get  raw posted data
    
    // GET ID
    $post->id_gastos = isset($_GET['id_gastos']) ? $_GET['id_gastos'] : die();
    $post->estado = $GLOBALS['data']->estado;

    
    $validador=true;


    if ($post->Validador_id_gastos($post->id_gastos)==false) {
        echo json_encode(
            array('Error' => 'Falta el id de gasto')
        );
        $validador=false;
    }
    if ($post->Validador_estado($post->estado)==false) {
        echo json_encode(
            array('Error' => 'Falta establecer el estado')
        );
        $validador=false;
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
