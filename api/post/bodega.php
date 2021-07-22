<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, PUT, DELETE, GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


include_once '../../config/conexion.php';
include_once '../../Controller/controller_bodega.php';
include_once '../../Controller/Controller_Trabajador.php';
include_once '../../Controller/Controller_trabajador_has_bodega.php';
include_once '../../Controller/Controller_detalle_inventario.php';
include_once '../../Controller/Controller_Inventario.php';
include_once '../../Controller/Controller_Gasto.php';

$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = new controller_bodega($GLOBALS['db']);
    $pos = new Controller_Trabajador($GLOBALS['db']);
    $po = new Controller_trabajador_has_bodega($GLOBALS['db']);
    $g = new Controller_Gasto($GLOBALS['db']);

    $post->numero_bodega = $GLOBALS['data']->numero_bodega;
    $post->nombre_bodega = $GLOBALS['data']->nombre_bodega;

    $pos->id_tipo_trabajador = 1;
    $validador = true;
    $g->fecha = $fecha = date('Y-m-d');
    $g->estado = 1;

    if ($post->buscar_numero($post->numero_bodega) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Existe numero de bodega')
        );
    }
    if ($post->nombre_bodega == null) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese el nombre de la bodega')
        );
    }
    if ($validador == true) {
        if ($pos->Validar_tipo_trabajador($pos->id_tipo_trabajador) != null) {
            if ($post->create()) {

                if ($post->buscar_el_ultimo_id() != null) {

                    //accion a trabajador as bodega (rut_trabajador,id_bodega)
                    $rut_trabajador = $pos->Validar_tipo_trabajador($pos->id_tipo_trabajador);
                    $id_bodega = $post->buscar_el_ultimo_id();
                    if ($po->create_trabajador_has_bodega($rut_trabajador, $id_bodega) == false) {
                        echo json_encode(
                            array('message' => 'Error en ingreso de datos teniendo en cuenta el rut del trabajador y el codigo de la bodega')
                        );
                    } else {
                        echo json_encode(
                            array('message' => 'Post Created')
                        );
                    }
                } else {
                    echo json_encode(
                        array('message' => 'Post not created')
                    );
                }
            } else {
                echo json_encode(
                    array('Error' => 'No existe bodega')
                );
            }
        } else {
            echo json_encode(
                array('Error' => 'No existe el usuario administrador')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['id_bodega'])) {
        // Instiate blog post object
        $post = new controller_bodega($GLOBALS['db']);
        $g = new Controller_Gasto($GLOBALS['db']);

        // GET ID
        $post->id_bodega = isset($_GET['id_bodega']) ? $_GET['id_bodega'] : die();


        if (!empty($post->buscar_id_bodega($post->id_bodega))) {
            echo json_encode(
                array('message' => 'No existe datos sobre la bodega N°' . $post->id_bodega)
            );
        } else {
            /*
            //obtenr los gastos de esa bodega y actualizar datos de estos a gastos
            $g->valor_gastos=$g->Obtener_total_gasto_only($post->id_bodega);

            if ($g->update_gasto_por_bodega($post->id_bodega,$g->valor_gastos)== false) {
                echo json_encode(
                    array('message' => 'no se pudo actualizar los gastos')
                );
            }else {
               
            }
            */
            $g->valor_gastos = $g->Obtener_total_gasto_only($post->id_bodega);
            //obtner el gasto de esa bodega 
            $valor_del_inventario = 'total_del_inventario :' . $g->valor_gastos;
            if ($post->read_single()) {
                $post_item = array(
                    'id_bodega' => $post->id_bodega,
                    'numero_bodega' => $post->numero_bodega,
                    'nombre_bodega' => $post->nombre_bodega

                );

                //Make JSON

                print_r(json_encode($post_item));
                print_r(json_encode($valor_del_inventario));
            } else {
                echo json_encode(
                    array('message' => 'No Posts Found')
                );
            }
        }
    } else {
        $post =  new controller_bodega($GLOBALS['db']);

        // Blog post query
        $result = $post->read();
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
                    'id_bodega' => $id_bodega,
                    'numero_bodega' => $numero_bodega,
                    'nombre_bodega' => $nombre_bodega
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

    $validator = true;
    // Instiate blog post object
    $post = new controller_bodega($GLOBALS['db']);
    $po = new Controller_trabajador_has_bodega($GLOBALS['db']);
    $i = new Controller_Inventario($GLOBALS['db']);
    $di = new Controller_detalle_inventario($GLOBALS['db']);
    $g = new Controller_Gasto($GLOBALS['db']);


    // GET ID
    $post->id_bodega = isset($_GET['id_bodega']) ? $_GET['id_bodega'] : die();



    if (!empty($post->buscar_id_bodega($post->id_bodega))) {
        $validator = false;
        echo json_encode(
            array('message' => 'no se encontro la bodega para eliminar')
        );
    }

    if ($post->buscar_referncias_tablas($post->id_bodega) == false) {
        $validator = false;
        echo json_encode(
            array('message' => 'no se puede eliminar esta bodega ya que esta relaciona a un gasto existente')
        );
    }
    if ($di->Comprobar_existencia_productos($post->id_bodega) != false) { //validar la exitencia de la bodega en cuanto a producto
        $validator = false;
        echo json_encode(
            array('message' => 'no se puede eliminar esta bodega ya que tiene producto')
        );
    } 
    //eliminar los productos de detalle inv , gastos y actulizar el total del inv.

    if ($validator == true) {
        $valor_di = $di->buscardor_valor_producto_por_bodega($post->id_bodega);
        //obtenr el valor actual del inventario
        if ($i->Obtner_valor_inventario()==null) {
            echo json_encode(
                array('message' => 'Post not deleted')
            );
        }else {
            $vi=$i->Obtner_valor_inventario();
        }
        $vi = $vi -$valor_di;
        $i -> actualizar_valor($vi,1);

        if ($g ->delete_single_gasto_por_bodega($post->id_bodega)==false) {
            echo json_encode(
                array('message' => 'No se elimino el gasto de la bodega')
            );
        }


        if ($po->delete_trabajador_has_bodega($post->id_bodega)) {
            if ($post->delete_single()) {
                
                echo json_encode(
                    array('message' => 'Post deleted')
                );
            } else {
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
    $post = new controller_bodega($GLOBALS['db']);
    $po = new Controller_trabajador_has_bodega($GLOBALS['db']);

    $post->id_bodega = $GLOBALS['data']->id_bodega;
    $post->numero_bodega = $GLOBALS['data']->numero_bodega;
    $post->nombre_bodega = $GLOBALS['data']->nombre_bodega;

    $validador = true;

    if ($post->buscar_numero_comprobar_datos($post->numero_bodega, $post->nombre_bodega) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Existe numero de la bodega')
        );
    }
    if ($post->nombre_bodega == null) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese el nombre de la bodega')
        );
    }

    if ($validador == true) {
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
