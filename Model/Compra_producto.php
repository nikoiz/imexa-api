<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Compra_producto{
    public $id_compra;
    public $id_producto;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($id_compra, $id_producto) {
        $this->id_compra = $id_compra;
        $this->id_producto = $id_producto;
    }

    function getId_compra() {
        return $this->id_compra;
    }

    function getId_producto() {
        return $this->id_producto;
    }

    function setId_compra($id_compra) {
        $this->id_compra = $id_compra;
    }

    function setId_producto($id_producto) {
        $this->id_producto = $id_producto;
    }


}
?>