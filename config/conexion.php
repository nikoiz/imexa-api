<?php
class conexion
{
    private $db_host = "localhost";
    private $db_nombre = "proyecto_titulo";
    private $db_usuarios = "root";
    private $db_contra = "";
    private $conn;

    //DB Connect
    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                'mysql:host='. $this->db_host . ';dbname=' . $this->db_nombre,
                $this->db_usuarios,
                $this->db_contra);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;
    }
}
