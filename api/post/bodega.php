<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, PUT, DELETE, GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


include_once '../../config/conexion.php';
include_once '../../Controller/controller_bodega.php';
include_once '../../Controller/Controller_Trabajador.php';
include_once '../../Controller/Controller_trabajador_has_bodega.php';

$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = new controller_bodega($GLOBALS['db']);
    $pos = new Controller_Trabajador($GLOBALS['db']);
    $po = new Controller_trabajador_has_bodega($GLOBALS['db']);

    $post->numero_bodega = $GLOBALS['data']->numero_bodega;
    $post->nombre_bodega = $GLOBALS['data']->nombre_bodega;
    $pos->id_tipo_trabajador = 1;
    $validador = true;

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
                echo json_encode(
                    array('message' => 'Post Created')
                );
            if ($post->buscar_el_ultimo_id() != null) {
                
                    //accion a trabajador as bodega (rut_trabajador,id_bodega)
                    $rut_trabajador = $pos->Validar_tipo_trabajador($pos->id_tipo_trabajador);
                    $id_bodega=$post->buscar_el_ultimo_id();
                    echo json_encode(
                        array('message' => $rut_trabajador."asd".$id_bodega)
                    );
                    if ($po->create_trabajador_has_bodega($rut_trabajador,$id_bodega)==false) {
                        echo json_encode(
                            array('message' => 'Error en ingreso de datos teniendo en cuenta el rut del trabajador y el codigo de la bodega')
                        );
                    }
                } else {
                    echo json_encode(
                        array('message' => 'Post not created')
                    );
                }
            }else {
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

        // GET ID
        $post->id_bodega = isset($_GET['id_bodega']) ? $_GET['id_bodega'] : die();


        if (!empty($post->buscar_id_bodega($post->id_bodega))) {
            echo json_encode(
                array('message' => 'No existe datos sobre la bodega N°' . $post->id_bodega)
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
    
    // GET ID
    $post->id_bodega = isset($_GET['id_bodega']) ? $_GET['id_bodega'] : die();



    if (!empty($post->buscar_id_bodega($post->id_bodega))) {
        echo json_encode(
            array('message' => 'no se encontro la bodega para eliminar')
        );
    } else {
        if (empty($post->buscar_referncias_tablas($post->id_bodega))) {
            $validator = false;
            echo json_encode(
                array('message' => 'no se puede eliminar esta bodega ya que esta relaciona a un gasto existente')
            );
        }

        if ($validator == true) {
            if ($post->delete_single()) {
                if ($po->delete_trabajador_has_bodega($post->id_bodega)==true) {
                    echo json_encode(
                        array('message' => 'Post deleted')
                    );
                }else {
                    echo json_encode(
                        array('messag' => 'Post not deleted')
                    );
                }
               
                
            } else {
                echo json_encode(
                    array('message' => 'Post not deleted')
                );
            }
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
