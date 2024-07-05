<?php

use MVC\Model;

class ModelsBitacora extends Model
{
    public function obtenerDatosBitacora()
    {
        try {
            // sql statement
            $sql = "SELECT DISTINCT " . DB_PREFIX . "
                  bita.accion,dpa.nombre_departamento,doc.titulo,bita.fecha from bitacora bita inner join 
departamento dpa on dpa.id_departamento=bita.departamento
inner join documento doc on doc.id_documento=bita.idtabla";

            // exec query
            $query = $this->db->query($sql);

            // Initialize data as an empty array
            $data = [];

            // Check if there are any rows
            if ($query->num_rows) {
                foreach ($query->rows as $value) {
                    $data['data'][] = $value;
                }
            } else {
                $data['data'] = [];
            }

            // Return the data array
            return $data;

        } catch (\Exception $e) {
            throw new \Exception('Error ejecutando la consulta: ' . $e->getMessage());
        }
    }





} ?>