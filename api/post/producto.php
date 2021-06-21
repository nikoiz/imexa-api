<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
//se usuran 3 innvantario - producto - inventario/bodega
include_once '../../Controller/Controller_Producto.php';
include_once '../../Controller/Controller_bodega_has_producto.php';
include_once '../../Controller/controller_bodega.php';
include_once '../../Controller/Controller_Inventario.php';
include_once '../../Controller/Controller_detalle_inventario.php';

$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validador=true;
    $post = new Controller_Producto($GLOBALS['db']);
    $po = new Controller_bodega_has_producto($GLOBALS['db']);
    $p = new controller_bodega($GLOBALS['db']);
    $di = new Controller_detalle_inventario($GLOBALS['db']);
    $i = new Controller_Inventario($GLOBALS['db']);

    $post->nombre_producto = $GLOBALS['data']->nombre_producto;
    $post->valor_producto = $GLOBALS['data']->valor_producto;
    $cantidad_total = $GLOBALS['data']->cantidad_total;
    $p->id_bodega = $GLOBALS['data']->id_bodega;
    $id_inventario = 1;
    $fecha = date('Y-m-d');
    //$fecha = date('d-m-Y');
    //$validador=true;
    
    if ($post->validador_nombre($post->nombre_producto)!=null) {
        $validador=false;
        echo json_encode(
            array('message' => $post->validador_nombre($post->nombre_producto))
        );
    }
    if ($post->Validador_valor_producto($post->valor_producto)!=null) {
        $validador=false;
        echo json_encode(
            array('message' => $post->Validador_valor_producto($post->valor_producto))
        );
    }
    if ($po->Validador_cantidad_total($cantidad_total)!=null) {
        $validador=false;
        echo json_encode(
            array('message' => $po->Validador_cantidad_total($cantidad_total))
        );
    }
    if (empty($p->id_bodega)) {
        $validador=false;
        echo json_encode(
            array('message' => "ingrese una bodega")
        );
    }else {
        if ($p->buscar_id_bodega($p->id_bodega)!=false) {
            $validador=false;
            echo json_encode(
                array('message' => "No existe la bodega")
            );
        }
    }

    
    if ($validador==true) {
        if ($post->create_producto()) {

           
            $id_producto=$post->obtener_el_ultimo_id();//manda
            if ($po->create_bodega_has_producto($p->id_bodega,$id_producto,$cantidad_total)==false) {//funciona
                echo json_encode(
                    array('message' => 'Error en ingreso de datos teniendo en cuenta el codigo del producto y el codigo de la bodega')
                );
            }else {
                
                //buscar el 

                if ($di->create_detalle_inventario($post->nombre_producto,$cantidad_total,$post->valor_producto,1,$p->id_bodega,$id_producto,$fecha)==false) {//listo
                    echo json_encode(
                        array('message' => 'Error en ingreso para el inventario')
                    );
                }else {


                    $valor_total_inv =$di->valor_total(); //valor total de todo
                    //multiplicar al cantidad por el valor
                    //sacar el valor de ese producto
                    $valor_del_prod = $post->valor_producto * $cantidad_total;
                    $valor_total_inv +=  $valor_del_prod;
                    if ($i-> actualizar_valor($valor_total_inv,$id_inventario)==false) {//me falta
                        echo json_encode(
                            array('message' => 'Error al actualizar el valor del inventario')
                        );
                    }else {
                        echo json_encode(
                            array('message' => 'Post Created')
                        );
                    }
                }
            }
        } else {
            echo json_encode(
                array('message' => 'Post not created')
            );
        }
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
                array('message' => 'No existe datos el producto N°' . $post->id_producto)
            );
        } else {
            if ($post->read_single()) {
                $post_item = array(
                    'producto.id_producto' => $post->id_producto,
                    'valor_producto' => $post->valor_producto,
                    'nombre_producto' => $post->nombre_producto,
                    'cantidad_total' =>$post->cantidad_total
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
                    'valor_producto' => $valor_producto,
                    'cantidad_total' => $cantidad_total
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
    $po = new Controller_bodega_has_producto($GLOBALS['db']);

    // GET ID
    $post->id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : die();

    if (!empty($post->buscar_id_producto($post->id_producto))) {
        echo json_encode(
            array('message' => 'no se encontro la bodega para eliminar')
        );
    } else {
        if ($po->delete_bodega_has_producto($post->id_producto)) {
            if ($post->delete_single_producto()) {
                echo json_encode(
                    array('message' => 'Post deleted')
                );
            }else {
                echo json_encode(
                    array('message' => 'Post not deleted')
                );
            }
            
        } else {
            echo json_encode(
                array('message' => 'Post not deleted')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $post = new Controller_Producto($GLOBALS['db']);
    $po = new Controller_bodega_has_producto($GLOBALS['db']);
    $p = new controller_bodega($GLOBALS['db']);
    // Get  raw posted data

    // GET ID
    $post->id_producto = isset($_GET['id_producto']) ? $_GET['id_producto'] : die();
    $post->valor_producto = $GLOBALS['data']->valor_producto;
    $post->nombre_producto = $GLOBALS['data']->nombre_producto;
    $cantidad_total = $GLOBALS['data']->cantidad_total;
    $p->id_bodega = $GLOBALS['data']->id_bodega;
    $validador = true;
    
    if ($post->buscar_id_producto($post->id_producto)==true) {
        $validador = false;
        echo json_encode(
            array('message' => 'No existe la bodega')
        );
    }
    if ($post->buscar_nombre_producto($post->nombre_producto) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Existe mombre del Producto')
        );
    }
    if ($post->Validador_valor_producto($post->valor_producto)!=null) {
        $validador=false;
        echo json_encode(
            array('message' => $post->Validador_valor_producto($post->valor_producto))
        );
    }
    if ($po->Validador_cantidad_total($cantidad_total)!=null) {
        $validador=false;
        echo json_encode(
            array('message' => $po->Validador_cantidad_total($cantidad_total))
        );
    }
    if ($validador == true) {
        if ($post->update_producto()) {
            if ($po->update_bodega_has_producto($p->id_bodega,$post->id_producto,$cantidad_total)) {
                echo json_encode(
                    array('message' => 'Post Update')
                );
            }else {
                echo json_encode(
                    array('message' => 'Post not Update')
                );
            }
            
        } else {
            echo json_encode(
                array('message' => 'Post not Update')
            );
        }
    }
}
