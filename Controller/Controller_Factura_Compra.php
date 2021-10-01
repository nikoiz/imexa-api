<?php

class Controller_Factura_Compra
{
    private $conn;

    public $id_compra;
    public $fecha_compra;
    public $valor_compra;
    public $estado;
    public $rut_proveedor;
    public $id_tipo_compra;
    public $recursiva_compra_id;
    public $id_tipo_f_compra;


    //datos de producto y prod_hasbodega
    public $id_producto;
    public $nombre_producto;
    public $valor_producto;
    public $cantidad_total;

    //datos de detalle compra
    public $id_detalle_compra;
    public $descripcion_compra_producto;
    public $cantidad_compra_producto;
    public $valor;
    //$id_compra; se refleja arriba
    public $producto_id_producto;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function Read_Factura_Compra()
    {
        $query = "SELECT * FROM `factura_compra`";
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
    public function Read_Factura_Compra_no_pagados()
    {
        $query = "SELECT * FROM `factura_compra` WHERE `estado` = 'Pendiente'";
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

    public function Read_single_Factura_Compra()
    {
        $query = "SELECT * FROM `factura_compra` INNER JOIN detalle_compra ON factura_compra.id_compra=detalle_compra.id_compra INNER join producto on detalle_compra.producto_id_producto=producto.id_producto where factura_compra.id_compra=?";
        $stmt = $this->conn->prepare($query);
        //Bind id
        $stmt->bindParam(1, $this->id_compra);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties
        $this->id_compra = $row['id_compra'];
        $this->fecha_compra = $row['fecha_compra'];
        $this->valor_compra = $row['valor_compra'];
        $this->estado = $row['estado'];
        $this->rut_proveedor = $row['rut_proveedor'];
        $this->id_tipo_compra = $row['id_tipo_compra'];
        $this->id_tipo_f_compra = $row['id_tipo_f_compra'];

        //resto de la query
        $this->id_detalle_compra = $row['id_detalle_compra'];
        $this->descripcion_compra_producto = $row['descripcion_compra_producto'];
        $this->cantidad_compra_producto = $row['cantidad_compra_producto'];
        $this->valor = $row['valor'];
        $this->producto_id_producto = $row['producto_id_producto'];
        $this->id_producto = $row['id_producto'];
        $this->nombre_producto = $row['nombre_producto'];
        $this->valor_producto = $row['valor_producto'];


        try {
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $e) {
            printf("Error: %s.\n", $e);

            return false;
        }
    }


    public function create_Factura_Compra()
    {
        $validador = true;

        if (empty(htmlspecialchars(strip_tags($this->id_compra)))) {
            $validador = false;
        }
        if (empty(htmlspecialchars(strip_tags($this->fecha_compra)))) {
            $validador = false;
        }
        if (empty(htmlspecialchars(strip_tags($this->valor_compra)))) {
            $validador = false;
        }
        if (empty(htmlspecialchars(strip_tags($this->estado)))) {
            $validador = false;
        }
        if (empty(htmlspecialchars(strip_tags($this->rut_proveedor)))) {
            $validador = false;
        }
        if (empty(htmlspecialchars(strip_tags($this->id_tipo_compra)))) {
            $validador = false;
        }
        if (empty(htmlspecialchars(strip_tags($this->id_tipo_f_compra)))) {
            $validador = false;
        }
        if (empty(htmlspecialchars(strip_tags($this->recursiva_compra_id)))) {
            $validador = false;
        }

        if ($validador == true) {
            $query = 'INSERT INTO factura_compra 
        SET 
            
            id_compra = "' . $this->id_compra . '",
            fecha_compra = "' . $this->fecha_compra . '",
            valor_compra ="' . $this->valor_compra . '",
            estado = "' . $this->estado . '",
            rut_proveedor = "' . $this->rut_proveedor . '",
            id_tipo_compra = "' . $this->id_tipo_compra . '",
            recursiva_compra_id = "' . $this->recursiva_compra_id . '",
            id_tipo_f_compra = "' . $this->id_tipo_f_compra . '"';


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

    public function delete_Factura_Compra()
    {
        $validador = true;
        $query = "DELETE FROM factura_compra WHERE id_compra = ? and recursiva_compra_id = ?";
        $stmt = $this->conn->prepare($query);

        if (htmlspecialchars(strip_tags($this->id_compra)) != "") {
            $this->id_compra = htmlspecialchars(strip_tags($this->id_compra));
        } else {
            $validador = false;
        }

        if ($validador == true) {
            $stmt->bindParam(1, $this->id_compra);
            $stmt->bindParam(2, $this->id_compra);
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

    public function update_Factura_Compra()
    {
        $validador = true;
        //poner atencion a la nomenclatura de las palabas.
        $query = "UPDATE factura_compra SET 
            estado =:estado
        WHERE id_compra = :id_compra";
        $stmt = $this->conn->prepare($query);

        if (!empty(htmlspecialchars(strip_tags($this->id_compra)))) {
            $this->id_compra = htmlspecialchars(strip_tags($this->id_compra));
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
            $stmt->bindParam(':id_compra', $this->id_compra);
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
    function buscar_folio_factura($id_compra)
    {
        $query = "SELECT id_compra FROM factura_compra WHERE id_compra = ?";

        $stmt = $this->conn->prepare($query);

        //Bind id
        $stmt->bindParam(1, $id_compra);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set properties

        $numero_comparar = $row['id_compra'];

        if ($numero_comparar == $id_compra) {
            return false;
        } else {
            return true;
        }
    }
    public function Validador_de_valor_compra($numero)
    {
        if ($numero == "") {
            return "Falta el valor compra";
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


    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    public function Validacion_parametro($parametro)
    {
        if (empty($parametro)) {
            return false;
        } else {
            return true;
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
    /*
    function buscar_el_ultimo_id_de_factura_compra()
    {
        $query = "SELECT MAX(id_compra) AS id_compra FROM factura_compra";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero_comparar = $row['id_compra'];

        if ($numero_comparar != null) {
            return $numero_comparar;
        } else {
            return false;
        }
    }
    */


    public function Alter_table_delete_Factura_Compra($id_compra)
    {
        $validador = true;
        $query = "UPDATE factura_compra SET `recursiva_compra_id` = NULL WHERE `id_compra` = ?";
        $stmt = $this->conn->prepare($query);

        if ($id_compra == "") {
            $validador = false;
        }
        if ($validador == true) {
            $stmt->bindParam(1, $id_compra);
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
    function buscar_las_id_de_todos($id_compra)
    {
        $query = "SELECT factura_compra.id_compra,id_detalle_compra,producto_id_producto  FROM `factura_compra` INNER JOIN detalle_compra ON factura_compra.id_compra=detalle_compra.id_compra INNER join producto on detalle_compra.producto_id_producto=producto.id_producto where factura_compra.id_compra=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_compra);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $numero_comparar = $row['id_detalle_compra'];
        $numero_comparar2 = $row['producto_id_producto'];
        $a = array($numero_comparar, $numero_comparar2);
        /*
        foreach ($a as $a) {
            printf("Error: %s.\n", $a);
        }
        */
        if ($a != null) {
            return $a;
        } else {
            return false;
        }
    }
}
