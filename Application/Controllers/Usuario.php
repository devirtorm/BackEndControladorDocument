<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


use MVC\Controller;
require 'vendor/autoload.php';
class ControllersUsuario extends Controller
{
    function enviarCorreo($destinatario, $asunto, $cuerpo) {
        $mail = new PHPMailer(true);
    
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'correo'; // Tu correo de Gmail
            $mail->Password = 'contra'; // Tu contraseña de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            // Configuración del correo
            $mail->setFrom('archivoscontrolador@gmail.com', 'UTDELACOSTA');
            $mail->addAddress($destinatario);
    
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $cuerpo;
    
            $mail->send();
           
        } catch (Exception $e) {
            echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
        }
    }

    public function usuario()
    {

        // Connect to database
        $model = $this->model('Usuario');

        $data_list = $model->usuario(1);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }


    public function obtenerUsuario($param) {

            $model = $this->model('Usuario');
            $result = $model->usuario($param['id']);

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($result);
    } 
    
    public function usuariosDesactivados()
    {

        // Connect to database
        $model = $this->model('Usuario');

        $data_list = $model->usuario(0);

        // Send Response
        $this->response->sendStatus(200);
        $this->response->setContent($data_list);
    }

    public function crearUsuario() {
        $model = $this->model('Usuario');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
    
        if ($data !== null && isset($data['correo']) && isset($data['contrasenia']) && isset($data['fk_departamento']) && isset($data['fk_rol'])) {
            $correo = filter_var($data['correo'], FILTER_SANITIZE_STRING);
            $contrasenia = filter_var($data['contrasenia'], FILTER_SANITIZE_STRING);
            $fk_departamento = filter_var($data['fk_departamento'], FILTER_SANITIZE_STRING);
            $fk_rol = filter_var($data['fk_rol'], FILTER_SANITIZE_STRING);
            $inserted = $model->insertUsuario(['correo' => $correo, 'contrasenia' => $contrasenia, 'fk_departamento' => $fk_departamento, 'fk_rol' => $fk_rol]);
    
            if ($inserted) {
                echo json_encode(['message' => 'Usuario guardado correctamente.']);
            } else {
                echo json_encode(['message' => 'Error al guardar Usuario']);
            }
        } else {
            echo json_encode(['message' => 'Error: Los datos de proceso son inválidos o incompletos.']);
        }
    }

    
    public function eliminarProceso($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Procesos');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $deleted = $model->eliminarProceso($id);
    
            // Preparar la respuesta
            if ($deleted) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Proceso eliminado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo eliminar el proceso.'
                ]);
            }
        } else {
            // Preparar la respuesta para parámetro inválido
            $this->response->sendStatus(400);
            $this->response->setContent([
                'message' => 'Invalid ID or ID is missing.'
            ]);
        }
    }
    

    public function solicitarRecuperacion()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data !== null && isset($data['email'])) {
            $email = filter_var($data['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $model = $this->model('Usuario');
            $user = $model->buscarUsuarioPorCorreo($email);

            if ($user) {
                $token = bin2hex(random_bytes(20));
                date_default_timezone_set('America/Mexico_City');

                $expiracion = date('Y-m-d H:i:s', strtotime('+5 minutes'));
                $model->guardarToken($user['id_usuario'], $token, $expiracion);

                // Enviar el correo utilizando $this->enviarCorreo
                $destinatario = $user['correo'];
                $asunto = 'Recuperación de contraseña';
                $cuerpo = "Para recuperar tu contraseña, utiliza el siguiente token: $token";

               
           
            
                    // Envía el correo
                    $this->enviarCorreo($destinatario, $asunto, $cuerpo);
    
                    // Envío exitoso
                    
                    $this->response->sendStatus(200);
                    $this->response->setContent(json_encode([
                        'message' => 'Correo enviado correctamente.'
        
                    ]));
                   
               
            }} else {
                $this->response->sendStatus(401);
                $this->response->setContent(json_encode(['message' => 'Credenciales inválidas']));
            }
        }
    




    // Método auxiliar para validar el ID
    private function validId($id) {
        return filter_var($id, FILTER_VALIDATE_INT) !== false && $id > 0;
    }

    public function desactivarUsuario($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Usuario');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 0);
    
            // Preparar la respuesta
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Usuario desactivado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo desactivar el usuario.'
                ]);
            }
        } else {
            // Preparar la respuesta para parámetro inválido
            $this->response->sendStatus(400);
            $this->response->setContent([
                'message' => 'Invalid ID or ID is missing.'
            ]);
        }
    }

    public function activarUsuario($param) {
        // Verificar si el parámetro 'id' está presente y es válido
        if (isset($param['id']) && $this->validId($param['id'])) {
    
            $model = $this->model('Usuario');
            $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
            $updated = $model->actualizarActivo($id, 1);
    
            // Preparar la respuesta
            if ($updated) {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Proceso desactivado correctamente.'
                ]);
            } else {
                $this->response->sendStatus(200);
                $this->response->setContent([
                    'message' => 'Error: No se pudo desactivar el proceso.'
                ]);
            }
        } else {
            // Preparar la respuesta para parámetro inválido
            $this->response->sendStatus(400);
            $this->response->setContent([
                'message' => 'Invalid ID or ID is missing.'
            ]);
        }
    }
    

    public function actualizarProceso($param) {
        $model = $this->model('Procesos');
        $json_data = file_get_contents('php://input');
        error_log("JSON Data: " . $json_data);
        $data = json_decode($json_data, true);
    
        // Verificar si los datos son válidos
        if ($data !== null && isset($data['proceso']) && isset($data['proposito'])) {
            $proceso = filter_var($data['proceso'], FILTER_SANITIZE_STRING);
            $proposito = filter_var($data['proposito'], FILTER_SANITIZE_STRING);
            
            if (isset($param['id']) && $this->validId($param['id'])) {
                // Actualizar el área existente
                $id = filter_var($param['id'], FILTER_SANITIZE_NUMBER_INT);
                $updated = $model->updateProceso(['id' => $id, 'proceso' => $proceso, 'proposito' => $proposito]);
        
                if ($updated) {
                    $this->response->sendStatus(200);
                    $this->response->setContent([
                        'message' => 'Departamento actualizada correctamente.'
                    ]);
                } else {
                    $this->response->sendStatus(500);
                    $this->response->setContent([
                        'message' => 'Error: No se pudo actualizar el departamento.'
                    ]);
                }
            } 
        } 
    }
    
    
    


}