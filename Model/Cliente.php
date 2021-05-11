<?php

class Cliente{
    public $rut_cliente;
    public $nombre_cliente;
    public $empresa_cliente;
    public $venta_id_venta;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($rut_cliente, $nombre_cliente, $empresa_cliente, $venta_id_venta) {
        $this->rut_cliente = $rut_cliente;
        $this->nombre_cliente = $nombre_cliente;
        $this->empresa_cliente = $empresa_cliente;
        $this->venta_id_venta = $venta_id_venta;
    }

    function getRut_cliente() {
        return $this->rut_cliente;
    }

    function getNombre_cliente() {
        return $this->nombre_cliente;
    }

    function getEmpresa_cliente() {
        return $this->empresa_cliente;
    }

    function getVenta_id_venta() {
        return $this->venta_id_venta;
    }

    function setRut_cliente($rut_cliente) {
        $this->rut_cliente = $rut_cliente;
    }

    function setNombre_cliente($nombre_cliente) {
        $this->nombre_cliente = $nombre_cliente;
    }

    function setEmpresa_cliente($empresa_cliente) {
        $this->empresa_cliente = $empresa_cliente;
    }

    function setVenta_id_venta($venta_id_venta) {
        $this->venta_id_venta = $venta_id_venta;
    }

    
}

?>