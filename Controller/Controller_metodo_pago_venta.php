<?php
class Controller_metodo_pago_compra{

    public $id_tipo_venta;
    public $tipo_venta;

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function read_metodo_pago_compra()
    {
        //$query = "SELECT id, descripcion FROM text";
        $query = "SELECT * from metodo_pago_compra";
        $stmt = $this->conn->prepare($query);
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }

    public function Read_single_metodo_pago_compra()
    {
        $query = "SELECT * from metodo_pago_compra WHERE id_tipo_venta = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->id_tipo_venta);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id_tipo_venta = $row['id_tipo_venta'];
        $this->tipo_venta = $row['tipo_venta'];
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);
            return false;
        }
    }

    public function buscar_metodo_pago_venta($id_tipo_venta)
    {
        
        $query = "SELECT id_tipo_venta FROM metodo_pago_venta WHERE id_tipo_venta  = '" . $id_tipo_venta . "'";
        
        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $comparar = $row['id_tipo_venta'];
        if ($comparar == $id_tipo_venta) {
            return true;
        } else {
            return false;
        }
    }
}
?>