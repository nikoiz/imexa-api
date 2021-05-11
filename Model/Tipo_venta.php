<?php
class Tipo_venta{
    public $id_tipo_venta;
    public $tipo_venta;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($id_tipo_venta, $tipo_venta) {
        $this->id_tipo_venta = $id_tipo_venta;
        $this->tipo_venta = $tipo_venta;
    }

    function getId_tipo_venta() {
        return $this->id_tipo_venta;
    }

    function getTipo_venta() {
        return $this->tipo_venta;
    }

    function setId_tipo_venta($id_tipo_venta) {
        $this->id_tipo_venta = $id_tipo_venta;
    }

    function setTipo_venta($tipo_venta) {
        $this->tipo_venta = $tipo_venta;
    }


    
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
