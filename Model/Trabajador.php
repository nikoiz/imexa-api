<?php
class trabajador{
    public $rut_trabajador;
    public $nombre_trabajador;
    public $fecha_contratacion;
    public $id_tipo_trabajador;
    public $id_asistencia;
    
    
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    
    function __construct1($rut_trabajador, $nombre_trabajador, $fecha_contratacion, $id_tipo_trabajador, $id_asistencia) {
        $this->rut_trabajador = $rut_trabajador;
        $this->nombre_trabajador = $nombre_trabajador;
        $this->fecha_contratacion = $fecha_contratacion;
        $this->id_tipo_trabajador = $id_tipo_trabajador;
        $this->id_asistencia = $id_asistencia;
    }

    function getRut_trabajador() {
        return $this->rut_trabajador;
    }

    function getNombre_trabajador() {
        return $this->nombre_trabajador;
    }

    function getFecha_contratacion() {
        return $this->fecha_contratacion;
    }

    function getId_tipo_trabajador() {
        return $this->id_tipo_trabajador;
    }

    function getId_asistencia() {
        return $this->id_asistencia;
    }

    function setRut_trabajador($rut_trabajador) {
        $this->rut_trabajador = $rut_trabajador;
    }

    function setNombre_trabajador($nombre_trabajador) {
        $this->nombre_trabajador = $nombre_trabajador;
    }

    function setFecha_contratacion($fecha_contratacion) {
        $this->fecha_contratacion = $fecha_contratacion;
    }

    function setId_tipo_trabajador($id_tipo_trabajador) {
        $this->id_tipo_trabajador = $id_tipo_trabajador;
    }

    function setId_asistencia($id_asistencia) {
        $this->id_asistencia = $id_asistencia;
    }


    
}

?>