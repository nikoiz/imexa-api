<?php
class Controller_Cliente
{

    public $rut_cliente;
    public $nombre_cliente;


    // DB stuf
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read_cliente()
    {
        //$query = "SELECT id, descripcion FROM text";
        $query = "SELECT * from cliente";
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

    public function Read_single_cliente()
    {
        $query = "SELECT * from cliente WHERE rut_cliente = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->rut_cliente);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->rut_cliente = $row['rut_cliente'];
        $this->nombre_cliente = $row['nombre_cliente'];
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }

    public function create_cliente()
    {
        $validador = true;
        $query = 'INSERT INTO cliente 
        SET 
            
            rut_cliente = :rut_cliente,
            nombre_cliente = :nombre_cliente';

        $stmt = $this->conn->prepare($query);
        if (htmlspecialchars(strip_tags($this->rut_cliente)) == "") {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->nombre_cliente)) == "") {
            $validador = false;
        }



        if ($validador == true) {
            $stmt->bindParam(':rut_cliente', $this->rut_cliente);
            $stmt->bindParam(':nombre_cliente', $this->nombre_cliente);
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
    public function delete_single_cliente()
    {
        $validador = true;
        $query = "DELETE FROM cliente WHERE rut_cliente = ?";
        $stmt = $this->conn->prepare($query);
        if (htmlspecialchars(strip_tags($this->rut_cliente)) != "") {
            $this->rut_cliente = htmlspecialchars(strip_tags($this->rut_cliente));
        }else {
            $validador = false;
        }
        $stmt->bindParam(1, $this->rut_cliente);

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

    public function update_cliente()
    {
        $validador = true;
        $query = "UPDATE cliente SET rut_cliente =:rut_cliente, nombre_cliente= :nombre_cliente  WHERE rut_cliente = :rut_cliente";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->rut_cliente)) == "") {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->nombre_cliente)) == "") {
            $validador = false;
        }

        if ($validador == true) {
            $stmt->bindParam(':rut_cliente', $this->rut_cliente);
            $stmt->bindParam(':nombre_cliente', $this->nombre_cliente);
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
    function buscar_id_gastos($rut_cliente)
    {
        $query = "SELECT rut_cliente FROM cliente WHERE rut_cliente = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $rut_cliente);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['rut_cliente'];

        if ($numero_comparar == $rut_cliente) {
            return false;
        } else {
            return true;
        }
    }

}
