<?php
class asistencia{
    public $id_asistencia;
    public $fecha_asistencia;
    public $cant_asistidos;
    public $trabajador_rut_trabajador; 
    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this,$f='__construct'.$i)) {
            call_user_func_array(array($this,$f),$a);
        }
    }
    function __construct1($id_asistencia, $fecha_asistencia, $cant_asistidos, $trabajador_rut_trabajador) {
        $this->id_asistencia = $id_asistencia;
        $this->fecha_asistencia = $fecha_asistencia;
        $this->cant_asistidos = $cant_asistidos;
        $this->trabajador_rut_trabajador = $trabajador_rut_trabajador;
    }
    function getId_asistencia() {
        return $this->id_asistencia;
    }
    function getFecha_asistencia() {
        return $this->fecha_asistencia;
    }
    function getCant_asistidos() {
        return $this->cant_asistidos;
    }
    function getTrabajador_rut_trabajador() {
        return $this->trabajador_rut_trabajador;
    }
    function setId_asistencia($id_asistencia) {
        $this->id_asistencia = $id_asistencia;
    }
    function setFecha_asistencia($fecha_asistencia) {
        $this->fecha_asistencia = $fecha_asistencia;
    }
    function setCant_asistidos($cant_asistidos) {
        $this->cant_asistidos = $cant_asistidos;
    }
    function setTrabajador_rut_trabajador($trabajador_rut_trabajador) {
        $this->trabajador_rut_trabajador = $trabajador_rut_trabajador;
    }
}



?>