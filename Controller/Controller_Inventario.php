<?php 
class Controller_Inventario{
    private $conn;

    public $id_inventario;
    public $fecha_inventario;
    public $responsable_inventario;
    public $cantidad_inventariada;
    public $valor_inventario;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function Read_inventario()
    {
        $query = "SELECT * from inventario";
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
    public function Read_single_inventario()
    {
        $query = "SELECT * FROM inventario WHERE id_inventario = ?";
        $stmt = $this->conn->prepare($query);
         //Bind id
         $stmt->bindParam(1, $this->id_inventario);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);

         // set properties
         $this->valor_inventario = $row['valor_inventario'];
         $this->cantidad_inventariada = $row['cantidad_inventariada'];
         $this->responsable_inventario = $row['responsable_inventario'];
         $this->fecha_inventario= $row['fecha_inventario'];
         $this->id_inventario = $row['id_inventario'];

         try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n",$stmt->error);
            return false;
        }
    }

    public function create_invantario()
    {
        $validador=true;
        $query = 'INSERT INTO inventario 
        SET 
            
            fecha_inventario = :fecha_inventario,
            responsable_inventario = :responsable_inventario,
            cantidad_inventariada = :cantidad_inventariada,
            valor_inventario = :valor_inventario';

        $stmt = $this->conn->prepare($query);
        
        if (!empty(htmlspecialchars(strip_tags($this->fecha_inventario)))) {
            $this->fecha_inventario=htmlspecialchars(strip_tags($this->fecha_inventario));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->responsable_inventario)))) {
            $this->responsable_inventario=htmlspecialchars(strip_tags($this->responsable_inventario));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->cantidad_inventariada)))) {
            if (is_numeric(htmlspecialchars(strip_tags($this->cantidad_inventariada)))) {
                if (htmlspecialchars(strip_tags($this->cantidad_inventariada))>=1) {
                    $this->cantidad_inventariada=htmlspecialchars(strip_tags($this->cantidad_inventariada));
                }else {
                    $validador=false;
                }  
            }else {
                $validador=false;
            }  
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->valor_inventario)))) {
            if (is_numeric(htmlspecialchars(strip_tags($this->valor_inventario)))) {
                if (htmlspecialchars(strip_tags($this->valor_inventario))>=1) {
                    $this->valor_inventario=htmlspecialchars(strip_tags($this->valor_inventario));
                }else {
                    $validador=false;
                }  
            }else {
                $validador=false;
            }  
        }else {
            $validador=false;
        }


        $stmt-> bindParam(':fecha_inventario',$this->fecha_inventario);
        $stmt-> bindParam(':responsable_inventario',$this->responsable_inventario);
        $stmt-> bindParam(':cantidad_inventariada',$this->cantidad_inventariada);
        $stmt-> bindParam(':valor_inventario',$this->valor_inventario);

        if ($validador==true) {
            try {
                if ($stmt->execute()) {
                    return true;//retorna y despues se debe crear el producto inventario
                }
            } catch (Exception $e) {
                printf("Error: %s.\n",$stmt->error);
               
                return false;
            }
        }else {
            return false;
        }

    }

    public function delete_single_inventario()
    {
        $validador = true;
        $query = "DELETE FROM inventario WHERE id_inventario = ?";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->id_inventario)) != "") {
            $this->id_inventario = htmlspecialchars(strip_tags($this->id_inventario));
        } else {
            $validador = false;
        }

        $stmt->bindParam(1, $this->id_inventario);

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

    public function update_inventario()
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE inventario
        SET       
        fecha_inventario = :fecha_inventario,
        responsable_inventario = :responsable_inventario,
        cantidad_inventariada = :cantidad_inventariada,
        valor_inventario = :valor_inventario
        WHERE id_inventario = :id_inventario";

        $stmt = $this->conn->prepare($query);
        if (!empty(htmlspecialchars(strip_tags($this->id_inventario)))) {
            $this->id_inventario=htmlspecialchars(strip_tags($this->id_inventario));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->fecha_inventario)))) {
            $this->fecha_inventario=htmlspecialchars(strip_tags($this->fecha_inventario));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->responsable_inventario)))) {
            $this->responsable_inventario=htmlspecialchars(strip_tags($this->responsable_inventario));
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->cantidad_inventariada)))) {
            if (is_numeric(htmlspecialchars(strip_tags($this->cantidad_inventariada)))) {
                if (htmlspecialchars(strip_tags($this->cantidad_inventariada))>=1) {
                    $this->cantidad_inventariada=htmlspecialchars(strip_tags($this->cantidad_inventariada));
                }else {
                    $validador=false;
                }  
            }else {
                $validador=false;
            }  
        }else {
            $validador=false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->valor_inventario)))) {
            if (is_numeric(htmlspecialchars(strip_tags($this->valor_inventario)))) {
                if (htmlspecialchars(strip_tags($this->valor_inventario))>=1) {
                    $this->valor_inventario=htmlspecialchars(strip_tags($this->valor_inventario));
                }else {
                    $validador=false;
                }  
            }else {
                $validador=false;
            }  
        }else {
            $validador=false;
        }
        // Bind Data
        if ($validador == true) {
            $stmt->bindParam(':id_inventario', $this->id_inventario);
            $stmt->bindParam(':fecha_inventario', $this->fecha_inventario);
            $stmt->bindParam(':responsable_inventario', $this->responsable_inventario);
            $stmt->bindParam(':cantidad_inventariada', $this->cantidad_inventariada);
            $stmt->bindParam(':valor_inventario', $this->valor_inventario);
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
    public function Validacion_parametros($parma)
    {
        if ($parma==null) {
            return false;
        }else {
            return true;
        }
    }
    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function Busacar_id_inventario($tipo)
    {
        $query = "SELECT id_inventario FROM inventario WHERE id_inventario = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $tipo);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $comparar = $row['id_inventario'];

        if ($comparar == $tipo) {
            return false;
        } else {
            return true;
        }
    }
}





?>