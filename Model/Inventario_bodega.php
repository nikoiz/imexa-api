<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Inventario_bodega
 *
 * @author guillermo
 */
class Inventario_bodega {
   public $id_bodega;
   public $id_inventario;
   
   function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
   function __construct1($id_bodega, $id_inventario) {
       $this->id_bodega = $id_bodega;
       $this->id_inventario = $id_inventario;
   }
   
   function getId_bodega() {
       return $this->id_bodega;
   }

   function getId_inventario() {
       return $this->id_inventario;
   }

   function setId_bodega($id_bodega) {
       $this->id_bodega = $id_bodega;
   }

   function setId_inventario($id_inventario) {
       $this->id_inventario = $id_inventario;
   }


}
