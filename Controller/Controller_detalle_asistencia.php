<?php
class Controller_detalle_asistencia
{
    private $conn;

    public $id_detalle_asistencia;
    public $falla_laboral;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function Read_Detalle_asistencia()
    {
        $query = "SELECT * FROM detalle_asistencia";
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
    public function Read_single_detalle_asistencia()
    {
        $p = new controller_bodega($this->conn);
        $query = "SELECT * FROM detalle_asistencia WHERE id_detalle_asistencia = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->id_detalle_asistencia);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id_detalle_asistencia = $row['id_detalle_asistencia'];
        $this->falla_laboral = $row['falla_laboral'];
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }
    public function Create_detalle_asistencia()
    {
        $validador = true;
        $query = 'INSERT INTO detalle_asistencia 
        SET 
        |   falla_laboral = :falla_laboral';

        $stmt = $this->conn->prepare($query);
        //validadores
        if (htmlspecialchars(strip_tags($this->falla_laboral)) == "") {
            $validador = false;
        } else {
            $this->falla_laboral = htmlspecialchars(strip_tags($this->falla_laboral));
        }
        if ($validador == true) {
            $stmt->bindParam(':falla_laboral', $this->falla_laboral);
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
    public function Create_detalle_asistencia_automatico()
    {
        $validador = true;
        $query = 'INSERT INTO detalle_asistencia (id_detalle_asistencia,falla_laboral)
        values 
        (1,1),
        (2,2)';

        $stmt = $this->conn->prepare($query);
        if ($validador == true) {
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
    public function delete_single_detalle_asistencia()
    {
        $validador = true;
        $query = "DELETE FROM detalle_asistencia WHERE id_detalle_asistencia = ?";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->id_detalle_asistencia)) != "") {
            $this->id_detalle_asistencia = htmlspecialchars(strip_tags($this->id_detalle_asistencia));
        } else {
            $validador = false;
        }

        $stmt->bindParam(1, $this->id_detalle_asistencia);

        if ($validador == true) {
            try {
                if ($stmt->execute()) {
                    return true;
                }
            } catch (Exception $e) {
                printf("Error: %s.\n", $stmt->error);

                return false;
            }
        } else {
            return false;
        }
    }
    public function Update_detalle_asistencia()
    {
        $validador = true;
        $query = 'UPDATE  detalle_asistencia
        SET 
        |   falla_laboral = :falla_laboral
        WHERE 
        id_detalle_asistencia =:id_detalle_asistencia';

        $stmt = $this->conn->prepare($query);
        //validadores
        if (htmlspecialchars(strip_tags($this->falla_laboral)) == "") {
            $validador = false;
        } else {
            $this->falla_laboral = htmlspecialchars(strip_tags($this->falla_laboral));
        }
        if (htmlspecialchars(strip_tags($this->id_detalle_asistencia)) == "") {
            $validador = false;
        } else {
            $this->id_detalle_asistencia = htmlspecialchars(strip_tags($this->id_detalle_asistencia));
        }
        if ($validador == true) {
            $stmt->bindParam(':falla_laboral', $this->falla_laboral);
            $stmt->bindParam(':id_detalle_asistencia', $this->id_detalle_asistencia);
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
    function buscar_id_detalle_asistencia($id_detalle_asistencia)
    {
        $query = "SELECT id_detalle_asistencia FROM detalle_asistencia WHERE id_detalle_asistencia = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_detalle_asistencia);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_detalle_asistencia'];

        if ($numero_comparar == $id_detalle_asistencia) {
            return false;
        } else {
            return true;
        }
    }
    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
