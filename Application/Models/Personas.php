<?php 

use MVC\Model;

class ModelsPersonas extends Model {
    public function getAllPersons() {
        // sql statement
        $sql = "SELECT * FROM " . DB_PREFIX . "persona";

        // exec query
        $query = $this->db->query($sql);

        $data = [];
        // Ensure page_data is defined if needed, otherwise remove this line
        // $data['page_data'] = $page_data;

        // Initialize books as an empty array
        $data['personas'] = [];

        // Conclusion
        if ($query->num_rows) {
            foreach($query->rows as $value) {
                $data['personas'][] = [
                    'persona'    => $value,
                ];
            }
        } else {
            $data['personas'][] = [
                'persona'    => [],
            ];
        }

        return $data;
    }

    public function insertPerson($personData) {
        // Extract person data
        $nombres = $personData['nombres'];
        $primer_apellido = $personData['primer_apellido'];
        $segundo_apellido = $personData['segundo_apellido'];
        $telefono = $personData['telefono'];
        $correo = $personData['correo'];
        $contrasenia = $personData['contrasenia'];
        $rol = $personData['rol'];
        $fecha = $personData['fecha'];
        $hora = $personData['hora'];
        $activo = $personData['activo'];
        
        try {
            // Prepare SQL statement
            $sql = "INSERT INTO " . DB_PREFIX . "persona (nombres, primer_apellido, segundo_apellido, telefono, correo, contrasenia, rol, fecha, hora, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            // Bind parameters
            $stmt->bindParam(1, $nombres);
            $stmt->bindParam(2, $primer_apellido);
            $stmt->bindParam(3, $segundo_apellido);
            $stmt->bindParam(4, $telefono);
            $stmt->bindParam(5, $correo);
            $stmt->bindParam(6, $contrasenia);
            $stmt->bindParam(7, $rol);
            $stmt->bindParam(8, $fecha);
            $stmt->bindParam(9, $hora);
            $stmt->bindParam(10, $activo);
    
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
    
}