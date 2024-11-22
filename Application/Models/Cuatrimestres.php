<?php

use MVC\Model;

class ModelsCuatrimestres extends Model
{
    public function Cuatrimestres()
    {
        $sql = "SELECT *
                FROM cuatrimestre";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }
        return $data;
    }

    public function CuatrimestresActivos()
    {
        $sql = "SELECT *
                FROM cuatrimestre
                WHERE activo = 1";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }

        return $data;
    }

    public function CuatrimestresInactivos()
    {
        $sql = "SELECT *
                FROM cuatrimestre
                WHERE activo = 0";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }
        return $data;
    }

    public function Cuatrimestre($id)
    {
        try {
            $sql = "SELECT * 
                    FROM cuatrimestre
                    WHERE id_cuatrimestre = ?";
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

    public function createCuatrimestre($data)
    {
        date_default_timezone_set('America/Mazatlan');

        $nombre_cuatrimestre = $data['nombre_cuatrimestre'];
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $activo = 1;

        $sql = "INSERT INTO cuatrimestre (nombre_cuatrimestre, fecha, hora, activo) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nombre_cuatrimestre, PDO::PARAM_STR);
        $stmt->bindParam(2, $fecha, PDO::PARAM_STR);
        $stmt->bindParam(3, $hora, PDO::PARAM_STR);
        $stmt->bindParam(4, $activo, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateCuatrimestre($id, $nombre_cuatrimestre)
    {
        $sql = "UPDATE cuatrimestre SET nombre_cuatrimestre = ? WHERE id_cuatrimestre = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nombre_cuatrimestre, PDO::PARAM_STR);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateActivo($id, $activo)
    {
        $sql = "UPDATE cuatrimestre SET activo = ? WHERE id_cuatrimestre = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $activo, PDO::PARAM_INT);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteCuatrimestre($id)
    {
        $sql = "DELETE FROM cuatrimestre WHERE id_cuatrimestre = ?";
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
 