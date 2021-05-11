<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Producto{
    public $id_producto;
    public $nombre_producto;
    public $valor_producto;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($id_producto, $nombre_producto, $valor_producto) {
        $this->id_producto = $id_producto;
        $this->nombre_producto = $nombre_producto;
        $this->valor_producto = $valor_producto;
    }
    function __construct2( $nombre_producto, $valor_producto) {
        $this->nombre_producto = $nombre_producto;
        $this->valor_producto = $valor_producto;
    }
    function getId_producto() {
        return $this->id_producto;
    }

    function getNombre_producto() {
        return $this->nombre_producto;
    }

    function getValor_producto() {
        return $this->valor_producto;
    }

    function setId_producto($id_producto) {
        $this->id_producto = $id_producto;
    }

    function setNombre_producto($nombre_producto) {
        $this->nombre_producto = $nombre_producto;
    }

    function setValor_producto($valor_producto) {
        $this->valor_producto = $valor_producto;
    }


    
}
?>