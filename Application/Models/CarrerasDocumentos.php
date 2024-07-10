<?php

use MVC\Model;

class ModelsCarreraDocumentos extends Model
{
    public function CarreraDocumentos()
    {
        $sql = "SELECT cd.*, c.nombre_carrera, d.titulo 
                FROM carrera_documento cd
                LEFT JOIN carrera c ON cd.fk_carrera = c.id_carrera
                LEFT JOIN documento d ON cd.fk_documento = d.id_documento";
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

    public function createCarreraDocumento($data)
    {
        $fk_carrera = $data['fk_carrera'];
        $fk_documento = $data['fk_documento'];

        try {
            $sql = "INSERT INTO carrera_documento (fk_carrera, fk_documento) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(1, $fk_carrera, PDO::PARAM_INT);
            $stmt->bindParam(2, $fk_documento, PDO::PARAM_INT);

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
