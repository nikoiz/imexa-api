<?php 
class Controller_Factura_Venta
{
    private $conn;

    public $nombre_contribuyente;
    public $folio_factura_venta;
    public $rut_contribuyente;
    public $total_factura;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function Read_Factura_Venta()
    {
        $query = "SELECT * from factura_venta";
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

    public function Read_single_Factura_Venta()
    {
        $query = "SELECT * FROM factura_venta WHERE folio_factura_venta = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->folio_factura_venta);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->folio_factura_venta = $row['folio_factura_venta'];
        $this->nombre_contribuyente = $row['nombre_contribuyente'];
        $this->rut_contribuyente = $row['rut_contribuyente'];
        $this->total_factura = $row['total_factura'];

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }

    public function create_Factura_Venta()
    {
        $validador = true;
        $query = 'INSERT INTO factura_venta 
        SET 
            
            folio_factura_venta = :folio_factura_venta,
            nombre_contribuyente = :nombre_contribuyente,
            rut_contribuyente = :rut_contribuyente,
            total_factura = :total_factura';

        $stmt = $this->conn->prepare($query);


        if (empty(htmlspecialchars(strip_tags($this->folio_factura_venta)))) {
            $validador = false;
        } else {
            if (is_numeric(htmlspecialchars(strip_tags($this->folio_factura_venta)))) {
                if (htmlspecialchars(strip_tags($this->folio_factura_venta)) > 0) {
                    //poner mensaje en validador de que solo se aceptan n° positivos
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        }
        if (htmlspecialchars(strip_tags($this->nombre_contribuyente)) == "") {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->rut_contribuyente)) == "") {
            $validador = false;
        }

        if (empty(htmlspecialchars(strip_tags($this->total_factura)))) {
            $validador = false;
        } else {
            if (is_numeric(htmlspecialchars(strip_tags($this->total_factura)))) {
                if (htmlspecialchars(strip_tags($this->total_factura)) > 0) {
                    //poner mensaje en validador de que solo se aceptan n° positivos
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        }

        if ($validador == true) {
            $stmt->bindParam(':folio_factura_venta', $this->folio_factura_venta);
            $stmt->bindParam(':nombre_contribuyente', $this->nombre_contribuyente);
            $stmt->bindParam(':rut_contribuyente', $this->rut_contribuyente);
            $stmt->bindParam(':total_factura', $this->total_factura);
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

    public function delete_Factura_Venta()
    {
        $validador = true;
        $query = "DELETE FROM factura_venta WHERE folio_factura_venta = ?";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->folio_factura_venta)) != "") {
            $this->id_gastos = htmlspecialchars(strip_tags($this->folio_factura_venta));
        } else {
            $validador = false;
        }

        $stmt->bindParam(1, $this->folio_factura_venta);

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

    public function update_Factura_Venta()
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE factura_venta SET nombre_contribuyente =:nombre_contribuyente,rut_contribuyente =:rut_contribuyente,total_factura=:total_factura 
        WHERE folio_factura_venta = :folio_factura_venta";
        $stmt = $this->conn->prepare($query);
        
        if (empty(htmlspecialchars(strip_tags($this->folio_factura_venta)))) {
            $validador = false;
        } else {
            if (is_numeric(htmlspecialchars(strip_tags($this->folio_factura_venta)))) {
                if (htmlspecialchars(strip_tags($this->folio_factura_venta)) > 0) {
                    //poner mensaje en validador de que solo se aceptan n° positivos
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        }
        if (htmlspecialchars(strip_tags($this->nombre_contribuyente)) == "") {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->rut_contribuyente)) == "") {
            $validador = false;
        }

        if (empty(htmlspecialchars(strip_tags($this->total_factura)))) {
            $validador = false;
        } else {
            if (is_numeric(htmlspecialchars(strip_tags($this->total_factura)))) {
                if (htmlspecialchars(strip_tags($this->total_factura)) > 0) {
                    //poner mensaje en validador de que solo se aceptan n° positivos
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        }
        // Bind Data
        if ($validador == true) {
            $stmt->bindParam(':folio_factura_venta', $this->folio_factura_venta);
            $stmt->bindParam(':nombre_contribuyente', $this->nombre_contribuyente);
            $stmt->bindParam(':rut_contribuyente', $this->rut_contribuyente);
            $stmt->bindParam(':total_factura', $this->total_factura);
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
    public function Validador_nombre_contribuyente($nombre_contribuyente)
    {
        if ($nombre_contribuyente == "") {
            return "Falta el nombre del contribuyente";
        }else {
            return "";
        } 
    }
    public function Validador_folio_factura_venta($folio_factura_venta)
    {
        if ($folio_factura_venta == "") {
            return "Falta el total de la factura venta";
        }else {
            if (is_numeric($folio_factura_venta)) {
                if (!$folio_factura_venta>0) {
                    return "Ingrese solo valores positivos";
                }else {
                    return "";
                }
            }else {
                return "Ingrese solo numeros";
            }
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

    public function buscar_folio_factura_venta($folio_factura_venta)
    {
        $query = "SELECT folio_factura_venta FROM factura_venta WHERE folio_factura_venta  like '%" . $folio_factura_venta . "%'";
        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $folio_comparar = $row['folio_factura_venta'];

        if ($folio_comparar == $folio_factura_venta) {
            return false;
        } else {
            return true;
        }
    }



}




?>