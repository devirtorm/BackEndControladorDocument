<?php 

use MVC\Model;

class ModelsObjetivos extends Model {
    public function getObjetivos($activo = 1) {
        $sql = "SELECT 
            o.id, o.numero, o.descripcion,o.fecha,
            i.id as indicador_id, i.nombre as indicador_nombre
        FROM 
            objetivos o
        LEFT JOIN 
            indicadores i ON o.id = i.objetivo_id
        WHERE 
            o.activo = $activo
        ORDER BY 
            o.numero, i.id";
    
        $query = $this->db->query($sql);
    
        $data = [];
    
        if ($query->num_rows) {
            $objetivos = [];
            foreach($query->rows as $row) {
                $objetivoId = $row['id'];
                if (!isset($objetivos[$objetivoId])) {
                    $objetivos[$objetivoId] = [
                        'id' => $row['id'],
                        'numero' => $row['numero'],
                        'descripcion' => $row['descripcion'],
                        'fecha' => $row['fecha'],
                        'indicadores' => []
                    ];
                }
                if ($row['indicador_id']) {
                    $objetivos[$objetivoId]['indicadores'][] = [
                        'id' => $row['indicador_id'],
                        'nombre' => $row['indicador_nombre']
                    ];
                }
            }
            $data['data'] = array_values($objetivos);
        } else {
            $data['data'] = [];
        }
    
        return $data;
    }
    public function getObjeti($activo = 0) {
        $sql = "SELECT * FROM objetivos where activo = '0'";
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
        $sql = "UPDATE " . DB_PREFIX . "objetivos SET activo = :activo WHERE id = :id";
    
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
        $sqlIndicadores = "DELETE FROM " . DB_PREFIX . "indicadores WHERE objetivo_id = " .$id;
        $sql = "DELETE FROM " . DB_PREFIX . "objetivos WHERE id = " . $id;
    
        // Preparar y ejecutar la consulta
        $stmts = $this->db->prepare($sqlIndicadores);
        $stmts->execute();
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    
        // Verificar si la fila fue afectada (eliminada)
        return $stmt->rowCount() > 0;
    }public function insertarObjetivo($data)
    {
        try {
            error_log("Iniciando inserción de objetivo");
    
            // Obtener fecha y hora actual
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;
    
            // Insertar el objetivo
            $sql = "INSERT INTO objetivos (numero, descripcion, fecha, hora, active_tab, activo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            // Enlazar parámetros
            $stmt->bindParam(1, $data['numero'], PDO::PARAM_INT);
            $stmt->bindParam(2, $data['descripcion'], PDO::PARAM_STR);
            $stmt->bindParam(3, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(4, $hora, PDO::PARAM_STR);
            $stmt->bindParam(5, $data['active_tab'], PDO::PARAM_INT);
            $stmt->bindParam(6, $activo, PDO::PARAM_INT);
            
            // Ejecutar la consulta
            $result = $stmt->execute();
    
            if (!$result) {
                error_log("Error al insertar objetivo: " . print_r($stmt->errorInfo(), true));
                return false;
            }
    
            // Obtener el ID del objetivo recién insertado
            $sqlGetId = "SELECT MAX(id) as last_id FROM objetivos WHERE numero = ? AND descripcion = ?";
            $stmtGetId = $this->db->prepare($sqlGetId);
            $stmtGetId->bindParam(1, $data['numero'], PDO::PARAM_INT);
            $stmtGetId->bindParam(2, $data['descripcion'], PDO::PARAM_STR);
            $stmtGetId->execute();
            $row = $stmtGetId->fetch(PDO::FETCH_ASSOC);
            $objetivoId = $row['last_id'];
    
            if (!$objetivoId) {
                error_log("Error al obtener el ID del objetivo recién insertado");
                return false;
            }
    
            // Insertar los indicadores
            $stmtIndicador = "INSERT INTO indicadores (objetivo_id, nombre, fecha, hora, activo) VALUES (?, ?, ?, ?, ?)";
            $stmtI = $this->db->prepare($stmtIndicador);
            foreach ($data['indicadores'] as $indicador) {
                // Enlazar parámetros
                $stmtI->bindParam(1, $objetivoId, PDO::PARAM_INT);
                $stmtI->bindParam(2, $indicador['nombre'], PDO::PARAM_STR);
                $stmtI->bindParam(3, $fecha, PDO::PARAM_STR);
                $stmtI->bindParam(4, $hora, PDO::PARAM_STR);
                $stmtI->bindParam(5, $activo, PDO::PARAM_INT);
    
                $resultIndicador = $stmtI->execute();
                if (!$resultIndicador) {
                    error_log("Error al insertar indicador: " . print_r($stmtI->errorInfo(), true));
                    // Nota: No podemos hacer rollback aquí, así que solo registramos el error y continuamos
                }
            }
    
            error_log("Proceso de inserción completado");
            return true;
    
        } catch (Exception $e) {
            error_log("Error en insertarObjetivo: " . $e->getMessage());
            return false;
        }
    }public function updateObjetivo($data)
    {
        try {
            error_log("Iniciando actualización de objetivo");
    
            // Obtener fecha y hora actual
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $activo = 1;
    
            // Verificar si se proporciona el ID
            if (!isset($data['id']) || empty($data['id'])) {
                error_log("ID no proporcionado o inválido.");
                return false;
            }
    
            // Actualizar el objetivo
            $sql = "UPDATE objetivos SET numero = ?, descripcion = ?, fecha = ?, hora = ?, active_tab = ?, activo = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
    
            // Enlazar parámetros
            $stmt->bindParam(1, $data['numero'], PDO::PARAM_INT);
            $stmt->bindParam(2, $data['descripcion'], PDO::PARAM_STR);
            $stmt->bindParam(3, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(4, $hora, PDO::PARAM_STR);
            $stmt->bindParam(5, $data['active_tab'], PDO::PARAM_INT);
            $stmt->bindParam(6, $activo, PDO::PARAM_INT);
            $stmt->bindParam(7, $data['id'], PDO::PARAM_INT);
    
            // Ejecutar la consulta
            $result = $stmt->execute();
    
            if (!$result) {
                error_log("Error al actualizar objetivo: " . print_r($stmt->errorInfo(), true));
                return false;
            }
    
            // Eliminar indicadores existentes
            $sqlDeleteIndicators = "DELETE FROM indicadores WHERE objetivo_id = ?";
            $stmtDelete = $this->db->prepare($sqlDeleteIndicators);
            $stmtDelete->bindParam(1, $data['id'], PDO::PARAM_INT);
            $stmtDelete->execute();
    
            // Insertar los indicadores actualizados
            $stmtIndicador = "INSERT INTO indicadores (objetivo_id, nombre, fecha, hora, activo) VALUES (?, ?, ?, ?, ?)";
            $stmtI = $this->db->prepare($stmtIndicador);
            foreach ($data['indicadores'] as $indicador) {
                // Enlazar parámetros
                $stmtI->bindParam(1, $data['id'], PDO::PARAM_INT);
                $stmtI->bindParam(2, $indicador['nombre'], PDO::PARAM_STR);
                $stmtI->bindParam(3, $fecha, PDO::PARAM_STR);
                $stmtI->bindParam(4, $hora, PDO::PARAM_STR);
                $stmtI->bindParam(5, $activo, PDO::PARAM_INT);
    
                $resultIndicador = $stmtI->execute();
                if (!$resultIndicador) {
                    error_log("Error al insertar indicador: " . print_r($stmtI->errorInfo(), true));
                    // Nota: No podemos hacer rollback aquí, así que solo registramos el error y continuamos
                }
            }
    
            error_log("Proceso de actualización completado");
            return true;
    
        } catch (Exception $e) {
            error_log("Error en actualizarObjetivo: " . $e->getMessage());
            return false;
        }
    }
    
    
    

}

