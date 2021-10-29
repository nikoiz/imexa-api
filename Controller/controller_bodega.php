<?php

class controller_bodega
{
    // DB stuf
    private $conn;

    // Post Properties
    public $id_bodega;
    public $numero_bodega;
    public $nombre_bodega;

    public $valor_gastos;//total del inventario

    public $total_gasto;//de los gastos

    //producto
    public $nombre_producto;

    // Constructor with DB

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        //$query = "SELECT id, descripcion FROM text";
        $query = "SELECT * from bodega";
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
    public function buscar_bodeganombre_producto()
    {
        $query = "SELECT nombre_bodega,bodega.id_bodega as id_bodega FROM bodega INNER JOIN detalle_inventario
                  ON bodega.id_bodega = detalle_inventario.id_bodega WHERE nombre_producto = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->nombre_producto);
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }

    public function read_single()
    {
        $query = "SELECT * FROM bodega WHERE id_bodega = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $this->id_bodega);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->nombre_bodega = $row['nombre_bodega'];
        $this->numero_bodega = $row['numero_bodega'];
        $this->id_bodega = $row['id_bodega'];

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }

        /*
        if ($stmt->execute()) {  
            return $stmt;
        }
        */
    }

    // Create post      
    public function create()
    { // //id_bodega = :id_bodega,
        $validador = true;
        $query = 'INSERT INTO bodega 
        SET 
            
            numero_bodega = :numero_bodega,
            nombre_bodega = :nombre_bodega';

        $stmt = $this->conn->prepare($query);

        //$this->id_bodega = htmlspecialchars(strip_tags($this->id_bodega));
        if (htmlspecialchars(strip_tags($this->numero_bodega)) != "") { //campo vasio
            if (is_numeric(htmlspecialchars(strip_tags($this->numero_bodega)))) { // si es numerico
                //que no se repita en la base de datos
                $this->numero_bodega = htmlspecialchars(strip_tags($this->numero_bodega));
            } else {
                $validador = false;
            }
        } else {
            $validador = false;
        }
        //utilizar medio para validar metodo empty
        if (!empty(htmlspecialchars(strip_tags($this->nombre_bodega)))) {
            $this->nombre_bodega = htmlspecialchars(strip_tags($this->nombre_bodega));
        } else {
            $validador = false;
        }


        // Bind Data

        //$stmt-> bindParam(':id_bodega', $this->id_bodega);
        $stmt->bindParam(':numero_bodega', $this->numero_bodega);
        $stmt->bindParam(':nombre_bodega', $this->nombre_bodega);

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


    function buscar_numero($numero_bodega)
    {
        $query = "SELECT numero_bodega FROM bodega WHERE numero_bodega = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $numero_bodega);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        if ($row == null) {
            return true;
        } else {
            $numero_comparar = $row['numero_bodega'];
            if ($numero_comparar == $numero_bodega) {
                return false;
            } else {
                return true;
            }
        }
    }
    function buscar_numero_comprobar_datos($numero_bodega, $nombre_bodega)
    {
        $validador = true;
        $query = "SELECT numero_bodega,nombre_bodega FROM bodega WHERE numero_bodega = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $numero_bodega);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['numero_bodega'];
        $nombre_bodega_comparar = $row['nombre_bodega'];
        if ($numero_comparar == $numero_bodega) {
            $validador == false;
        }
        if (strcmp($nombre_bodega, $nombre_bodega_comparar) == 0) {
            $validador = true;
        }

        if ($validador == true) {
            return true;
        } else {
            return false;
        }
    }


    public function delete_single()
    {
        $validador = true;
        $query = "DELETE FROM bodega WHERE id_bodega = ?";

        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->id_bodega)) != "") {
            $this->id_bodega = htmlspecialchars(strip_tags($this->id_bodega));
        } else {
            $validador = false;
        }

        $stmt->bindParam(1, $this->id_bodega);

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
    public function delete_single_detalle_inventario()
    {
        $validador = true;
        $query = "DELETE FROM bodega WHERE id_bodega = ?";

        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->id_bodega)) != "") {
            $this->id_bodega = htmlspecialchars(strip_tags($this->id_bodega));
        } else {
            $validador = false;
        }

        $stmt->bindParam(1, $this->id_bodega);

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

    public function update()
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE bodega SET numero_bodega =:numero_bodega, nombre_bodega= :nombre_bodega  WHERE id_bodega = :id_bodega";
        $stmt = $this->conn->prepare($query);


        if (htmlspecialchars(strip_tags($this->numero_bodega)) != "") {
            $this->numero_bodega = htmlspecialchars(strip_tags($this->numero_bodega));
        } else {
            $validador = false;
        }

        if (htmlspecialchars(strip_tags($this->nombre_bodega)) == empty("")) {
            $this->nombre_bodega = htmlspecialchars(strip_tags($this->nombre_bodega));
        } else {
            $validador = false;
        }

        if (htmlspecialchars(strip_tags($this->id_bodega)) != "") {
            $this->id_bodega = htmlspecialchars(strip_tags($this->id_bodega));
        } else {
            $validador = false;
        }

        // Bind Data

        if ($validador == true) {
            $stmt->bindParam(':numero_bodega', $this->numero_bodega);
            $stmt->bindParam(':nombre_bodega', $this->nombre_bodega);
            $stmt->bindParam(':id_bodega', $this->id_bodega);

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
    function buscar_id_bodega($id_bodega)
    {
        $query = "SELECT id_bodega FROM bodega WHERE id_bodega = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_bodega);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_bodega'];

        if ($numero_comparar == $id_bodega) {
            return false;
        } else {
            return true;
        }
    }
    function buscar_referncias_tablas($id_bodega)
    {
        $query = "SELECT id_bodega FROM gastos WHERE id_bodega=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_bodega);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_comparar = $row['id_bodega'];
        
        if ($numero_comparar == $id_bodega) {
            return false;
        } else {
            return true;
        }
    }

    function buscar_el_ultimo_id()
    {
        $query = "SELECT MAX(id_bodega) AS id_bodega FROM bodega";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_comparar = $row['id_bodega'];

        if ($numero_comparar != null) {
            return $numero_comparar;
        } else {
            return false;
        }
    }
    function Obtener_total_gasto_only($id_bodega)
    {
        $query = "SELECT SUM(detalle_inventario.cantidad_producto * detalle_inventario.valor) as total FROM `detalle_inventario`
        inner JOIN bodega_has_producto ON detalle_inventario.id_producto = bodega_has_producto.id_producto
        INNER JOIN producto ON bodega_has_producto.id_producto =producto.id_producto
        where detalle_inventario.id_bodega=?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_bodega);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['total'];

        if ($numero_comparar !=null) {
            return $numero_comparar;
        } else {
            return null;
        }
    }
    function Obtener_total_gasto_bodega($id_bodega)
    {
        $query = "SELECT SUM(gastos.valor_gastos) as total_gasto ,bodega.nombre_bodega FROM `gastos` INNER JOIN bodega ON gastos.id_bodega=bodega.id_bodega WHERE bodega.id_bodega=?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_bodega);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['total_gasto'];

        if ($numero_comparar !=null) {
            return $numero_comparar;
        } else {
            return null;
        }
    }
}
