<?php
class Controller_Abono
{
    private $conn;

    public $id_abono;
    public $valor_abono;
    public $fecha_abono;
    public $id_venta;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function Read_Abono()
    {
        $query = "SELECT * FROM abono";
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
    public function Read_single_abono()
    {
        //SELECT  cliente.rut_cliente, abono.id_abono,abono.valor_abono,abono.fecha_abono,abono.id_venta FROM cliente INNER JOIN factura_venta on cliente.rut_cliente=factura_venta.rut_cliente INNER JOIN abono ON factura_venta.id_venta=abono.id_venta WHERE  cliente.rut_cliente  = ?
        $query = "SELECT  abono.id_abono,abono.valor_abono,abono.fecha_abono,abono.id_venta FROM cliente INNER JOIN factura_venta on cliente.rut_cliente=factura_venta.rut_cliente INNER JOIN abono ON factura_venta.id_venta=abono.id_venta WHERE  cliente.rut_cliente  = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->id_abono);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id_abono = $row['abono.id_abono'];
        $this->valor_abono = $row['abono.valor_abono'];
        $this->fecha_abono = $row['abono.fecha_abono'];
        $this->id_venta = $row['abono.id_venta'];

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }
    public function create_abono()
    {
        $validador = true;
        $query = 'INSERT INTO abono 
        SET 
        id_abono = :id_abono,
        valor_abono = :valor_abono,
        fecha_abono = :fecha_abono,
        id_venta = :id_venta';

        $stmt = $this->conn->prepare($query);

        if (!empty(htmlspecialchars(strip_tags($this->id_abono)))) {
            $this->id_abono = htmlspecialchars(strip_tags($this->id_abono));
        } else {
            $validador = false;
        }
        if (empty(htmlspecialchars(strip_tags($this->valor_abono)))) {
            $validador = false;
        } else {
            if (!is_numeric(htmlspecialchars(strip_tags($this->valor_abono)))) {
                $validador = false;
            } else {
                $this->valor_abono = htmlspecialchars(strip_tags($this->valor_abono));
            }
        }
        if (!empty(htmlspecialchars(strip_tags($this->fecha_venta)))) {
            $this->fecha_venta = htmlspecialchars(strip_tags($this->fecha_venta));
        } else {
            $validador = false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->id_venta)))) {
            $this->id_venta = htmlspecialchars(strip_tags($this->id_venta));
        } else {
            $validador = false;
        }


        if ($validador == true) {
            $stmt->bindParam(':id_abono', $this->id_abono);
            $stmt->bindParam(':valor_abono', $this->valor_abono);
            $stmt->bindParam(':fecha_abono', $this->fecha_abono);
            $stmt->bindParam(':id_venta', $this->id_venta);
            try {
                if ($stmt->execute()) {
                    return true;
                }
            } catch (Exception $e) {
                printf("Error: %s.\n", $stmt->e);

                return false;
            }
        } else {
            return false;
        }
    }
    public function update_abono()
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE abono SET    
        valor_abono = :valor_abono,
        fecha_abono = :fecha_abono,
        id_venta = :id_venta
          WHERE id_abono = :id_abono";
        $stmt = $this->conn->prepare($query);
        if (!empty(htmlspecialchars(strip_tags($this->id_abono)))) {
            $this->id_abono = htmlspecialchars(strip_tags($this->id_abono));
        } else {
            $validador = false;
        }
        if (empty(htmlspecialchars(strip_tags($this->valor_abono)))) {
            $validador = false;
        } else {
            if (!is_numeric(htmlspecialchars(strip_tags($this->valor_abono)))) {
                $validador = false;
            } else {
                $this->valor_abono = htmlspecialchars(strip_tags($this->valor_abono));
            }
        }
        if (!empty(htmlspecialchars(strip_tags($this->fecha_venta)))) {
            $this->fecha_venta = htmlspecialchars(strip_tags($this->fecha_venta));
        } else {
            $validador = false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->id_venta)))) {
            $this->id_venta = htmlspecialchars(strip_tags($this->id_venta));
        } else {
            $validador = false;
        }

        // Bind Data
        if ($validador == true) {
            $stmt->bindParam(':id_abono', $this->id_abono);
            $stmt->bindParam(':valor_abono', $this->valor_abono);
            $stmt->bindParam(':fecha_abono', $this->fecha_abono);
            $stmt->bindParam(':id_venta', $this->id_venta);
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
    function buscar_id_abono($id_abono)
    {
        $query = "SELECT id_abono FROM abono WHERE id_abono = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_abono);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_abono'];

        if ($numero_comparar == $id_abono) {
            return false;
        } else {
            return true;
        }
    }
    public function Validador_de_valor_abono($numero)
    {
        if ($numero == "") {
            return "Falta el valor abono";
        } else {
            if (is_numeric($numero)) {
                if (!$numero > 0) {
                    return "Ingrese solo valores positivos";
                } else {
                    return "";
                }
            } else {
                return "Ingrese solo numeros";
            }
        }
    }
    function obtener_valor_total($rut_cliente)
    {
        $query = "SELECT  cliente.rut_cliente,SUM(abono.valor_abono) as total_abono FROM cliente INNER JOIN factura_venta on cliente.rut_cliente=factura_venta.rut_cliente INNER JOIN abono ON factura_venta.id_venta=abono.id_venta WHERE  cliente.rut_cliente =  ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $rut_cliente);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['rut_cliente'];
        $total_abono  = $row['total_abono'];

        if ($numero_comparar == $rut_cliente) {
            return $total_abono;
        } else {
            return "";
        }
    }
}
