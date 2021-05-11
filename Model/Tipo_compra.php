<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tipo_compra
 *
 * @author guillermo
 */
class Tipo_compra {
    public $id_tipo_compra;
    public $descripcion_compra;
    public $id_compra;
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    
    function __construct1($id_tipo_compra, $descripcion_compra, $id_compra) {
        $this->id_tipo_compra = $id_tipo_compra;
        $this->descripcion_compra = $descripcion_compra;
        $this->id_compra = $id_compra;
    }
    
    
    function getId_tipo_compra() {
        return $this->id_tipo_compra;
    }

    function getDescripcion_compra() {
        return $this->descripcion_compra;
    }

    function getId_compra() {
        return $this->id_compra;
    }

    function setId_tipo_compra($id_tipo_compra) {
        $this->id_tipo_compra = $id_tipo_compra;
    }

    function setDescripcion_compra($descripcion_compra) {
        $this->descripcion_compra = $descripcion_compra;
    }

    function setId_compra($id_compra) {
        $this->id_compra = $id_compra;
    }


}
