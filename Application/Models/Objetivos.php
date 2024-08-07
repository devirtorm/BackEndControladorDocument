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
    }

}