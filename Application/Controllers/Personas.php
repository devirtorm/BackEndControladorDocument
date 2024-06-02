<?php

use MVC\Controller;

class ControllersPersonas extends Controller
{

    public function personas()
    {

        // Connect to database
        $model = $this->model('Personas');

        $data_list = $model->getAllPersons();

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function addPerson() {
        // Conectar al modelo
        $model = $this->model('Personas');
    
        // Leer los datos JSON de la solicitud
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
    
        // Verificar si se recibieron datos válidos
        if ($data !== null && isset($data['nombres']) && isset($data['primer_apellido']) && isset($data['segundo_apellido']) && isset($data['telefono']) && isset($data['correo']) && isset($data['contrasenia']) && isset($data['rol']) && isset($data['fecha']) && isset($data['hora']) && isset($data['activo'])) {
            // Insertar la persona en la base de datos
            $inserted = $model->insertPerson($data);
    
            // Verificar si la inserción fue exitosa
            if ($inserted) {
                echo "La persona se ha insertado correctamente.";
            } else {
                echo "Error al insertar la persona.";
            }
        } else {
            // Si no se recibieron datos válidos, mostrar un mensaje de error
            echo "Error: Los datos de la persona son inválidos o incompletos.";
        }
    }
    


}
