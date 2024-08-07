<?php

use MVC\Model;

class ModelsNotificaciones extends Model
{
    public function dataMensajes()
    {
        try {
            // sql statement
            $sql = " SELECT 
                notificaciones.id, 
                departamento.nombre_departamento,
                notificaciones.mensaje,
                notificaciones.id_documento,
                notificaciones.fecha
            FROM 
                " . DB_PREFIX . "notificaciones
            INNER JOIN 
                " . DB_PREFIX . "departamento 
            ON 
                departamento.id_departamento = notificaciones.departamento
            WHERE 
                notificaciones.visto = false";

            // exec query
            $query = $this->db->query($sql);

            // Initialize data as an empty array
            $data = [];

            // Check if there are any rows
            if ($query->num_rows) {
                foreach ($query->rows as $value) {
                    $data['data'][] = $value;
                }
            } else {
                $data['data'] = [];
            }

            // Return the data array
            return $data;

        } catch (\Exception $e) {
            throw new \Exception('Error ejecutando la consulta: ' . $e->getMessage());
        }
    }
    public function CantNotificaciones()
    {
        try {
            // sql statement
            $sql = "
            SELECT COUNT(*) AS total_no_leidos
            FROM " . DB_PREFIX . "notificaciones
            WHERE visto = false
        ";
            // exec query
            $query = $this->db->query($sql);

            // Initialize data as an empty array
            $data = [];

            // Check if there are any rows
            if ($query->num_rows) {
                foreach ($query->rows as $value) {
                    $data['data'][] = $value;
                }
            } else {
                $data['data'] = [];
            }

            // Return the data array
            return $data;

        } catch (\Exception $e) {
            throw new \Exception('Error ejecutando la consulta: ' . $e->getMessage());
        }
    }

    public function mensajeVisto($id) {
        try {
            // Consulta SQL corregida
            $sql = "UPDATE " . DB_PREFIX . "notificaciones SET visto = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            $visto = true; 
            $stmt->bindParam(1, $visto, PDO::PARAM_BOOL); 
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar notificación: " . $e->getMessage());
            return false;
        }
    }
    
    
}



