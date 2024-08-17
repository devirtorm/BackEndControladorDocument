<?php

use MVC\Model;

class ModelsMacroprocesos extends Model
{
    public function allMacroprocesos() {
        // sql statement
        $sql = "SELECT * FROM " . DB_PREFIX . "macroproceso";
    
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

    public function macroprocesos($activo) {
        // sql statement
        $sql = "SELECT * FROM " . DB_PREFIX . "macroproceso WHERE activo = $activo";
    
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

    public function TodoslosMacroprocesos()
    {
        $sql = "SELECT * FROM macroproceso";
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

    public function MacroprocesosActivos()
    {
        $sql = "SELECT * FROM macroproceso WHERE activo = 1";
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

    public function MacroprocesosInactivos()
    {
        $sql = "SELECT * FROM macroproceso WHERE activo = 0";
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

    public function Macroproceso($id)
    {
        try {
            $sql = "SELECT * FROM macroproceso WHERE id_macroproceso = ?";
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

    public function createMacroproceso($data)
    {
        date_default_timezone_set('America/Mazatlan');

        $macroproceso = $data['macroproceso'];
        $proposito = $data['proposito'];
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $activo = 1;

        $sql = "INSERT INTO macroproceso (macroproceso, proposito, fecha, hora, activo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $macroproceso, PDO::PARAM_STR);
        $stmt->bindParam(2, $proposito, PDO::PARAM_STR);
        $stmt->bindParam(3, $fecha, PDO::PARAM_STR);
        $stmt->bindParam(4, $hora, PDO::PARAM_STR);
        $stmt->bindParam(5, $activo, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateMacroproceso($id, $macroproceso, $proposito)
    {
        $sql = "UPDATE macroproceso SET macroproceso = ?, proposito = ? WHERE id_macroproceso = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $macroproceso, PDO::PARAM_STR);
        $stmt->bindParam(2, $proposito, PDO::PARAM_STR);
        $stmt->bindParam(3, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateActivo($id, $activo)
    {
        $sql = "UPDATE macroproceso SET activo = ? WHERE id_macroproceso = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $activo, PDO::PARAM_INT);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteMacroproceso($id)
    {
        $sql = "DELETE FROM macroproceso WHERE id_macroproceso = ?";
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