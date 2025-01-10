<?php

class Seccion_Update extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database('admin_tramites'); // Configura tu conexión a la base de datos
    }

    public function actualizar_seccion($id, $datos) {
        // Construye la consulta SQL
        $sql = "UPDATE cat_secciones 
        SET valor = ?, descripcion = ? 
        WHERE cat_id = ?";

        // Ejecuta la consulta con los valores proporcionados
        $this->db->query($sql, [$datos['valor_seccion'], $datos['descripcion_seccion'], $id]);

        // Verifica si se actualizaron filas
        return $this->db->affected_rows() > 0;
    }
}