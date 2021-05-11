<?php

class Bodega{
    public $id_bodega;
    public $numero_bodega;
    public $nombre_bodega;
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    function __construct1($id_bodega, $numero_bodega, $nombre_bodega) {
        $this->id_bodega = $id_bodega;
        $this->numero_bodega = $numero_bodega;
        $this->nombre_bodega = $nombre_bodega;
    }
    
    function getId_bodega() {
        return $this->id_bodega;
    }

    function getNumero_bodega() {
        return $this->numero_bodega;
    }

    function getNombre_bodega() {
        return $this->nombre_bodega;
    }

    function setId_bodega($id_bodega) {
        $this->id_bodega = $id_bodega;
    }

    function setNumero_bodega($numero_bodega) {
        $this->numero_bodega = $numero_bodega;
    }

    function setNombre_bodega($nombre_bodega) {
        $this->nombre_bodega = $nombre_bodega;
    }


}

?>