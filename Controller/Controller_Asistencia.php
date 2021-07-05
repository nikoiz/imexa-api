<?php
class Controller_Asistencia
{

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
        $query = "SELECT * FROM asistencia WHERE id_asistencia = ?";
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
        $this->id_detalle_asistencia = $row['id_detalle_asistencia'];
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
        } else {
            $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        }
        if (empty(htmlspecialchars(strip_tags($this->cantidad_dias_fallados)))) {
            $validador = false;
        } else {
            $this->cantidad_dias_fallados = htmlspecialchars(strip_tags($this->cantidad_dias_fallados));
        }
        if (empty(htmlspecialchars(strip_tags($this->rut_trabajador)))) {
            $validador = false;
        } else {
            $this->rut_trabajador = htmlspecialchars(strip_tags($this->rut_trabajador));
        }
        if (empty(htmlspecialchars(strip_tags($this->id_detalle_asistencia)))) {
            $validador = false;
        } else {
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
    public function Update_asistencia()
    {
        $validador = true;
        $query = 'UPDATE asistencia 
        SET 
            fecha = :fecha,
            cantidad_dias_fallados = :cantidad_dias_fallados,
            rut_trabajador = :rut_trabajador,
            id_detalle_asistencia = :id_detalle_asistencia
        WHERE id_asistencia = :id_asistencia';

        $stmt = $this->conn->prepare($query);
        //validadores
        if (htmlspecialchars(strip_tags($this->fecha)) == "") {
            $validador = false;
        } else {
            $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        }
        if (empty(htmlspecialchars(strip_tags($this->cantidad_dias_fallados)))) {
            $validador = false;
        } else {
            $this->cantidad_dias_fallados = htmlspecialchars(strip_tags($this->cantidad_dias_fallados));
        }
        if (empty(htmlspecialchars(strip_tags($this->rut_trabajador)))) {
            $validador = false;
        } else {
            $this->rut_trabajador = htmlspecialchars(strip_tags($this->rut_trabajador));
        }
        if (empty(htmlspecialchars(strip_tags($this->id_detalle_asistencia)))) {
            $validador = false;
        } else {
            $this->id_detalle_asistencia = htmlspecialchars(strip_tags($this->id_detalle_asistencia));
        }
        if (empty(htmlspecialchars(strip_tags($this->id_asistencia)))) {
            $validador = false;
        } else {
            $this->id_asistencia = htmlspecialchars(strip_tags($this->id_asistencia));
        }
        if ($validador == true) {
            $stmt->bindParam(':fecha', $this->fecha);
            $stmt->bindParam(':cantidad_dias_fallados', $this->cantidad_dias_fallados);
            $stmt->bindParam(':rut_trabajador', $this->rut_trabajador);
            $stmt->bindParam(':id_detalle_asistencia', $this->id_detalle_asistencia);
            $stmt->bindParam(':id_asistencia', $this->id_asistencia);
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
    public function Delete_asistencia()
    {
        $validador = true;
        $query = "DELETE FROM asistencia WHERE id_asistencia = ?";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->id_asistencia)) != "") {
            $this->id_asistencia = htmlspecialchars(strip_tags($this->id_asistencia));
        } else {
            $validador = false;
        }

        $stmt->bindParam(1, $this->id_asistencia);

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
    public function Validacion_parametro($parametro)
    {
        if (empty($parametro)) {
            return false;
        } else {
            return true;
        }
    }
    public function Validator_run($rut) //
    {
        /*
        validador quee asepta con/o son los puntos "."  
        se puede establecer en el parametro de formulario HTML sin que el usuario lo plasme los puntos y el guion
        usar de ejemplo el formulario de banco estado
        */
        if ($rut != "") {
            $rut = preg_replace('/[^k0-9]/i', '', $rut);
            $dv  = substr($rut, -1);
            $numero = substr($rut, 0, strlen($rut) - 1);
            $i = 2;
            $suma = 0;
            foreach (array_reverse(str_split($numero)) as $v) {
                if ($i == 8)
                    $i = 2;

                $suma += $v * $i;
                ++$i;
            }

            $dvr = 11 - ($suma % 11);

            if ($dvr == 11)
                $dvr = 0;
            if ($dvr == 10)
                $dvr = 'K';

            if ($dvr == strtoupper($dv)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    function buscar_id_asistencia($id_asistencia)
    {
        $query = "SELECT id_asistencia FROM asistencia WHERE id_asistencia = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_asistencia);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_asistencia'];

        if ($numero_comparar == $id_asistencia) {
            return false;
        } else {
            return true;
        }
    }
}
