<?php

class Requisitos_Delete extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database('admin_tramites'); // Configurar la base de datos
    }
    
    public function eliminar_requisito($id_requisito) {
        // Inicia la transacción
        $this->db->trans_start();

         // Elimina la sección asociada (cat_secciones)
         $this->db->where('cat_id', $id_requisito);
         $this->db->delete('cat_secciones');

        // Elimina el requisito (cat_requisitos)
        $this->db->where('id', $id_requisito);
        $this->db->delete('cat_requisitos');

        // Completa la transacción
        $this->db->trans_complete();

        // Verifica si la transacción fue exitosa
        if ($this->db->trans_status() === FALSE) {
            return false; // Algo falló
        }

        return true; // Eliminación exitosa
    }
}
