<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Factura_proveedor
 *
 * @author guillermo
 */
class Factura_proveedor {
    public $compra_folio_factura;
    public $rut_proveedor;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($compra_folio_factura, $rut_proveedor) {
        $this->compra_folio_factura = $compra_folio_factura;
        $this->rut_proveedor = $rut_proveedor;
    }
    
    function getCompra_folio_factura() {
        return $this->compra_folio_factura;
    }

    function getRut_proveedor() {
        return $this->rut_proveedor;
    }

    function setCompra_folio_factura($compra_folio_factura) {
        $this->compra_folio_factura = $compra_folio_factura;
    }

    function setRut_proveedor($rut_proveedor) {
        $this->rut_proveedor = $rut_proveedor;
    }


    
}
