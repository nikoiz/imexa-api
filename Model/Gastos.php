<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Gastos
 *
 * @author guillermo
 */
class Gastos {
    public $id_gastos;
    public $descripcion_gastos;
    public $valor_gastos;
    public $estado;
    public $bodega_id_bodega;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($id_gastos, $descripcion_gastos, $valor_gastos, $estado, $bodega_id_bodega) {
        $this->id_gastos = $id_gastos;
        $this->descripcion_gastos = $descripcion_gastos;
        $this->valor_gastos = $valor_gastos;
        $this->estado = $estado;
        $this->bodega_id_bodega = $bodega_id_bodega;
    }

    function getId_gastos() {
        return $this->id_gastos;
    }

    function getDescripcion_gastos() {
        return $this->descripcion_gastos;
    }

    function getValor_gastos() {
        return $this->valor_gastos;
    }

    function getEstado() {
        return $this->estado;
    }

    function getBodega_id_bodega() {
        return $this->bodega_id_bodega;
    }

    function setId_gastos($id_gastos) {
        $this->id_gastos = $id_gastos;
    }

    function setDescripcion_gastos($descripcion_gastos) {
        $this->descripcion_gastos = $descripcion_gastos;
    }

    function setValor_gastos($valor_gastos) {
        $this->valor_gastos = $valor_gastos;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setBodega_id_bodega($bodega_id_bodega) {
        $this->bodega_id_bodega = $bodega_id_bodega;
    }


    
}
