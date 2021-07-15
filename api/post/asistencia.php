<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Asistencia.php';
include_once '../../Controller/Controller_Trabajador.php';
include_once '../../Controller/Controller_detalle_asistencia.php';

$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $post = new Controller_Asistencia($GLOBALS['db']);
    $dta = new Controller_detalle_asistencia($GLOBALS['db']);
    $t = new Controller_Trabajador($GLOBALS['db']);
    $post->fecha =  $GLOBALS['data']->fecha; 
    
    $post->rut_trabajador = $GLOBALS['data']->rut_trabajador;
    $post->id_detalle_asistencia = $GLOBALS['data']->id_detalle_asistencia;
    $validador = true;

    if ($post->Validacion_parametro($post->fecha) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una fecha')
        );
    } else {
        if ($post->validateDate($post->fecha) == false) {
            echo json_encode(
                array('Error' => "fecha mal ingresada")
            );
            $validador = false;
        }
    }

    if ($post->Validacion_parametro($post->rut_trabajador) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => 'ingrese el rut del trabajador')
        );
    } else {
        if ($post->Validator_run($post->rut_trabajador) == false) {
            $validador = false;
            echo json_encode(
                array('Error' => 'rut mal ingresado')
            );
        } else {
            //buscar el trabajador
            if (!empty($t->Buscar_rut_trabajador($post->rut_trabajador))) {
                $validador = false;
                echo json_encode(
                    array('Error' => 'rut no existe')
                );
            }
        }
    }
    if ($post->id_detalle_asistencia == 1) {
        $post->cant_dias_fallados = 1;
    }else {
        $post->cant_dias_fallados = 0;
    }

    if ($validador == true) {
        if ($post->Create_asistencia()) {
            echo json_encode(
                array('message' => 'Post Created')
            );
        } else {
            echo json_encode(
                array('message' => 'Post not Created')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') { //enel get actualize el sueldo por el valor de dias de atraso del trabajador (aplicar formula)
    $t = new Controller_Trabajador($GLOBALS['db']);
    $validador = true;
    if (isset($_GET['fecha_incio']) && isset($_GET['fecha_termino']) && isset($_GET['rut_trabajador'])) { //fecha busqueda prrinciapl y termino
        // Instiate blog post object
        $post = new Controller_Asistencia($GLOBALS['db']);

        //GET ID
        //se buscara la cantidad de inasistencia 

        $post->fecha_incio = isset($_GET['fecha_incio']) ? $_GET['fecha_incio'] : die();
        $post->fecha_termino = isset($_GET['fecha_termino']) ? $_GET['fecha_termino'] : die();
        $post->rut_trabajador = isset($_GET['rut_trabajador']) ? $_GET['rut_trabajador'] : die();
        if ($post->validateDate($post->fecha_incio) == false) {
            $validador = false;
            echo json_encode(
                array('Error' => "Fecha mal ingresada para la fecha inicio")
            );
        }
        if ($post->validateDate($post->fecha_termino) == false) {
            $validador = false;
            echo json_encode(
                array('Error' => "Fecha mal ingresada para la fecha termino")
            );
        }
        if ($validador == true) {
            $separa = explode("-", $post->fecha_termino);
            $ano = $separa[0];
            $mes = $separa[1];
            $dia = $separa[2];
            
            if (!empty($post->Buscar_rut_trabajador($post->rut_trabajador))) {
                echo json_encode(
                    array('message' => 'No existe datos sobre la asistencia')
                );
            } else {
                if ($post->Read_single_asistencia()) {
                    $post_item = array(

                        'cant_dias_fallados' => $post->cant_dias_fallados,
                        'rut_trabajador' => $post->rut_trabajador
                    );
                    //Make JSON

                    print_r(json_encode($post_item));
                    print_r(json_encode("el dia ".$dia = $separa[2]));
                    //actualizar el sueldo del trabajor de ese mes
                    $total_sueldo = $mes-$post->cant_dias_fallados * $post->Buscar_rut_trabajador($post->rut_trabajador);
                    if ($t->update_trabajador_para_asistencia_del_mes($post->rut_trabajador,$total_sueldo)==false) {
                        echo json_encode(
                            array('message' => 'No se actualizo el rut del trabajador')
                        );
                    }

                } else {
                    echo json_encode(
                        array('message' => 'No Posts Found')
                    );
                }
            }
        }
    } else {
        $post = new Controller_Asistencia($GLOBALS['db']);
        $result = $post->Read_Asistencia();
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_asistencia' => $id_asistencia,
                    'fecha' => $fecha,
                    'cant_dias_fallados' => $cant_dias_fallados,
                    'rut_trabajador' => $rut_trabajador,
                    'id_detalle_asistencia' => $id_detalle_asistencia
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
    $post = new Controller_Asistencia($GLOBALS['db']);


    // GET ID
    $post->id_asistencia = isset($_GET['id_asistencia']) ? $_GET['id_asistencia'] : die();

    if (!empty($post->buscar_id_asistencia($post->id_asistencia))) {
        echo json_encode(
            array('message' => 'no se encontro la gasto para eliminar')
        );
    } else {
        if ($post->Delete_asistencia()) {
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
    $post = new Controller_Asistencia($GLOBALS['db']);
    $post->fecha = $GLOBALS['data']->fecha;
    $post->cant_dias_fallados;
    $post->rut_trabajador = $GLOBALS['data']->rut_trabajador;
    $post->id_detalle_asistencia = $GLOBALS['data']->id_detalle_asistencia;
    $validador = true;

    if ($post->Validacion_parametro($post->rut_trabajador) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => 'ingrese el rut del trabajador')
        );
    } else {
        if ($post->Validator_run($post->rut_trabajador) == false) {
            $validador = false;
            echo json_encode(
                array('Error' => 'rut mal ingresado')
            );
        }
    }
    if ($post->Validacion_parametro($post->id_detalle_asistencia) == false) {
        $validador = false;
        echo json_encode(
            array('Error' => 'ingrese si existio una falta')
        );
    } else {
        $post->cant_dias_fallados = 0; // 0 = significa que no ahi falta en ese dia
    }
    if ($validador == true) {
        if ($post->Update_asistencia()) {
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
//header("HTTP/1.1 400 Bad Request");
