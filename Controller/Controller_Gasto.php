<?php 

class Controller_Gasto
{

    private $conn;

    public $id_gastos;
    public $descripcion_gastos;
    public $valor_gastos;
    public $estado;
    public $bodega_id_bodega;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function Read_Gasto()
    {
        $query = "SELECT * from gastos";
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
        $query = "SELECT * FROM gastos WHERE id_gastos = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->id_gastos);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->bodega_id_bodega=$row['bodega_id_bodega'];
        $this->estado=$row['estado'];
        $this->valor_gastos= $row['valor_gastos'];
        $this->descripcion_gastos = $row['descripcion_gastos'];
        $this->id_gastos = $row['id_gastos'];

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
            
            bodega_id_bodega = :bodega_id_bodega,
            estado = :estado,
            valor_gastos = :valor_gastos,
            descripcion_gastos = :descripcion_gastos';

        $stmt = $this->conn->prepare($query);


        if (empty(htmlspecialchars(strip_tags($this->descripcion_gastos)))) {
            $validador = false;
        } 
        if (empty(htmlspecialchars(strip_tags($this->valor_gastos)))) {
            $validador = false;
        } 
        if (empty(htmlspecialchars(strip_tags($this->estado)))) {
            $validador = false;
        } 
        if (empty(htmlspecialchars(strip_tags($this->bodega_id_bodega)))) {
            $validador = false;
        }else {
            if(!is_numeric(htmlspecialchars(strip_tags($this->bodega_id_bodega)))){
                $validador = false;
            }
        }
        if ($validador == true) {
            $stmt->bindParam(':bodega_id_bodega', $this->bodega_id_bodega);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':valor_gastos', $this->valor_gastos);
            $stmt->bindParam(':descripcion_gastos', $this->descripcion_gastos);
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
        $query = "UPDATE gastos SET descripcion_gastos =:descripcion_gastos, valor_gastos= :valor_gastos,estado =:estado,bodega_id_bodega =:bodega_id_bodega  WHERE id_gastos = :id_gastos";
        $stmt = $this->conn->prepare($query);
        if (htmlspecialchars(strip_tags($this->id_gastos)) == "") {
            $validador = false;
        }else {
            if (is_numeric(htmlspecialchars(strip_tags($this->id_gastos)))) {
                if (!htmlspecialchars(strip_tags($this->id_gastos))>=1) {
                    $validador = false;
                }
            }else {
                $validador = false;
            }
        }
        if (htmlspecialchars(strip_tags($this->descripcion_gastos)) == "") {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->valor_gastos)) == "") {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->estado)) == "") {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->bodega_id_bodega)) == "") {
            $validador = false;
        }else {
            if (is_numeric(htmlspecialchars(strip_tags($this->bodega_id_bodega)))) {
                if (!htmlspecialchars(strip_tags($this->bodega_id_bodega))>=1) {
                    $validador = false;
                }
            }else {
                $validador = false;
            }
        } 
        // Bind Data
        if ($validador == true) {
            $stmt->bindParam(':bodega_id_bodega', $this->bodega_id_bodega);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':valor_gastos', $this->valor_gastos);
            $stmt->bindParam(':descripcion_gastos', $this->descripcion_gastos);
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
        if ($estado=="") {
            return false;
        }else {
            return true;
        }
    }
    public function Validador_valor_gastos($valor_gastos)
    {
        if ($valor_gastos == "") {
            return "Ingrese un valor para el gasto";
        }else{
            if (is_numeric($valor_gastos)) {
                if (!$valor_gastos>=1) {
                    return "Ingrese solo valores positivos";
                }
            }else {
                return "Ingrese solo numeros";
            }
        }
    }
    public function Validador_descripcion_gastos($descripcion_gastos)
    {
        if ($descripcion_gastos == "") {
            return false;
        }else {
            return true;
        }
    }
    public function Validador_bodega_id_bodega($bodega_id)
    {
        if ($bodega_id == "") {
            return "Falta Id De la bodega";
        }else {
            if (is_numeric($bodega_id)) {
                if (!$bodega_id>0) {
                    return "Ingrese solo valores positivos";
                }else {
                    return "";
                }
            }else {
                return "Ingrese solo numeros";
            }
        } 
    }
    
    public function Validador_id_gastos()
    {
        if (htmlspecialchars(strip_tags($this->id_gastos)) == "") {
            return false;
        }else {
            if (is_numeric(htmlspecialchars(strip_tags($this->id_gastos)))) {
                if (!htmlspecialchars(strip_tags($this->id_gastos))>=1) {
                    return false;
                }else {
                    return true;
                }
            }else {
                return false;
            }
        }
    }
    function buscar_id_gastos($id_gastos){
        $query = "SELECT id_gastos FROM gastos WHERE id_gastos = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_gastos);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        
        $numero_comparar= $row['id_gastos'];

                if ($numero_comparar==$id_gastos) {
                    return false;
                }else {
                    return true;
                }
                
    }
}
