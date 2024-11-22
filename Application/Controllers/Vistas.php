<?php 

use MVC\Controller;

class ControllersVistas extends Controller
{ 

    public function registrarVisita() {
        error_log("Método registrarVisita() llamado");
        
        // Asegurarse de que la solicitud sea POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Método no permitido: " . $_SERVER['REQUEST_METHOD']);
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido. Use POST."));
            return;
        }
    
        // Obtener los datos de la solicitud
        $raw_data = file_get_contents("php://input");
        error_log("Datos recibidos: " . $raw_data);
        $data = json_decode($raw_data, true);
    
        // Verificar si se recibieron datos
        if (!$data || !isset($data['visitorId'])) {
            error_log("Datos inválidos o faltantes");
            http_response_code(400);
            echo json_encode(array("message" => "No se recibieron datos válidos."));
            return;
        }
    
        error_log("ID de visita recibida: " . $data['visitorId']);
    
        // Crear una instancia del modelo y llamar al método registrarVisita
        $model = $this->model('Vistas');
        $resultado = $model->registrarVisita($data);
    
        error_log("Resultado de registrarVisita: " . $resultado);
        echo $resultado;
    }
    

    public function obtenerNumeroVisitas() {
        error_log("Método obtenerNumeroVisitas() llamado");
        try {
            // Connect to database
        $model = $this->model('Vistas');
    
        $data_list = $model->read();
    
        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
            
        } catch (Exception $e) {
            // Manejo de errores
            error_log("Error en obtenerNumeroVisitas: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(array("message" => "Error: " . $e->getMessage()));
        }
    }
}