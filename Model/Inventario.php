<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Inventario
 *
 * @author guillermo
 */
class Inventario {
   public $id_inventario;
   public $fecha_inventario;
   public $responsable_inventario;
   public $cantidad_inventariada;
   public $valor_inventario;
   
   
   function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
   
   function __construct1($id_inventario, $fecha_inventario, $responsable_inventario, $cantidad_inventariada, $valor_inventario) {
       $this->id_inventario = $id_inventario;
       $this->fecha_inventario = $fecha_inventario;
       $this->responsable_inventario = $responsable_inventario;
       $this->cantidad_inventariada = $cantidad_inventariada;
       $this->valor_inventario = $valor_inventario;
   }
   
   function getId_inventario() {
       return $this->id_inventario;
   }

   function getFecha_inventario() {
       return $this->fecha_inventario;
   }

   function getResponsable_inventario() {
       return $this->responsable_inventario;
   }

   function getCantidad_inventariada() {
       return $this->cantidad_inventariada;
   }

   function getValor_inventario() {
       return $this->valor_inventario;
   }

   function setId_inventario($id_inventario) {
       $this->id_inventario = $id_inventario;
   }

   function setFecha_inventario($fecha_inventario) {
       $this->fecha_inventario = $fecha_inventario;
   }

   function setResponsable_inventario($responsable_inventario) {
       $this->responsable_inventario = $responsable_inventario;
   }

   function setCantidad_inventariada($cantidad_inventariada) {
       $this->cantidad_inventariada = $cantidad_inventariada;
   }

   function setValor_inventario($valor_inventario) {
       $this->valor_inventario = $valor_inventario;
   }


   
   

}
