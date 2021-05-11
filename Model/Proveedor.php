<?php

class Proveedor{
    public $rut_proveedor;
    public $nombre_proveedor;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($rut_proveedor, $nombre_proveedor) {
        $this->rut_proveedor = $rut_proveedor;
        $this->nombre_proveedor = $nombre_proveedor;
    }

    function getRut_proveedor() {
        return $this->rut_proveedor;
    }

    function getNombre_proveedor() {
        return $this->nombre_proveedor;
    }

    function setRut_proveedor($rut_proveedor) {
        $this->rut_proveedor = $rut_proveedor;
    }

    function setNombre_proveedor($nombre_proveedor) {
        $this->nombre_proveedor = $nombre_proveedor;
    }


}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
