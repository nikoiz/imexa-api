<?php
class Factura_venta{
    public $nombre_contribuyente;
    public $folio_factura_venta;
    public $rut_contribuyente;
    public $total_factura;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($nombre_contribuyente, $folio_factura_venta, $rut_contribuyente, $total_factura) {
        $this->nombre_contribuyente = $nombre_contribuyente;
        $this->folio_factura_venta = $folio_factura_venta;
        $this->rut_contribuyente = $rut_contribuyente;
        $this->total_factura = $total_factura;
    }

    function getNombre_contribuyente() {
        return $this->nombre_contribuyente;
    }

    function getFolio_factura_venta() {
        return $this->folio_factura_venta;
    }

    function getRut_contribuyente() {
        return $this->rut_contribuyente;
    }

    function getTotal_factura() {
        return $this->total_factura;
    }

    function setNombre_contribuyente($nombre_contribuyente) {
        $this->nombre_contribuyente = $nombre_contribuyente;
    }

    function setFolio_factura_venta($folio_factura_venta) {
        $this->folio_factura_venta = $folio_factura_venta;
    }

    function setRut_contribuyente($rut_contribuyente) {
        $this->rut_contribuyente = $rut_contribuyente;
    }

    function setTotal_factura($total_factura) {
        $this->total_factura = $total_factura;
    }
}

?>
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

