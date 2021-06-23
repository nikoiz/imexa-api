<?php
class Controller_bodega_has_producto
{
    public $id_bodega;
    public $id_producto;
    public $cantidad_total;

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create_bodega_has_producto($id_bodega, $id_producto, $cantidad_total)
    {
        $query = "INSERT INTO bodega_has_producto 
        SET
        id_bodega = :id_bodega,
        id_producto = :id_producto,
        cantidad_total = :cantidad_total";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_bodega', $id_bodega);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':cantidad_total', $cantidad_total);



        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }
    function delete_bodega_has_producto($id_producto)
    {
        $query = "DELETE FROM bodega_has_producto WHERE id_producto = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_producto);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function update_bodega_has_producto($id_bodega,$id_producto,$cantidad_total)
    {
        $validador = true;
        $query = "UPDATE bodega_has_producto SET id_bodega =:id_bodega, id_producto= :id_producto, cantidad_total =:cantidad_total  WHERE id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        if ($id_producto == null) {
            $validador = false;
        }
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':id_bodega', $id_bodega);
        $stmt->bindParam(':cantidad_total', $cantidad_total);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function Validador_cantidad_total($cantidad_producto)
    {
        if (empty($cantidad_producto)) {
            return "ingrese una cantidad del producto ";
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

    function buscar_cantidad_producto($id_producto)
    {
        $query = "SELECT cantidad_total FROM bodega_has_producto WHERE id_producto = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_producto);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['cantidad_total'];

        if ($numero_comparar != null) {
            return $numero_comparar;
        } else {
            return null;
        }
    }
}
