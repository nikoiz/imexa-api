<?php 
class Controller_Asistencia{

    private $conn;

    public $id_asistencia;
    public $fecha;
    public $cantidad_dias_fallados;
    public $rut_trabajador;
    public $id_detalle_asistencia;




    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function Read_Asistencia()
    {
        $query = "SELECT * FROM asistencia";
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
    public function Read_single_asistencia()
    {
        $p = new controller_bodega($this->conn);
        $query = "SELECT * FROM asistencia WHERE rut_trabajador = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->rut_trabajador);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id_asistencia = $row['id_asistencia'];
        $this->fecha = $row['fecha'];
        $this->cantidad_dias_fallados = $row['cantidad_dias_fallados'];
        $this->rut_trabajador = $row['rut_trabajador'];
        $this->id_detalle_asistencia =$row['id_detalle_asistencia'];
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }
    public function Create_asistencia()
    {
        $validador = true;
        $query = 'INSERT INTO asistencia 
        SET 
            

            fecha = :fecha,
            cantidad_dias_fallados = :cantidad_dias_fallados,
            rut_trabajador = :rut_trabajador,
            id_detalle_asistencia = :id_detalle_asistencia';

        $stmt = $this->conn->prepare($query);
        //validadores
        if (htmlspecialchars(strip_tags($this->fecha)) == "") {
            $validador = false;
        }else {
            $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        }
        if (empty(htmlspecialchars(strip_tags($this->cantidad_dias_fallados)))) {
            $validador = false;
        }else {
            $this->cantidad_dias_fallados = htmlspecialchars(strip_tags($this->cantidad_dias_fallados));
        }
        if (empty(htmlspecialchars(strip_tags($this->rut_trabajador)))) {
            $validador = false;
        }else {
            $this->rut_trabajador = htmlspecialchars(strip_tags($this->rut_trabajador));
        }
        if (empty(htmlspecialchars(strip_tags($this->id_detalle_asistencia)))) {
            $validador = false;
        }else {
            $this->id_detalle_asistencia = htmlspecialchars(strip_tags($this->id_detalle_asistencia));
        }
        if ($validador == true) {
            $stmt->bindParam(':fecha', $this->fecha);
            $stmt->bindParam(':cantidad_dias_fallados', $this->cantidad_dias_fallados);
            $stmt->bindParam(':rut_trabajador', $this->rut_trabajador);
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
}
?>