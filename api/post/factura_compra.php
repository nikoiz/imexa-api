<?php
//falta cosas
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Factura_Compra.php';
include_once '../../Controller/Controller_metodo_pago_compra.php';
include_once '../../Controller/Controller_Proveedor.php';
include_once '../../Controller/Controller_tipo_factura_compra.php';
include_once '../../Controller/Controller_bodega_has_producto.php';
include_once '../../Controller/Controller_detalle_compra.php';
include_once '../../Controller/Controller_Producto.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));
//linea 80 preguntar otra forma de validar
//si al inngresar un producto se necesitara enviar a que bodega ira

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Instiate blog post object
    $validador = true;
    $post = new Controller_Factura_Compra($GLOBALS['db']);
    $p = new Controller_metodo_pago_compra($GLOBALS['db']);
    $po = new Controller_Proveedor($GLOBALS['db']);
    $pos = new Controller_tipo_factura_compra($GLOBALS['db']);
    //guardar producto en un array para luego manda esa parte al ingreso de producto => descartada

    //opcion viable 2: que al ingresar le mande al post de producto factura y de ai se sume al  detalle compra los datos
    //factura datos
    $post->id_compra = $GLOBALS['data']->id_compra; //folio
    $post->fecha_compra = $GLOBALS['data']->fecha_compra;
    $post->valor_compra = $GLOBALS['data']->valor_compra;
    $post->estado = $GLOBALS['data']->estado;
    $post->rut_proveedor = $GLOBALS['data']->rut_proveedor;
    $post->id_tipo_compra = $GLOBALS['data']->id_tipo_compra;
    $post->recursiva_compra_id = $post->id_compra;
    $post->id_tipo_f_compra = $GLOBALS['data']->id_tipo_f_compra;

    //validacion de factura compra
    if ($post->Validacion_parametro($post->id_compra) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una codigo de compra')
        );
    } else {
        if ($post->buscar_folio_factura($post->id_compra) == false) {
            $validador = false;
            echo json_encode(
                array('message' => 'Existe numero de la factura compra')
            );
        }
    }
    if ($post->Validacion_parametro($post->fecha_compra) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una fecha')
        );
    } else {
        if ($post->validateDate($post->fecha_compra) == false) {
            echo json_encode(
                array('Error' => "fecha mal ingresada")
            );
            $validador = false;
        }
    }
    if ($post->Validador_de_valor_compra($post->valor_compra) != "") {
        $validador = false;
        echo json_encode(
            array('message' => $post->Validador_de_valor_compra($post->valor_compra))
        );
    }
    if ($post->Validacion_parametro($post->estado) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una estado')
        );
    } else {
        if ($post->estado == "Pagado" || $post->estado == "Pendiente") {
        } else {
            $validador = false;
            echo json_encode(
                array('message' => 'favor de ingresar un estado de Pagado o Pendiente')
            );
        }
    }
    if ($post->Validacion_parametro($post->rut_proveedor) == false) { //validacion mas busqeuda
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese el rut del proveedor')
        );
    } else {
        if ($post->Validator_run($post->rut_proveedor) == false) {
            $validador = false;
            echo json_encode(
                array('message' => 'Error rut mal ingresado')
            );
        } else {
            //buscar rut en proveedores
            if (!empty($po->buscar_rut_proveedor($post->rut_proveedor))) {
                echo json_encode(
                    array('message' => 'No existe datos del provedor')
                );
            }
        }
    }

    if ($post->Validacion_parametro($post->id_tipo_compra) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese un metodo de pago')
        );
    } else {
        if ($p->buscar_metodo_pago_compra($post->id_tipo_compra) == false) {
            $validador = false;
            echo json_encode(
                array('message' => 'No existe metodo de pago')
            );
        }
    }
    if ($post->Validacion_parametro($post->id_tipo_f_compra) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese un tipo de factura')
        );
    } else {
        if ($pos->buscar_tipo_factura_compratipo_factura_compra($post->id_tipo_f_compra) == false) {
            $validador = false;
            echo json_encode(
                array('message' => 'Error no existe un tipo de factura')
            );
        }
    }
    //me falta hacer el producto 
    echo json_encode(
        array('message' => $post->id_compra)
    );
    if ($validador == true) {
        if ($post->create_Factura_Compra()) {
            echo json_encode(
                array('message' => 'Se creo la factura compra')
            );
        } else {
            echo json_encode(
                array('message' => 'No se creo la factura compra')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') { //se hara el get de todas la entidades
    if (isset($_GET['id_compra'])) {
        // Instiate blog post object
        $post = new Controller_Factura_Compra($GLOBALS['db']);
        $pos = new Controller_detalle_compra($GLOBALS['db']);

        // GET ID
        $post->id_compra = isset($_GET['id_compra']) ? $_GET['id_compra'] : die();


        if (!empty($post->buscar_folio_factura($post->folio_factura))) {
            echo json_encode(
                array('message' => 'No existe datos sobre la factura N°' . $post->folio_factura)
            );
        } else {
            if ($post->Read_single_Factura_Compra()) {
                $post_item = array(

                    //datos de la factura
                    'id_compra' => $post->id_compra,
                    'fecha_compra' => $post->fecha_compra,
                    'valor_compra' => $post->valor_compra,
                    'estado' => $post->estado,
                    'rut_proveedor' => $post->rut_proveedor,
                    'id_tipo_f_compra' => $post->id_tipo_f_compra,


                );
                //otro if con todos los detalles
                if ($post->Read_single_Factura_Compra_para_detalles) {
                    $detalle = array(
                    'id_detalle_compra' => $id_detalle_compra,
                    'descripcion_compra_producto' => $descripcion_compra_producto,
                    'cantidad_compra_producto' => $cantidad_compra_producto,
                    'valor' => $valor,
                    'producto_id_producto' => $producto_id_producto,
                    'id_producto' => $id_producto,
                    'nombre_producto' => $nombre_producto,
                    'valor_producto' => $valor_producto

                    );
                    
                }

                //Make JSON

                $detalle_completo = json_encode(array(
                    "Factura" => array(
                        $post_item
                        ),
                    "Detalle" => array(
                    $detalle
                    )
                    ));







                print_r(json_encode($detalle_completo));
            } else {
                echo json_encode(
                    array('message' => 'No se encontro el codigo de la factura compra')
                );
            }
        }
    } else {
        $post =  new Controller_Factura_Compra($GLOBALS['db']);
        $result = $post->Read_Factura_Compra();
        $num = $result->rowCount();

        if ($num > 0) {
            // Post array
            $posts_arr = array();
            $posts_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $post_item = array(
                    'id_compra' => $id_compra,
                    'fecha_compra' => $fecha_compra,
                    'valor_compra' => $valor_compra,
                    'estado' => $estado,
                    'rut_proveedor' => $rut_proveedor,
                    'id_tipo_f_compra' => $id_tipo_f_compra

                    /*
                    'id_detalle_compra' => $id_detalle_compra,
                    'descripcion_compra_producto' => $descripcion_compra_producto,
                    'cantidad_compra_producto' => $cantidad_compra_producto,
                    'valor' => $valor,
                    'producto_id_producto' => $producto_id_producto,
                    'id_producto' => $id_producto,
                    'nombre_producto' => $nombre_producto,
                    'valor_producto' => $valor_producto
                    */
                    
                );
                array_push($posts_arr['data'], $post_item);
            }

            echo json_encode($posts_arr);
        } else {
            // No posts
            echo json_encode(

                array('message' => 'No existen facturas compras')
            );
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') { //se hra delete de todas las entidades relacionadas
    /*
    $post = new Controller_Factura_Compra($GLOBALS['db']);
    $p = new Controller_metodo_pago_compra($GLOBALS['db']);
    $po = new Controller_Proveedor($GLOBALS['db']);
    $pos = new Controller_tipo_factura_compra($GLOBALS['db']);
    $has = new Controller_bodega_has_producto($GLOBALS['db']);
    $dc = new Controller_detalle_compra($GLOBALS['db']);
    $p = new Controller_Producto($GLOBALS['db']);

    // GET ID
    $post->id_compra = isset($_GET['id_compra']) ? $_GET['id_compra'] : die();
    if (!empty($post->buscar_folio_factura($post->folio_factura))) {
        echo json_encode(
            array('message' => 'no se encontro la gasto para eliminar')
        );
    } else {
        //eliminar detalle y prdocuto (eliminacion en cadena sql)

        //metood alter table
        if ($post->Alter_table_delete_Factura_Compra($post->id_compra) == false) {
            echo json_encode(
                array('message' => 'No se pudo borrar la factura recursiva')
            );
        } else {
            //borrar las foreign keys de la factura tanto en detalle producto y prod_has_bodega
            //para ello deebere obtener los ids de detalle producto y prod_has_bodega

            if ($post->buscar_las_id_de_todos($post->id_compra) == false) {
                echo json_encode(
                    array('message' => 'No se encuentran datos referentes al detalle de la factura')
                );
            } else {
                $a = $post->buscar_las_id_de_todos($post->id_compra);
                $n = 0;
                foreach ($a as $a) {

                    if ($n == 1) {
                        $dc->id_detalle_compra = $a;
                    } else {
                        $p->id_producto = $a;
                    }
                    $n++;
                }
                echo json_encode(
                    array('message' => $dc->id_detalle_compra)
                );
                echo json_encode(
                    array('message' => $p->id_producto = $a)
                );
                //delete producot



                
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
    }
    
    */
    
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $validador = true;
    $post = new Controller_Factura_Compra($GLOBALS['db']);
    // Get  raw posted data

    // GET ID

    //por linkeo
    $post->id_compra = isset($_GET['id_compra']) ? $_GET['id_compra'] : die();
    $post->estado = $GLOBALS['data']->estado;

    //validacion de factura compra
    if ($post->Validacion_parametro($post->id_compra) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una codigo de compra')
        );
    }
    if ($post->Validacion_parametro($post->estado) == false) {
        $validador = false;
        echo json_encode(
            array('message' => 'Ingrese una estado')
        );
    } else {
        if ($post->estado == "Pagado" || $post->estado == "Pendiente") {
        } else {
            $validador = false;
            echo json_encode(
                array('message' => 'favor de ingresar un estado de Pagado o Pendiente')
            );
        }
    }
    if ($validador == true) {
        if ($post->update_Factura_Compra()) {
            echo json_encode(
                array('message' => 'Se actualizo la factura compra')
            );
        } else {
            echo json_encode(
                array('message' => 'No se actualizo la factura compra')
            );
        }
    }
}
