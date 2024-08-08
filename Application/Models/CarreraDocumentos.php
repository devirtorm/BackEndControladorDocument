<?php

use MVC\Model;

class ModelsCarreraDocumentos extends Model
{
    public function CarreraDocumentos($id)
    {
        // Obtener el nombre del departamento
        $sql1 = "SELECT dp.nombre_departamento 
                FROM usuario us 
                INNER JOIN departamento dp ON dp.id_departamento = us.fk_departamento 
                WHERE us.fk_departamento = $id";

        $query1 = $this->db->query($sql1);
        
        $nombre_departamento = '';
        if ($query1->num_rows) {
            $nombre_departamento = $query1->row['nombre_departamento'];
        }

        // Construir la consulta adecuada basada en el nombre del departamento
        if (strtolower($nombre_departamento) == 'calidad') {
            $sql = "SELECT cd.*, c.nombre_carrera, d.titulo 
                    FROM carrera_documento cd
                    LEFT JOIN carrera c ON cd.fk_carrera = c.id_carrera
                    LEFT JOIN documento d ON cd.fk_documento = d.id_documento";
        } else {
            $sql = "SELECT cd.*, c.nombre_carrera, d.titulo 
                    FROM carrera_documento cd
                    LEFT JOIN carrera c ON cd.fk_carrera = c.id_carrera
                    LEFT JOIN documento d ON cd.fk_documento = d.id_documento
                    WHERE d.fk_departamento = $id";
        }

        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data['data'][] = [
                    'id_carrera_documento' => $value['id_carrera_documento'],
                    'fk_carrera' => $value['fk_carrera'],
                    'fk_documento' => $value['fk_documento'],
                    'nombre_carrera' => $value['nombre_carrera'],
                    'nombre_documento' => $value['titulo'],
                    'tsu' => $value['tsu'],
                    'ing' => $value['ing'],
                    'fecha' => $this->formatDate($value['fecha']),
                    'hora' => $this->formatTime($value['hora']),
                    'activo' => $value['activo'],
                ];
            }
        } else {
            $data['data'] = [];
        }

        return $data;
    }

    public function CarreraDocumento($id)
    {
        try {
            $sql = "SELECT cd.*, c.nombre_carrera, d.titulo 
                    FROM carrera_documento cd
                    LEFT JOIN carrera c ON cd.fk_carrera = c.id_carrera
                    LEFT JOIN documento d ON cd.fk_documento = d.id_documento
                    WHERE id_carrera_documento = ? AND cd.activo = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "Datos encontrados: ";
                return;
            }
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return null;
        }
    }

    public function CarreraDocumentoporCarrera($id)
    {
        try {
            $sql = "SELECT cd.*, c.nombre_carrera, d.titulo, d.url 
                    FROM carrera_documento cd
                    LEFT JOIN carrera c ON cd.fk_carrera = c.id_carrera
                    LEFT JOIN documento d ON cd.fk_documento = d.id_documento
                    WHERE c.id_carrera = ? AND cd.activo = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                echo "Datos encontrados: ";
                return [];
            }
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return null;
        }
    }

    public function CarreraDocumentosActivas($id)
    {
        // Obtener el nombre del departamento
        $sql1 = "SELECT dp.nombre_departamento 
                FROM usuario us 
                INNER JOIN departamento dp ON dp.id_departamento = us.fk_departamento 
                WHERE us.fk_departamento = $id";

        $query1 = $this->db->query($sql1);
        
        $nombre_departamento = '';
        if ($query1->num_rows) {
            $nombre_departamento = $query1->row['nombre_departamento'];
        }

        // Construir la consulta adecuada basada en el nombre del departamento
        if (strtolower($nombre_departamento) == 'calidad') {
            $sql = "SELECT cd.*, c.nombre_carrera, d.titulo 
                    FROM carrera_documento cd
                    LEFT JOIN carrera c ON cd.fk_carrera = c.id_carrera
                    LEFT JOIN documento d ON cd.fk_documento = d.id_documento
                    WHERE cd.activo = 1";
        } else {
            $sql = "SELECT cd.*, c.nombre_carrera, d.titulo 
                    FROM carrera_documento cd
                    LEFT JOIN carrera c ON cd.fk_carrera = c.id_carrera
                    LEFT JOIN documento d ON cd.fk_documento = d.id_documento
                    WHERE d.fk_departamento = $id AND cd.activo = 1";
        }

        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data['data'][] = [
                    'id_carrera_documento' => $value['id_carrera_documento'],
                    'fk_carrera' => $value['fk_carrera'],
                    'fk_documento' => $value['fk_documento'],
                    'nombre_carrera' => $value['nombre_carrera'],
                    'nombre_documento' => $value['titulo'],
                    'tsu' => $value['tsu'],
                    'ing' => $value['ing'],
                    'fecha' => $this->formatDate($value['fecha']),
                    'hora' => $this->formatTime($value['hora']),
                    'activo' => $value['activo']
                ];
            }
        } else {
            $data['data'] = [];
        }

        return $data;
    }

    public function CarreraDocumentosInactivas($id)
    {
        // Obtener el nombre del departamento
        $sql1 = "SELECT dp.nombre_departamento 
                FROM usuario us 
                INNER JOIN departamento dp ON dp.id_departamento = us.fk_departamento 
                WHERE us.fk_departamento = $id";

        $query1 = $this->db->query($sql1);
        
        $nombre_departamento = '';
        if ($query1->num_rows) {
            $nombre_departamento = $query1->row['nombre_departamento'];
        }

        // Construir la consulta adecuada basada en el nombre del departamento
        if (strtolower($nombre_departamento) == 'calidad') {
            $sql = "SELECT cd.*, c.nombre_carrera, d.titulo 
                    FROM carrera_documento cd
                    LEFT JOIN carrera c ON cd.fk_carrera = c.id_carrera
                    LEFT JOIN documento d ON cd.fk_documento = d.id_documento
                    WHERE cd.activo = 0";
        } else {
            $sql = "SELECT cd.*, c.nombre_carrera, d.titulo 
                    FROM carrera_documento cd
                    LEFT JOIN carrera c ON cd.fk_carrera = c.id_carrera
                    LEFT JOIN documento d ON cd.fk_documento = d.id_documento
                    WHERE d.fk_departamento = $id AND cd.activo = 0";
        }

        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data['data'][] = [
                    'id_carrera_documento' => $value['id_carrera_documento'],
                    'fk_carrera' => $value['fk_carrera'],
                    'fk_documento' => $value['fk_documento'],
                    'nombre_carrera' => $value['nombre_carrera'],
                    'nombre_documento' => $value['titulo'],
                    'tsu' => $value['tsu'],
                    'ing' => $value['ing'],
                    'fecha' => $this->formatDate($value['fecha']),
                    'hora' => $this->formatTime($value['hora']),
                    'activo' => $value['activo']
                ];
            }
        } else {
            $data['data'] = [];
        }

        return $data;
    }

    public function createCarreraDocumento($data)
    {
        date_default_timezone_set('America/Mazatlan');

        $fk_carrera = $data['fk_carrera'];
        $fk_documento = $data['fk_documento'];
        $tsu = $data['tsu'];
        $ing = $data['ing'];
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $activo = 1;

        try {
            $sql = "INSERT INTO carrera_documento (fk_carrera, fk_documento, tsu, ing, fecha, hora, activo) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(1, $fk_carrera, PDO::PARAM_INT);
            $stmt->bindParam(2, $fk_documento, PDO::PARAM_INT);
            $stmt->bindParam(3, $tsu, PDO::PARAM_BOOL);
            $stmt->bindParam(4, $ing, PDO::PARAM_BOOL);
            $stmt->bindParam(5, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(6, $hora, PDO::PARAM_STR);
            $stmt->bindParam(7, $activo, PDO::PARAM_INT);
           

            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Error executing query: " . implode(", ", $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return false;
        }
    }

    public function updateCarreraDocumento($id, $fk_carrera, $fk_documento, $tsu, $ing)
    {
        // Convertir tsu e ing a enteros (0 o 1) para asegurar que bindParam reciba los tipos correctos
        $tsu_int = $tsu ? 1 : 0;
        $ing_int = $ing ? 1 : 0;

        $sql = "UPDATE carrera_documento SET fk_carrera = ?, fk_documento = ?, tsu = ?, ing = ? WHERE id_carrera_documento = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $fk_carrera, PDO::PARAM_INT);
        $stmt->bindParam(2, $fk_documento, PDO::PARAM_INT);
        $stmt->bindParam(3, $tsu_int, PDO::PARAM_INT);
        $stmt->bindParam(4, $ing_int, PDO::PARAM_INT);
        $stmt->bindParam(5, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateActivo($id, $activo)
    {
        $sql = "UPDATE carrera_documento SET activo = ? WHERE id_carrera_documento = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $activo, PDO::PARAM_INT);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteCarreraDocumento($id)
    {
        $sql = "DELETE FROM carrera_documento WHERE id_carrera_documento = ?";
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
