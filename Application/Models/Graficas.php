<?php

use MVC\Model;

class ModelsGraficas extends Model
{
    /* Grafica de cantidad de documentos por tipo */
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



        /* Grafica de cantidad de documentos por departamento */
        public function cantDocumentosDepartamentoGrafica()
        {
            try {
                // sql statement
                $sql = "SELECT " . DB_PREFIX . "
                      d.id_departamento, 
    d.nombre_departamento, 
    COUNT(doc.id_documento) AS cantidad_documentos
FROM 
    departamento d
left JOIN 
    documento doc ON d.id_departamento = doc.fk_departamento and d.activo=1
WHERE 
    doc.activo = 1
GROUP BY 
    d.id_departamento, 
    d.nombre_departamento
ORDER BY 
    d.id_departamento;";
    
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


    /* Función para obtener los datos de la card de homeAdmin */
    public function
        cantDocumentosCard(
    ) {
        try {
            // sql statement
            $sql = "SELECT " . DB_PREFIX . "
                  COUNT(*) AS total_documentos FROM documento;";

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


    

    /* Función para obtener los datos de la card de homeAdmin */
    public function documentosPorRevisar()
    {
        try {
            // sql statement
            $sql = "SELECT " . DB_PREFIX . "  COUNT(*) AS por_revisar FROM documento where revisado=0";

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



    /* Función para obtener los datos de la card de homeAdmin */
    public function documentosPorAutorizar()
    {
        try {
            // sql statement
            $sql = "SELECT " . DB_PREFIX . "COUNT(*) AS por_autorizar FROM documento where autorizado=0;";

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


/* 
SELECT 
    d.id_departamento, 
    d.nombre_departamento, 
    COUNT(doc.id_documento) AS cantidad_documentos_activos
FROM 
    departamento d
left JOIN 
    documento doc ON d.id_departamento = doc.fk_departamento
WHERE 
    doc.activo = 1
GROUP BY 
    d.id_departamento, 
    d.nombre_departamento
ORDER BY 
    d.id_departamento;
 */
?>