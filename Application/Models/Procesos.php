<?php 

use MVC\Model;

class ModelsProcesos extends Model {

    public function procesos($activo) {
        // sql statement
        $sql = "SELECT * FROM " . DB_PREFIX . "proceso WHERE activo = $activo";
    
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

    public function getProcesosBymacroprocesoId($id) {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;
    
        // Construir la consulta SQL
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "proceso WHERE fk_macroproceso = $id AND activo = 1");
    
        $data = [];
    
        // Verificar si hay alguna fila en el resultado
        if ($query->num_rows) {
            // Obtener todas las filas
            $data['data'] = $query->rows;
        } else {
            // Devolver un array vacío si no se encuentra ninguna fila
            $data['data'] = [];
        }
    
        // Devolver el array de datos
        return $data;
    }

    public function getProcesosByDepartamento($id) {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;
    
        // Obtener el nombre del departamento
        $sqlDepartamento = "SELECT nombre_departamento FROM departamento WHERE id_departamento = $id";
        $queryDepartamento = $this->db->query($sqlDepartamento);
    
        if ($queryDepartamento->num_rows) {
            $nombre_departamento = $queryDepartamento->row['nombre_departamento'];
    
            // Verificar si el departamento es "rectoría"
            if (strtolower($nombre_departamento) === 'Calidad' || strtolower($nombre_departamento) === 'calidad') {
                // Construir la consulta SQL para todos los procesos
                $sql = "SELECT dp.fk_departamento,
                               dp.fk_proceso,
                               d.nombre_departamento,
                               p.proceso,
                               mp.macroproceso
                        FROM departamentoProceso dp
                        INNER JOIN departamento d ON dp.fk_departamento = d.id_departamento
                        INNER JOIN proceso p ON dp.fk_proceso = p.id_proceso
                        INNER JOIN macroproceso mp on p.fk_macroproceso=mp.id_macroproceso
                        WHERE d.activo = 1";
            } else {
                // Construir la consulta SQL para el departamento específico
                $sql = "SELECT dp.fk_departamento,
                               dp.fk_proceso,
                               d.nombre_departamento,
                               p.proceso,
                               mp.macroproceso
                        FROM departamentoProceso dp
                        INNER JOIN departamento d ON dp.fk_departamento = d.id_departamento
                        INNER JOIN proceso p ON dp.fk_proceso = p.id_proceso
                        INNER JOIN macroproceso mp on p.fk_macroproceso=mp.id_macroproceso
                        WHERE d.activo = 1 AND dp.fk_departamento = $id";
            }
        } else {
            // Si no se encuentra el departamento, devolver un array vacío
            return ['data' => []];
        }
    
        // Ejecutar la consulta
        $query = $this->db->query($sql);
    
        // Inicializar data como un array vacío
        $data = [];
    
        // Verificar si hay filas
        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data['data'][] = $value;
            }
        } else {
            $data['data'] = [];
        }
    
        // Devolver el array de datos
        return $data;
    }
    


/* obtener el subproceso dependiendo del proceso que seleccione en el select del front */
    public function getSubprocesosByProceso($id) {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;
        
        // Construir la consulta SQL
        $sql = "select*from subproceso
	where activo =1 and fk_proceso=$id
";
    
        // Ejecutar la consulta
        $query = $this->db->query($sql);
    
        // Inicializar data como un array vacío
        $data = [];
    
        // Verificar si hay filas
        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data['data'][] = $value;
            }
        } else {
            $data['data'] = [];
        }
    
        // Devolver el array de datos
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
    
    
    public function insertProceso($procesoData) {
        // Extract person data
        $proceso = $procesoData['proceso'];
        $proposito = $procesoData['proposito'];
    
        try {
            // Get current date and time
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;


            
            // Prepare SQL statement
            $sql = "INSERT INTO " . DB_PREFIX . "proceso (proceso, proposito, fecha, hora, activo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            // Bind parameters
            $stmt->bindParam(1, $proceso, PDO::PARAM_STR);
            $stmt->bindParam(2, $proposito, PDO::PARAM_STR);
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


    public function updateProceso($procesoData) {
        $id = $procesoData['id'];
        $proceso = $procesoData['proceso'];
        $proposito = $procesoData['proposito'];
    
        try {
            $sql = "UPDATE " . DB_PREFIX . "proceso SET proceso = ?, proposito = ? WHERE id_proceso = ?";
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(1, $proceso, PDO::PARAM_STR);
            $stmt->bindParam(2, $proposito, PDO::PARAM_STR);
            $stmt->bindParam(3, $id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating proceso: " . $e->getMessage());
            return false;
        }
    }


    public function eliminarProceso($id) {
        // Escapar el id para evitar inyecciones SQL
        $id = (int)$id;
    
        // sql statement
        $sql = "DELETE FROM " . DB_PREFIX . "proceso WHERE id_proceso = " . $id;
    
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
        $sql = "UPDATE " . DB_PREFIX . "proceso SET activo = :activo WHERE id_proceso = :id";
    
        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    
    
    
    
}