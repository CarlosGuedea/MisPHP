<?php
class Requisitos_Read extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database('admin_tramites');
    }

    public function obtener_requisitos() {
        $this->db->select('id, estatus, codigo, valor, etiqueta');
        $this->db->from('cat_requisitos');
        $this->db->where('estatus', 'True');
        $query = $this->db->get();

        return $query->result_array(); // Devuelve como arreglo
    }
}
