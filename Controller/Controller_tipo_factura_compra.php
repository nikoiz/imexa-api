<?php 
class Controller_tipo_factura_compra
{
    private $conn;
    public $id_tipo_f_compra;
    public $descripcion;


    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function read_tipo_factura_compra()
    {
        //$query = "SELECT id, descripcion FROM text";
        $query = "SELECT * from tipo_factura_compra";
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

    public function Read_single_tipo_factura_compra()
    {
        $query = "SELECT * from tipo_factura_compra WHERE id_tipo_f_compra = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->id_tipo_f_compra);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id_tipo_f_compra = $row['id_tipo_f_compra'];
        $this->descripcion = $row['descripcion'];
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);
            return false;
        }
    }

    function buscar_tipo_factura_compratipo_factura_compra($id_tipo_f_compra)
    {
        $query = "SELECT id_tipo_f_compra FROM tipo_factura_compra WHERE id_tipo_f_compra = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_tipo_f_compra);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $descripcion_comprar =$row['id_tipo_f_compra'];
        if ($descripcion_comprar == $id_tipo_f_compra) {
            return true;
        } else {
            return false;
        }
    }
}
?>