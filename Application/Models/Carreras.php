<?php

use MVC\Model;

class ModelsCarreras extends Model
{
    public function Carreras()
    {
        $sql = "SELECT c.*, d.nombre_direccion 
                FROM carrera c
                LEFT JOIN direccion d ON c.fk_direccion = d.id_direccion";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }

        return $data;
    }

    public function CarrerasActivas()
    {
        $sql = "SELECT c.*, d.nombre_direccion 
                FROM carrera c
                LEFT JOIN direccion d ON c.fk_direccion = d.id_direccion
                WHERE c.activo = 1";
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

    public function CarrerasInactivas()
    {
        $sql = "SELECT c.*, d.nombre_direccion 
                FROM carrera c
                LEFT JOIN direccion d ON c.fk_direccion = d.id_direccion
                WHERE c.activo = 0";
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

    public function Carrera($id)
    {
        try {
            $sql = "SELECT c.*, d.nombre_direccion 
                    FROM carrera c
                    LEFT JOIN direccion d ON c.fk_direccion = d.id_direccion
                    WHERE c.id_carrera = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return null;
        }
    }

    public function CarrerasPorDireccion($fk_direccion)
    {
        try {
            $sql = "SELECT c.*, d.nombre_direccion 
                    FROM carrera c
                    LEFT JOIN direccion d ON c.fk_direccion = d.id_direccion
                    WHERE c.fk_direccion = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$fk_direccion]);

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return [];
            }
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return [];
        }
    }


    public function createCarrera($data)
    {
        date_default_timezone_set('America/Mazatlan');

        $nombre_carrera = $data['nombre_carrera'];
        $fk_direccion = $data['fk_direccion'];
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $activo = 1;

        $sql = "INSERT INTO carrera (nombre_carrera, fk_direccion, fecha, hora, activo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nombre_carrera, PDO::PARAM_STR);
        $stmt->bindParam(2, $fk_direccion, PDO::PARAM_INT);
        $stmt->bindParam(3, $fecha, PDO::PARAM_STR);
        $stmt->bindParam(4, $hora, PDO::PARAM_STR);
        $stmt->bindParam(5, $activo, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateCarrera($id, $nombre_carrera, $fk_direccion)
    {
        $sql = "UPDATE carrera SET nombre_carrera = ?, fk_direccion = ? WHERE id_carrera = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nombre_carrera, PDO::PARAM_STR);
        $stmt->bindParam(2, $fk_direccion, PDO::PARAM_INT);
        $stmt->bindParam(3, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateActivo($id, $activo)
    {
        $sql = "UPDATE carrera SET activo = ? WHERE id_carrera = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $activo, PDO::PARAM_INT);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteCarrera($id)
    {
        $sql = "DELETE FROM carrera WHERE id_carrera = ?";
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
