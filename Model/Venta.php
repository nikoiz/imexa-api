<?php

class Venta {
    public $id_venta;
    public $fecha_venta;
    public $valor_venta;
    public $id_producto;
    public $estado;
    public $id_tipo_ventaid_tipo_venta;
    public $folio_factura_venta;
    public $rut_trabajador;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($id_venta, $fecha_venta, $valor_venta, $id_producto, $estado, $id_tipo_ventaid_tipo_venta, $folio_factura_venta, $rut_trabajador) {
        $this->id_venta = $id_venta;
        $this->fecha_venta = $fecha_venta;
        $this->valor_venta = $valor_venta;
        $this->id_producto = $id_producto;
        $this->estado = $estado;
        $this->id_tipo_ventaid_tipo_venta = $id_tipo_ventaid_tipo_venta;
        $this->folio_factura_venta = $folio_factura_venta;
        $this->rut_trabajador = $rut_trabajador;
    }
    
    function getId_venta() {
        return $this->id_venta;
    }

    function getFecha_venta() {
        return $this->fecha_venta;
    }

    function getValor_venta() {
        return $this->valor_venta;
    }

    function getId_producto() {
        return $this->id_producto;
    }

    function getEstado() {
        return $this->estado;
    }

    function getId_tipo_ventaid_tipo_venta() {
        return $this->id_tipo_ventaid_tipo_venta;
    }

    function getFolio_factura_venta() {
        return $this->folio_factura_venta;
    }

    function getRut_trabajador() {
        return $this->rut_trabajador;
    }

    function setId_venta($id_venta) {
        $this->id_venta = $id_venta;
    }

    function setFecha_venta($fecha_venta) {
        $this->fecha_venta = $fecha_venta;
    }

    function setValor_venta($valor_venta) {
        $this->valor_venta = $valor_venta;
    }

    function setId_producto($id_producto) {
        $this->id_producto = $id_producto;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setId_tipo_ventaid_tipo_venta($id_tipo_ventaid_tipo_venta) {
        $this->id_tipo_ventaid_tipo_venta = $id_tipo_ventaid_tipo_venta;
    }

    function setFolio_factura_venta($folio_factura_venta) {
        $this->folio_factura_venta = $folio_factura_venta;
    }

    function setRut_trabajador($rut_trabajador) {
        $this->rut_trabajador = $rut_trabajador;
    }


}


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>