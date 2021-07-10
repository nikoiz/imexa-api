<?php
class Controller_Proveedor
{

    private $conn;

    public $rut_proveedor;
    public $nombre_proveedor;
    public $contacto;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function Read_proveedor()
    {
        $query = "SELECT * from proveedor";
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

    public function Read_single_proveedor()
    {
        $query = "SELECT * FROM proveedor WHERE rut_proveedor = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->rut_proveedor);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->nombre_proveedor = $row['nombre_proveedor'];
        $this->rut_proveedor = $row['rut_proveedor'];
        $this->contacto = $row['contacto'];

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }

    public function create_producto()
    {
        $validador = true;
        $query = 'INSERT INTO proveedor 
        SET 
            
            nombre_proveedor = :nombre_proveedor,
            rut_proveedor = :rut_proveedor,
            contacto = :contacto';

        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->rut_proveedor)) == "") {
            $validador = false;
        }else {
            $this->rut_proveedor = htmlspecialchars(strip_tags($this->rut_proveedor));
        }

        if (!empty(htmlspecialchars(strip_tags($this->nombre_proveedor)))) {
            $this->nombre_proveedor = htmlspecialchars(strip_tags($this->nombre_proveedor));
        } else {
            $validador = false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->contacto)))) {
            $this->contacto = htmlspecialchars(strip_tags($this->contacto));
        }else {
            $validador = false;
        }


        if ($validador == true) {
            $stmt->bindParam(':nombre_proveedor', $this->nombre_proveedor);
            $stmt->bindParam(':rut_proveedor', $this->rut_proveedor);
            $stmt->bindParam(':contacto', $this->contacto);

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
    public function delete_single_proveedor()
    {
        $validador = true;
        $query = "DELETE FROM proveedor WHERE rut_proveedor = ?";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->rut_proveedor)) != "") {
            $this->rut_proveedor = htmlspecialchars(strip_tags($this->rut_proveedor));
        } else {
            $validador = false;
        }

        $stmt->bindParam(1, $this->rut_proveedor);

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

    public function update_proveedor()
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE proveedor SET rut_proveedor =:rut_proveedor, nombre_proveedor= :nombre_proveedor,contacto= :contacto  WHERE rut_proveedor = :rut_proveedor";
        $stmt = $this->conn->prepare($query);


        if (htmlspecialchars(strip_tags($this->rut_proveedor)) == "") {
            $validador = false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->nombre_proveedor)))) {
            $this->nombre_proveedor = htmlspecialchars(strip_tags($this->nombre_proveedor));
        } else {
            $validador = false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->contacto)))) {
            $this->contacto = htmlspecialchars(strip_tags($this->contacto));
        }else {
            $validador = false;
        }
        // Bind Data

        if ($validador == true) {
            $stmt->bindParam(':nombre_proveedor', $this->nombre_proveedor);
            $stmt->bindParam(':rut_proveedor', $this->rut_proveedor);
            $stmt->bindParam(':contacto', $this->contacto);

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

    public function buscar_nombre_proveedor($nombre_proveedor)
    {
        $query = "SELECT nombre_proveedor FROM proveedor WHERE nombre_proveedor  like '%" . $nombre_proveedor . "%'";
        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $nombre_comparar = $row['nombre_proveedor'];

        if ($nombre_comparar == $nombre_proveedor) {
            return false;
        } else {
            return true;
        }
    }
    public function buscar_rut_proveedor($rut_proveedor)
    {
        $query = "SELECT rut_proveedor FROM proveedor WHERE rut_proveedor  like '%" . $rut_proveedor . "%'";
        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $rut_comparar = $row['rut_proveedor'];

        if ($rut_comparar == $rut_proveedor) {
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
    public function Validador_nombre_proveedor($nombre_proveedor)
    {
        if ($nombre_proveedor==null) {
            return false;
        }else {
            return true;
        }
    }
    public function Validador_contacto_proveedor($contacto)
    {
        if ($contacto==null) {
            return false;
        }else {
            return true;
        }
    }
    function validarTelefono($numero)
    {// para chile 
        //$reg = "/^(\+?56)?(\s?)(0?9)(\s?)[9876543]\d{7}$/";
        $reg = "#^\(?\d{2}\)?[\s\.-]?\d{4}[\s\.-]?\d{4}$#";
        if (preg_match($reg, $numero)) {
                  return true;
              }else {
                  return false;
              }
      }
}
