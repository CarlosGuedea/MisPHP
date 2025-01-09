<?php

class Requisitos_Update extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database('admin_tramites'); // Configura tu conexión a la base de datos
    }

    public function actualizar_requisito($id, $datos) {
        $this->db->where('id', $id);
        return $this->db->update('cat_requisitos', $datos); // Tabla y datos a actualizar
    }
}