<?php

use \Firebase\JWT\JWT as FirebaseJWT; // Alias para la clase JWT de Firebase
ini_set('memory_limit', '1200M');

class Jwt {

    private $key = 'SECRETO_COMPARTIDO'; // Reemplaza con tu clave secreta

    // Generar un token JWT
    public function encode($data) {
        // Obtener la fecha y hora actual
        $issuedAt = new DateTime();
        // Clonar la fecha actual y sumarle 1 día
        $expirationTime = clone $issuedAt;
        $expirationTime->modify('+1 day');  // Sumar 1 día (24 horas)

        // Convertir las fechas a timestamps
        $issuedAtTimestamp = $issuedAt->getTimestamp();
        $expirationTimeTimestamp = $expirationTime->getTimestamp();

        $payload = array(
            'iat' => $issuedAtTimestamp, // Fecha de emisión
            'exp' => $expirationTimeTimestamp, // Fecha de expiración (1 día)
            'data' => $data
        );

        return JWT::encode($payload, $this->key);  // Genera y retorna el token
    }

    // Decodificar un token JWT
    public function decode($token) {
        try {
            $decoded = JWT::decode($token, $this->key);
            return (array) $decoded->data;  // Devuelve los datos decodificados
        } catch (Exception $e) {
            return null;  // Si el token es inválido, retorna null
        }
    }
}
