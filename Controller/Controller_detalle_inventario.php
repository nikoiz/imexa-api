<?php 
class Controller_detalle_inventario{
    private $conn;

    //datos de la clase
    public $id_detalle_inventario;
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

    public function create_detalle_inventario($cantidad_producto,$valor,$id_inventario,$id_bodega,$id_producto)
    {//esto se realizara en el metodo producto
        $validador = true; 
        $query = 'INSERT INTO detalle_inventario 
        SET 
        cantidad_producto = :cantidad_producto,
        valor = :valor,
        fecha_inventario = CONVERT (date, GETDATE()),
        id_inventario = :id_inventario,
        id_bodega = :id_bodega,
        id_producto = :id_producto';

        $stmt = $this->conn->prepare($query);


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

        if ($valor!= "") {
            if (is_numeric($valor)) {
                if ($valor >= 0) {
                    $this->valor =$valor;
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

        if ($validador == true) {
            $stmt->bindParam(':cantidad_producto', $this->cantidad_producto);
            $stmt->bindParam(':valor', $this->valor);
            $stmt->bindParam(':id_inventario', $this->id_inventario);
            $stmt->bindParam(':id_bodega', $this->id_bodega);
            $stmt->bindParam(':id_producto', $this->id_producto);
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
    public function valor_total(){
        $query="SELECT SUM(valor_producto) FROM `producto`inner join bodega_has_producto on producto.id_producto=bodega_has_producto.id_producto";
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
}
?>