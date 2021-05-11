<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Producto_venta
 *
 * @author guillermo
 */
class Producto_venta {
    public $id_producto;
    public $id_venta;
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($id_producto, $id_venta) {
        $this->id_producto = $id_producto;
        $this->id_venta = $id_venta;
    }
    
    function getId_producto() {
        return $this->id_producto;
    }

    function getId_venta() {
        return $this->id_venta;
    }

    function setId_producto($id_producto) {
        $this->id_producto = $id_producto;
    }

    function setId_venta($id_venta) {
        $this->id_venta = $id_venta;
    }


    
}
