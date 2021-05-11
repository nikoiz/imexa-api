<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Abono{
    public $id_abono;
    public $valor_abono;
    public $fecha_abono;
    public $venta_id_venta;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($id_abono, $valor_abono, $fecha_abono, $venta_id_venta) {
        $this->id_abono = $id_abono;
        $this->valor_abono = $valor_abono;
        $this->fecha_abono = $fecha_abono;
        $this->venta_id_venta = $venta_id_venta;
    }

    function getId_abono() {
        return $this->id_abono;
    }

    function getValor_abono() {
        return $this->valor_abono;
    }

    function getFecha_abono() {
        return $this->fecha_abono;
    }

    function getVenta_id_venta() {
        return $this->venta_id_venta;
    }

    function setId_abono($id_abono) {
        $this->id_abono = $id_abono;
    }

    function setValor_abono($valor_abono) {
        $this->valor_abono = $valor_abono;
    }

    function setFecha_abono($fecha_abono) {
        $this->fecha_abono = $fecha_abono;
    }

    function setVenta_id_venta($venta_id_venta) {
        $this->venta_id_venta = $venta_id_venta;
    }
  
}



?>