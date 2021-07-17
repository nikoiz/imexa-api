<?php
class Controller_Asistencia
{

    private $conn;

    public $id_asistencia;
    public $fecha;
    public $cant_dias_fallados;
    public $rut_trabajador;
    public $id_detalle_asistencia;

    //para buscador de asistencia
    public $fecha_incio;
    public $fecha_termino;




    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function Read_Asistencia()
    {
        $query = "SELECT * FROM `asistencia` WHERE `id_detalle_asistencia` = 1";
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
        //SELECT SUM(`cant_dias_fallados`) as cant_dias_fallados,`rut_trabajador` FROM `asistencia` WHERE `fecha` BETWEEN '2021-07-01' AND '2021-07-31' and `rut_trabajador`="11344366-9"

        $query = "SELECT SUM(`cant_dias_fallados`) as cant_dias_fallados,`rut_trabajador` FROM `asistencia` WHERE `fecha` BETWEEN ? AND ? and `rut_trabajador`=?";
        //$query = "SELECT * FROM asistencia WHERE id_asistencia = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->fecha_incio);
        $stmt->bindParam(2, $this->fecha_termino);
        $stmt->bindParam(3, $this->rut_trabajador);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $this->cant_dias_fallados = $row['cant_dias_fallados'];
        $this->rut_trabajador = $row['rut_trabajador'];
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
        $query = "INSERT INTO asistencia 
        SET 
            fecha = :fecha,
            cant_dias_fallados = :cant_dias_fallados,
            rut_trabajador = :rut_trabajador,
            id_detalle_asistencia = :id_detalle_asistencia";

        $stmt = $this->conn->prepare($query);

        //validadores
        if (htmlspecialchars(strip_tags($this->fecha)) == "") {
            $validador = false;
        } else {
            $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        }
        if (htmlspecialchars(strip_tags($this->cant_dias_fallados)) == "") {
            $validador = false;
        } else {
            $this->cant_dias_fallados = htmlspecialchars(strip_tags($this->cant_dias_fallados));
        }

        if (empty(htmlspecialchars(strip_tags($this->rut_trabajador)))) {
            $validador = false;
        } else {
            $this->rut_trabajador = htmlspecialchars(strip_tags($this->rut_trabajador));
        }


        $this->id_detalle_asistencia = htmlspecialchars(strip_tags($this->id_detalle_asistencia));
        if ($validador == true) {
            $stmt->bindParam(':fecha', $this->fecha);
            $stmt->bindParam(':cant_dias_fallados', $this->cant_dias_fallados);
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
            cant_dias_fallados = :cant_dias_fallados,
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
        if (empty(htmlspecialchars(strip_tags($this->cant_dias_fallados)))) {
            $validador = false;
        } else {
            $this->cant_dias_fallados = htmlspecialchars(strip_tags($this->cant_dias_fallados));
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
            $stmt->bindParam(':cant_dias_fallados', $this->cant_dias_fallados);
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
    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;

        /*
        if(validateDate('2012-02-28')==false){
    $validador=false;
    }
        */
    }
    public function Buscar_rut_trabajador($tipo)
    {
        $query = 'SELECT rut_trabajador,valor_dia FROM trabajador WHERE rut_trabajador = "'.$tipo.'"';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        
        $comparar = $row['rut_trabajador'];
        $valor_dia =$row['valor_dia'];

        if ($comparar == $tipo) {
            return false;
        } else {
            return true;
        }
    }
    public function Buscar_rut_trabajador_valor_dia($tipo)
    {
        $query = 'SELECT rut_trabajador,valor_dia FROM trabajador WHERE rut_trabajador = "'.$tipo.'"';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        
        $comparar = $row['rut_trabajador'];
        $valor_dia =$row['valor_dia'];

        if ($comparar == $tipo) {
            return $valor_dia;
        } else {
            return "";
        }
    }
}
