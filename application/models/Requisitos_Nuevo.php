<?php

class Requisitos_Nuevo extends CI_Model {
    
    public function nuevo_requisito($requisito, $seccion) {
        $uuid = uniqid('', true); 
        // SQL para insertar los datos en las tablas
        $sql = "
            BEGIN TRANSACTION;
            INSERT INTO cat_requisitos 
                (uuid, id_area, valor, etiqueta, codigo, descripcion, id_tarifa, activo, estatus, usuario_creado, fecha_creado, fecha_eliminado)
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
            
            DECLARE @id_insertado INT = SCOPE_IDENTITY();
            
            INSERT INTO cat_secciones 
                (cat_id, tipo, valor, descripcion, fila, activo, estatus, usuario_creado, fecha_creado, fecha_eliminado)
            VALUES 
                (@id_insertado, ?, ?, ?, ?, ?, ?, ?, ?, ?);
            
            COMMIT TRANSACTION;
        ";

        // Combina los datos del requisito y la sección
        $params = [
            $uuid,
            $requisito['id_area'],
            $requisito['valor'],
            $requisito['etiqueta'],
            $requisito['codigo'],
            $requisito['descripcion'],
            null,
            true,
            true,
            1,
            date('Y-m-d H:i:s'),
            null,
            'R',
            $seccion['valor'],
            $seccion['descripcion'],
            1,
            true,
            true,
            1,
            date('Y-m-d H:i:s'),
            null
        ];

        // Ejecuta la consulta
        $this->db->trans_start(); // Inicia la transacción
        $this->db->query($sql, $params);
        $this->db->trans_complete(); // Finaliza la transacción

        // Verifica el estado de la transacción
        if ($this->db->trans_status() === FALSE) {
            // Algo falló, devuelve falso
            return false;
        }

        // Retorna true si todo fue exitoso
        return true;
    }
}
