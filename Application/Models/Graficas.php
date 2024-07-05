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
    public function cantDocumentosCard($id)
    {
        try {
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
            if (strtolower($nombre_departamento) == 'Calidad' || strtolower($nombre_departamento) == 'calidad') {
                $sql = "SELECT COUNT(*) AS total_documentos FROM " . DB_PREFIX . "documento";
            } else {
                $sql = "SELECT COUNT(*) AS total_documentos FROM " . DB_PREFIX . "documento WHERE fk_departamento = $id";
            }
    
            // Ejecutar la consulta
            $query = $this->db->query($sql);
            $data = [];
    
            if ($query->num_rows) {
                foreach ($query->rows as $value) {
                    $data['data'][] = $value;
                }
            } else {
                $data['data'] = [];
            }
    
            return $data;
    
        } catch (\Exception $e) {
            throw new \Exception('Error ejecutando la consulta: ' . $e->getMessage());
        }
    }
    

    

    /* Función para obtener los datos de la card de homeAdmin */
    public function documentosPorRevisar($id)
    {
        try {
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
            if (strtolower($nombre_departamento) == 'Calidad' || strtolower($nombre_departamento) == 'calidad') {
                $sql = "SELECT COUNT(*) AS por_revisar FROM " . DB_PREFIX . "documento WHERE revisado = 0";
            } else {
                $sql = "SELECT COUNT(*) AS por_revisar FROM " . DB_PREFIX . "documento WHERE revisado = 0 AND fk_departamento = $id";
            }
    
            // Ejecutar la consulta
            $query = $this->db->query($sql);
            $data = [];
    
            if ($query->num_rows) {
                foreach ($query->rows as $value) {
                    $data['data'][] = $value;
                }
            } else {
                $data['data'] = [];
            }
    
            return $data;
    
        } catch (\Exception $e) {
            throw new \Exception('Error ejecutando la consulta: ' . $e->getMessage());
        }
    }
    



    /* Función para obtener los datos de la card de homeAdmin */
    public function documentosPorAutorizar($id)
    {
        try {
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
            if (strtolower($nombre_departamento) == 'Calidad' || strtolower($nombre_departamento) == 'calidad') {
                $sql = "SELECT COUNT(*) AS por_autorizar FROM " . DB_PREFIX . "documento WHERE autorizado = 0";
            } else {
                $sql = "SELECT COUNT(*) AS por_autorizar FROM " . DB_PREFIX . "documento WHERE autorizado = 0 AND fk_departamento = $id";
            }
    
            // Ejecutar la consulta
            $query = $this->db->query($sql);
            $data = [];
    
            if ($query->num_rows) {
                foreach ($query->rows as $value) {
                    $data['data'][] = $value;
                }
            } else {
                $data['data'] = [];
            }
    
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