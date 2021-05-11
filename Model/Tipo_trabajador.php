<?php

class Tipo_trabajador{
   public $id_tipo_trabajador;
   public $cargo;
   
   function __construct($id_tipo_trabajador, $cargo) {
       $this->id_tipo_trabajador = $id_tipo_trabajador;
       $this->cargo = $cargo;
   }
   
   function __construct1()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    function getId_tipo_trabajador() {
        return $this->id_tipo_trabajador;
    }

    function getCargo() {
        return $this->cargo;
    }

    function setId_tipo_trabajador($id_tipo_trabajador) {
        $this->id_tipo_trabajador = $id_tipo_trabajador;
    }

    function setCargo($cargo) {
        $this->cargo = $cargo;
    }


}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>