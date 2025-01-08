<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CORS_Hook {

    public function handle()
    {
        $CI =& get_instance();  // Obtener la instancia de CodeIgniter

        // Configurar los headers de CORS
        $allowed_origins = ['http://localhost:5173', 'https://misalfa.netlify.app'];  // Añadir tus dominios permitidos

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';  // Obtener el origen de la solicitud

        if (in_array($origin, $allowed_origins)) {
            // Permitir los orígenes específicos
            header("Access-Control-Allow-Origin: http://localhost:5173");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Authorization, Origin, Accept, Cache-Control");
            header("Access-Control-Allow-Credentials: true");
        }

        // Si la solicitud es OPTIONS, no se necesita procesar nada más
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }
}
