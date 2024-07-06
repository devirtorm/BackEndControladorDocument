<?php

use MVC\Model;

class ModelsCarreraDocumentos extends Model
{
    public function CarreraDocumentos()
    {
        $sql = "SELECT * FROM carrera_documento";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }

        return $data;
    }

    public function CarreraDocumentosActivos()
    {
        $sql = "SELECT cd.*, c.nombre_carrera, d.titulo 
                FROM carrera_documento cd
                LEFT JOIN carrera c ON cd.fk_carrera = c.id_carrera
                LEFT JOIN documento d ON cd.fk_documento = d.id_documento
                WHERE c.activo = 1 AND d.activo = 1";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }

        return $data;
    }

    public function CarreraDocumentosInactivos()
    {
        $sql = "SELECT cd.*, c.nombre_carrera, d.titulo 
                FROM carrera_documento cd
                LEFT JOIN carrera c ON cd.fk_carrera = c.id_carrera
                LEFT JOIN documento d ON cd.fk_documento = d.id_documento
                WHERE c.activo = 0 OR d.activo = 0";
        $query = $this->db->query($sql);
        $data = [];

        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                $data[] = $value;
            }
        }

        return $data;
    }

    public function CarreraDocumento($id)
    {
        try {
            $sql = "SELECT * FROM carrera_documento WHERE id_carrera_documento = ?";
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

    public function CarreraDocumentosPorCarrera($fk_carrera)
    {
        try {
            $sql = "SELECT cd.*, d.titulo 
                    FROM carrera_documento cd
                    LEFT JOIN documento d ON cd.fk_documento = d.id_documento
                    WHERE cd.fk_carrera = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$fk_carrera]);

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

    public function createCarreraDocumento($data)
    {
        $fk_carrera = $data['fk_carrera'];
        $fk_documento = $data['fk_documento'];

        $sql = "INSERT INTO carrera_documento (fk_carrera, fk_documento) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $fk_carrera, PDO::PARAM_INT);
        $stmt->bindParam(2, $fk_documento, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateCarreraDocumento($id, $fk_carrera, $fk_documento)
    {
        $sql = "UPDATE carrera_documento SET fk_carrera = ?, fk_documento = ? WHERE id_carrera_documento = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $fk_carrera, PDO::PARAM_INT);
        $stmt->bindParam(2, $fk_documento, PDO::PARAM_INT);
        $stmt->bindParam(3, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteCarreraDocumento($id)
    {
        $sql = "DELETE FROM carrera_documento WHERE id_carrera_documento = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

?>
