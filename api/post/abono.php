<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Abono.php';
include_once '../../Controller/Controller_Factura_Venta.php';
include_once '../../Controller/Controller_Cliente.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validador = true;
    $post = new Controller_Abono($GLOBALS['db']);
    $fv = new Controller_Factura_Venta($GLOBALS['db']);


    $post->valor_abono = $GLOBALS['data']->valor_abono;
    $post->fecha_abono = $fecha = date('Y-m-d'); //se establece como directa 
    $post->id_venta = $GLOBALS['data']->id_venta;
    $fv->id_venta=$post->id_venta;
    if ($post->Validador_de_valor_abono($post->valor_abono) == true) {
        $validador = false;
        echo json_encode(
            array('message' => $post->Validador_de_valor_abono($post->valor_abono))
        );
    }
    if ($post->Validacion_parametro($post->id_venta) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una codigo de venta')
        );
    } else {
        if ($fv->buscar_id_venta($post->id_venta) == true) {
            $validador = false;
            echo json_encode(
                array('message' => 'Existe numero de la factura venta')
            );
        }
    }

    //obtner el total y restarlo 
    if ($fv->obtner_valor_venta()==null) {
        $validador = false;
        echo json_encode(
            array('message' => 'Existe numero de la factura venta')
        );
        
    } else {
        //500  - 1
        $valor_total= $fv->obtner_valor_venta();

        if ($valor_total >= $post->valor_abono) {
            echo json_encode(
                array('message' => "asd".$valor_total)
            );
            $valor_total = $valor_total - $post->valor_abono;
            $fv->valor_venta = $valor_total;
           
        }else {
            $validador = false;
            echo json_encode(
                array('message' => 'Limite exedido para el abono')
            );
        }
    }



if ($validador == true) {
        if ($post->create_abono()) {
            echo json_encode(
                array('message' => 'Se creo el abono')
            );
            //actualizar factura
            
            if ($fv->update_valor_factura_venta()) {
                echo json_encode(
                    array('message' => 'Se actualizo el valor de la factura venta: ' . $post->id_venta)
                );
            } else {
                echo json_encode(
                    array('message' => 'No se actualizo la factura venta')
                );
            }
        } else {
            echo json_encode(
                array('message' => 'No se creo el abono')
            );
        }
    }
 


    
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_venta'])) { //por un cliente y junto con ello mostar 
        $post = new Controller_Abono($GLOBALS['db']);
        $cl = new Controller_Cliente($GLOBALS['db']);
        $post->id_venta = isset($_GET['id_venta']) ? $_GET['id_venta'] : die();
        if (!empty($post->id_venta)) {
            if ($result = $post->Read_single_abono()) {

                $num = $result->rowCount();

                if ($num > 0) {
                    // Post array
                    $posts_arr = array();
                    $posts_arr['data'] = array();

                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $datos = array(
                            'id_abono' => $id_abono,
                            'valor_abono' => $valor_abono,
                            'fecha_abono' => $fecha_abono,
                            'id_venta' => $id_venta
                        );

                        array_push($posts_arr['data'], $datos);
                    }
                }

                //Make JSON
                //por medio del rut cliente hacer el tan el single y despues mostar el total sumado de los abonos
                $total_abono = $post->obtener_valor_total();


                $abono_completo = array(
                    "Abono" => array(
                        $posts_arr
                    ),
                    "Total_Abono" =>   $total_abono
                );
                print_r(json_encode($abono_completo));
            } else {
                echo json_encode(
                    array('message' => 'No se encontro  abonos del rut: ' . $cl->rut_cliente)
                );
            }
        } else {
            echo json_encode(
                array('message' => 'Ingrese rut del cliente')
            );
        }
    } else {
        $post = new Controller_Abono($GLOBALS['db']);
        $result = $post->Read_Abono();
        // Get row count
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_abono' => $id_abono,
                    'valor_abono' => $valor_abono,
                    'fecha_abono' => $fecha_abono,
                    'id_venta' => $id_venta
                );
                array_push($posts_arr['data'], $post_item);
            }
            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(
                array('message' => 'No exiten abonos')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    /*
    ver si como actualizar el valor
    si es necesario el actualizar 
    */
    // 
    $validador = true;
    $post = new Controller_Abono($GLOBALS['db']);
    $fv = new Controller_Factura_Venta($GLOBALS['db']);

    $post->id_abono = $GLOBALS['data']->id_abono;
    $post->valor_abono = $GLOBALS['data']->valor_abono;
    $post->fecha_abono = $fecha = date('Y-m-d'); //se establece como directa 
    $post->id_venta = $GLOBALS['data']->id_venta;
    $fv->id_venta = $post->id_venta;
    $valor_actual = 0;
    $valor_total = 0;


    if ($post->Validacion_parametro($post->id_abono) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una codigo de venta')
        );
    } else {
        if ($post->buscar_id_abono($post->id_abono) == true) {
            $validador = false;
            echo json_encode(
                array('message' => 'Existe numero de la factura venta')
            );
        }
    }
    if ($post->Validador_de_valor_abono($post->valor_abono) == true) {
        $validador = false;
        echo json_encode(
            array('message' => $post->Validador_de_valor_abono($post->valor_abono))
        );
    }
    if ($post->Validacion_parametro($post->id_venta) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una codigo de venta')
        );
    } else {
        if ($fv->buscar_id_venta($post->id_venta) == true) {
            $validador = false;
            echo json_encode(
                array('message' => 'Existe numero de la factura venta')
            );
        }
    }
    //obtenr el valor abono actual
    if ($post->Obtner_valor_actual() == null) {
        $validador = false;
        echo json_encode(
            array('message' => 'No se encontro el valor actual del abono N°: ' . $post->id_abono)
        );
    } else {
        $valor_actual = $post->Obtner_valor_actual();
    }



    //obtner el total y restarlo 
    if ($fv->obtner_valor_venta() == null) {
        $validador = false;
        echo json_encode(
            array('message' => 'Existe numero de la factura venta')
        );
    } else {

        $valor_total = $fv->obtner_valor_venta();
        $valor_total = $valor_total + $valor_actual;

        if ($valor_total > $post->valor_abono) {
            $valor_total = $valor_total - $post->valor_abono;
            $fv->valor_venta = $valor_total;
        }
    }

    if ($validador == true) {
        if ($post->update_abono()) {
            echo json_encode(
                array('message' => 'Se actualizo el abono')
            );
            if ($fv->update_valor_factura_venta()) {
                echo json_encode(
                    array('message' => 'Se actualizo la factura venta N°: '.$post->id_venta)
                );
            }else{
                echo json_encode(
                    array('message' => 'No se actualizo la factura venta N°: '.$post->id_venta)
                );
            }
        } else {
            echo json_encode(
                array('message' => 'No se actualizo el abono')
            );
        }
    }
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
