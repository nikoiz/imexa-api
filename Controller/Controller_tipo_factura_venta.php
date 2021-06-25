<?php 
class Controller_tipo_factura_venta
{
    private $conn;
    public $id_tipo_f_venta;
    public $descripcion;


    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function read_tipo_factura_venta()
    {
        //$query = "SELECT id, descripcion FROM text";
        $query = "SELECT * from tipo_factura_venta";
        $stmt = $this->conn->prepare($query);
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }

    public function Read_single_tipo_factura_venta()
    {
        $query = "SELECT * from tipo_factura_venta WHERE id_tipo_f_venta = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->id_tipo_f_venta);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id_tipo_f_venta = $row['id_tipo_f_venta'];
        $this->descripcion = $row['descripcion'];
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }

    function buscar_tipo_factura_venta($id_tipo_f_venta)
    {
        $query = "SELECT id_tipo_f_venta FROM tipo_factura_venta WHERE id_tipo_f_venta = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_tipo_f_venta);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $descripcion_comprar =$row['id_tipo_f_venta'];
        if ($descripcion_comprar == $id_tipo_f_venta) {
            return true;
        } else {
            return false;
        }
    }

}
?>