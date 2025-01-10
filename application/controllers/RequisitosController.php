<?php
error_reporting(0);

class RequisitosController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Requisitos_Read');
        $this->load->database();
    }

    //Lista todos los requisitos que hay en la base de datos
    public function obtener_requisitos() {

        $user_session = $this->input->cookie('access_token', TRUE); // Suponiendo que la cookie es 'user_session'

        if (!$user_session) {
            // Si la cookie no existe o no es válida, redirigir al login
            exit();
        }

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

        $user_session = $this->input->cookie('access_token', TRUE); // Suponiendo que la cookie es 'user_session'

        if (!$user_session) {
            // Si la cookie no existe o no es válida, redirigir al login
            exit();
        }

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


    public function actualizar($id) {

        $this->load->model('Requisitos_Update'); // Carga el modelo
         
        // Obtén los datos de la solicitud POST
        $inputData = file_get_contents('php://input');
        $datos = json_decode($inputData, true); // Decodifica el JSON en un array asociativo
        
        if (!$datos) {
            show_error('Datos inválidos', 400);
        }

         // Eliminar el campo 'id' si existe en los datos
         unset($datos['cat_id']); // Esto evita que el campo 'id' sea enviado al modelo
    
        // Actualiza los datos
        $resultado = $this->Requisitos_Update->actualizar_requisito($id, $datos);
    
        if ($resultado) {
            $response = ['mensaje' => 'Requisito actualizado exitosamente.'];
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        } else {
            $response = ['mensaje' => 'Error al actualizar el requisito.'];
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode($response));
        }
    }
    

    public function actualizar_secciones($id) {

        $this->load->model('Seccion_Update'); // Carga el modelo
         
        // Obtén los datos de la solicitud POST
        $inputData = file_get_contents('php://input');
        $datos = json_decode($inputData, true); // Decodifica el JSON en un array asociativo
        
        if (!$datos) {
            show_error('Datos inválidos', 400);
        }

         // Eliminar el campo 'id' si existe en los datos
         unset($datos['id']); // Esto evita que el campo 'id' sea enviado al modelo
    
        // Actualiza los datos
        $resultado = $this->Seccion_Update->actualizar_seccion($id, $datos);
    
        if ($resultado) {
            $response = ['mensaje' => 'Requisito actualizado exitosamente.'];
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        } else {
            $response = ['mensaje' => 'Error al actualizar el requisito.'];
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode($response));
        }
    }
}
