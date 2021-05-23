<?php 
class Controller_trabajador_has_bodega{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function create_trabajador_has_bodega($rut_trabajador,$id_bodega)
    {
        $query = "INSERT INTO trabajador_has_bodega 
        SET
        rut_trabajador =:rut_trabajador,
        id_bodega= :id_bodega";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':rut_trabajador', $rut_trabajador);
        $stmt->bindParam(':id_bodega', $id_bodega);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }
    function delete_trabajador_has_bodega($id_bodega)
    {
        $query= "DELETE FROM trabajador_has_bodega WHERE id_bodega = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_bodega);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function update_trabajador_has_bodega($id_bodega)
    {
        $validador=true;
        $query = "UPDATE trabajador_has_bodega SET rut_trabajador =:rut_trabajador, id_bodega= :id_bodega  WHERE id_bodega = :id_bodega";
        $stmt = $this->conn->prepare($query);
        if ($id_bodega== null) {
            $validador=false;
        }
        $stmt->bindParam(':id_bodega', $id_bodega);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}




?>