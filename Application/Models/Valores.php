<?php 

use MVC\Model;

class ModelsValores extends Model {

    public function getValores($activo = 1) {
        $sql = "SELECT * FROM valores where activo = '1'";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }

        return $data;


    }
    public function getValor($activo = 0) {
        $sql = "SELECT * FROM valores where activo = '0'";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }

        return $data;


    }
    public function actualizarActivo($id, $activo) {
        // Escapar el id y el valor de activo para evitar inyecciones SQL
        $id = (int)$id;
        $activo = (int)$activo;
    
        // sql statement
        $sql = "UPDATE " . DB_PREFIX . "valores SET activo = :activo WHERE id = :id";
    
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    public function eliminarObjetivo($id) {
        // Escapar el id para evitar inyecciones SQL
        $id = (int)$id;
    
        // sql statement

        $sql = "DELETE FROM " . DB_PREFIX . "valores WHERE id = " . $id;
    
        // Preparar y ejecutar la consulta
      
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (eliminada)
        return $stmt->rowCount() > 0;
    }public function insertUsuario($usuarioData) {
        // Extract person data
        $nombre = $usuarioData['nombre'];
        $descripcion = $usuarioData['descripcion'];
        $icono = $usuarioData['icono'];
    
        try {
            // Get current date and time
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;
    
            // Prepare SQL statement
            $sql = "INSERT INTO " . DB_PREFIX . "valores (nombre, descripcion, icono, fecha, hora, activo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            // Bind parameters
            $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
            $stmt->bindParam(2, $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(3, $icono, PDO::PARAM_STR);
            $stmt->bindParam(4, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(5, $hora, PDO::PARAM_STR);
            $stmt->bindParam(6, $activo, PDO::PARAM_INT);
    
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
            error_log("PDO Error: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateUsuario($procesoData) {
        $id = $procesoData['id'];
        $nombre = $procesoData['nombre']; // Asumimos que estás pasando el ID del departamento
        $descripcion = $procesoData['descripcion'];
        $icono = $procesoData['icono']; // Asumimos que estás pasando el ID del rol
    
        try {
            $sql = "UPDATE " . DB_PREFIX . "valores SET nombre = ?, descripcion = ?, icono = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(1, $nombre, PDO::PARAM_STR); // Cambiado a INT
            $stmt->bindParam(2, $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(3, $icono, PDO::PARAM_STR); // Cambiado a INT
            $stmt->bindParam(4, $id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating usuario: " . $e->getMessage());
            return false;
        }
    }
    public function insertValor($valorData) {
        // Extraer los datos del valor
        $nombre = $valorData['nombre'];
        $descripcion = $valorData['descripcion'];
        $icono = $valorData['icono'];

        try {
            // Obtener la fecha y hora actual
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;

            // Preparar la declaración SQL
            $sql = "INSERT INTO valores (nombre, descripcion, icono, fecha, hora, activo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            // Enlazar parámetros
            $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
            $stmt->bindParam(2, $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(3, $icono, PDO::PARAM_STR);
            $stmt->bindParam(4, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(5, $hora, PDO::PARAM_STR);
            $stmt->bindParam(6, $activo, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si la consulta fue exitosa
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // Manejar errores potenciales aquí
            error_log("Error al insertar valor: " . $e->getMessage());
            return false;
        }
    }


}