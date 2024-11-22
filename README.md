# **Instrucciones para Configurar el Proyecto**

## **Requisitos Previos**
Antes de comenzar, asegúrate de tener instalados los siguientes programas y herramientas:

- **PHP** (versión 7.4 o superior)
- **Composer** (gestor de dependencias de PHP)
- **Servidor web** (Apache o Nginx recomendado)
- **PostgreSQL** (base de datos)

---

## **Pasos de Configuración**

### **Paso 1 - Configuración del Archivo `config.php`**
1. Abre el archivo `config.php` en la raíz del proyecto.
2. Modifica la sección de configuración de la base de datos con los valores correspondientes a tu entorno.

   Ejemplo de configuración:
   ```php
   // Configuración de la base de datos
   define('DATABASE', [
       'Port'   => '5432',        // Puerto de tu base de datos PostgreSQL
       'Host'   => 'localhost',   // Dirección del servidor de base de datos
       'Driver' => 'PDO',         // Motor de conexión
       'Name'   => 'nombre_bd',   // Nombre de la base de datos
       'User'   => 'usuario_bd',  // Usuario de la base de datos
       'Pass'   => 'contraseña',  // Contraseña del usuario
       'Prefix' => ''             // Prefijo de tablas, si aplica
   ]);

### **Paso 2 - Importar la base de datos **
1. Localiza el archivo SQL en la carpeta sql del proyecto.

2. Importa la base de datos en PostgreSQL utilizando tu herramienta preferida (como pgAdmin o el cliente de línea de comandos).


### **Paso 3 - Instalar dependencias **

1. Asegúrate de estar en la raíz del proyecto.

2. Ejecuta el siguiente comando para instalar las dependencias necesarias:

composer install

Descargará e instalará las librerías necesarias definidas en el archivo composer.json.
Configurará el proyecto con las dependencias más actualizadas.