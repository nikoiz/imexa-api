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