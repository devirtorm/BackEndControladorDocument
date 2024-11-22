<?php

use MVC\Model;

class ModelsMaterias extends Model
{
    public function Materias()
    {
        $sql = "SELECT m.*, cu.nombre_cuatrimestre, ca.nombre_carrera 
                FROM materia m
                LEFT JOIN carrera ca ON m.fk_carrera = ca.id_carrera
                LEFT JOIN cuatrimestre cu ON m.fk_cuatrimestre = cu.id_cuatrimestre";
        $query = $this->db->query($sql);
        $data = [];
        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }
        return $data;
    }

    public function MateriasActivas()
    {
        $sql = "SELECT m.*, cu.nombre_cuatrimestre, ca.nombre_carrera 
                FROM materia m
                LEFT JOIN carrera ca ON m.fk_carrera = ca.id_carrera
                LEFT JOIN cuatrimestre cu ON m.fk_cuatrimestre = cu.id_cuatrimestre
                WHERE m.activo = 1";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }
        return $data;
    }

    public function MateriasInactivas()
    {
        $sql = "SELECT m.*, cu.nombre_cuatrimestre, ca.nombre_carrera 
                FROM materia m
                LEFT JOIN carrera ca ON m.fk_carrera = ca.id_carrera
                LEFT JOIN cuatrimestre cu ON m.fk_cuatrimestre = cu.id_cuatrimestre
                WHERE m.activo = 0";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }
        return $data;
    }

    public function Materia($id)
    {
        try {
            $sql = "SELECT m.*, cu.nombre_cuatrimestre, ca.nombre_carrera 
                    FROM materia m
                    LEFT JOIN carrera ca ON m.fk_carrera = ca.id_carrera
                    LEFT JOIN cuatrimestre cu ON m.fk_cuatrimestre = cu.id_cuatrimestre
                    WHERE m.id_materia = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                $value = $stmt->fetch(PDO::FETCH_ASSOC);
                $value['fecha'] = $this->formatDate($value['fecha']);
                $value['hora'] = $this->formatTime($value['hora']);
                return $value;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return null;
        }
    }

    public function createMateria($data)
    {
        date_default_timezone_set('America/Mazatlan');

        $nombre_materia = $data['nombre_materia'];
        $archivo_materia = $data['archivo_materia'];
        $fk_carrera = $data['fk_carrera'];
        $fk_cuatrimestre = $data['fk_cuatrimestre'];
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $activo = 1;

        $sql = "INSERT INTO materia (nombre_materia, archivo_materia, fk_carrera, fk_cuatrimestre, fecha, hora, activo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nombre_materia, PDO::PARAM_STR);
        $stmt->bindParam(2, $archivo_materia, PDO::PARAM_STR);
        $stmt->bindParam(3, $fk_carrera, PDO::PARAM_INT);
        $stmt->bindParam(4, $fk_cuatrimestre, PDO::PARAM_INT);
        $stmt->bindParam(5, $fecha, PDO::PARAM_STR);
        $stmt->bindParam(6, $hora, PDO::PARAM_STR);
        $stmt->bindParam(7, $activo, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateMateria($data) {
        $id = $data['id'];
        $nombre_materia = $data['nombre_materia'];
        $archivo_url = isset($data['archivo_url']) ? $data['archivo_url'] : null;
        $fk_carrera = $data['fk_carrera'];
        $fk_cuatrimestre = $data['fk_cuatrimestre'];
        
        try {
            $sql = "UPDATE materia SET 
                        nombre_materia = ?, 
                        fk_carrera = ?,
                        fk_cuatrimestre = ?";
    
            if ($archivo_url) {
                $sql .= ", archivo_materia = ?";
            }
            
            $sql .= " WHERE id_materia = ?";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(1, $nombre_materia, PDO::PARAM_STR);
            $stmt->bindParam(2, $fk_carrera, PDO::PARAM_INT);
            $stmt->bindParam(3, $fk_cuatrimestre, PDO::PARAM_INT);
            
            if ($archivo_url) {
                $stmt->bindParam(4, $archivo_url, PDO::PARAM_STR);
                $stmt->bindParam(5, $id, PDO::PARAM_INT);
            } else {
                $stmt->bindParam(4, $id, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $errorMessage = "Error updating materia: " . $e->getMessage();
            error_log($errorMessage);
            echo json_encode(['message' => $errorMessage]);
            return false;
        }
    }    

    public function updateActivo($id, $activo)
    {
        $sql = "UPDATE materia SET activo = ? WHERE id_materia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $activo, PDO::PARAM_INT);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteMateria($id)
    {
        $sql = "DELETE FROM materia WHERE id_materia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    private function formatDate($date)
    {
        $dateTime = new DateTime($date);
        return $dateTime->format('d-m-Y');
    }

    private function formatTime($time)
    {
        $dateTime = new DateTime($time);
        return $dateTime->format('h:i:s A');
    }
}

?>
