<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Factura_compra{
    public $folio_factura;
    public $total_factura;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($folio_factura, $total_factura) {
        $this->folio_factura = $folio_factura;
        $this->total_factura = $total_factura;
    }

    function getFolio_factura() {
        return $this->folio_factura;
    }

    function getTotal_factura() {
        return $this->total_factura;
    }

    function setFolio_factura($folio_factura) {
        $this->folio_factura = $folio_factura;
    }

    function setTotal_factura($total_factura) {
        $this->total_factura = $total_factura;
    }


}
?>