<?php 

use MVC\Model;


class ModelsCategorias extends Model {

    public function categorias($activo) {
        $sql = "SELECT * FROM " . DB_PREFIX . "categoria WHERE activo = " . (int)$activo;

        $query = $this->db->query($sql);
    
        // inicializar los datos con array vacio
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
    


    
    public function departamento($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "categoria WHERE id_categoria = $id");

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
    
    
    public function insertCategoria($areaData) {
        // Extract person data
        $nombre_categoria = $areaData['nombre_categoria'];

    
        try {
            // Get current date and time
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;


            
            // Prepare SQL statement
            $sql = "INSERT INTO " . DB_PREFIX . "categoria (nombre_categoria, fecha, hora, activo) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            // Bind parameters
            $stmt->bindParam(1, $nombre_categoria, PDO::PARAM_STR);
            $stmt->bindParam(2, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(3, $hora, PDO::PARAM_STR);
            $stmt->bindParam(4, $activo, PDO::PARAM_INT);
    
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


    public function updateCategoria($categoriaData) {
        $id = $categoriaData['id'];
        $nombre_categoria = $categoriaData['nombre_categoria'];
    
        try {
            // Corrección de la consulta SQL
            $sql = "UPDATE " . DB_PREFIX . "categoria SET nombre_categoria = ? WHERE id_categoria = ?";
            $stmt = $this->db->prepare($sql);
    
            // Corrección de la vinculación de parámetros
            $stmt->bindParam(1, $nombre_categoria, PDO::PARAM_STR);
            $stmt->bindParam(2, $id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar categoría: " . $e->getMessage());
            return false;
        }
    }
    


    public function eliminarCategoria($id) {
        // Escapar el id para evitar inyecciones SQL
        $id = (int)$id;
    
        // sql statement
        $sql = "DELETE FROM " . DB_PREFIX . "categoria WHERE id_categoria = " . $id;
    
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
        $sql = "UPDATE " . DB_PREFIX . "categoria SET activo = :activo WHERE id_categoria = :id";
    
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    
    
    
    
}