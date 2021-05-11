<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Compra_proveedor
 *
 * @author guillermo
 */
class Compra_proveedor {
    //put your code here
    public $id_compra;
    public $rut_proveedor;
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($id_compra, $rut_proveedor) {
        $this->id_compra = $id_compra;
        $this->rut_proveedor = $rut_proveedor;
    }
    function getId_compra() {
        return $this->id_compra;
    }

    function getRut_proveedor() {
        return $this->rut_proveedor;
    }

    function setId_compra($id_compra) {
        $this->id_compra = $id_compra;
    }

    function setRut_proveedor($rut_proveedor) {
        $this->rut_proveedor = $rut_proveedor;
    }


}
?>
