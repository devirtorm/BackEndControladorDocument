<?php 

use MVC\Model;

class ModelsPersonas extends Model {
    public function getAllPersons() {
        // sql statement
        $sql = "
    SELECT p.*, r.nombre_rol
    FROM " . DB_PREFIX . "persona p
    INNER JOIN " . DB_PREFIX . "rol r ON p.fk_rol = r.id_rol
    WHERE p.activo = '1'
";


        // exec query
        $query = $this->db->query($sql);

        $data = [];
        // Ensure page_data is defined if needed, otherwise remove this line
        // $data['page_data'] = $page_data;

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
    

    public function insertarPersona($personData) {
        // Extract person data
        $nombres = $personData['nombres'];
        $primer_apellido = $personData['primer_apellido'];
        $segundo_apellido = $personData['segundo_apellido'];
        $telefono = $personData['telefono'];
        $correo = $personData['correo'];
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $activo = 1;
        $rol = 2;

        
        try {
            // Prepare SQL statement
            $sql = "INSERT INTO " . DB_PREFIX . "persona (nombres, primer_apellido, segundo_apellido, telefono, correo, fecha, hora, activo, fk_rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            // Bind parameters
            $stmt->bindParam(1, $nombres);
            $stmt->bindParam(2, $primer_apellido);
            $stmt->bindParam(3, $segundo_apellido);
            $stmt->bindParam(4, $telefono);
            $stmt->bindParam(5, $correo);
            $stmt->bindParam(6, $fecha);
            $stmt->bindParam(7, $hora);
            $stmt->bindParam(8, $activo);
            $stmt->bindParam(9, $rol);

    
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
    public function  personasDesactivadas() {
        // sql statement
        $sql = "
    SELECT p.*, r.nombre_rol
    FROM " . DB_PREFIX . "persona p
    INNER JOIN " . DB_PREFIX . "rol r ON p.fk_rol = r.id_rol
    WHERE p.activo = '0'
";


        // exec query
        $query = $this->db->query($sql);

        $data = [];
        // Ensure page_data is defined if needed, otherwise remove this line
        // $data['page_data'] = $page_data;

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

    public function actualizarActivo($id, $activo) {
        // Escapar el id y el valor de activo para evitar inyecciones SQL
        $id = (int)$id;
        $activo = (int)$activo;
    
        // sql statement
        $sql = "UPDATE " . DB_PREFIX . "persona SET activo = :activo WHERE id_persona = :id";
    
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    public function eliminarPersona($id) {
        // Escapar el id para evitar inyecciones SQL
        $id = (int)$id;
    
        // sql statement
        $sql = "DELETE FROM " . DB_PREFIX . "persona WHERE id_persona = " . $id;
    
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (eliminada)
        return $stmt->rowCount() > 0;
    }
    
   
    
}