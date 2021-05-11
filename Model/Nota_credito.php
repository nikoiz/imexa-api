<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Nota_credito
 *
 * @author guillermo
 */
class Nota_credito {
    public $folio_nota_credito;
    public $motivo_nota_credito;
    public $valor_nota_credito;
    public $factura_compra_folio_factura;
    public $factura_venta_folio_factura_venta;
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($folio_nota_credito, $motivo_nota_credito, $valor_nota_credito, $factura_compra_folio_factura, $factura_venta_folio_factura_venta) {
        $this->folio_nota_credito = $folio_nota_credito;
        $this->motivo_nota_credito = $motivo_nota_credito;
        $this->valor_nota_credito = $valor_nota_credito;
        $this->factura_compra_folio_factura = $factura_compra_folio_factura;
        $this->factura_venta_folio_factura_venta = $factura_venta_folio_factura_venta;
    }
    
    function getFolio_nota_credito() {
        return $this->folio_nota_credito;
    }

    function getMotivo_nota_credito() {
        return $this->motivo_nota_credito;
    }

    function getValor_nota_credito() {
        return $this->valor_nota_credito;
    }

    function getFactura_compra_folio_factura() {
        return $this->factura_compra_folio_factura;
    }

    function getFactura_venta_folio_factura_venta() {
        return $this->factura_venta_folio_factura_venta;
    }

    function setFolio_nota_credito($folio_nota_credito) {
        $this->folio_nota_credito = $folio_nota_credito;
    }

    function setMotivo_nota_credito($motivo_nota_credito) {
        $this->motivo_nota_credito = $motivo_nota_credito;
    }

    function setValor_nota_credito($valor_nota_credito) {
        $this->valor_nota_credito = $valor_nota_credito;
    }

    function setFactura_compra_folio_factura($factura_compra_folio_factura) {
        $this->factura_compra_folio_factura = $factura_compra_folio_factura;
    }

    function setFactura_venta_folio_factura_venta($factura_venta_folio_factura_venta) {
        $this->factura_venta_folio_factura_venta = $factura_venta_folio_factura_venta;
    }



}
