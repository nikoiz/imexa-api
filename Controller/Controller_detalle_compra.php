<?php
class Controller_detalle_compra
{

    public $id_detalle_compra;
    public $descripcion_compra_producto;
    public $cantidad_compra_producto;
    public $valor;
    public $id_compra;
    public $producto_id_producto;


    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function create_detalle_compra($descripcion_compra_producto, $cantidad_compra_producto, $valor, $id_compra, $producto_id_producto)
    {
        //se realizara en api/post producto_factura para mantener a las los tipos de forma tanto sola como con factura
        //CANTIDAD = cantida de ese producto chek
        //valor =de ese producto
        $query = 'INSERT INTO detalle_compra 
        SET
        descripcion_compra_producto = "'.$descripcion_compra_producto.'",
        cantidad_compra_producto = "'.$cantidad_compra_producto.'",
        valor = "'.$valor.'",
        id_compra = "'.$id_compra.'",
        producto_id_producto = "'.$producto_id_producto.'"';
        $stmt = $this->conn->prepare($query);

        
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }

    public function update_detalle_compra($id_detalle_compra, $descripcion_compra_producto, $cantidad_compra_producto, $valor, $id_compra, $producto_id_producto)
    {
        $query = "UPDATE detalle_compra SET
        id_detalle_compra = :id_detalle_compra,
        descripcion_compra_producto = :descripcion_compra_producto,
        cantidad_compra_producto = :cantidad_compra_producto,
        valor = :valor,
        id_compra =:id_compra,
        producto_id_producto =:producto_id_producto
        WHERE id_detalle_compra = :id_detalle_compra";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_detalle_compra', $id_detalle_compra);
        $stmt->bindParam(':descripcion_compra_producto', $descripcion_compra_producto);
        $stmt->bindParam(':cantidad_compra_producto', $cantidad_compra_producto);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':id_compra', $id_compra);
        $stmt->bindParam(':producto_id_producto', $producto_id_producto);


        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);
            return false;
        }
    }
    public function Validador_cantidad_compra_producto($cantidad_compra_producto)
    {
        if (empty($cantidad_compra_producto)) {
            return "ingrese una cantidad del producto";
        } else {
            if (is_numeric($cantidad_compra_producto)) {
                if (!$cantidad_compra_producto > 0) {
                    return "ingrese un numero mayor a 0";
                } else {
                    return "";
                }
            } else {
                return "solo se aceptan numeros";
            }
        }
    }

    public function Validador_valor_compra_producto($valor)
    {
        if (empty($valor)) {
            return "ingrese un valor del producto";
        } else {
            if (is_numeric($valor)) {
                if (!$valor > 0) {
                    return "ingrese un numero mayor a 0";
                } else {
                    return "";
                }
            } else {
                return "solo se aceptan numeros";
            }
        }
    }
    function buscar_id_detalle_compra($id_detalle_compra)
    {
        $query = "SELECT id_detalle_compra FROM detalle_compra WHERE id_detalle_compra = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_detalle_compra);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_detalle_compra'];

        if ($numero_comparar == $id_detalle_compra) {
            return false;
        } else {
            return true;
        }
    }
}
