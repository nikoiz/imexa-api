<?php
//dejar el local host a puerto 3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST,GET,PUT,DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/conexion.php';
include_once '../../Controller/Controller_Proveedor.php';


$database = new conexion();
$db = $database->connect();
error_reporting(0);
$data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $validador=true;
    $post= new Controller_Proveedor($GLOBALS['db']);
    $post->nombre_proveedor= $GLOBALS['data']->nombre_proveedor;
    $post->rut_proveedor= $GLOBALS['data']->rut_proveedor;
    $post->contacto= $GLOBALS['data']->contacto;
    
    if ($post->rut_proveedor == "") {
        $validador=false;
        echo json_encode(
            array('message' => 'Error Ingrese un rut')
        );
    }else {
        if ($post->Validator_run($post->rut_proveedor)==false) {
            $validador=false;
            echo json_encode(
                array('message' => 'Error rut mal ingresado')
            );
        }
    }
    
    if ($post->Validador_nombre_proveedor($post->nombre_proveedor)==false) {
        $validador=false;
        echo json_encode(
            array('message' => 'Error ingrese un nombre proveedor')
        );
    }
    if ($post->Validador_contacto_proveedor($post->contacto)==false) {
        $validador=false;
        echo json_encode(
            array('message' => 'Error ingrese un contacto del proveedor')
        );
    }else{
        if ($post->validarTelefono($post->contacto)==false) {
            $validador=false;
            echo json_encode(
                array('message' => 'Error numero mal ingrasdo')
            );
        }
    }
    

    if ($validador==true) {
        if ($post->create_producto()) {
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
    if (isset($_GET['rut_proveedor'])){
         // Instiate blog post object
         $post = new Controller_Proveedor($GLOBALS['db']);
    
         // GET ID
         $post->rut_proveedor = isset($_GET['rut_proveedor']) ? $_GET['rut_proveedor'] : die();
     
         if ($post->Validator_run($post->rut_proveedor) == true) {
             if (!empty($post->buscar_rut_proveedor($post->rut_proveedor))) {
                 echo json_encode(
                     array('message' => 'No existe datos del provedor')
                 );
             } else {
                 if ($post->Read_single_proveedor()) {
                     $post_item = array(
                         'rut_proveedor' => $post->rut_proveedor,
                         'nombre_proveedor' => $post->nombre_proveedor,
                         'contacto' => $post->contacto
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
             echo json_encode(
                 array('message' => 'Error no se rut mal ingresado')
             );
         }
        }else{
            $post =  new Controller_Proveedor($GLOBALS['db']);
            $result=$post->Read_proveedor();
            $num = $result->rowCount();
        
            if ($num > 0) {
                // Post array
                $posts_arr = array();
                $posts_arr['data'] = array();
            
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $post_item = array(
                        'rut_proveedor' => $rut_proveedor,
                        'nombre_proveedor' => $nombre_proveedor,
                        'contacto' => $contacto                 
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

if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
      // Instiate blog post object
      $post = new Controller_Proveedor($GLOBALS['db']);


      // GET ID
      $post->rut_proveedor = isset($_GET['rut_proveedor']) ? $_GET['rut_proveedor'] : die();
  
      if ($post->Validator_run($post->rut_proveedor) == true) {
          if (!empty($post->buscar_rut_proveedor($post->rut_proveedor))) {
              echo json_encode(
                  array('message' => 'no se encontro proveedor con el rut: ' . $post->rut_proveedor)
              );
          } else {
              if ($post->delete_single_proveedor()) {
                  echo json_encode(
                      array('message' => 'Post deleted')
                  );
              } else {
                  echo json_encode(
                      array('message' => 'Post not deleted')
                  );
              }
          }
      } else {
          echo json_encode(
              array('message' => 'Error no se rut mal ingresado')
          );
      }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $post = new Controller_Proveedor($GLOBALS['db']);

    // Get  raw posted data
    
    //$post->rut_proveedor = $GLOBALS['data']->rut_proveedor;
    $post->rut_proveedor = isset($_GET['rut_proveedor']) ? $_GET['rut_proveedor'] : die();
    $post->nombre_proveedor = $GLOBALS['data']->nombre_proveedor;
    $post->contacto= $GLOBALS['data']->contacto;
    
    $validador=true;

    if ($post->Validator_run($post->rut_proveedor) == false) {
        $validador=false;
        echo json_encode(
            array('message' => 'Rut del Proveedor mal ingresado')
        );
    }
    if ($post->Validador_nombre_proveedor($post->nombre_proveedor)==false) {
        $validador=false;
        echo json_encode(
            array('message' => 'Error ingrese un nombre proveedor')
        );
    }

    if ($validador==true) {
        if ($post->update_proveedor()) {
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