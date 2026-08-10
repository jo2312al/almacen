# ASSRP - Sistema Inteligente de Archivo y Seguimiento Documental

ASSRP es una aplicación web desarrollada con Yii2 para administrar expedientes documentales de alumnos, cajas físicas y archivos PDF. El sistema combina captura tradicional, carga masiva por caja, análisis automático de PDFs mediante API externa, revisión de excepciones y bitácora operativa.

## Problema que resuelve

En un archivo físico institucional, los documentos suelen estar distribuidos en cajas y expedientes. La captura manual archivo por archivo consume tiempo, dificulta la trazabilidad y vuelve lenta la consulta posterior.

ASSRP permite digitalizar una caja completa, detectar datos de alumnos desde PDFs, clasificar documentos, resolver excepciones y consultar la información desde una interfaz centralizada.

## Funcionalidad principal

- Dashboard con métricas generales del archivo documental.
- Gestión de alumnos, carreras, generaciones, periodos y servicios.
- Gestión física: anaqueles, niveles de almacenamiento y cajas.
- Registro individual de documentos PDF.
- Carga masiva por caja con procesamiento de varios PDFs.
- Detección de alumnos existentes mediante API de extracción.
- Bandeja de pendientes para alumnos inexistentes.
- Revisión de alumno pendiente con formulario prellenado.
- Bitácora de acciones relevantes del sistema.
- Descarga y consulta de archivos registrados.
- Generación de códigos de caja y soporte para QR.
- Usuarios y permisos mediante `webvimark/module-user-management`.

## Flujo demo recomendado

1. Entrar al dashboard y mostrar métricas generales.
2. Crear o seleccionar una caja física.
3. Ir a `Crear > Carga Masiva`.
4. Subir varios PDFs de una caja.
5. Mostrar el resumen del lote: guardados, pendientes y errores.
6. Abrir un pendiente y usar `Revisar alumno`.
7. Guardar el alumno prellenado.
8. Ver cómo el pendiente cambia a guardado.
9. Abrir la bitácora para mostrar evidencia de trazabilidad.

## Módulos principales

### Dashboard

La página inicial muestra conteos de alumnos, cajas, archivos, cargas masivas, pendientes y errores. También presenta cargas recientes y acciones recientes de la bitácora.

### Carga masiva

Permite seleccionar una caja y cargar múltiples PDFs. Cada documento se procesa individualmente y queda con uno de tres estados:

- `guardado`: alumno encontrado y archivo registrado.
- `pendiente`: alumno no existe y requiere revisión.
- `error`: hubo un problema al analizar o guardar el documento.

### Revisión de alumnos inexistentes

Cuando un PDF queda pendiente, el sistema conserva el archivo temporal y los datos extraídos. Desde el detalle del lote se puede revisar el alumno, guardar el registro y asociar automáticamente el PDF a la caja.

### Bitácora

Registra acciones como creación de alumnos, creación/eliminación de archivos, procesamiento de cargas masivas y resolución de pendientes.

## Requisitos

- PHP 7.4 o superior.
- MySQL/MariaDB.
- Composer.
- Apache/XAMPP o servidor equivalente.
- Extensión PHP para MySQL.
- API de extracción de PDFs compatible con el endpoint configurado.

## Instalación local con XAMPP

1. Coloca el proyecto en:

   ```text
   C:\xampp\htdocs\servicio2
   ```

2. Instala dependencias PHP:

   ```bash
   composer install
   ```

3. Crea la base de datos `servicio` e importa tu respaldo local si existe.

4. Configura la conexión local en `config/db.local.php` usando `config/db.local.example.php` como base.

5. Ejecuta migraciones:

   ```bash
   php yii migrate
   ```

6. Configura la API de PDFs con variable de entorno si no usas localhost:

   ```text
   PDF_API_URL=http://127.0.0.1:5000/extract
   ```

7. Abre el sistema:

   ```text
   http://localhost/servicio2/web/
   ```

## Base de datos

Los dumps SQL no se versionan en Git. Mantén respaldos locales en `database/` o en una carpeta externa.

Tablas principales:

- `alumno`
- `archivo`
- `caja`
- `anaquel`
- `nivelalmacenamiento`
- `carga_masiva`
- `carga_masiva_detalle`
- `bitacora_accion`
- `fondo`
- `area_generadora`
- `clave_programatica`
- `seccion_serie`

## Seguridad y producción

- Cambiar credenciales iniciales.
- Mantener `config/*.local.php` fuera de Git.
- Usar HTTPS en producción.
- Respaldar base de datos y carpeta `web/archivos`.
- Revisar permisos de escritura en `runtime`, `web/assets` y `web/archivos`.

## Manual

Consulta [MANUAL_USUARIO.md](MANUAL_USUARIO.md) para el flujo de operación.

## Estado del proyecto

El sistema ya cuenta con despliegue probado, carga individual, carga masiva, revisión de pendientes, dashboard y bitácora. Como trabajo futuro se recomienda fortalecer reportes, búsqueda global, backups automatizados y QR consultable por caja/documento.
