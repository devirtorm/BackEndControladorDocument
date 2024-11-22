<?php 

use MVC\Model;

class ModelsVistas extends Model {
    private $table_name = "vistas";

    public function registrarVisita($data) {
        error_log("Método registrarVisita del modelo llamado");
        
        // Extraer idVisita del data
        $idVisita = isset($data['visitorId']) ? $data['visitorId'] : null;
        if ($idVisita === null) {
            error_log("ID de visita no proporcionado");
            http_response_code(400);
            return json_encode(array("message" => "ID de visita no proporcionado."));
        }
        
        try {
            // Obtener fecha y hora actuales
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
    
            // Verificar si la visita ya está registrada hoy con el mismo idVisita
            $checkSql = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE idvisita = ? AND fecha = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$idVisita, $fecha]);
    
            if ($checkStmt->fetchColumn() > 0) {
                error_log("Visita ya registrada hoy con el mismo ID");
                return json_encode(array("message" => "La visita con el ID ya está registrada hoy. No se contará como nueva visita."));
            }
    
            // Preparar la consulta SQL para la inserción
            $sql = "INSERT INTO " . $this->table_name . " (idvisita, fecha, hora) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            // Ejecutar la consulta
            $stmt->execute([$idVisita, $fecha, $hora]);
    
            if ($stmt->rowCount() > 0) {
                error_log("Visita registrada exitosamente");
                http_response_code(201); // Creado
                return json_encode(array("message" => "Visita registrada exitosamente."));
            } else {
                error_log("No se pudo registrar la visita");
                http_response_code(500);
                return json_encode(array("message" => "No se pudo registrar la visita."));
            }
        } catch (PDOException $e) {
            error_log("PDO Error: " . $e->getMessage());
            http_response_code(500);
            return json_encode(array("message" => "Error al registrar la visita: " . $e->getMessage()));
        }
    }
    
    
    
    
    function read() {
        error_log("Método read() llamado");
        $query = "SELECT COUNT(*) as total_visitas FROM " . $this->table_name;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("Total de visitas: " . $row['total_visitas']);
        return $row['total_visitas'];
    }



}