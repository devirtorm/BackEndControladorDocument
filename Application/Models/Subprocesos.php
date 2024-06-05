<?php 

use MVC\Model;

class ModelsSubprocesos extends Model {

    public function subprocesos() {
        // sql statement
        $sql = "SELECT * FROM " . DB_PREFIX . "subproceso";

        // exec query
        $query = $this->db->query($sql);

        $data = [];
        // Ensure page_data is defined if needed, otherwise remove this line
        // $data['page_data'] = $page_data;

        // Initialize books as an empty array
        $data['data'] = [];

        // Conclusion
        if ($query->num_rows) {
            foreach($query->rows as $value) {
                $data['data'][] = [
                    'subproceso'    => $value,
                ];
            }
        } else {
            $data['data'][] = [
                'subproceso'    => [],
            ];
        }

        return $data;
    }
    
    public function insertarSubproceso($subprocesoData) {
        // Extract person data
        $subproceso = $subprocesoData['subproceso'];
    
        try {
            // Get current date and time
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;


            
            // Prepare SQL statement
            $sql = "INSERT INTO " . DB_PREFIX . "subproceso (subproceso, fecha, hora, activo) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            // Bind parameters
            $stmt->bindParam(1, $subproceso, PDO::PARAM_STR);
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
    
}