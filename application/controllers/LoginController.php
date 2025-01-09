<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {

    public function login() {
        // Configurar la carga de la base de datos y librerías necesarias
        $this->load->database('usuarios'); // Configura correctamente la conexión
        $this->load->helper('url'); // Para redirecciones y URLs
        $this->load->library('session'); // Para sesiones
        

        // Obtener el cuerpo de la solicitud y decodificar el JSON
        $data = json_decode($this->input->raw_input_stream, true);

        // Acceder a los datos
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            // Validar que ambos datos se recibieron
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(["error" => "Datos incompletos"], JSON_UNESCAPED_UNICODE))
                ->set_status_header(400);
            return;
        }

        // Consultar la base de datos para encontrar al usuario
        $query = $this->db->query("SELECT id, uuid, password FROM usuarios WHERE email = ?", [$email]);

        if ($query->num_rows() === 0) {
            // Si no se encuentra el usuario
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(["error" => "Usuario no encontrado"], JSON_UNESCAPED_UNICODE))
                ->set_status_header(404);
            return;
        }

        // Validar la contraseña (considerando que no está cifrada en este ejemplo)
        $usuario = $query->row();
        if ($usuario->password !== $password) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(["error" => "Contraseña incorrecta"], JSON_UNESCAPED_UNICODE))
                ->set_status_header(401);
            return;
        }

        // Configurar la cookie HTTP con el token
        setcookie(
            'access_token',       // Nombre de la cookie
            $usuario->uuid,        // Valor de la cookie
            time() + 3600,        // Expira en 1 hora
            '/',                  // Ruta válida
            '',                   // Dominio
            false,                // Cambiar a true en HTTPS
            true                  // Solo HTTP (no accesible desde JavaScript)
        );

        // Enviar respuesta JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(["message" => "Inicio de sesión exitoso"], JSON_UNESCAPED_UNICODE));
    }

    public function protected() {
        // Recuperar el token desde la cookie
        $access_token = $_COOKIE['access_token'] ?? null;

        if (!$access_token) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(["error" => "Acceso no autorizado"], JSON_UNESCAPED_UNICODE))
                ->set_status_header(401);
            return;
        }

        try {
            // Decodificar el token JWT
            $decoded_token = $this->jwt->decode($access_token, 'SECRETO_COMPARTIDO', ['HS256']);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    "message" => "Bienvenido, usuario " . $decoded_token->email
                ], JSON_UNESCAPED_UNICODE));
        } catch (Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(["error" => "Token inválido"], JSON_UNESCAPED_UNICODE))
                ->set_status_header(401);
        }
    }
}
