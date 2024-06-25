<?php

use MVC\Model;

class ModelsGraficas extends Model
{

    public function cantDocumentosTipo()
    {
        try {


            // sql statement
            $sql = "SELECT " . DB_PREFIX . "
                 ct.id_categoria,
    ct.nombre_categoria,
    COUNT(d.id_documento) as cantidad_documentos
FROM 
    categoria ct
LEFT JOIN 
    documento d ON ct.id_categoria = d.fk_categoria AND d.activo = 1
WHERE 
    ct.activo = 1
GROUP BY 
    ct.id_categoria, ct.nombre_categoria
ORDER BY 
    cantidad_documentos DESC;";

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
}
?>