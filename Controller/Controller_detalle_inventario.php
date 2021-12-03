<?php
class Controller_detalle_inventario
{
    private $conn;

    //datos de la clase
    public $id_detalle_inventario;
    public $nombre_producto;
    public $cantidad_producto;
    public $valor;
    public $fecha_inventario;
    public $id_inventario;
    public $id_bodega;
    public $id_producto;
    public $peso_unitario;

    //datos de la bodega
    public $nombre_bodega;
    public $numero_bodega;


    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function Read_single_detalle_invetario($nombre_producto)
    {
        $query = "SELECT detalle_inventario.nombre_producto as nombre_producto,detalle_inventario.cantidad_producto as cantidad_producto,
        detalle_inventario.valor as valor,bodega.nombre_bodega as nombre_bodega,bodega.numero_bodega as numero_bodega,detalle_inventario.peso_unitario as peso_unitario
        FROM `detalle_inventario`
        inner JOIN bodega_has_producto on detalle_inventario.id_producto = bodega_has_producto.id_producto 
        INNER JOIN producto on bodega_has_producto.id_producto=producto.id_producto 
        INNER JOIN bodega on bodega_has_producto.id_bodega=bodega.id_bodega
        WHERE detalle_inventario.nombre_producto= ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $nombre_producto);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        //$this->id_detalle_inventario = $row['id_detalle_inventario'];
        $this->nombre_producto = $row['nombre_producto'];
        $this->cantidad_producto = $row['cantidad_producto'];
        $this->valor = $row['valor'];
        $this->nombre_bodega = $row['nombre_bodega'];
        $this->numero_bodega = $row['numero_bodega'];
        $this->peso_unitario = $row['peso_unitario'];
        //$this->fecha_inventario = $row['fecha_inventario'];
        //$this->id_inventario = $row['id_inventario'];
        //$this->id_bodega = $row['id_bodega'];
        //$this->id_producto = $row['id_producto'];

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }
    public function Read_single_detalle_invetario_por_bodega($id_bodega)
    {
        $query = "SELECT detalle_inventario.nombre_producto as nombre_producto,detalle_inventario.cantidad_producto as cantidad_producto,
        detalle_inventario.valor as valor,bodega.nombre_bodega as nombre_bodega,bodega.numero_bodega as numero_bodega,
        detalle_inventario.id_detalle_inventario as id_detalle_inventario,detalle_inventario.peso_unitario
        FROM `detalle_inventario`
        inner JOIN bodega_has_producto on detalle_inventario.id_producto = bodega_has_producto.id_producto 
        INNER JOIN producto on bodega_has_producto.id_producto=producto.id_producto 
        INNER JOIN bodega on bodega_has_producto.id_bodega=bodega.id_bodega WHERE detalle_inventario.id_bodega=  ?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $id_bodega);
        $stmt->execute();
        


        /*
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->nombre_producto = $row['nombre_producto'];
        $this->cantidad_producto = $row['cantidad_producto'];
        $this->valor = $row['valor'];
        */
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }
    public function Read_single_detalle_invetario_nombre_prod()
    {
        $query = "SELECT * from detalle_inventario where cantidad_producto >=1 AND nombre_producto =?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->nombre_producto);
        // se creo para el mostrar producto
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id_detalle_inventario = $row['id_detalle_inventario'];
        $this->nombre_producto = $row['nombre_producto'];
        $this->cantidad_producto = $row['cantidad_producto'];
        $this->valor = $row['valor'];
        $this->fecha_inventario = $row['fecha_inventario'];
        $this->id_inventario = $row['id_inventario'];
        $this->id_bodega = $row['id_bodega'];
        $this->id_producto = $row['id_producto'];

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }

    public function Read_producto_detalle_invetario() //tirar a produc_has_bodega grupby nombre_producto 
    {
        $query = "SELECT * from detalle_inventario where cantidad_producto >=1";
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
    }public function update_detalle_inventario()
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE `detalle_inventario` SET 
        `nombre_producto`=:nombre_producto,`cantidad_producto`=:cantidad_producto,`valor`=:valor,
        `fecha_inventario`=:fecha_inventario,`id_inventario`=:id_inventario,`id_bodega`=:id_bodega WHERE `id_detalle_inventario`=:id_detalle_inventario";
        $stmt = $this->conn->prepare($query);


        if (htmlspecialchars(strip_tags($this->nombre_producto)) != "") {
            $this->nombre_producto = htmlspecialchars(strip_tags($this->nombre_producto));
        } else {
            $validador = false;
        }



        if (htmlspecialchars(strip_tags($this->valor)) != "") {
            $this->valor = htmlspecialchars(strip_tags($this->valor));
        } else {
            $validador = false;
        }

        if (htmlspecialchars(strip_tags($this->fecha_inventario)) != "") {
            $this->fecha_inventario = htmlspecialchars(strip_tags($this->fecha_inventario));
        } else {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->id_inventario)) != "") {
            $this->id_inventario = htmlspecialchars(strip_tags($this->id_inventario));
        } else {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->id_bodega)) != "") {
            $this->id_bodega = htmlspecialchars(strip_tags($this->id_bodega));
        } else {
            $validador = false;
        }
        if (htmlspecialchars(strip_tags($this->id_detalle_inventario)) != "") {
            $this->id_detalle_inventario = htmlspecialchars(strip_tags($this->id_detalle_inventario));
        } else {
            $validador = false;
        }

        $this->cantidad_producto = htmlspecialchars(strip_tags($this->cantidad_producto));
        // Bind Data

        if ($validador == true) {
            $stmt->bindParam(':nombre_producto', $this->nombre_producto);
            $stmt->bindParam(':cantidad_producto', $this->cantidad_producto);
            $stmt->bindParam(':valor', $this->valor);
            $stmt->bindParam(':fecha_inventario', $this->fecha_inventario);
            $stmt->bindParam(':id_inventario', $this->id_inventario);
            $stmt->bindParam(':id_bodega', $this->id_bodega);
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
    function buscar_nombre_producto($nombre_producto)//ver°°
    {
       //$query = "SELECT `nombre_producto`,`id_bodega` FROM `detalle_inventario` WHERE nombre_producto = ?";
        $query = "SELECT `nombre_producto` FROM `detalle_inventario` WHERE nombre_producto = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $nombre_producto);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['nombre_producto'];

        if ($numero_comparar == $nombre_producto) {
            return false;
        } else {
            return true;
        }
    }
    function buscar_id_detalle_inventario($id_detalle_inventario)//ver°°
    {
        $query = "SELECT `id_detalle_inventario` FROM `detalle_inventario` WHERE id_detalle_inventario = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_detalle_inventario);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_detalle_inventario'];

        if ($numero_comparar == $id_detalle_inventario) {
            return false;
        } else {
            return true;
        }
    }
    public function create_detalle_inventario($nombre_producto, $cantidad_producto, $valor, $id_inventario, $id_bodega, $id_producto, $fecha,$peso_unitario)
    { //esto se realizara en el metodo producto
        $validador = true;
        $query = 'INSERT INTO detalle_inventario 
        SET 
        nombre_producto = :nombre_producto,
        cantidad_producto = :cantidad_producto,
        valor = :valor,
        fecha_inventario = :fecha_inventario,
        peso_unitario = :peso_unitario, 
        id_inventario = :id_inventario,
        id_bodega = :id_bodega,
        id_producto = :id_producto';
        //errror en el date
        $stmt = $this->conn->prepare($query);



        if (empty($nombre_producto)) {
            $validador = false;
        } else {
            $this->nombre_producto = $nombre_producto;
        }


        if ($cantidad_producto != "") {
            if (is_numeric($cantidad_producto)) {
                if ($cantidad_producto >= 0) {
                    $this->cantidad_producto = $cantidad_producto;
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        } else {
            $validador = false;
        }

        if ($valor != "") {
            if (is_numeric($valor)) {
                if ($valor >= 0) {
                    $this->valor = $valor;
                } else {
                    $validador = false;
                }
            } else {
                $validador = false;
            }
        } else {
            $validador = false;
        }
        //validacion de ids
        if (!empty($id_inventario)) {
            $this->id_inventario = $id_inventario;
        } else {
            $validador = false;
        }

        if (!empty($id_bodega)) {
            $this->id_bodega = $id_bodega;
        } else {
            $validador = false;
        }

        if (!empty($id_producto)) {
            $this->id_producto = $id_producto;
        } else {
            $validador = false;
        }
        if (!empty($peso_unitario)) {
            $this->peso_unitario = $peso_unitario;
        } else {
            $validador = false;
        }
        $this->fecha_inventario = "'" . $fecha . "'";


        if ($validador == true) {

            /*
            
INSERT INTO `detalle_inventario`(`nombre_producto`, `cantidad_producto`, `valor`, `fecha_inventario`, `id_inventario`, `id_bodega`, `id_producto`) 
VALUES ("APIO",2,1313,'2021-06-19',1,1,72) // me lo toma con comillas la fecha
            */
            $stmt->bindParam(':nombre_producto', $this->nombre_producto);
            $stmt->bindParam(':cantidad_producto', $this->cantidad_producto);
            $stmt->bindParam(':valor', $this->valor);
            $stmt->bindParam(':id_inventario', $this->id_inventario);
            $stmt->bindParam(':id_bodega', $this->id_bodega);
            $stmt->bindParam(':id_producto', $this->id_producto);
            $stmt->bindParam(':fecha_inventario', $fecha);
            $stmt->bindParam(':peso_unitario', $this->peso_unitario);



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

    //hacer un update con la cantidad y el date
    public function Sumar_mismo_producto($id_detalle_inventario, $cantidad_del_detalle, $fecha)
    {

        $validador = true;


        if ($cantidad_del_detalle == null) {
            $validador = false;
        }
        if ($id_detalle_inventario == null) {
            $validador = false;
        }

        if ($validador == true) {
            $query = 'UPDATE `detalle_inventario` SET fecha_inventario = "' . $fecha . '" ,cantidad_producto = ' . $cantidad_del_detalle . '
            WHERE id_detalle_inventario = ' . $id_detalle_inventario . '';

            $stmt = $this->conn->prepare($query);
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



    public function buscardor_igual_producto($nombre_producto_buscar, $valor,$id_bodega) //buscar si es el mismo producto
    {
        $nombre_producto_buscar = '"' . $nombre_producto_buscar . '"';
        $query = "SELECT nombre_producto FROM detalle_inventario WHERE nombre_producto = $nombre_producto_buscar AND valor = $valor and id_bodega=$id_bodega";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_producto = $row['nombre_producto'];
        return $nombre_producto;
    }
    public function buscardor_igual_nombre($nombre_producto_buscar)
    {
        $nombre_producto_buscar = '"' . $nombre_producto_buscar . '"';
        $query = "SELECT nombre_producto FROM detalle_inventario WHERE nombre_producto = $nombre_producto_buscar";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_producto = $row['nombre_producto'];
        if ($nombre_producto == null) {
            return false;
        } else {
            return true;
        }
    }

    public function buscardor_igual_producto_id($nombre_producto_buscar, $valor,$id_bodega)
    {
        $nombre_producto_buscar = '"' . $nombre_producto_buscar . '"';
        $query = "SELECT nombre_producto,id_detalle_inventario,cantidad_producto FROM detalle_inventario WHERE nombre_producto = $nombre_producto_buscar AND valor = $valor and id_bodega= $id_bodega";
      
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_producto = $row['nombre_producto'];
        $id_inventario = $row['id_detalle_inventario'];
        $cantidad_producto = $row['cantidad_producto '];

        printf("Nombre del producto desde controller: " . $nombre_producto);

        if ($nombre_producto != null) {
                printf("Inventarios desde controller: " . $id_inventario);
            return $id_inventario;
        } else {
            return false;
        }
    }

    public function buscardor_id_detalle($nombre_producto_buscar)
    {
        $nombre_producto_buscar = '"' . $nombre_producto_buscar . '"';
        $query = "SELECT id_detalle_inventario FROM detalle_inventario WHERE nombre_producto = $nombre_producto_buscar";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $row['id_detalle_inventario'];
        if ($id == null) {
            return null;
        } else {
            return $id;
        }
    }
    public function buscardor_valor_producto($nombre_producto_buscar)
    {
        $nombre_producto_buscar = '"' . $nombre_producto_buscar . '"';
        $query = "SELECT valor FROM detalle_inventario WHERE nombre_producto = $nombre_producto_buscar";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $valor = $row['valor'];
        if ($valor == null) {
            return "";
        } else {
            return $valor;
        }
    }
    public function buscardor_valor_producto_por_bodega($id_bodega)
    {
        $id_bodega = '"' . $id_bodega . '"';

        $query = "SELECT SUM(valor) FROM `detalle_inventario` WHERE `id_bodega`= $id_bodega";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $valor = $row['SUM(valor)'];
        if ($valor == null) {
            return "";
        } else {
            return $valor;
        }
    }
  
    public function buscardor_igual_producto_cantidad($nombre_producto_buscar, $valor,$id_bodega)
    {
        $nombre_producto_buscar = '"' . $nombre_producto_buscar . '"';
        $query = "SELECT `nombre_producto`,`id_detalle_inventario`,`cantidad_producto` FROM `detalle_inventario` WHERE nombre_producto = $nombre_producto_buscar AND valor = $valor  and id_bodega=$id_bodega";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_producto = $row['nombre_producto'];
        $cantidad_producto = $row['cantidad_producto'];

        if ($nombre_producto != null) {
            return $cantidad_producto;
        } else {
            return "";
        }
    }
    public function buscardor_cantidad_producto($nombre_producto_buscar)
    {
        $nombre_producto_buscar = '"' . $nombre_producto_buscar . '"';
        $query = "SELECT `nombre_producto`,`id_detalle_inventario`,`cantidad_producto` FROM `detalle_inventario` WHERE nombre_producto = $nombre_producto_buscar";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $cantidad_producto = $row['cantidad_producto'];

        if ($cantidad_producto != null) {
            return $cantidad_producto;
        } else {
            return null;
        }
    }

    public function cantidad_total()
    {
        $query = "SELECT SUM(`cantidad_producto`) as cantidad_producto  FROM `producto`inner join bodega_has_producto on producto.id_producto=bodega_has_producto.id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total = $row['cantidad_producto'];
        if ($total != null) {
            return $total;
        } else {
            return false;
        }
    }
    public function valor_total()
    {
        $query = "SELECT SUM(valor_producto) as valor_producto FROM `producto`inner join bodega_has_producto on producto.id_producto=bodega_has_producto.id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total = $row['valor_producto'];


        if ($total != null) {
            return $total;
        } else {
            return false;
        }
    }
    public function Read_single_inventario_only($nombre_producto_buscar, $valor) //quety funcionando
    {
        $query = "SELECT nombre_producto,id_detalle_inventario FROM detalle_inventario WHERE nombre_producto = $nombre_producto_buscar AND valor = $valor";
        $stmt = $this->conn->prepare($query);


        //Bind id
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->nombre_producto = $row['nombre_producto'];
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
    public function Obtener_nombre_producto_por_inv($id_producto)
    {
        $query = "SELECT nombre_producto FROM id_detalle_inventario WHERE id_detalle_inventario = ?";
        printf("Error: %s.\n", $query);
        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_producto);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_comparar = $row['nombre_producto'];
        printf("Error: %s.\n", $numero_comparar);

        if ($numero_comparar != null) {
            return $numero_comparar;
        } else {
            return null;
        }
    }
    public function Comprobar_existencia_productos($id_bodega)
    {
        $id_bodega = '"' . $id_bodega . '"';
        $query = "SELECT nombre_producto FROM detalle_inventario WHERE id_bodega = $id_bodega";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_producto = $row['nombre_producto'];
        if ($nombre_producto == null) {
            return false;
        } else {
            return true;
        }
    }
}
