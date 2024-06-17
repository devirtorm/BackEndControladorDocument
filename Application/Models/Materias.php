<?php

use MVC\Model;

class ModelsMaterias extends Model
{
    public function Materias()
    {
        $sql = "SELECT m.*, c.nombre_cuatrimestre 
                FROM materia m
                LEFT JOIN cuatrimestre c ON m.fk_cuatrimestre = c.id_cuatrimestre";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $value['fecha'] = $this->formatDate($value['fecha']);
                $value['hora'] = $this->formatTime($value['hora']);
                $data[] = $value;
            }
        }

        return $data;
    }

    public function MateriasActivas()
    {
        $sql = "SELECT m.*, c.nombre_cuatrimestre 
                FROM materia m
                LEFT JOIN cuatrimestre c ON m.fk_cuatrimestre = c.id_cuatrimestre
                WHERE m.activo = 1";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $value['fecha'] = $this->formatDate($value['fecha']);
                $value['hora'] = $this->formatTime($value['hora']);
                $data[] = $value;
            }
        }

        return $data;
    }

    public function MateriasInactivas()
    {
        $sql = "SELECT m.*, c.nombre_cuatrimestre 
                FROM materia m
                LEFT JOIN cuatrimestre c ON m.fk_cuatrimestre = c.id_cuatrimestre
                WHERE m.activo = 0";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $value['fecha'] = $this->formatDate($value['fecha']);
                $value['hora'] = $this->formatTime($value['hora']);
                $data[] = $value;
            }
        }

        return $data;
    }

    public function Materia($id)
    {
        try {
            $sql = "SELECT m.*, c.nombre_cuatrimestre 
                    FROM materia m
                    LEFT JOIN cuatrimestre c ON m.fk_cuatrimestre = c.id_cuatrimestre
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
        $fk_cuatrimestre = $data['fk_cuatrimestre'];
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $activo = 1;

        $sql = "INSERT INTO materia (nombre_materia, archivo_materia, fk_cuatrimestre, fecha, hora, activo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nombre_materia, PDO::PARAM_STR);
        $stmt->bindParam(2, $archivo_materia, PDO::PARAM_STR);
        $stmt->bindParam(3, $fk_cuatrimestre, PDO::PARAM_INT);
        $stmt->bindParam(4, $fecha, PDO::PARAM_STR);
        $stmt->bindParam(5, $hora, PDO::PARAM_STR);
        $stmt->bindParam(6, $activo, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateMateria($id, $nombre_materia, $archivo_materia, $fk_cuatrimestre)
    {
        $sql = "UPDATE materia SET nombre_materia = ?, archivo_materia = ?, fk_cuatrimestre = ? WHERE id_materia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nombre_materia, PDO::PARAM_STR);
        $stmt->bindParam(2, $archivo_materia, PDO::PARAM_STR);
        $stmt->bindParam(3, $fk_cuatrimestre, PDO::PARAM_INT);
        $stmt->bindParam(4, $id, PDO::PARAM_INT);

        return $stmt->execute();
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
