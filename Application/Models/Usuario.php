<?php 

use MVC\Model;

class ModelsUsuario extends Model {

    public function usuario($activo) {
        // sql statement
        $sql = "SELECT 
    u.*,
    r.nombre_rol,
    d.nombre_departamento
FROM 
    usuario u
JOIN 
    rol r ON u.fk_rol = r.id_rol
JOIN 
    departamento d ON u.fk_departamento = d.id_departamento
WHERE 
    u.activo = $activo
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
    
    public function insertUsuario($usuarioData) {
        // Extract person data
        $correo = $usuarioData['correo'];
        $contrasenia = $usuarioData['contrasenia'];
        $fk_departamento = $usuarioData['fk_departamento'];
        $fk_rol = $usuarioData['fk_rol'];
    
        try {
            // Get current date and time
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;


            
            // Prepare SQL statement
            $sql = "INSERT INTO " . DB_PREFIX . "usuario(correo, contrasenia, fecha, hora, activo,fk_departamento,fk_rol) VALUES (?, ?, ?, ?, ?,?,?)";
            $stmt = $this->db->prepare($sql);
    
            // Bind parameters
            $stmt->bindParam(1, $correo, PDO::PARAM_STR);
            $stmt->bindParam(2, $contrasenia, PDO::PARAM_STR);
          
            $stmt->bindParam(3, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(4, $hora, PDO::PARAM_STR);
            $stmt->bindParam(5, $activo, PDO::PARAM_STR);
            $stmt->bindParam(6, $fk_departamento, PDO::PARAM_STR);
            $stmt->bindParam(7, $fk_rol, PDO::PARAM_STR);
    
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


    public function updateUsuario($procesoData) {
        $id = $procesoData['id'];
        $fk_departamento = $procesoData['fk_departamento']; // Asumimos que estás pasando el ID del departamento
        $correo = $procesoData['correo'];
        $fk_rol = $procesoData['fk_rol']; // Asumimos que estás pasando el ID del rol
    
        try {
            $sql = "UPDATE " . DB_PREFIX . "usuario SET fk_departamento = ?, correo = ?, fk_rol = ? WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(1, $fk_departamento, PDO::PARAM_INT); // Cambiado a INT
            $stmt->bindParam(2, $correo, PDO::PARAM_STR);
            $stmt->bindParam(3, $fk_rol, PDO::PARAM_INT); // Cambiado a INT
            $stmt->bindParam(4, $id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating usuario: " . $e->getMessage());
            return false;
        }
    }
    
    


    public function eliminarUsuario($id) {
        // Escapar el id para evitar inyecciones SQL
        $id = (int)$id;
        
    
        // sql statement
        $sql = "DELETE FROM " . DB_PREFIX . "usuario WHERE id_usuario = " . $id;
    
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
    
    public function obtenerUsuarioPorToken($token) {
        $this->limpiarTokensExpirados();
        $sql = "SELECT u.* FROM " . DB_PREFIX . "usuario u
                INNER JOIN " . DB_PREFIX . "token_recuperacion t ON u.id_usuario = t.id_usuario
                WHERE t.token = ? AND t.expiracion > NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $token, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function actualizarContraseña($userId, $nuevaContrasenia) {
        $sql = "UPDATE " . DB_PREFIX . "usuario SET contrasenia = ? WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nuevaContrasenia, PDO::PARAM_STR);
        $stmt->bindParam(2, $userId, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            // Si la actualización fue exitosa, eliminar el token
           
            return true;
        }
        return false;
    }

 
}
    
    
