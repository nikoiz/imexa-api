<?php
class Controller_detalle_inventario
{
    private $conn;

    //datos de la clase
    public $id_detalle_inventario;
    public $nombre_producto;
    public $cantidad_producto;
    public $valor;
    public $fecha_inventario;
    public $id_inventario;
    public $id_bodega;
    public $id_producto;

    //

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create_detalle_inventario($nombre_producto, $cantidad_producto, $valor, $id_inventario, $id_bodega, $id_producto, $fecha)
    { //esto se realizara en el metodo producto
        $validador = true;
        $query = 'INSERT INTO detalle_inventario 
        SET 
        nombre_producto = :nombre_producto,
        cantidad_producto = :cantidad_producto,
        valor = :valor,
        fecha_inventario = :fecha_inventario, 
        id_inventario = :id_inventario,
        id_bodega = :id_bodega,
        id_producto = :id_producto';
        //errror en el date
        $stmt = $this->conn->prepare($query);



        if (empty($nombre_producto)) {
            $validador = false;
        } else {
            $this->nombre_producto = $nombre_producto;
        }


        if ($cantidad_producto != "") {
            if (is_numeric($cantidad_producto)) {
                if ($cantidad_producto >= 0) {
                    $this->cantidad_producto = $cantidad_producto;
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        } else {
            $validador = false;
        }

        if ($valor != "") {
            if (is_numeric($valor)) {
                if ($valor >= 0) {
                    $this->valor = $valor;
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        } else {
            $validador = false;
        }
        //validacion de ids
        if (!empty($id_inventario)) {
            $this->id_inventario = $id_inventario;
        } else {
            $validador = false;
        }

        if (!empty($id_bodega)) {
            $this->id_bodega = $id_bodega;
        } else {
            $validador = false;
        }

        if (!empty($id_producto)) {
            $this->id_producto = $id_producto;
        } else {
            $validador = false;
        }
        $this->fecha_inventario = "'" . $fecha . "'";


        if ($validador == true) {

            /*
            
INSERT INTO `detalle_inventario`(`nombre_producto`, `cantidad_producto`, `valor`, `fecha_inventario`, `id_inventario`, `id_bodega`, `id_producto`) 
VALUES ("APIO",2,1313,'2021-06-19',1,1,72) // me lo toma con comillas la fecha
            */
            $stmt->bindParam(':nombre_producto', $this->nombre_producto);
            $stmt->bindParam(':cantidad_producto', $this->cantidad_producto);
            $stmt->bindParam(':valor', $this->valor);
            $stmt->bindParam(':id_inventario', $this->id_inventario);
            $stmt->bindParam(':id_bodega', $this->id_bodega);
            $stmt->bindParam(':id_producto', $this->id_producto);
            $stmt->bindParam(':fecha_inventario', $fecha);



            try {
                if ($stmt->execute()) {
                    return true;
                }
            } catch (Exception $e) {
                printf("Error: %s.\n", $e);

                return false;
            }
        } else {
            return false;
        }
    }

    //hacer un update con la cantidad y el date
    public function Sumar_mismo_producto($id_detalle_inventario, $cantidad_del_detalle, $fecha)
    {

        $validador = true;


        if ($cantidad_del_detalle == null) {
            $validador = false;
        }
        if ($id_detalle_inventario == null) {
            $validador = false;
        }

        if ($validador == true) {
            $query = 'UPDATE `detalle_inventario` SET fecha_inventario = "' . $fecha . '" ,cantidad_producto = ' . $cantidad_del_detalle . '
            WHERE id_detalle_inventario = ' . $id_detalle_inventario . '';
            
            $stmt = $this->conn->prepare($query);
            try {
                if ($stmt->execute()) {
                    return true;
                }
            } catch (Exception $e) {
                printf("Error: %s.\n", $e);
                return false;
            }
        } else {
            return false;
        }
    }


    //buscar si es el mismo producto
    public function buscardor_igual_producto($nombre_producto_buscar, $valor)
    {
        $nombre_producto_buscar = '"' . $nombre_producto_buscar . '"';
        $query = "SELECT nombre_producto FROM detalle_inventario WHERE nombre_producto = $nombre_producto_buscar AND valor = $valor";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_producto = $row['nombre_producto'];



        return $nombre_producto;
    }
    public function buscardor_igual_producto_id($nombre_producto_buscar, $valor)
    {
        $nombre_producto_buscar = '"' . $nombre_producto_buscar . '"';
        $query = "SELECT nombre_producto,id_detalle_inventario,cantidad_producto FROM detalle_inventario WHERE nombre_producto = $nombre_producto_buscar AND valor = $valor";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nombre_producto_buscar);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_producto = $row['nombre_producto'];
        $id_inventario = $row['id_detalle_inventario'];
        $cantidad_producto = $row['cantidad_producto '];
        if ($nombre_producto != null) {
            return $id_inventario;
        } else {
            return false;
        }
    }
    public function buscardor_igual_producto_cantidad($nombre_producto_buscar, $valor)
    {
        $nombre_producto_buscar = '"' . $nombre_producto_buscar . '"';
        $query = "SELECT `nombre_producto`,`id_detalle_inventario`,`cantidad_producto` FROM `detalle_inventario` WHERE nombre_producto = $nombre_producto_buscar AND valor = $valor";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nombre_producto_buscar);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_producto = $row['nombre_producto'];
        $id_inventario = $row['id_detalle_inventario'];
        $cantidad_producto = $row['cantidad_producto'];

        if ($nombre_producto != null) {
            return $cantidad_producto;
        } else {
            return false;
        }
    }
    public function cantidad_total()
    {
        $query = "SELECT SUM(`cantidad_producto`) FROM `producto`inner join bodega_has_producto on producto.id_producto=bodega_has_producto.id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total = $row['SUM(`cantidad_producto`)'];
        if ($total != null) {
            return $total;
        } else {
            return false;
        }
    }
    public function valor_total()
    {
        $query = "SELECT SUM(valor_producto) FROM `producto`inner join bodega_has_producto on producto.id_producto=bodega_has_producto.id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total = $row['SUM(valor_producto)'];
        if ($total != null) {
            return $total;
        } else {
            return false;
        }
    }
    public function Read_single_inventario_only($nombre_producto_buscar, $valor) //quety funcionando
    {
        $query = "SELECT nombre_producto,id_detalle_inventario FROM detalle_inventario WHERE nombre_producto = $nombre_producto_buscar AND valor = $valor";
        $stmt = $this->conn->prepare($query);


        //Bind id
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->nombre_producto = $row['nombre_producto'];
        $this->id_detalle_inventario = $row['id_detalle_inventario'];

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }
}
