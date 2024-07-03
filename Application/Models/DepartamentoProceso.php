<?php 

use MVC\Model;

class ModelsDepartamentoProceso  extends Model {

    public function departamentoproceso($activo) {
        // sql statement
        $sql = "SELECT 
    dp.id_departamentoProceso,
    d.nombre_departamento,
    p.proceso,
    dp.fecha,
    dp.hora,
    dp.activo
FROM 
    departamentoProceso dp
JOIN 
    departamento d ON dp.fk_departamento = d.id_departamento
JOIN 
    proceso p ON dp.fk_proceso = p.id_proceso;

";
    
        // exec query
        $query = $this->db->query($sql);
    
        // Initialize data as an empty array
        $data = [];
    
        // Check if there are any rows
        if ($query->num_rows) {
            foreach($query->rows as $value) {
                $data['data'][] = $value;
            }
        } else {
            $data['data'] = [];
        }
    
        // Return the data array
        return $data;
    }
    public function proceso($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "proceso WHERE id_proceso = $id");

        $data = [];

        if ($query->num_rows) {
            foreach($query->rows as $value) {
                $data['data'][] = $value;
            }
        } else {
            $data['data'] = [];
        }

        return $data;
    }   
    public function buscarUsuarioPorCorreo($correo) {
        $sql = "SELECT * FROM " . DB_PREFIX . "usuario WHERE correo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $correo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function limpiarTokensExpirados() {
        $sql = "DELETE FROM " . DB_PREFIX . "token_recuperacion WHERE expiracion < NOW()";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    public function guardarToken($idUsuario, $token, $expiracion) {
        $this->limpiarTokensExpirados();
        $sql = "INSERT INTO " . DB_PREFIX . "token_recuperacion (id_usuario, token, expiracion) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(2, $token, PDO::PARAM_STR);
        $stmt->bindParam(3, $expiracion, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    public function insertDepartamento($usuarioData) {
        // Extract person data
        $fk_departamento = $usuarioData['fk_departamento'];
        $fk_proceso = $usuarioData['fk_proceso'];
    
    
        try {
            // Get current date and time
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;


            
            // Prepare SQL statement
            $sql = "INSERT INTO " . DB_PREFIX . "departamentoProceso(fk_departamento, fk_proceso, fecha, hora, activo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            // Bind parameters
            $stmt->bindParam(1, $fk_departamento, PDO::PARAM_STR);
            $stmt->bindParam(2, $fk_proceso, PDO::PARAM_STR);
          
            $stmt->bindParam(3, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(4, $hora, PDO::PARAM_STR);
            $stmt->bindParam(5, $activo, PDO::PARAM_STR);
        
    
            // Execute the query
            $stmt->execute();
    
            // Check if the query was successful
            if ($stmt->rowCount() > 0) {
                // Person inserted successfully
                return true;
            } else {
                // Failed to insert person
                return false;
            }
        } catch (PDOException $e) {
            // Handle any potential errors here
            return false;
        }
    }


    public function updateProceso($procesoData) {
        $id = $procesoData['id'];
        $proceso = $procesoData['proceso'];
        $proposito = $procesoData['proposito'];
    
        try {
            $sql = "UPDATE " . DB_PREFIX . "proceso SET proceso = ?, proposito = ? WHERE id_proceso = ?";
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(1, $proceso, PDO::PARAM_STR);
            $stmt->bindParam(2, $proposito, PDO::PARAM_STR);
            $stmt->bindParam(3, $id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating proceso: " . $e->getMessage());
            return false;
        }
    }


    public function eliminarProceso($id) {
        // Escapar el id para evitar inyecciones SQL
        $id = (int)$id;
    
        // sql statement
        $sql = "DELETE FROM " . DB_PREFIX . "proceso WHERE id_proceso = " . $id;
    
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (eliminada)
        return $stmt->rowCount() > 0;
    }


    public function actualizarActivo($id, $activo) {
        // Escapar el id y el valor de activo para evitar inyecciones SQL
        $id = (int)$id;
        $activo = (int)$activo;
    
        // sql statement
        $sql = "UPDATE " . DB_PREFIX . "usuario SET activo = :activo WHERE id_usuario = :id";
    
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    
    
    
    
}