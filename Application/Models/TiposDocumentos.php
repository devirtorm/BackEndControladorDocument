<?php 

use MVC\Model;


class ModelsTiposDocumentos extends Model {

    public function tiposDocumentos($activo) {
        // sql statement
        $sql = "SELECT * FROM " . DB_PREFIX . "tipo_documento WHERE activo = $activo";
    
        // exec query
        $query = $this->db->query($sql);
    
        // Initialize data as an empty array
        $data = [];
    
        // Check if there are any rows
        if ($query->num_rows) {
            foreach($query->rows as $value) {
                // Add the document type data to the result
                $data['data'][] = $value;
            }
        } else {
            $data['data'] = [];
        }
    
        // Return the data array
        return $data;
    }

    
    
    public function tipoDocumento($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tipo_documento WHERE id_tipo = $id");

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
    
    
    public function insertTipoDocumento($Data) {
        // Extract person data
        $tipo_documento = $Data['tipo_documento'];


    
        try {
            // Get current date and time
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;


            
            // Prepare SQL statement
            $sql = "INSERT INTO " . DB_PREFIX . "tipo_documento (tipo_documento, fecha, hora, activo) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            // Bind parameters
            $stmt->bindParam(1, $tipo_documento, PDO::PARAM_STR);
            $stmt->bindParam(2, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(3, $hora, PDO::PARAM_STR);
            $stmt->bindParam(4, $activo, PDO::PARAM_STR);
    
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


    public function updateTipoDocumento($data) {
        $id = $data['id'];
        $tipo_documento = $data['tipo_documento'];
    
        try {
            $sql = "UPDATE " . DB_PREFIX . "tipo_documento SET tipo_documento = ? WHERE id_tipo = ?";
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(1, $tipo_documento, PDO::PARAM_STR);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("error al actualizar tipo de documentos: " . $e->getMessage());
            return false;
        }
    }


    public function eliminarTipoDocumento($id) {
        // Escapar el id para evitar inyecciones SQL
        $id = (int)$id;
    
        // sql statement
        $sql = "DELETE FROM " . DB_PREFIX . "tipo_documento WHERE id_tipo = " . $id;
    
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
        $sql = "UPDATE " . DB_PREFIX . "tipo_documento SET activo = :activo WHERE id_tipo = :id";
    
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    
    
    
    
}