<?php
error_reporting(0);

function enable_cors() {
    header("Access-Control-Allow-Origin:*"); // Cambia a tu dominio frontend
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0); // Responder a las solicitudes preflight
    }
}

class RequisitosController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Requisitos_Read');
        $this->load->database();
    }

    //Lista todos los requisitos que hay en la base de datos
    public function obtener_requisitos() {
        $requisitos = $this->Requisitos_Read->obtener_requisitos();

    // Decodificar entidades HTML
    foreach ($requisitos as &$fila) {
        $fila = array_map('html_entity_decode', $fila);
    }

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($requisitos));
}

    //Lista los detalles de los requisitos que estan en la base de datos
    public function detalle($id) {
        $this->load->model('Requisitos_Detalle'); // Carga el modelo

        $resultado = $this->Requisitos_Detalle->obtener_requisito_y_seccion($id);

        if ($resultado) {
            // Retornar el resultado como JSON
            echo json_encode($resultado);
        } else {
            // Manejar el caso donde no se encuentra el requisito o la sección
            echo json_encode(['error' => 'Requisito o sección no encontrados']);
        }
    }
}