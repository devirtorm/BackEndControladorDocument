<?php 

use MVC\Model;

class ModelsDirecciones extends Model {

    public function Direcciones() { //funciona
        $sql = "SELECT * FROM direccion";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }

        return $data;
    }
    
    public function DireccionesActivas() { //funciona
        $sql = "SELECT * FROM direccion WHERE activo = 1";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }
        return $data;
    }
    
    public function DireccionesInactivas() { //funciona
        $sql = "SELECT * FROM direccion WHERE activo = 0";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }
        return $data;
    }

    public function Direccion($id) { //funciona
        try {
            // Consulta SQL
            $sql = "SELECT * FROM direccion WHERE id_direccion = ?";
          
            // Ejecutar consulta con parámetros
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
    
            // Verificar si se encontraron resultados
            if ($stmt->rowCount() > 0) {
                // Retornar resultado único
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                // Retornar null si no se encontraron resultados
                return null;
            }
        } catch (PDOException $e) {
            // Manejar errores de PDO
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return null;
        }
    }    

    public function createDireccion($data) {
        // Establecer la zona horaria de Tepic, Nayarit, México
        date_default_timezone_set('America/Mazatlan');
        
        // Obtener la fecha y hora actuales
        $nombre_direccion = $data['nombre_direccion'];
        $logo = $data['logo'];
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $activo = 1;
    
        // Preparar y ejecutar la consulta SQL
        $sql = "INSERT INTO direccion (nombre_direccion, logo, fecha, hora, activo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nombre_direccion, PDO::PARAM_STR);
        $stmt->bindParam(2, $logo, PDO::PARAM_STR);
        $stmt->bindParam(3, $fecha, PDO::PARAM_STR);
        $stmt->bindParam(4, $hora, PDO::PARAM_STR);
        $stmt->bindParam(5, $activo, PDO::PARAM_INT);
    
        return $stmt->execute();
    }    

    public function updateDireccion($data) {
        $id = $data['id'];
        $nombre_direccion = $data['nombre_direccion'];
        $logo_url = isset($data['logo_url']) ? $data['logo_url'] : null;
        
        try {
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
    
            $sql = "UPDATE direccion SET 
                        nombre_direccion = ?, 
                        fecha = ?, 
                        hora = ?";
    
            if ($logo_url) {
                $sql .= ", logo = ?";
            }
    
            $sql .= " WHERE id_direccion = ?";
    
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(1, $nombre_direccion, PDO::PARAM_STR);
            $stmt->bindParam(2, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(3, $hora, PDO::PARAM_STR);
    
            if ($logo_url) {
                $stmt->bindParam(4, $logo_url, PDO::PARAM_STR);
                $stmt->bindParam(5, $id, PDO::PARAM_INT);
            } else {
                $stmt->bindParam(4, $id, PDO::PARAM_INT);
            }
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating direccion: " . $e->getMessage());
            return false;
        }
    }    

    public function updateActivo($id, $activo) {
        $sql = "UPDATE direccion SET activo = ? WHERE id_direccion = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $activo, PDO::PARAM_INT);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);
    
        return $stmt->execute();
    }

    public function deleteDireccion($id) { //funciona
        $sql = "DELETE FROM direccion WHERE id_direccion = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
    
        // Ejecutar la eliminación y retornar el resultado
        return $stmt->execute();
    }

    private function formatDate($date) {
        $dateTime = new DateTime($date);
        return $dateTime->format('d-m-Y');
    }
    
    private function formatTime($time) {
        $dateTime = new DateTime($time);
        return $dateTime->format('h:i:s A');
    }
}

?>
