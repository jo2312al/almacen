# Manual de usuario - ASSRP

ASSRP es un sistema para registrar, digitalizar, clasificar y consultar expedientes vinculados a alumnos, cajas físicas y documentos PDF.

## 1. Inicio de sesión

1. Entra al sistema desde el navegador.
2. Inicia sesión con un usuario autorizado.
3. Al entrar verás el panel de control con métricas generales.

## 2. Panel de control

El dashboard muestra:

- Total de alumnos registrados.
- Total de cajas.
- Total de documentos.
- Cargas masivas realizadas.
- Pendientes por revisar.
- Errores de procesamiento.
- Últimas cargas masivas.
- Últimas acciones registradas en bitácora.

Este panel sirve para presentar el estado operativo del archivo documental.

## 3. Registro individual de archivos

Ruta: `Crear > Archivo`

1. Selecciona un alumno existente o sube una constancia PDF para análisis.
2. Selecciona fondo, clave programática, área generadora, sección/serie y caja.
3. El sistema genera el código clasificador.
4. Guarda el archivo.

El PDF queda almacenado en la carpeta del alumno y registrado en la base de datos.

## 4. Carga masiva por caja

Ruta: `Crear > Carga Masiva`

1. Selecciona la caja física que se va a digitalizar.
2. Selecciona la clasificación documental base.
3. Adjunta varios PDFs de la caja.
4. Presiona `Procesar Caja`.

El sistema procesa cada PDF, consulta la API de extracción y clasifica el resultado.

Estados posibles:

- `guardado`: el alumno existe y el archivo quedó registrado.
- `pendiente`: la API detectó datos, pero el alumno no existe todavía.
- `error`: el PDF no pudo analizarse o guardar correctamente.

## 5. Revisión de alumnos inexistentes

Ruta: `Cargas Masivas > Ver lote > Revisar alumno`

Cuando un PDF queda pendiente:

1. Abre el lote de carga masiva.
2. Presiona `Revisar alumno`.
3. Revisa los datos prellenados por la API.
4. Guarda el alumno.

Al guardar, el sistema:

- crea el alumno,
- mueve el PDF temporal a su carpeta final,
- registra el archivo,
- cambia el detalle de pendiente a guardado,
- actualiza los contadores del lote.

## 6. Búsqueda y consulta

Ruta: `Buscar`

Desde el menú se pueden consultar alumnos, cajas y anaqueles. Las vistas permiten revisar registros, abrir documentos asociados y consultar información de clasificación.

## 7. Bitácora

Ruta: `Buscar > Bitácora`

La bitácora muestra acciones relevantes:

- creación de alumnos,
- creación de archivos,
- eliminación de archivos,
- procesamiento de cargas masivas,
- resolución de pendientes.

Sirve como evidencia de trazabilidad y control institucional.

## 8. Recomendaciones operativas

- Registrar primero los catálogos base: carreras, servicios, fondos, áreas, claves y secciones.
- Crear cajas antes de subir documentos.
- Revisar pendientes después de cada carga masiva.
- Mantener respaldos de base de datos y carpeta `web/archivos`.
- Cambiar credenciales iniciales antes de usar el sistema en producción.
