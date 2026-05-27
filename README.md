# Servicio 2

Sistema web desarrollado con Yii2 para administrar archivos documentales de alumnos. Permite registrar catalogos, alumnos, anaqueles, cajas y documentos PDF; tambien genera codigos QR para cajas y puede apoyarse en una API local de Python para extraer datos desde PDFs.

## Funcionalidad principal

- Gestion de alumnos, carreras, generaciones, periodos y servicios.
- Gestion de archivo fisico: anaqueles, niveles de almacenamiento y cajas.
- Registro de documentos PDF clasificados por alumno y caja.
- Subida, almacenamiento, descarga y eliminacion de PDFs.
- Generacion automatica de codigos de caja.
- Generacion de codigos QR para consultar cajas.
- Modulo de usuarios y permisos mediante `webvimark/module-user-management`.
- Integracion opcional con API Python local para procesar PDFs en `http://127.0.0.1:5000/extract`.

## Requisitos

- PHP 7.4 o superior.
- MySQL/MariaDB.
- Composer.
- XAMPP o un servidor web equivalente con Apache.
- Extension PHP para MySQL habilitada.
- Opcional: Node.js si se van a reinstalar dependencias frontend.

## Instalacion local con XAMPP

1. Coloca el proyecto en:

   ```bash
   C:\xampp\htdocs\servicio2
   ```

2. Instala dependencias PHP:

   ```bash
   composer install
   ```

3. Si necesitas reinstalar dependencias JavaScript:

   ```bash
   npm install
   ```

4. Crea o restaura la base de datos con el script incluido:

   ```bash
   mysql -u root -p < database/servicio_schema.sql
   ```

   En una instalacion tipica de XAMPP tambien puedes importarlo desde phpMyAdmin.

5. Revisa la conexion en `config/db.php`:

   ```php
   return [
       'class' => 'yii\db\Connection',
       'dsn' => 'mysql:host=localhost;port=3306;dbname=servicio',
       'username' => 'root',
       'password' => '2312',
       'charset' => 'utf8',
   ];
   ```

6. Entra desde el navegador:

   ```text
   http://localhost/servicio2/web/
   ```

## Base de datos

El archivo de reconstruccion esta en:

```text
database/servicio_schema.sql
```

Ese script crea la base `servicio`, las tablas principales de la aplicacion y las tablas necesarias para el modulo de usuarios.

Tablas principales:

- `alumno`
- `archivo`
- `caja`
- `anaquel`
- `nivelalmacenamiento`
- `carrera`
- `generacion`
- `periodo`
- `servicio`
- `fondo`
- `area_generadora`
- `clave_programatica`
- `seccion_serie`
- `usuario`

Tablas de autenticacion:

- `user`
- `auth_item`
- `auth_item_child`
- `auth_assignment`
- `auth_rule`
- `auth_item_group`
- `user_visit_log`

Usuario inicial incluido:

```text
Usuario: superadmin
Password: superadmin
```

Cambia esa contrasena inmediatamente despues de recuperar el sistema.

## Flujo de trabajo

1. Crea catalogos base: carreras, periodos, servicios, fondos, areas, claves programaticas y secciones/series.
2. Crea anaqueles y niveles de almacenamiento.
3. Crea cajas. El sistema genera el codigo de caja automaticamente desde `CajaService`.
4. Registra alumnos.
5. Sube documentos PDF desde el modulo de archivos.
6. Descarga documentos o consulta cajas mediante QR cuando sea necesario.

## Procesamiento de PDFs con API Python

El controlador `ArchivoController` tiene la accion `process-pdf`, que llama al servicio `PdfProcessorService`.

Ese servicio espera una API local en:

```text
http://127.0.0.1:5000/extract
```

La API debe recibir un archivo con el campo `file` y devolver JSON con datos como matricula, nombre, carrera y servicio. Si esa API no esta corriendo, la subida normal de archivos puede seguir funcionando, pero el autollenado desde PDF fallara.

## Estructura del proyecto

```text
assets/        Definicion de assets Yii
commands/      Comandos de consola
components/    Componentes personalizados
config/        Configuracion de aplicacion y base de datos
controllers/   Controladores web
database/      Scripts SQL para reconstruccion de la base
mail/          Vistas para correos
models/        Modelos ActiveRecord y formularios
modules/       Modulos propios, incluido admin
services/      Logica de negocio
views/         Vistas PHP
web/           Entrada publica, CSS, JS, imagenes y archivos subidos
widgets/       Widgets personalizados
```

## Archivos importantes

- `config/web.php`: configuracion general, modulos, login, URL manager, correo y componentes.
- `config/db.php`: conexion a MySQL.
- `controllers/ArchivoController.php`: CRUD de archivos, subida, descarga y procesamiento PDF.
- `services/ArchivoStorageService.php`: guarda PDFs fisicamente y registra rutas en base de datos.
- `services/CajaService.php`: genera codigos de caja y QR.
- `services/AnaquelService.php`: genera nombres consecutivos de anaqueles.
- `services/PdfProcessorService.php`: conecta con la API Python de extraccion.
- `database/servicio_schema.sql`: reconstruccion de la base de datos.

## Notas de seguridad

Actualmente hay credenciales de base de datos y correo configuradas directamente en archivos PHP. Para produccion conviene moverlas a variables de entorno o a un archivo local no versionado.

Tambien revisa:

- `cookieValidationKey` en `config/web.php`.
- Credenciales SMTP en `config/web.php`.
- Password de MySQL en `config/db.php`.
- Usuario inicial `superadmin`.

## Pruebas

El proyecto conserva configuracion de Codeception. Si las dependencias estan instaladas, puedes ejecutar:

```bash
vendor/bin/codecept run
```

## Problemas comunes

Si aparece error de conexion a base de datos, revisa que MySQL este iniciado en XAMPP y que `config/db.php` coincida con tus credenciales.

Si los PDFs no se guardan, revisa permisos de escritura en:

```text
web/archivos/
```

Si los QR no se generan, revisa que el componente `qr` este cargado en `config/web.php` y que las dependencias de Composer esten instaladas.

Si el autollenado desde PDF falla, inicia primero la API Python local en el puerto `5000`.
