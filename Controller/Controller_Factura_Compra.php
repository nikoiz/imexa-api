<?php

class Controller_Factura_Compra
{
    private $conn;

    public $folio_factura;
    public $total_factura;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function Read_Factura_Compra()
    {
        $query = "SELECT * from factura_compra";
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

    public function Read_single_Factura_Compra()
    {
        $query = "SELECT * FROM factura_compra WHERE folio_factura = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->folio_factura);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->folio_factura=$row['folio_factura'];
        $this->total_factura=$row['total_factura'];

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }

    public function create_Factura_Compra()
    {
        $validador = true;
        $query = 'INSERT INTO factura_compra 
        SET 
            
            folio_factura = :folio_factura,
            total_factura = :total_factura';

        $stmt = $this->conn->prepare($query);


        if (empty(htmlspecialchars(strip_tags($this->folio_factura)))) {
            $validador = false;
        }else {
            if (is_numeric(htmlspecialchars(strip_tags($this->folio_factura)))) {
                if (htmlspecialchars(strip_tags($this->folio_factura))>0) {
                    //poner mensaje en validador de que solo se aceptan n° positivos
                }else {
                    $validador = false;
                }
            }else {
                $validador = false;
            }
        } 
        if (empty(htmlspecialchars(strip_tags($this->total_factura)))) {
            $validador = false;
        }else {
            if (is_numeric(htmlspecialchars(strip_tags($this->total_factura)))) {
                if (htmlspecialchars(strip_tags($this->total_factura))>0) {
                    //poner mensaje en validador de que solo se aceptan n° positivos
                }else {
                    $validador = false;
                }
            }else {
                $validador = false;
            }
        } 
        
        if ($validador == true) {
            $stmt->bindParam(':folio_factura', $this->folio_factura);
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

    public function delete_Factura_Compra()
    {
        $validador = true;
        $query = "DELETE FROM factura_compra WHERE folio_factura = ?";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->folio_factura)) != "") {
            $this->id_gastos = htmlspecialchars(strip_tags($this->folio_factura));
        } else {
            $validador = false;
        }

        $stmt->bindParam(1, $this->folio_factura);

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

    public function update_Factura_Compra()
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE factura_compra SET total_factura =:total_factura WHERE folio_factura = :folio_factura";
        $stmt = $this->conn->prepare($query);
        if (htmlspecialchars(strip_tags($this->folio_factura)) == "") {
            $validador = false;
        }else {
            if (is_numeric(htmlspecialchars(strip_tags($this->folio_factura)))) {
                if (!htmlspecialchars(strip_tags($this->folio_factura))>=1) {
                    $validador = false;
                }
            }else {
                $validador = false;
            }
        }
        if (empty(htmlspecialchars(strip_tags($this->total_factura)))) {
            $validador = false;
        }else {
            if (is_numeric(htmlspecialchars(strip_tags($this->total_factura)))) {
                if (htmlspecialchars(strip_tags($this->total_factura))>0) {
                    //poner mensaje en validador de que solo se aceptan n° positivos
                }else {
                    $validador = false;
                }
            }else {
                $validador = false;
            }
        } 
        // Bind Data
        if ($validador == true) {
            $stmt->bindParam(':folio_factura', $this->folio_factura);
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
    function buscar_folio_factura($folio_factura){
        $query = "SELECT folio_factura FROM factura_compra WHERE folio_factura = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $folio_factura);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        
        $numero_comparar= $row['folio_factura'];

                if ($numero_comparar==$folio_factura) {
                    return false;
                }else {
                    return true;
                }          
    }
    public function Validador_total_factura($total_factura)
    {
        if ($total_factura == "") {
            return "Falta el total de la factura";
        }else {
            if (is_numeric($total_factura)) {
                if (!$total_factura>0) {
                    return "Ingrese solo valores positivos";
                }else {
                    return "";
                }
            }else {
                return "Ingrese solo numeros";
            }
        } 
    }
}



?>