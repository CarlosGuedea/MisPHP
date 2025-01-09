<?php
class Requisitos_Detalle extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database('admin_tramites'); // Configurar la base de datos
    }

    /**
     * Obtiene el detalle de un requisito y su sección correspondiente por ID utilizando SQL directo.
     * @param int $id ID del requisito
     * @return array|null Formato: ['requisito' => {...}, 'seccion' => {...}] o null si no se encuentra
     */
    public function obtener_requisito_y_seccion($id) {
        // Consulta SQL para obtener el detalle del requisito
        $sqlRequisito = "SELECT id, estatus, codigo, valor, etiqueta, descripcion
                         FROM cat_requisitos
                         WHERE id = ?";
        $queryRequisito = $this->db->query($sqlRequisito, [$id]);

        if ($queryRequisito->num_rows() === 0) {
            return null; // Si no hay datos del requisito, retorna null
        }

        $requisito = $queryRequisito->row_array(); // Resultado del requisito

        // Consulta SQL para obtener los detalles de la sección
        $sqlSeccion = "SELECT 
                           cr.id AS id_requisito,
                           cr.descripcion AS descripcion_requisito,
                           cs.cat_id AS id_seccion,
                           cs.valor AS valor_seccion,
                           cs.descripcion AS descripcion_seccion
                       FROM cat_requisitos cr
                       INNER JOIN cat_secciones cs ON cr.id = cs.cat_id
                       WHERE cr.id = ?";
        $querySeccion = $this->db->query($sqlSeccion, [$id]);

        if ($querySeccion->num_rows() === 0) {
            return null; // Si no hay datos de la sección, retorna null
        }

        $seccion = $querySeccion->row_array(); // Resultado de la sección

        // Formatear el resultado como un arreglo asociativo
        return [
            'requisito' => $requisito,
            'seccion' => $seccion
        ];
    }
}
