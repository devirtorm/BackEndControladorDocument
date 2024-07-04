<?php 

use MVC\Model;


class ModelsDepartamentos extends Model {

    public function departamentos($activo) {
        // sql statement
        $sql = "SELECT d.*, a.nombre_area
FROM " . DB_PREFIX . "departamento d
JOIN area a ON d.fk_area = a.id_area
WHERE d.activo = $activo";
    
        // exec query
        $query = $this->db->query($sql);
    
        // Initialize data as an empty array
        $data = [];
    
    
        // consultar si hay alguna fila
        if ($query->num_rows) {
            foreach($query->rows as $value) {
                // agregar datos de categorias al resultado
                $data['data'][] = $value;
            }
        } else {
            $data['data'] = [];
        }
    
        // Retorna los datos del array
        return $data;
    
      
    }

    public function persona($id) {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;
    
        // Construir la consulta SQL
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "persona WHERE id_persona = $id");
    
        $data = [];
    
        // Verificar si hay alguna fila en el resultado
        if ($query->num_rows) {
            // Obtener la primera fila (ya que se espera solo una persona con un ID específico)
            $data['data'] = $query->row;
        } else {
            // Devolver un array vacío si no se encuentra ninguna persona con el ID dado
            $data['data'] = [];
        }
    
        // Devolver el array de datos
        return $data;
    }

    public function area($id) {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;
    
        // Construir la consulta SQL
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "area WHERE id_area = $id");
    
        $data = [];
    
        // Verificar si hay alguna fila en el resultado
        if ($query->num_rows) {
            // Obtener la primera fila (ya que se espera solo una persona con un ID específico)
            $data['data'] = $query->row;
        } else {
            // Devolver un array vacío si no se encuentra ninguna persona con el ID dado
            $data['data'] = [];
        }
    
        // Devolver el array de datos
        return $data;
    }
    
    
    public function departamento($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "departamento WHERE id_departamento = $id");

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
    
    
    public function insertDepartamento($departamentoData) {
        // Extract department data
        $nombre_departamento = $departamentoData['nombre_departamento'];
        $fk_area = $departamentoData['fk_area'];
    
        try {
            // Get current date and time
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;
    
            // Prepare SQL statement
            $sql = "INSERT INTO " . DB_PREFIX . "departamento (nombre_departamento, fk_area, fecha, hora, activo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            // Bind parameters
            $stmt->bindParam(1, $nombre_departamento, PDO::PARAM_STR); 
            $stmt->bindParam(2, $fk_area, PDO::PARAM_STR);
            $stmt->bindParam(3, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(4, $hora, PDO::PARAM_STR);
            $stmt->bindParam(5, $activo, PDO::PARAM_INT);
    
            // Execute the query
            $stmt->execute();
    
            // Check if the query was successful
            if ($stmt->rowCount() > 0) {
                // Department inserted successfully
                return true;
            } else {
                // Failed to insert department
                return false;
            }
        } catch (PDOException $e) {
            // Handle any potential errors here
            error_log("Error en insertDepartamento: " . $e->getMessage());
            return false;
        }
    }

    public function updateDepartamento($departamentoData) {
        $id = $departamentoData['id'];
        $nombre_departamento = $departamentoData['nombre_departamento'];
        $fk_area = $departamentoData['fk_area'];
    
        try {
            $sql = "UPDATE " . DB_PREFIX . "departamento SET nombre_departamento = ?, fk_area = ? WHERE id_departamento = ?";
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(1, $nombre_departamento, PDO::PARAM_STR);
            $stmt->bindParam(2, $fk_area, PDO::PARAM_INT);
            $stmt->bindParam(3, $id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar departamento: " . $e->getMessage());
            return false;
        }
    }


    public function eliminarDepartamento($id) {
        // Escapar el id para evitar inyecciones SQL
        $id = (int)$id;
    
        // sql statement
        $sql = "DELETE FROM " . DB_PREFIX . "departamento WHERE id_departamento = " . $id;
    
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
        $sql = "UPDATE " . DB_PREFIX . "departamentoProceso SET activo = :activo WHERE id_departamentopr = :id";
    
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    
    
    
    
}