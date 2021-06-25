<?php
class Controller_detalle_venta
{

    public $id_detalle_venta;
    public $descripcion_producto;
    public $cantidad_producto;
    public $valor;
    public $id_venta;
    public $producto_id_producto;


    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function create_detalle_venta($descripcion_producto, $cantidad_producto, $valor, $id_venta, $producto_id_producto)
    {
        //se realizara en api/post producto_factura para mantener a las los tipos de forma tanto sola como con factura
        //CANTIDAD = cantida de ese producto chek
        //valor =de ese producto
        $query = "INSERT INTO detalle_venta
        SET
        descripcion_producto = :descripcion_producto,
        cantidad_producto = :cantidad_producto,
        valor = :valor,
        id_venta =:id_venta,
        producto_id_producto =:producto_id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':descripcion_producto', $descripcion_producto);
        $stmt->bindParam(':cantidad_producto', $cantidad_producto);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':id_venta', $id_venta);
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

    public function update_detalle_venta($id_detalle_venta, $descripcion_producto, $cantidad_producto, $valor, $id_venta, $producto_id_producto)
    {
        $query = "UPDATE detalle_ventaSET
        id_detalle_venta= :id_detalle_venta,
        descripcion_producto = :descripcion_producto,
        cantidad_producto = :cantidad_producto,
        valor = :valor,
        id_venta =:id_venta,
        producto_id_producto =:producto_id_producto
        WHERE id_detalle_venta= :id_detalle_venta";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_detalle_venta', $id_detalle_venta);
        $stmt->bindParam(':descripcion_producto', $descripcion_producto);
        $stmt->bindParam(':cantidad_producto', $cantidad_producto);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':id_venta', $id_venta);
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
    public function Validador_cantidad_producto($cantidad_producto)
    {
        if (empty($cantidad_producto)) {
            return "ingrese una cantidad del producto";
        } else {
            if (is_numeric($cantidad_producto)) {
                if (!$cantidad_producto > 0) {
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
    function buscar_id_detalle_venta($id_detalle_venta)
    {
        $query = "SELECT id_detalle_ventaFROM detalle_ventaWHERE id_detalle_venta= ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_detalle_venta);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_detalle_venta'];

        if ($numero_comparar == $id_detalle_venta) {
            return false;
        } else {
            return true;
        }
    }
    public function Validacion_parametro($parametro)
    {
        if (empty($parametro)) {
            return false;
        } else {
            return true;
        }
    }
    public function Validador_cantidad_venta_producto($cantidad_venta_producto)
    {
        if (empty($cantidad_venta_producto)) {
            return "ingrese una cantidad del producto";
        } else {
            if (is_numeric($cantidad_venta_producto)) {
                if (!$cantidad_venta_producto > 0) {
                    return "ingrese un numero mayor a 0";
                } else {
                    return "";
                }
            } else {
                return "solo se aceptan numeros";
            }
        }
    }
    public function Validador_id_detalle_producto($id_detalle_producto)
    {
        if (empty($id_detalle_producto)) {
            return "ingrese una el codigo del detalle producto";
        } else {
            if (is_numeric($id_detalle_producto)) {
                if (!$id_detalle_producto > 0) {
                    return "ingrese un numero mayor a 0";
                } else {
                    return "";
                }
            } else {
                return "solo se aceptan numeros";
            }
        }
    }
    
}
