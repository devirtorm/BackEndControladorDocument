<?php 

use MVC\Model;

class ModelsSubprocesos extends Model {


    public function subprocesos() {
        // sql statement
        $sql = "SELECT * FROM " . DB_PREFIX . "subproceso WHERE activo = 1";
    
        // exec query
        $query = $this->db->query($sql);
    
        // Initialize data as an empty array
        $data = [];
    
        // Check if there are any rows
        if ($query->num_rows) {
            foreach($query->rows as $value) {
                // Call the 'area' function to get the area data
                $area_data = $this->proceso($value['fk_proceso']);
    
                // Add the area data to the department data
                $value['proceso'] = $area_data['data'];
    
                // Add the department data to the result
                $data['data'][] = $value;
            }
        } else {
            $data['data'] = [];
        }
    
        // Return the data array
        return $data;
    }

  
    public function proceso($id) {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;
    
        // Construir la consulta SQL
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "proceso WHERE id_proceso = $id");
    
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
    

    public function subprocesosDesactivados() {
        // sql statement
        $sql = "SELECT * FROM " . DB_PREFIX . "subproceso WHERE activo = 0";
    
        // exec query
        $query = $this->db->query($sql);
    
        // Initialize data as an empty array
        $data = [];
    
        // Check if there are any rows
        if ($query->num_rows) {
            foreach($query->rows as $value) {
                // Call the 'area' function to get the area data
                $area_data = $this->proceso($value['fk_proceso']);
    
                // Add the area data to the department data
                $value['proceso'] = $area_data['data'];
    
                // Add the department data to the result
                $data['data'][] = $value;
            }
        } else {
            $data['data'] = [];
        }
    
        // Return the data array
        return $data;
    }

    
    
    public function insertarSubproceso($subprocesoData) {
        // Extract person data
        $subproceso = $subprocesoData['subproceso'];
        $proceso = $subprocesoData['fk_proceso'];
    
        try {
            // Get current date and time
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;


            
            // Prepare SQL statement
            $sql = "INSERT INTO " . DB_PREFIX . "subproceso (subproceso, fk_proceso, fecha, hora, activo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            // Bind parameters
            $stmt->bindParam(1, $subproceso, PDO::PARAM_STR);
            $stmt->bindParam(2, $proceso, PDO::PARAM_STR);
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

    public function updateSubproceso($subprocesoData) {
        $id = $subprocesoData['id'];
        $subproceso = $subprocesoData['subproceso'];
        $proceso = $subprocesoData['fk_proceso'];
    
        try {
            $sql = "UPDATE " . DB_PREFIX . "subproceso SET subproceso = ?, fk_proceso = ? WHERE id_subproceso = ?";
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(1, $subproceso, PDO::PARAM_STR);
            $stmt->bindParam(2, $proceso, PDO::PARAM_INT);
            $stmt->bindParam(3, $id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating area: " . $e->getMessage());
            return false;
        }
    }


    public function eliminarSubproceso($id) {
        // Escapar el id para evitar inyecciones SQL
        $id = (int)$id;
    
        // sql statement
        $sql = "DELETE FROM " . DB_PREFIX . "subproceso WHERE id_subproceso = " . $id;
    
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
        $sql = "UPDATE " . DB_PREFIX . "subproceso SET activo = :activo WHERE id_subproceso = :id";
    
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    
    
    
    
}