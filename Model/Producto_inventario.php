<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Producto_inventario
 *
 * @author guillermo
 */
class Producto_inventario {
   public $id_producto;
   public $id_inventario;
   
   
   function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
   
   function __construct1($id_producto, $id_inventario) {
       $this->id_producto = $id_producto;
       $this->id_inventario = $id_inventario;
   }
   
   function getId_producto() {
       return $this->id_producto;
   }

   function getId_inventario() {
       return $this->id_inventario;
   }

   function setId_producto($id_producto) {
       $this->id_producto = $id_producto;
   }

   function setId_inventario($id_inventario) {
       $this->id_inventario = $id_inventario;
   }



}
