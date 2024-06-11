<?php

use MVC\Model;

class ModelsLogin extends Model
{
    public function getUserByEmail($email)
    {
        $sql = "SELECT p.id_persona, p.correo, a.contrasenia
                FROM persona p
                INNER JOIN administrador a ON p.id_persona = a.fk_persona
                WHERE p.correo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }
}
