<?php
class Controller_Factura_Venta
{
    private $conn;

    public $id_venta;
    public $fecha_venta;
    public $valor_venta;
    public $estado;
    public $id_tipo_venta;
    public $rut_cliente;
    public $recursiva_id;
    public $id_tipo_f_venta;

    //datos de detalle compra
    public $id_detalle_venta;
    public $descripcion_producto;
    public $cantidad_producto;
    public $valor;
    //public $id_venta;
    public $producto_id_producto;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function Read_Factura()
    {
        $query = "SELECT * FROM factura_venta";
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
    public function Read_Factura_no_pagadas()
    {
        $query = "SELECT * FROM factura_venta WHERE `estado` = 'Pendiente'";
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
    public function Read_single_factura()
    {
        $query = "SELECT * FROM `factura_venta` where id_venta=?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->id_venta);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        //factura
        $this->id_venta = $row['id_venta'];
        $this->fecha_venta = $row['fecha_venta'];
        $this->valor_venta = $row['valor_venta'];
        $this->estado = $row['estado'];
        $this->id_tipo_venta = $row['id_tipo_venta'];
        $this->rut_cliente = $row['rut_cliente'];
        $this->recursiva_id = $row['recursiva_id'];
        $this->id_tipo_f_venta = $row['id_tipo_f_venta'];

        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }
    public function Read_single_Factura_Venta_para_detalles()
    {
 //SELECT * FROM detalle_venta INNER join producto on detalle_venta.producto_id_producto=producto.id_producto where detalle_venta.producto_id_producto= ?
        $query = "SELECT * FROM detalle_venta INNER join producto on detalle_venta.producto_id_producto=producto.id_producto where detalle_venta.id_venta=?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->id_venta);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);



        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }

    public function Read_single_factura_no_pagadas()
    {
        $query = "SELECT * FROM `factura_venta` INNER JOIN detalle_venta on factura_venta.id_venta=detalle_venta.id_venta where factura_venta.rut_cliente= ? and `estado` = 'Pendiente'";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->rut_cliente);
        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
        /*
        factura
        $this->id_venta = $row['id_venta'];
        $this->fecha_venta = $row['fecha_venta'];
        $this->valor_venta = $row['valor_venta'];
        $this->estado = $row['estado'];
        $this->id_tipo_venta = $row['id_tipo_venta'];
        $this->rut_cliente = $row['rut_cliente'];
        $this->recursiva_id = $row['recursiva_id'];
        $this->id_tipo_f_venta = $row['id_tipo_f_venta'];


        detalle venta (no mostrar el producto como tal)
        $this->id_detalle_venta = $row['id_detalle_venta'];
        $this->descripcion_producto = $row['descripcion_producto'];
        $this->cantidad_producto = $row['cantidad_producto'];
        $this->valor = $row['valor'];
        $this->producto_id_producto = $row['producto_id_producto'];
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
    public function Suma_facturas_Npagadas_cliente()
    {
        $query = "SELECT SUM('valor_venta') as 'Total a Pagar' FROM `factura_venta` INNER JOIN detalle_venta on factura_venta.id_venta=detalle_venta.id_venta where `estado` = 'Pendiente' and factura_venta.rut_cliente= '".$this->rut_cliente."'";
        $stmt = $this->conn->prepare($query);
        try {
            if ($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $total= $row['Total_a_Pagar'];
                return $total;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return null;
        }
    }
    public function Suma_facturas_Npagadas()
    {
        $query = "SELECT SUM('valor_venta') as 'Total a Pagar' FROM `factura_venta` INNER JOIN detalle_venta on factura_venta.id_venta=detalle_venta.id_venta where `estado` = 'Pendiente'";
        $stmt = $this->conn->prepare($query);
        try {
            if ($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $total= $row['Total_a_Pagar'];
                return $total;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return null;
        }
    }
    public function create_factura_venta()
    {
        $validador = true;

        $query = 'INSERT INTO factura_venta 
        SET 
            
            id_venta = :id_venta,
            fecha_venta = :fecha_venta,
            valor_venta = :valor_venta,
            estado = :estado,
            id_tipo_venta = :id_tipo_venta,
            rut_cliente = :rut_cliente,
            recursiva_id = :recursiva_id,
            id_tipo_f_venta = :id_tipo_f_venta';

        $stmt = $this->conn->prepare($query);

        if (!empty(htmlspecialchars(strip_tags($this->id_venta)))) {
            $this->id_venta = htmlspecialchars(strip_tags($this->id_venta));
        } else {
            $validador = false;
        }

        if (!empty(htmlspecialchars(strip_tags($this->fecha_venta)))) {
            $this->fecha_venta = htmlspecialchars(strip_tags($this->fecha_venta));
        } else {
            $validador = false;
        }

        if (empty(htmlspecialchars(strip_tags($this->valor_venta)))) {
            $validador = false;
        } else {
            if (!is_numeric(htmlspecialchars(strip_tags($this->valor_venta)))) {
                $validador = false;
            } else {
                $this->valor_venta = htmlspecialchars(strip_tags($this->valor_venta));
            }
        }

        if (empty(htmlspecialchars(strip_tags($this->estado)))) {
            $validador = false;
        } else {
            $this->estado = htmlspecialchars(strip_tags($this->estado));
        }


        if (empty(htmlspecialchars(strip_tags($this->id_tipo_venta)))) {
            $validador = false;
        } else {
            if (!is_numeric(htmlspecialchars(strip_tags($this->id_tipo_venta)))) {
                $validador = false;
            } else {
                $this->id_tipo_venta = htmlspecialchars(strip_tags($this->id_tipo_venta));
            }
        }
        if (!empty(htmlspecialchars(strip_tags($this->rut_cliente)))) {
            $this->rut_cliente = htmlspecialchars(strip_tags($this->rut_cliente));
        } else {
            $validador = false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->recursiva_id)))) {
            $this->recursiva_id = htmlspecialchars(strip_tags($this->recursiva_id));
        } else {
            $validador = false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->id_tipo_f_venta)))) {
            $this->id_tipo_f_venta = htmlspecialchars(strip_tags($this->id_tipo_f_venta));
        } else {
            $validador = false;
        }


        if ($validador == true) {
            $stmt->bindParam(':id_venta', $this->id_venta);
            $stmt->bindParam(':fecha_venta', $this->fecha_venta);
            $stmt->bindParam(':valor_venta', $this->valor_venta);
            $stmt->bindParam(':estado', $this->estado);
            $stmt->bindParam(':id_tipo_venta', $this->id_tipo_venta);
            $stmt->bindParam(':rut_cliente', $this->rut_cliente);
            $stmt->bindParam(':recursiva_id', $this->recursiva_id);
            $stmt->bindParam(':id_tipo_f_venta', $this->id_tipo_f_venta);

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
    public function delete_single_factura_venta()
    {
        $validador = true;
        $query = "DELETE FROM factura_venta WHERE id_venta = ?";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->id_venta)) != "") {
            $this->id_venta = htmlspecialchars(strip_tags($this->id_venta));
        } else {
            $validador = false;
        }

        $stmt->bindParam(1, $this->id_venta);

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
    public function update_factura_venta()
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE factura_venta SET    
            estado = :estado
          WHERE id_venta = :id_venta";
        $stmt = $this->conn->prepare($query);

        if (!empty(htmlspecialchars(strip_tags($this->id_venta)))) {
            $this->id_venta = htmlspecialchars(strip_tags($this->id_venta));
        } else {
            $validador = false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->estado)))) {
            $this->estado = htmlspecialchars(strip_tags($this->estado));
        } else {
            $validador = false;
        }
        
        // Bind Data
        if ($validador == true) {
            $stmt->bindParam(':id_venta', $this->id_venta);
            $stmt->bindParam(':estado', $this->estado);
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
    public function Validacion_parametro($parametro)
    {
        if (empty($parametro)) {
            return false;
        } else {
            return true;
        }
    }
    function buscar_id_venta($id_venta)
    {
        $query = "SELECT id_venta FROM factura_venta WHERE id_venta = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_venta);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_venta'];

        if ($numero_comparar == $id_venta) {
            return false;
        } else {
            return true;
        }
    }
    public function Validador_de_valor_venta($numero)
    {
        if ($numero == "") {
            return "Falta el valor venta";
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
    function buscar_el_ultimo_id_de_factura_venta()
    {
        $query = "SELECT MAX(id_venta) AS id_venta FROM factura_venta";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_comparar = $row['id_venta'];

        if ($numero_comparar != null) {
            return $numero_comparar;
        } else {
            return false;
        }
    }
    function obtner_valor_venta()
    {
        $query = "SELECT `valor_venta` FROM `factura_venta` WHERE `id_venta` = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_venta);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_comparar = $row['valor_venta'];

        if ($numero_comparar != null) {
            return $numero_comparar;
        } else {
            return null;
        }
    }
    public function update_valor_factura_venta()
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        

        if (!empty(htmlspecialchars(strip_tags($this->id_venta)))) {
            $this->id_venta = htmlspecialchars(strip_tags($this->id_venta));
        } else {
            $validador = false;
        }
        if (!empty(htmlspecialchars(strip_tags($this->valor_venta)))) {
            $this->valor_venta = htmlspecialchars(strip_tags($this->valor_venta));
        } else {
            $validador = false;
        }
        // Bind Data
        if ($validador == true) {
           
            $query = "UPDATE factura_venta SET    
            valor_venta = '".$this->valor_venta."'
          WHERE id_venta = '".$this->id_venta."'";
         
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
}
