<?php
class Controller_Dispositivo_peso{
    private $conn;

    //datos de la clase
    public $id_dispositivo;
    public $peso_dispositivo;
    public $unidad_de_medida;
    public $topico;
    public $id_detalle_inventario;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function Read_dispositivo()
    {
        $query = "SELECT * FROM dispositivo_peso";
        $stmt = $this->conn->prepare($query);

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }
    public function Read_single_dipositivo()
    {
        $query = "SELECT * FROM `dispositivo_peso` INNER JOIN detalle_inventario on dispositivo_peso.id_detalle_inventario = detalle_inventario.id_detalle_inventario
         where detalle_inventario.id_detalle_inventario= ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_detalle_inventario);
        //Bind id
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id_dispositivo = $row['id_dispositivo'];
        $this->peso_dispositivo = $row['peso_dispositivo'];
        $this->unidad_de_medida = $row['unidad_de_medida'];
        $this->topico = $row['topico'];
        $this->id_detalle_inventario = $row['id_detalle_inventario'];
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);
            return false;
        }
    }
    public function create_dispositivo()
    {
        $validador = true;

        if (empty(htmlspecialchars(strip_tags($this->peso_dispositivo)))) {
            $validador = false;
        } else {
            if (!is_numeric(htmlspecialchars(strip_tags($this->peso_dispositivo)))) {
                $validador = false;
            } else {
                $this->peso_dispositivo = htmlspecialchars(strip_tags($this->peso_dispositivo));
            }
        }
        if (!empty(htmlspecialchars(strip_tags($this->unidad_de_medida)))) {
            $this->unidad_de_medida = htmlspecialchars(strip_tags($this->unidad_de_medida));
        } else {
            $validador = false;
        }

        if (!empty(htmlspecialchars(strip_tags($this->topico)))) {
            $this->topico = htmlspecialchars(strip_tags($this->topico));
        } else {
            $validador = false;
        }

        if (!empty(htmlspecialchars(strip_tags($this->id_detalle_inventario)))) {
            $this->id_detalle_inventario = htmlspecialchars(strip_tags($this->id_detalle_inventario));
        } else {
            $validador = false;
        }


        if ($validador == true) {

            $query = "INSERT INTO dispositivo_peso 
        SET 
        peso_dispositivo = '" . $this->peso_dispositivo . "',
        unidad_de_medida = '" . $this->unidad_de_medida . "',
        topico = '" . $this->topico . "',
        id_detalle_inventario = '". $this->id_detalle_inventario ."'
        ";

            $stmt = $this->conn->prepare($query);

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
    public function update_dispositivo()
    {
        
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE abono SET    
        id_dispositivo = :id_dispositivo,
        peso_dispositivo = :peso_dispositivo,
        unidad_de_medida = :unidad_de_medida,
        topico = :topico,
        id_detalle_inventario = :id_detalle_inventario
          WHERE id_abono = :id_abono";
        $stmt = $this->conn->prepare($query);
        if (empty(htmlspecialchars(strip_tags($this->id_dispositivo)))) {
            $validador = false;
        }else {
            $this->id_dispositivo = htmlspecialchars(strip_tags($this->id_dispositivo));
        }
        if (empty(htmlspecialchars(strip_tags($this->peso_dispositivo)))) {
            $validador = false;
        }else {
            $this->peso_dispositivo = htmlspecialchars(strip_tags($this->peso_dispositivo));
        }
        if (empty(htmlspecialchars(strip_tags($this->peso_dispositivo)))) {
            $validador = false;
        } else {
            if (!is_numeric(htmlspecialchars(strip_tags($this->peso_dispositivo)))) {
                $validador = false;
            } else {
                $this->peso_dispositivo = htmlspecialchars(strip_tags($this->peso_dispositivo));
            }
        }
        if (!empty(htmlspecialchars(strip_tags($this->unidad_de_medida)))) {
            $this->unidad_de_medida = htmlspecialchars(strip_tags($this->unidad_de_medida));
        } else {
            $validador = false;
        }

        if (!empty(htmlspecialchars(strip_tags($this->topico)))) {
            $this->topico = htmlspecialchars(strip_tags($this->topico));
        } else {
            $validador = false;
        }

        if (!empty(htmlspecialchars(strip_tags($this->id_detalle_inventario)))) {
            $this->id_detalle_inventario = htmlspecialchars(strip_tags($this->id_detalle_inventario));
        } else {
            $validador = false;
        }

        // Bind Data
        if ($validador == true) {
            $stmt->bindParam(':id_dispositivo', $this->id_dispositivo);
            $stmt->bindParam(':peso_dispositivo', $this->peso_dispositivo);
            $stmt->bindParam(':unidad_de_medida', $this->fechunidad_de_medidaa_abono);
            $stmt->bindParam(':topico', $this->topico);
            $stmt->bindParam(':id_detalle_inventario', $this->id_detalle_inventario);
            
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
    public function delete_dispositivo()
    {
        $validador = true;
        $query = "DELETE FROM `dispositivo_peso` WHERE id_dispositivo = ?";
        $stmt = $this->conn->prepare($query);
        if (htmlspecialchars(strip_tags($this->id_dispositivo)) != "") {
            $this->id_dispositivo = htmlspecialchars(strip_tags($this->id_dispositivo));
        }else {
            $validador = false;
        }
        $stmt->bindParam(1, $this->id_dispositivo);

        if ($validador == true) {
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
    function buscar_id_dispositivo($id_dispositivo)
    {
        $query = "SELECT id_dispositivo FROM dispositivo_peso WHERE id_dispositivo = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_dispositivo);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_dispositivo'];

        if ($numero_comparar == $id_dispositivo) {
            return false;
        } else {
            return true;
        }
    }
    public function Validador_peso_dispositivo($peso_dispositivo)
    {
        if ($peso_dispositivo == "") {
            return "Ingrese un valor para el peso";
        } else {
            if (is_numeric($peso_dispositivo)) {
                if (!$peso_dispositivo >= 1) {
                    return "Ingrese solo valores positivos";
                }
            } else {
                return "Ingrese solo numeros";
            }
        }
    }
    public function Validador_unidad_de_medida($unidad_de_medida)
    {
        if ($unidad_de_medida == "") {
            return false;
        } else {
            return true;
        }
    }
    public function Validador_topico($topico)
    {
        if ($topico == "") {
            return false;
        } else {
            return true;
        }
    }
    public function Validador_id_detalle_inventario($id_detalle_inventario)
    {
        if ($id_detalle_inventario == "") {
            return "Falta Id del producto";
        } else {
            if (is_numeric($id_detalle_inventario)) {
                if (!$id_detalle_inventario > 0) {
                    return "Ingrese solo valores positivos";
                } else {
                    return "";
                }
            } else {
                return "Ingrese solo numeros";
            }
        }
    }
}

 ?>