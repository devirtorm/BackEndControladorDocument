<?php

use MVC\Model;

class ModelsDocumentos extends Model
{
    public function getDocumentoByProcesoId($id) {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;
    
        // Construir la consulta SQL
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "documento WHERE fk_proceso = $id AND activo = 1 AND autorizado = 1 AND revisado = 1");
    
        $data = [];
    
        // Verificar si hay alguna fila en el resultado
        if ($query->num_rows) {
            // Obtener todas las filas
            $data['data'] = $query->rows;
        } else {
            // Devolver un array vacío si no se encuentra ninguna fila
            $data['data'] = [];
        }
    
        // Devolver el array de datos
        return $data;
    }
    public function documentos($activo, $id)
    {
        // Obtener el nombre del departamento
        $sql1 = "SELECT dp.nombre_departamento 
                 FROM usuario us 
                 INNER JOIN departamento dp ON dp.id_departamento = us.fk_departamento 
                 WHERE us.fk_departamento = $id";
    
        $query1 = $this->db->query($sql1);
        
        // Inicializar variable para el nombre del departamento
        $nombre_departamento = '';
    
        // Verificar si hay filas en el resultado
        if ($query1->num_rows) {
            $nombre_departamento = $query1->row['nombre_departamento'];
        }
    
        // Construir la consulta adecuada basada en el nombre del departamento
        if (strtolower($nombre_departamento) == 'calidad' || strtolower($nombre_departamento) == 'calidad') {
            $sql = "SELECT * FROM " . DB_PREFIX . "documento WHERE activo = $activo";
        } else {
            $sql = "SELECT * FROM " . DB_PREFIX . "documento WHERE activo = $activo AND fk_departamento = $id";
        }
    
        // Ejecutar la consulta
        $query = $this->db->query($sql);
    
        // Inicializar los datos como un array vacío
        $data = [];
    
        // Verificar si hay filas en el resultado
        if ($query->num_rows) {
            foreach ($query->rows as $value) {
                // Llamar a la función 'departamento' para obtener los datos del departamento
                $departamento_data = $this->departamento($value['fk_departamento']);
                $value['departamento'] = $departamento_data['data'];
    
                // Llamar a la función 'tipoDocumento' para obtener los datos del tipo de documento
                $tipo_documento_data = $this->tipoDocumento($value['fk_tipo_documento']);
                $value['tipo_documento'] = $tipo_documento_data['data'];
    
                // Llamar a la función 'categoria' para obtener los datos de la categoría
                $categoria_data = $this->categoria($value['fk_categoria']);
                $value['categoria'] = $categoria_data['data'];

                // llamar a 'area' funcion que obtiene los datos de area
                $subproceso_data = $this->proceso($value['fk_proceso']);

                // Add the area data to the department data
                $value['proceso'] = $subproceso_data['data'];


                // agregar area a los resultados
                $data['data'][] = $value;
            }
        } else {
            $data['data'] = [];
        }
    
        // Devolver el array de datos
        return $data;
    }
    

    public function departamento($id)
    {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;

        // Construir la consulta SQL
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "departamento WHERE id_departamento = $id");

        $data = [];

        // Verificar si hay alguna fila en el resultado
        if ($query->num_rows) {
            // Obtener la primera fila (ya que se espera solo una persona con un ID específico)
            $data['data'] = $query->row;
        } else {
            // Devolver un array vacío si no se encuentra ninguna persona con el ID dado
            $data['data'] = [];
        }

        // Devolver el array de datos
        return $data;
    }


    public function todosDocumentos($activo)
    {
     
        try {
            // SQL statement
            $sql = "SELECT  " . DB_PREFIX . "documento.id_documento,documento.titulo,
        documento.url,
        proceso.proceso,
        macroproceso.macroproceso AS macroproceso_nombre,
        subproceso.subproceso,
        departamento.nombre_departamento,
        categoria.nombre_categoria,
        tipo_documento.tipo_documento,
        documento.num_revision,
		area.nombre_area,
		documento.fecha,
		documento.fecha_emision,
		documento.revisado,
		documento.autorizado
		
    FROM documento
    JOIN proceso ON documento.fk_proceso = proceso.id_proceso
    JOIN macroproceso ON proceso.fk_macroproceso = macroproceso.id_macroproceso
    LEFT JOIN subproceso ON documento.fk_subproceso = subproceso.id_subproceso
    JOIN departamento ON documento.fk_departamento = departamento.id_departamento
	join area on departamento.fk_area= area.id_area
    JOIN categoria ON documento.fk_categoria = categoria.id_categoria
    JOIN tipo_documento ON documento.fk_tipo_documento = tipo_documento.id_tipo
    WHERE 
         documento.activo=$activo";
    
            // Execute query
            $query = $this->db->query($sql);
    
            // Initialize data as an associative array
            $data = ['data' => []];
    
            // Check if there are any rows returned
            if ($query->num_rows > 0) {
                foreach ($query->rows as $row) {
                    $data['data'][] = $row;
                }
            }
    
            // Return the data array
            return $data;
    
        } catch (\Exception $e) {
            throw new \Exception('Error ejecutando la consulta: ' . $e->getMessage());
        }
    }

    public function categoria($id)
    {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;

        // Construir la consulta SQL
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "categoria WHERE id_categoria = $id");

        $data = [];

        // Verificar si hay alguna fila en el resultado
        if ($query->num_rows) {
            // Obtener la primera fila (ya que se espera solo una persona con un ID específico)
            $data['data'] = $query->row;
        } else {
            // Devolver un array vacío si no se encuentra ninguna dato con el ID dado
            $data['data'] = [];
        }

        // Devolver el array de datos
        return $data;
    }

    public function tipoDocumento($id)
    {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;

        // Construir la consulta SQL
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tipo_documento WHERE id_tipo = $id");

        $data = [];

        // Verificar si hay alguna fila en el resultado
        if ($query->num_rows) {
            // Obtener la primera fila (ya que se espera solo una persona con un ID específico)
            $data['data'] = $query->row;
        } else {
            // Devolver un array vacío si no se encuentra ninguna dato con el ID dado
            $data['data'] = [];
        }

        // Devolver el array de datos
        return $data;
    }

    public function proceso($id)
    {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;
    
        // Construir la consulta SQL
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "proceso WHERE id_proceso = $id");
    
        $data = [];
    
        // Verificar si hay alguna fila en el resultado
        if ($query->num_rows) {
            // Obtener la primera fila (ya que se espera solo una persona con un ID específico)
            $data['data'] = $query->row;
    
            // Obtener el id_macroproceso del proceso
            $id_proceso = $data['data']['fk_macroproceso'];
    
            // Llamar a la función proceso para obtener los datos del proceso
            $proceso_data = $this->macroproceso($id_proceso);
    
            // Añadir los datos del proceso al array de datos del subproceso
            $data['data']['macroproceso'] = $proceso_data['data'];
        } else {
            // Devolver un array vacío si no se encuentra ninguna dato con el ID dado
            $data['data'] = [];
        }
    
        // Devolver el array de datos
        return $data;
    }

    public function macroproceso($id) {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;
    
        // Construir la consulta SQL
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "macroproceso WHERE id_macroproceso = $id");
    
        $data = [];
    
        // Verificar si hay alguna fila en el resultado
        if ($query->num_rows) {
            // Obtener la primera fila (ya que se espera solo una persona con un ID específico)
            $data['data'] = $query->row;
        } else {
            // Devolver un array vacío si no se encuentra ninguna persona con el ID dado
            $data['data'] = [];
        }
    
        // Devolver el array de datos
        return $data;
    }


    public function documento($activo,$id)
    {
        // Sanitizar el ID para prevenir SQL Injection
        $id = (int)$id;
      
        // Construir la consulta SQL
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "documento WHERE id_documento = $id and activo=$activo");
    
        $data = [];
    
        // Verificar si hay alguna fila en el resultado
        if ($query->num_rows) {
            // Obtener la primera fila (ya que se espera solo un documento con un ID específico)
            $value = $query->row;
    
            // Llamar a 'departamento' funcion que obtiene los datos del departamento
            $departamento_data = $this->departamento($value['fk_departamento']);
            $value['departamento'] = $departamento_data['data'];
    
            // Llamar a 'tipoDocumento' funcion que obtiene los datos del tipo de documento
            $tipo_documento_data = $this->tipoDocumento($value['fk_tipo_documento']);
            $value['tipo_documento'] = $tipo_documento_data['data'];
    
            // Llamar a 'categoria' funcion que obtiene los datos de la categoría
            $categoria_data = $this->categoria($value['fk_categoria']);
            $value['categoria'] = $categoria_data['data'];
    
            // Llamar a 'subproceso' funcion que obtiene los datos del subproceso
            $subproceso_data = $this->proceso($value['fk_proceso']);
            $value['proceso'] = $subproceso_data['data'];
    
            // Agregar el documento con sus datos relacionados al resultado
            $data['data'] = $value;
        } else {
            // Devolver un array vacío si no se encuentra ningún documento con el ID dado
            $data['data'] = [];
        }
    
        // Retornar los datos en formato JSON
        return $data;
    }
    
    
    public function documentoDetalle($activo,$id)
    {
        try {
            // SQL statement
            $sql = "SELECT  " . DB_PREFIX . "documento.id_documento,documento.titulo,
        documento.url,
        proceso.proceso,
        macroproceso.macroproceso AS macroproceso_nombre,
        subproceso.subproceso,
        departamento.nombre_departamento,
        categoria.nombre_categoria,
        tipo_documento.tipo_documento,
        documento.num_revision,
		area.nombre_area,
		documento.fecha,
		documento.fecha_emision,
		documento.revisado,
		documento.autorizado
		
    FROM documento
    JOIN proceso ON documento.fk_proceso = proceso.id_proceso
    JOIN macroproceso ON proceso.fk_macroproceso = macroproceso.id_macroproceso
    LEFT JOIN subproceso ON documento.fk_subproceso = subproceso.id_subproceso
    JOIN departamento ON documento.fk_departamento = departamento.id_departamento
	join area on departamento.fk_area= area.id_area
    JOIN categoria ON documento.fk_categoria = categoria.id_categoria
    JOIN tipo_documento ON documento.fk_tipo_documento = tipo_documento.id_tipo
    WHERE 
         documento.id_documento=$id";
    
            // Execute query
            $query = $this->db->query($sql);
    
            // Initialize data as an associative array
            $data = ['data' => []];
    
            // Check if there are any rows returned
            if ($query->num_rows > 0) {
                foreach ($query->rows as $row) {
                    $data['data'][] = $row;
                }
            }
    
            // Return the data array
            return $data;
    
        } catch (\Exception $e) {
            throw new \Exception('Error ejecutando la consulta: ' . $e->getMessage());
        }
    }
    

    public function insertDocumento($data)
    {
        $titulo = $data['titulo'];
        $fk_departamento = $data['fk_departamento'];
        $fk_categoria = $data['fk_categoria'];
        $fk_tipo_documento = $data['fk_tipo_documento'];
        $fk_subproceso = !empty($data['fk_subproceso']) ? $data['fk_subproceso'] : null;
        $archivo_url = $data['archivo_url'];
        $num_revision = $data['num_revision'];
        $fecha_emision = $data['fecha_emision'];
        $fk_proceso = $data['fk_proceso'];
    
        try {
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
    
            $sql = "INSERT INTO " . DB_PREFIX . "documento (titulo, url, fk_departamento, fk_categoria, fk_tipo_documento, fk_subproceso, fecha, hora, num_revision, fecha_emision, fk_proceso) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(1, $titulo, PDO::PARAM_STR);
            $stmt->bindParam(2, $archivo_url, PDO::PARAM_STR);
            $stmt->bindParam(3, $fk_departamento, PDO::PARAM_INT);
            $stmt->bindParam(4, $fk_categoria, PDO::PARAM_INT);
            $stmt->bindParam(5, $fk_tipo_documento, PDO::PARAM_INT);
            if ($fk_subproceso !== null) {
                $stmt->bindParam(6, $fk_subproceso, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(6, null, PDO::PARAM_NULL);
            }
            $stmt->bindParam(7, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(8, $hora, PDO::PARAM_STR);
            $stmt->bindParam(9, $num_revision, PDO::PARAM_INT);
            $stmt->bindParam(10, $fecha_emision, PDO::PARAM_STR);
            $stmt->bindParam(11, $fk_proceso, PDO::PARAM_INT);
    
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Log the error message for debugging
            error_log('Insert Documento Error: ' . $e->getMessage());
    
            // Return the exception message
            return $e->getMessage();
        }
    }
    
    
    


    public function updateDocumento($data)
    {
        $id = $data['id'];
        $titulo = $data['titulo'];
        $fk_departamento = $data['fk_departamento'];
        $fk_categoria = $data['fk_categoria'];
        $fk_tipo_documento = $data['fk_tipo_documento'];
        $fk_subproceso = isset($data['fk_subproceso']) ? $data['fk_subproceso'] : null;
        $num_revision = $data['num_revision'];
        $fecha_emision = $data['fecha_emision'];
        $fk_proceso = $data['fk_proceso'];
        $archivo_url = isset($data['archivo_url']) ? $data['archivo_url'] : null;
    
        try {
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
    
            // Construye la consulta SQL
            $sql = "UPDATE " . DB_PREFIX . "documento SET 
                        titulo = ?, 
                        fk_departamento = ?, 
                        fk_categoria = ?, 
                        fk_tipo_documento = ?, 
                        fk_subproceso = ?, 
                        fecha = ?, 
                        hora = ?, 
                        num_revision = ?, 
                        fecha_emision = ?, 
                        fk_proceso = ?";
    
            // Añade la URL del archivo solo si se proporciona una nueva
            if ($archivo_url) {
                $sql .= ", url = ?";
            }
    
            $sql .= " WHERE id_documento = ?";
    
            $stmt = $this->db->prepare($sql);
    
            // Asigna los parámetros
            $stmt->bindParam(1, $titulo, PDO::PARAM_STR);
            $stmt->bindParam(2, $fk_departamento, PDO::PARAM_INT);
            $stmt->bindParam(3, $fk_categoria, PDO::PARAM_INT);
            $stmt->bindParam(4, $fk_tipo_documento, PDO::PARAM_INT);
            $stmt->bindParam(5, $fk_subproceso, $fk_subproceso !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindParam(6, $fecha, PDO::PARAM_STR);
            $stmt->bindParam(7, $hora, PDO::PARAM_STR);
            $stmt->bindParam(8, $num_revision, PDO::PARAM_STR);
            $stmt->bindParam(9, $fecha_emision, PDO::PARAM_STR);
            $stmt->bindParam(10, $fk_proceso, PDO::PARAM_INT);
    
            // Añade el archivo URL a los parámetros si es necesario
            if ($archivo_url) {
                $stmt->bindParam(11, $archivo_url, PDO::PARAM_STR);
                $stmt->bindParam(12, $id, PDO::PARAM_INT);
            } else {
                $stmt->bindParam(11, $id, PDO::PARAM_INT);
            }
    
            $stmt->execute();
    
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating document: " . $e->getMessage());
            return false; // Retorna false en caso de error
        }
    }
    
    
    
    
    




    public function eliminarDocumento($id)
    {
        // Escapar el id para evitar inyecciones SQL
        $id = (int)$id;

        // sql statement
        $sql = "DELETE FROM " . DB_PREFIX . "documento WHERE id_documento = " . $id;

        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        // Verificar si la fila fue afectada (eliminada)
        return $stmt->rowCount() > 0;
    }


    public function actualizarActivo($id, $activo)
    {
        // Escapar el id y el valor de activo para evitar inyecciones SQL
        $id = (int)$id;
        $activo = (int)$activo;

        // sql statement
        $sql = "UPDATE " . DB_PREFIX . "documento SET activo = :activo WHERE id_documento = :id";

        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    public function revisarDocumentoAdmin($id, $activo)
    {
        // Escapar el id y el valor de activo para evitar inyecciones SQL
        $id = (int)$id;
        $activo = (int)$activo;

        // sql statement
        $sql = "UPDATE " . DB_PREFIX . "documento SET revisado = :revisado WHERE id_documento = :id";

        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':revisado', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    public function autorizarDocumentoAdmin($id, $activo)
    {
        // Escapar el id y el valor de activo para evitar inyecciones SQL
        $id = (int)$id;
        $activo = (int)$activo;

        // sql statement
        $sql = "UPDATE " . DB_PREFIX . "documento SET autorizado = :autorizado WHERE id_documento = :id";

        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':autorizado', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Verificar si la fila fue afectada (actualizada)
        return $stmt->rowCount() > 0;
    }
    

/* Consulta para poder obtener la id del archivo que necesitamos descargar desde el nabvar "procesos específicos" */
public function documentProcesosEspecificos()
{
    try {
        // SQL statement
        $sql = "SELECT * FROM " . DB_PREFIX . "documento WHERE titulo = 'Proceso especifico'";

        // Execute query
        $query = $this->db->query($sql);

        // Initialize data as an associative array
        $data = ['data' => []];

        // Check if there are any rows returned
        if ($query->num_rows > 0) {
            foreach ($query->rows as $row) {
                $data['data'][] = $row;
            }
        }

        // Return the data array
        return $data;

    } catch (\Exception $e) {
        throw new \Exception('Error ejecutando la consulta: ' . $e->getMessage());
    }
}


public function documentosBuscador()
{
    try {
        // SQL statement
        $sql = "SELECT  " . DB_PREFIX . "documento.titulo,
    documento.url,
    proceso.proceso,
    macroproceso.macroproceso AS macroproceso_nombre,
    subproceso.subproceso,
    departamento.nombre_departamento,
    categoria.nombre_categoria,
    tipo_documento.tipo_documento,
    documento.num_revision
FROM documento
JOIN proceso ON documento.fk_proceso = proceso.id_proceso
JOIN macroproceso ON proceso.fk_macroproceso = macroproceso.id_macroproceso
LEFT JOIN subproceso ON documento.fk_subproceso = subproceso.id_subproceso
JOIN departamento ON documento.fk_departamento = departamento.id_departamento
JOIN categoria ON documento.fk_categoria = categoria.id_categoria
JOIN tipo_documento ON documento.fk_tipo_documento = tipo_documento.id_tipo
WHERE 
    documento.activo = 1";

        // Execute query
        $query = $this->db->query($sql);

        // Initialize data as an associative array
        $data = ['data' => []];

        // Check if there are any rows returned
        if ($query->num_rows > 0) {
            foreach ($query->rows as $row) {
                $data['data'][] = $row;
            }
        }

        // Return the data array
        return $data;

    } catch (\Exception $e) {
        throw new \Exception('Error ejecutando la consulta: ' . $e->getMessage());
    }
}


}
