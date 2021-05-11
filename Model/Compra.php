<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Compra{
    public $id_compra;
    public $fecha_compra;
    public $valor_compra;
    public $estado;
    public $folio_factura;
    public $rut_trabajador;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($id_compra, $fecha_compra, $valor_compra, $estado, $folio_factura, $rut_trabajador) {
        $this->id_compra = $id_compra;
        $this->fecha_compra = $fecha_compra;
        $this->valor_compra = $valor_compra;
        $this->estado = $estado;
        $this->folio_factura = $folio_factura;
        $this->rut_trabajador = $rut_trabajador;
    }
    function getId_compra() {
        return $this->id_compra;
    }

    function getFecha_compra() {
        return $this->fecha_compra;
    }

    function getValor_compra() {
        return $this->valor_compra;
    }

    function getEstado() {
        return $this->estado;
    }

    function getFolio_factura() {
        return $this->folio_factura;
    }

    function getRut_trabajador() {
        return $this->rut_trabajador;
    }

    function setId_compra($id_compra) {
        $this->id_compra = $id_compra;
    }

    function setFecha_compra($fecha_compra) {
        $this->fecha_compra = $fecha_compra;
    }

    function setValor_compra($valor_compra) {
        $this->valor_compra = $valor_compra;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setFolio_factura($folio_factura) {
        $this->folio_factura = $folio_factura;
    }

    function setRut_trabajador($rut_trabajador) {
        $this->rut_trabajador = $rut_trabajador;
    }


}
?>