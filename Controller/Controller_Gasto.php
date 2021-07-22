<?php

class Controller_Gasto
{

    private $conn;

    public $id_gastos;
    public $descripcion_gastos;
    public $valor_gastos;
    public $estado;
    public $fecha; //ponner en todas
    public $id_bodega;
    public $nombre_bodega;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function Read_Gasto()
    {
        $query = "SELECT id_gastos,descripcion_gastos,valor_gastos,estado,fecha,nombre_bodega, gastos.id_bodega FROM gastos inner JOIN bodega on bodega.id_bodega=gastos.id_bodega";
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

    public function Read_single_gasto()
    {
        $p = new controller_bodega($this->conn);
        $query = "SELECT id_gastos,descripcion_gastos,valor_gastos,estado,fecha,nombre_bodega, gastos.id_bodega FROM gastos inner JOIN bodega on bodega.id_bodega=gastos.id_gastos WHERE id_gastos = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->id_gastos);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id_bodega = $row['id_bodega'];
        $this->estado = $row['estado'];
        $this->valor_gastos = $row['valor_gastos'];
        $this->descripcion_gastos = $row['descripcion_gastos'];
        $this->fecha = $row['fecha'];
        $this->id_gastos = $row['id_gastos'];
        $this->nombre_bodega = $row['nombre_bodega'];


        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    }

    public function create_gasto()
    {
        $validador = true;
        $query = 'INSERT INTO gastos 
        SET 
            
            id_bodega = :id_bodega,
            estado = :estado,
            valor_gastos = :valor_gastos,
            descripcion_gastos = :descripcion_gastos,
            fecha = :fecha';

        $stmt = $this->conn->prepare($query);

        if (!empty(htmlspecialchars(strip_tags($this->fecha)))) {
            $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        } else {
            $validador = false;
        }
        if (empty(htmlspecialchars(strip_tags($this->descripcion_gastos)))) {
            $validador = false;
        }else {
            $this->descripcion_gastos = htmlspecialchars(strip_tags($this->descripcion_gastos));
        }
        if (empty(htmlspecialchars(strip_tags($this->valor_gastos)))) {
            $validador = false;
        }else {
            $this->valor_gastos = htmlspecialchars(strip_tags($this->valor_gastos));
        }
        if (empty(htmlspecialchars(strip_tags($this->estado)))) {
            $validador = false;
        }else {
            $this->estado = htmlspecialchars(strip_tags($this->estado));
        }

        if (empty(htmlspecialchars(strip_tags($this->id_bodega)))) {
            $validador = false;
        } else {
            if (!is_numeric(htmlspecialchars(strip_tags($this->id_bodega)))) {
                $validador = false;
            }else {
                $this->id_bodega = htmlspecialchars(strip_tags($this->id_bodega));
            }
        }
        if ($validador == true) {
            $stmt->bindParam(':id_bodega', $this->id_bodega);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':valor_gastos', $this->valor_gastos);
            $stmt->bindParam(':descripcion_gastos', $this->descripcion_gastos);
            $stmt->bindParam(':fecha', $this->fecha);
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

    public function delete_single_gasto()
    {
        $validador = true;
        $query = "DELETE FROM gastos WHERE id_gastos = ?";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->id_gastos)) != "") {
            $this->id_gastos = htmlspecialchars(strip_tags($this->id_gastos));
        } else {
            $validador = false;
        }

        $stmt->bindParam(1, $this->id_gastos);

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

    public function update_gasto()
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE gastos SET estado =:estado WHERE id_gastos = :id_gastos";
        $stmt = $this->conn->prepare($query);

        if (empty(htmlspecialchars(strip_tags($this->estado)))) {
            $validador = false;
        }else {
            $this->estado = htmlspecialchars(strip_tags($this->estado));
        }
        if (empty(htmlspecialchars(strip_tags($this->id_gastos)))) {
            $validador = false;
        } else {
            if (!is_numeric(htmlspecialchars(strip_tags($this->id_gastos)))) {
                $validador = false;
            }else {
                $this->id_gastos = htmlspecialchars(strip_tags($this->id_gastos));
            }
        }
        // Bind Data
        if ($validador == true) {
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':id_gastos', $this->id_gastos);
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

    public function Validador_estado($estado)
    {
        if ($estado == "") {
            return false;
        } else {
            return true;
        }
    }
    public function Validador_valor_gastos($valor_gastos)
    {
        if ($valor_gastos == "") {
            return "Ingrese un valor para el gasto";
        } else {
            if (is_numeric($valor_gastos)) {
                if (!$valor_gastos >= 1) {
                    return "Ingrese solo valores positivos";
                }
            } else {
                return "Ingrese solo numeros";
            }
        }
    }
    public function Validador_descripcion_gastos($descripcion_gastos)
    {
        if ($descripcion_gastos == "") {
            return false;
        } else {
            return true;
        }
    }
    public function Validador_bodega_id_bodega($bodega_id)
    {
        if ($bodega_id == "") {
            return "Falta Id De la bodega";
        } else {
            if (is_numeric($bodega_id)) {
                if (!$bodega_id > 0) {
                    return "Ingrese solo valores positivos";
                } else {
                    return "";
                }
            } else {
                return "Ingrese solo numeros";
            }
        }
    }

    public function Validador_id_gastos()
    {
        if (htmlspecialchars(strip_tags($this->id_gastos)) == "") {
            return false;
        } else {
            if (is_numeric(htmlspecialchars(strip_tags($this->id_gastos)))) {
                if (!htmlspecialchars(strip_tags($this->id_gastos)) >= 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
    }
    function buscar_id_gastos($id_gastos)
    {
        $query = "SELECT id_gastos FROM gastos WHERE id_gastos = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_gastos);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_gastos'];

        if ($numero_comparar == $id_gastos) {
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
    public function delete_single_gasto_por_bodega($id_bodega)
    {
        $validador = true;
        $query = "DELETE FROM gastos WHERE id_bodega = ?";
        $stmt = $this->conn->prepare($query);


        $stmt->bindParam(1, $id_bodega);

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
    public function create_gasto_desde_bodega($id_bodega,$estado,$descripcion_gastos,$fecha,$valor_gastos=0)
    {
        $validador = true;
        $query = 'INSERT INTO gastos 
        SET 
            
            id_bodega = :id_bodega,
            estado = :estado,
            valor_gastos = :valor_gastos,
            descripcion_gastos = :descripcion_gastos,
            fecha = :fecha';

        $stmt = $this->conn->prepare($query);

        if (!empty($fecha)) {
            $this->fecha = $fecha;
        } else {
            $validador = false;
        }
        if (empty($descripcion_gastos)) {
            $validador = false;
        }else {
            $this->descripcion_gastos = $descripcion_gastos;
        }
        if (empty($valor_gastos)) {
            $validador = false;
        }else {
            $this->valor_gastos = $valor_gastos;
        }
        if (empty($estado)) {
            $validador = false;
        }else {
            $this->estado = $estado;
        }

        if (empty($id_bodega)) {
            $validador = false;
        } else {
            if (!is_numeric($id_bodega)) {
                $validador = false;
            }else {
                $this->id_bodega = $id_bodega;
            }
        }
        if ($validador == true) {
            $stmt->bindParam(':id_bodega', $this->id_bodega);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':valor_gastos', $this->valor_gastos);
            $stmt->bindParam(':descripcion_gastos', $this->descripcion_gastos);
            $stmt->bindParam(':fecha', $this->fecha);
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
    
    public function update_gasto_por_bodega($id_bodega,$valor_gastos)
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE gastos SET  valor_gastos= :valor_gastos WHERE id_bodega = :id_bodega";
        $stmt = $this->conn->prepare($query);

        if (empty($id_bodega)) {
            $validador = false;
        } else {
            if (!is_numeric($id_bodega)) {
                $validador = false;
            }else {
                $this->id_bodega = $id_bodega;
            }
        }
        if (empty($valor_gastos)) {
            $validador = false;
        }else {
            $this->valor_gastos = $valor_gastos;
        }
        // Bind Data
        if ($validador == true) {
            $stmt->bindParam(':id_bodega', $this->id_bodega);
            $stmt->bindParam(':valor_gastos', $this->valor_gastos);
   

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
}
