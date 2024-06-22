<?php 

use MVC\Model;

class ModelsDocumentos extends Model {

    public function documentos($activo) {
        // sql statement
        $sql = "SELECT * FROM " . DB_PREFIX . "documento WHERE activo = $activo";
    
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

    public function documento($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "documento WHERE id_documento = $id");

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
    
    
    public function insertDocumento($data) {
        // Extract person data
        $titulo = $data['titulo'];
        $fk_departamento = $data['fk_departamento'];
        $fk_categoria = $data['fk_categoria'];
        $fk_tipo_documento = $data['fk_tipo_documento'];
        $fk_subproceso = $data['fk_subproceso'];
        $archivo_url = $data['archivo_url'];

        try {
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            
            $sql = "INSERT INTO " . DB_PREFIX . "documento (titulo, url, fk_departamento, fk_categoria, fk_tipo_documento, fk_subproceso, fecha, hora) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(1, $titulo, PDO::PARAM_STR);
            $stmt->bindParam(2, $archivo_url, PDO::PARAM_STR);
            $stmt->bindParam(3, $fk_departamento, PDO::PARAM_INT);
            $stmt->bindParam(4, $fk_categoria, PDO::PARAM_INT);
            $stmt->bindParam(5, $fk_tipo_documento, PDO::PARAM_STR);
            $stmt->bindParam(6, $fk_subproceso, PDO::PARAM_STR);
            $stmt->bindParam(7, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(8, $hora, PDO::PARAM_STR);
    
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }


    public function updateDocumento($data) {
        $id = $data['id'];
        $titulo = $data['titulo'];
        $fk_departamento = $data['fk_departamento'];
        $fk_categoria = $data['fk_categoria'];
        $fk_tipo_documento = $data['fk_tipo_documento'];
        $fk_subproceso = $data['fk_subproceso'];
        $archivo_url = isset($data['archivo_url']) ? $data['archivo_url'] : null;
    
        try {
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
    
            $sql = "UPDATE " . DB_PREFIX . "documento SET 
                        titulo = ?, 
                        url = ?, 
                        fk_departamento = ?, 
                        fk_categoria = ?, 
                        fk_tipo_documento = ?, 
                        fk_subproceso = ?, 
                        fecha = ?, 
                        hora = ? 
                    WHERE id_documento = ?";
    
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(1, $titulo, PDO::PARAM_STR);
            $stmt->bindParam(2, $archivo_url, PDO::PARAM_STR);
            $stmt->bindParam(3, $fk_departamento, PDO::PARAM_INT);
            $stmt->bindParam(4, $fk_categoria, PDO::PARAM_INT);
            $stmt->bindParam(5, $fk_tipo_documento, PDO::PARAM_INT);
            $stmt->bindParam(6, $fk_subproceso, PDO::PARAM_INT);
            $stmt->bindParam(7, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(8, $hora, PDO::PARAM_STR);
            $stmt->bindParam(9, $id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating document: " . $e->getMessage());
            return false;
        }
    }
    
    


    public function eliminarArea($id) {
        // Escapar el id para evitar inyecciones SQL
        $id = (int)$id;
    
        // sql statement
        $sql = "DELETE FROM " . DB_PREFIX . "area WHERE id_area = " . $id;
    
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
        $sql = "UPDATE " . DB_PREFIX . "area SET activo = :activo WHERE id_area = :id";
    
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    
    
    
    
}