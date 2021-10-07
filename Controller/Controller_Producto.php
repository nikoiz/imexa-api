<?php
class Controller_Producto
{
    private $conn;


    // Post Properties
    public $id_producto;
    public $nombre_producto;
    public $valor_producto;
    public $cantidad_total;




    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function Read_producto() //tirar a produc_has_bodega grupby nombre_producto 
    {
        $query = "SELECT * FROM producto GROUP BY nombre_producto";
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
    public function Read_single()
    {
        $query = "SELECT producto.id_producto,nombre_producto,valor_producto,bodega_has_producto.cantidad_total from producto INNER join bodega_has_producto on producto.id_producto=bodega_has_producto.id_producto WHERE id_producto = ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->id_producto);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->nombre_producto = $row['nombre_producto'];
        $this->valor_producto = $row['valor_producto'];
        $this->id_producto = $row['producto.id_producto'];
        $this->cantidad_total = $row['bodega_has_producto.cantidad_total'];

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }

    public function create_producto($nombre_producto,$valor_producto)
    {
        $validador = true;
        $query = 'INSERT INTO producto 
        SET 
            
            nombre_producto = :nombre_producto,
            valor_producto = :valor_producto';

        $stmt = $this->conn->prepare($query);
        if ($valor_producto != "") {
            if (is_numeric($valor_producto)) {
                if ($valor_producto >= 0) {
                    $this->valor_producto = $valor_producto;
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        } else {
            $validador = false;
        }

        if (!empty($nombre_producto)) {
            $this->nombre_producto = $nombre_producto;
        } else {
            $validador = false;
        }

        $stmt->bindParam(':nombre_producto', $this->nombre_producto);
        $stmt->bindParam(':valor_producto', $this->valor_producto);

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
    public function create_producto_factura()
    {
        $validador = true;
        $query = 'INSERT INTO producto 
        SET 
            id_producto =:id_producto,
            nombre_producto = :nombre_producto,
            valor_producto = :valor_producto';

        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->id_producto)) != "") {
            if (is_numeric(htmlspecialchars(strip_tags($this->id_producto)))) {
                if (htmlspecialchars(strip_tags($this->id_producto)) >= 0) {
                    $this->id_producto = htmlspecialchars(strip_tags($this->id_producto));
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        } else {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->valor_producto)) != "") {
            if (is_numeric(htmlspecialchars(strip_tags($this->valor_producto)))) {
                if (htmlspecialchars(strip_tags($this->valor_producto)) >= 0) {
                    $this->valor_producto = htmlspecialchars(strip_tags($this->valor_producto));
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        } else {
            $validador = false;
        }

        if (!empty(htmlspecialchars(strip_tags($this->nombre_producto)))) {
            $this->nombre_producto = htmlspecialchars(strip_tags($this->nombre_producto));
        } else {
            $validador = false;
        }

        $stmt->bindParam(':id_producto', $this->id_producto);
        $stmt->bindParam(':nombre_producto', $this->nombre_producto);
        $stmt->bindParam(':valor_producto', $this->valor_producto);

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

    public function delete_single_producto()
    {
        $validador = true;
        $query = "DELETE FROM producto WHERE id_producto = ?";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->id_producto)) != "") {
            $this->id_producto = htmlspecialchars(strip_tags($this->id_producto));
        } else {
            $validador = false;
        }

        $stmt->bindParam(1, $this->id_producto);

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

    public function update_producto()
    {
        $validador = true;
        $query = "UPDATE producto SET valor_producto =:valor_producto, nombre_producto= :nombre_producto  WHERE id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->valor_producto)) != "") {
            if (is_numeric(htmlspecialchars(strip_tags($this->valor_producto)))) {
                if (htmlspecialchars(strip_tags($this->valor_producto)) >= 0) {
                    $this->valor_producto = htmlspecialchars(strip_tags($this->valor_producto));
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        } else {
            $validador = false;
        }

        if (!empty(htmlspecialchars(strip_tags($this->nombre_producto)))) {
            $this->nombre_producto = htmlspecialchars(strip_tags($this->nombre_producto));
        } else {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->id_producto)) != "") {
            if (is_numeric(htmlspecialchars(strip_tags($this->id_producto)))) {
                if (htmlspecialchars(strip_tags($this->id_producto)) >= 0) {
                    $this->id_producto = htmlspecialchars(strip_tags($this->id_producto));
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        } else {
            $validador = false;
        }



        if ($validador == true) {
            $stmt->bindParam(':valor_producto', $this->valor_producto);
            $stmt->bindParam(':nombre_producto', $this->nombre_producto);
            $stmt->bindParam(':id_producto', $this->id_producto);
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
    function buscar_id_producto($id_producto)
    {
        $query = "SELECT id_producto FROM producto WHERE id_producto = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_producto);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_producto'];

        if ($numero_comparar == $id_producto) {
            return false;
        } else {
            return true;
        }
    }
    function buscar_id_producto_por_nombre($nombre)
    {
        $query = "SELECT id_producto FROM producto WHERE nombre_producto = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $nombre);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_producto'];

        if ($numero_comparar == null) {
            return null;
        } else {
            return $numero_comparar;
        }
    }
    

    public function buscar_nombre_producto($nombre_producto)
    {
        $query = "SELECT nombre_producto FROM producto WHERE nombre_producto  like '%" . $nombre_producto . "%'";
        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $nombre_comparar = $row['nombre_producto'];

        if ($nombre_comparar == $nombre_producto) {
            return false;
        } else {
            return true;
        }
    }
    public function Obtener_valor_producto($id_producto)
    {
        $query = "SELECT valor_producto FROM producto WHERE id_producto = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_producto);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_comparar = $row['valor_producto'];

        if ($numero_comparar != null) {
            return $numero_comparar;
        } else {
            return null;
        }
    }
    public function Obtener_nombre_producto($id_producto)
    {
        $query = "SELECT nombre_producto FROM producto WHERE id_producto = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_producto);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_comparar = $row['nombre_producto'];

        if ($numero_comparar != null) {
            return $numero_comparar;
        } else {
            return null;
        }
    }

    public function validador_nombre($nombre)
    {
        if (empty($nombre)) {
            return "ingrese nombre";
        } else {
            return "";
        }
    }
    public function Validador_valor_producto($valor_producto)
    {
        if (empty($valor_producto)) {
            return "ingrese un valor producto";
        } else {
            if (is_numeric($valor_producto)) {
                if (!$valor_producto > 0) {
                    return "ingrese un numero mayor a 0";
                } else {
                    return "";
                }
            } else {
                return "solo se aceptan numeros";
            }
        }
    }
    //buscar_el_ultimo_id
    function obtener_el_ultimo_id()// por medio del ultimo id se establecera el poder sumar el ultimo 
    {
        $query = "SELECT MAX(id_producto)+1 AS id_producto FROM producto";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_comparar = $row['id_producto'];
        if ($numero_comparar != null) {
            return $numero_comparar;
        } else {
            return false;
        }
    }
    function obtener_el_ultimo_id_sumado()// por medio del ultimo id se establecera el poder sumar el ultimo 
    {
        $query = "SELECT MAX(id_producto) AS id_producto FROM producto";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_comparar = $row['id_producto'];
        $numero_comparar += 1;
        if ($numero_comparar != null) {
            return $numero_comparar;
        } else {
            return false;
        }
    }
    function buscar_random_id($id_random)// por medio del ultimo id se establecera el poder sumar el ultimo 
    {
        $query = "SELECT  id_producto FROM producto WHERE id_producto =?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_random);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_comparar = $row['id_producto'];

        printf("el id que se obtiene por medio de comprobar el id si existe:", $numero_comparar);
        if ($numero_comparar == null) {
            return true;
        } else {
            return false;
        }
    }
}
