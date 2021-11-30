<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Dispositivo_peso.php';
include_once '../../Controller/Controller_detalle_inventario.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $validador = true;
    $post = new Controller_Dispositivo_peso($GLOBALS['db']);
    $di = new Controller_detalle_inventario($GLOBALS['db']);

    $post->peso_dispositivo = $GLOBALS['data']->peso_dispositivo;
    $post->unidad_de_medida = $GLOBALS['data']->unidad_de_medida;
    $post->topico = $GLOBALS['data']->topico;
    $post->id_detalle_inventario = $GLOBALS['data']->id_detalle_inventario;

    if (!$post->Validador_peso_dispositivo($post->peso_dispositivo) == "") {
        echo json_encode(
            array('Error' => $post->Validador_peso_dispositivo($post->valor_gastos))
        );
        $validador = false;
    }
    if (!$post->Validador_peso_dispositivo($post->peso_dispositivo) == "") {
        echo json_encode(
            array('Error' => $post->Validador_peso_dispositivo($post->valor_gastos))
        );
        $validador = false;
    }
    if (!$post->Validador_unidad_de_medida($post->unidad_de_medida) == "") {
        echo json_encode(
            array('Error' => $post->Validador_peso_dispositivo($post->unidad_de_medida))
        );
        $validador = false;
    }
    if (!$post->Validador_unidad_de_medida($post->topico) == "") {
        echo json_encode(
            array('Error' => $post->Validador_peso_dispositivo($post->topico))
        );
        $validador = false;
    }
    if (!$post->Validador_id_detalle_inventario($post->id_detalle_inventario) == "") {
        echo json_encode(
            array('Error' => $post->Validador_id_detalle_inventario($post->id_detalle_inventario))
        );
        $validador = false;
    }else {
        //buscar id_detalle_inventario
        if ($id->buscar_id_detalle_inventario($post->id_detalle_inventario)==true) {
            echo json_encode(
                array('Error' => 'No se encontro el codigo del invenatrio')
            );
            $validador = false;
        }
    } 
    if ($validador == true) {
        if ($post->create_dispositivo()) {
            echo json_encode(
                array('message' => 'Se creo el dispositivo')
            );
        } else {
            echo json_encode(
                array('message' => 'No se creo la dispositivo')
            );
        }
    }

    
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_dispositivo'])) {

        // Instiate blog post object
        $post = new Controller_Dispositivo_peso($GLOBALS['db']);



        // GET ID
        //se puede cambiar por el id_bodega (decir a compañeero para ver quer le parece)
        $post->id_dispositivo = isset($_GET['id_dispositivo']) ? $_GET['id_dispositivo'] : die();


        if (!empty($post->buscar_id_dispositivo($post->id_dispositivo))) {
            echo json_encode(
                array('message' => 'No existe datos sobre el dispositivo N° ' . $post->id_dispositivo)
            );
        } else {
            if ($post->Read_single_dipositivo()) {
                $post_item = array(
                    'id_dispositivo' => $post->id_dispositivo,
                    'peso_dispositivo' => $post->peso_dispositivo,
                    'unidad_de_medida' => $post->unidad_de_medida,
                    'topico' => $post->topico,
                    'id_detalle_inventario' =>$post->id_detalle_inventario
                );
                //Make JSON

                print_r(json_encode($post_item));
            } else {
                echo json_encode(
                    array('message' => 'No se encontro los gastos del codigo: '.$post->id_dispositivo )
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
                    'id_dispositivo' => $id_dispositivo,
                    'peso_dispositivo' => $peso_dispositivo,
                    'unidad_de_medida' => $post->unidad_de_medida,
                    'topico' => $topico,
                    'id_detalle_inventario' =>$id_detalle_inventario
                );

                array_push($posts_arr['data'], $post_item);
            }

            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(

                array('message' => 'No existe gastos')
            );
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    $post = new Controller_Dispositivo_peso($GLOBALS['db']);
    $post->id_dispositivo = isset($_GET['id_dispositivo']) ? $_GET['id_dispositivo'] : die();
    if (!empty($post->buscar_id_dispositivo($post->id_dispositivo))) {
        echo json_encode(
            array('message' => 'no se encontro el dispositivo a eliminar')
        );
    }else {
        if ($post->delete_dispositivo()) {
            echo json_encode(
                array('message' => 'Se elimino el dispositivo')
            );
        } else {
            echo json_encode(
                array('message' => 'No se elimino el dispositivo')
            );
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $post = new Controller_Dispositivo_peso($GLOBALS['db']);
    
    $post->id_dispositivo = $GLOBALS['data']->id_dispositivo;
    $post->peso_dispositivo = $GLOBALS['data']->peso_dispositivo;
    $post->unidad_de_medida = $GLOBALS['data']->unidad_de_medida;
    $post->topico = $GLOBALS['data']->topico;
    $post->id_detalle_inventario = $GLOBALS['data']->id_detalle_inventario;

    if ($post->id_dispositivo == null) {
        $validador = false;
        echo json_encode(
            array('Error' => "Falta el codigo de la pesa")
        );
    }

    if (!$post->Validador_peso_dispositivo($post->peso_dispositivo) == "") {
        echo json_encode(
            array('Error' => $post->Validador_peso_dispositivo($post->valor_gastos))
        );
        $validador = false;
    }
    if (!$post->Validador_peso_dispositivo($post->peso_dispositivo) == "") {
        echo json_encode(
            array('Error' => $post->Validador_peso_dispositivo($post->valor_gastos))
        );
        $validador = false;
    }
    if (!$post->Validador_unidad_de_medida($post->unidad_de_medida) == "") {
        echo json_encode(
            array('Error' => $post->Validador_peso_dispositivo($post->unidad_de_medida))
        );
        $validador = false;
    }
    if (!$post->Validador_unidad_de_medida($post->topico) == "") {
        echo json_encode(
            array('Error' => $post->Validador_peso_dispositivo($post->topico))
        );
        $validador = false;
    }
    if (!$post->Validador_id_detalle_inventario($post->id_detalle_inventario) == "") {
        echo json_encode(
            array('Error' => $post->Validador_id_detalle_inventario($post->id_detalle_inventario))
        );
        $validador = false;
    }else {
        //buscar id_detalle_inventario
        if ($id->buscar_id_detalle_inventario($post->id_detalle_inventario)==true) {
            echo json_encode(
                array('Error' => 'No se encontro el codigo del invenatrio')
            );
            $validador = false;
        }
    } 

    if ($validador==true) {
        if ($post->update_dispositivo()) {
            echo json_encode(
                array('message' => 'Se actualizo el dispositivo')
            );
        } else {
            echo json_encode(
                array('message' => 'No se actualizo dispositivo')
            );
        }
    } 
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
?>