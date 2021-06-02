<?php
class Controller_metodo_pago_compra{

    public $id_tipo_compra;
    public $descripcion_compra;

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function buscar_metodo_pago_compra($id_tipo_compra)
    {
        
        $query = "SELECT id_tipo_compra FROM metodo_pago_compra WHERE id_tipo_compra  = '" . $id_tipo_compra . "'";
        
        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $comparar = $row['id_tipo_compra'];
        if ($comparar == $id_tipo_compra) {
            return true;
        } else {
            return false;
        }
    }
}
?>