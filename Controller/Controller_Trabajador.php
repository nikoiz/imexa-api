<?php 

class Controller_Trabajador
{
    private $conn;
    public $rut_trabajador;
    public $nombre_trabajador;
    public $fecha_contratacion;
    public $usuario;
    public $contraseña;
    public $id_tipo_trabajador;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function Read_trabajador()
    {
        $query = "SELECT * from trabajador";
        $stmt = $this->conn->prepare($query);

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n",$stmt->error);
           
            return false;
        }
    }

    public function Read_single_trabajador()
    {
        $query = "SELECT * FROM trabajador WHERE rut_trabajador = ?";
        $stmt = $this->conn->prepare($query);
         //Bind id
         $stmt->bindParam(1, $this->rut_trabajador);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);

         // set properties
         $this->id_tipo_trabajador=$row['id_tipo_trabajador'];
         $this->contraseña=$row['contraseña'];
         $this->usuario=$row['usuario'];
         $this->fecha_contratacion = $row['fecha_contratacion'];
         $this->nombre_trabajador= $row['nombre_trabajador'];
         $this->rut_trabajador = $row['rut_trabajador'];

         try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n",$stmt->error);
           
            return false;
        }
    }
    public function create_trabajador()
    {
        $validador=true;
        $query = 'INSERT INTO trabajador 
        SET 
            id_tipo_trabajador =:id_tipo_trabajador,
            contraseña =:contraseña,
            usuario =:usuario,
            fecha_contratacion =:fecha_contratacion,
            nombre_trabajador = :nombre_trabajador,
            rut_trabajador = :rut_trabajador';

        $stmt = $this->conn->prepare($query);

        if (!empty(htmlspecialchars(strip_tags($this->rut_trabajador)))) {
            $this->rut_trabajador=htmlspecialchars(strip_tags($this->rut_trabajador));
            $rut =$this->rut_trabajador;
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
                    $validador=true;
                } else {
                    $validador=false;
                }
            } else {
                $validador=false;
            }
        }else {
            $validador=false;
        }

        if (!empty(htmlspecialchars(strip_tags($this->nombre_trabajador)))) {
            $this->nombre_trabajador=htmlspecialchars(strip_tags($this->nombre_trabajador));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->fecha_contratacion)))) {
            $this->fecha_contratacion=htmlspecialchars(strip_tags($this->fecha_contratacion));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->usuario)))) {
            $this->usuario=htmlspecialchars(strip_tags($this->usuario));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->contraseña)))) {
            $this->contraseña=htmlspecialchars(strip_tags($this->contraseña));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->id_tipo_trabajador)))) {
            if (is_numeric(htmlspecialchars(strip_tags($this->id_tipo_trabajador)))) {
                if (htmlspecialchars(strip_tags($this->id_tipo_trabajador))>=1) {
                    $this->id_tipo_trabajador=htmlspecialchars(strip_tags($this->id_tipo_trabajador));
                }else {
                    $validador=false;
                }  
            }else {
                $validador=false;
            }  
        }else {
            $validador=false;
        }

        if ($validador==true) {
            try {
                if ($stmt->execute()) {
                    return true;
                }
            } catch (Exception $e) {
                printf("Error: %s.\n",$stmt->error);
               
                return false;
            }
        }else {
            return false;
        }
    }

    public function delete_single_trabajador()
    {
        $validador=true;
        $query = "DELETE FROM trabajador WHERE rut_trabajador = ?";
        $stmt = $this->conn->prepare($query);

        if (!empty(htmlspecialchars(strip_tags($this->rut_trabajador)))) {
            $this->rut_trabajador=htmlspecialchars(strip_tags($this->rut_trabajador));
            $rut =$this->rut_trabajador;
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
                    $validador=true;
                } else {
                    $validador=false;
                }
            } else {
                $validador=false;
            }
        }else {
            $validador=false;
        }

        

        if ($validador==true) {
            $stmt->bindParam(1, $this->rut_trabajador);
            try {
                if ($stmt->execute()) {
                    return true;
                }
            } catch (Exception $e) {
                printf("Error: %s.\n",$stmt->error);
               
                return false;
            }
        }else {
            return false;
        }
    }

    public function update_trabajador()
    {
        $validador=true;
        $query = "UPDATE trabajador SET id_tipo_trabajador =:id_tipo_trabajador,
        contraseña =:contraseña,
        usuario =:usuario,
        fecha_contratacion =:fecha_contratacion,
        nombre_trabajador = :nombre_trabajador WHERE rut_trabajador = :rut_trabajador";
        $stmt = $this->conn->prepare($query);

        
        if (!empty(htmlspecialchars(strip_tags($this->rut_trabajador)))) {
            $this->rut_trabajador=htmlspecialchars(strip_tags($this->rut_trabajador));
            $rut =$this->rut_trabajador;
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
                    $validador=true;
                } else {
                    $validador=false;
                }
            } else {
                $validador=false;
            }
        }else {
            $validador=false;
        }

        if (!empty(htmlspecialchars(strip_tags($this->nombre_trabajador)))) {
            $this->nombre_trabajador=htmlspecialchars(strip_tags($this->nombre_trabajador));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->fecha_contratacion)))) {
            $this->fecha_contratacion=htmlspecialchars(strip_tags($this->fecha_contratacion));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->usuario)))) {
            $this->usuario=htmlspecialchars(strip_tags($this->usuario));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->contraseña)))) {
            $this->contraseña=htmlspecialchars(strip_tags($this->contraseña));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->id_tipo_trabajador)))) {
            if (is_numeric(htmlspecialchars(strip_tags($this->id_tipo_trabajador)))) {
                if (htmlspecialchars(strip_tags($this->id_tipo_trabajador))>=1) {
                    $this->id_tipo_trabajador=htmlspecialchars(strip_tags($this->id_tipo_trabajador));
                }else {
                    $validador=false;
                }  
            }else {
                $validador=false;
            }  
        }else {
            $validador=false;
        }



        if ($validador==true) {
            $stmt-> bindParam(':id_tipo_trabajador', $this->id_tipo_trabajador);
            $stmt-> bindParam(':contraseña', $this->contraseña);
            $stmt-> bindParam(':usuario', $this->usuario);
            $stmt-> bindParam(':fecha_contratacion', $this->fecha_contratacion);
            $stmt-> bindParam(':nombre_trabajador', $this->nombre_trabajador);
            $stmt-> bindParam(':rut_trabajador', $this->rut_trabajador);
            try {
                if ($stmt->execute()) {
                    return true;
                }
            } catch (Exception $e) {
                printf("Error: %s.\n",$stmt->error);
               
                return false;
            }
        }else {
            return false;
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
    public function Inicio_sesion()
    {
        $query = "SELECT * FROM trabajador WHERE usuario = ? and contraseña = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->usuario);
        $stmt->bindParam(2, $this->contraseña);
        $stmt->execute();


        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $usuario_comparar = $row['usuario'];
        $contraseña_comprar= $row['contraseña'];



        if ($usuario_comparar== $this->usuario && $this->contraseña== $contraseña_comprar) {
            return true;

        }else {
            
            return false;

         //header("Location: ../index.php?error=$error");
        }
    }
    public function Validacion_parametros($parma)
    {
        if ($parma==null) {
            return false;
        }else {
            return true;
        }
    }
    public function Buscar_tipo_trabajador($tipo)
    {
        $query = "SELECT id_tipo_trabajador FROM tipo_trabajador WHERE id_tipo_trabajador = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $tipo);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $comparar = $row['id_tipo_trabajador'];

        if ($comparar == $tipo) {
            return false;
        } else {
            return true;
        }
    }
}
