<?php

use MVC\Model;

class ModelsLogin extends Model
{
    public function getUserByEmail($email)
    {
        $sql = "SELECT u.id_usuario, u.correo, u.contrasenia, u.fk_departamento, d.nombre_departamento, r.nombre_rol
        FROM usuario u
        JOIN departamento d ON u.fk_departamento = d.id_departamento
        JOIN rol r ON u.fk_rol = r.id_rol
        WHERE u.correo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }
}
