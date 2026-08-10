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

## 6. Búsqueda global

Ruta: `Buscar > Búsqueda Global`

Permite localizar expedientes por matrícula, nombre del alumno, caja, nombre de documento o código clasificador. Desde los resultados se puede abrir la ficha del archivo, usar el localizador visual o descargar el PDF.

## 7. Localizador visual

Ruta: `Buscar > Búsqueda Global > Localizar`

El localizador muestra una representación 2D del anaquel donde se encuentra el expediente. La caja objetivo aparece resaltada y la ficha lateral muestra alumno, matrícula, código clasificador, documento y ubicación física.

## 8. Consulta por QR de caja

Ruta: `Buscar > Caja > Ver > Vista QR`

Cada caja puede generar un QR que abre una vista consultable con su ubicación física y los documentos registrados. Esta vista ayuda a demostrar que la caja física y su contenido digital están conectados.

## 9. Reporte de cajas

Ruta: `Reportes > Reporte de Cajas`

Muestra cada caja con su anaquel, nivel y cantidad de documentos registrados. Desde esta vista se puede abrir la caja, consultar su vista QR o exportar el reporte en CSV para revisarlo en Excel.

## 10. Reporte por alumno

Ruta: `Reportes > Reporte por Alumno`

Permite abrir una ficha documental por alumno con sus datos principales, expedientes registrados, código clasificador, caja, anaquel y nivel. La ficha puede imprimirse o exportarse en CSV.

## 11. Vista profesional de archivo

Ruta: `Buscar > Búsqueda Global > Ver`

La ficha de archivo muestra alumno, matrícula, documento, código clasificador, ubicación física y clasificación documental. Desde la misma pantalla se puede descargar el PDF, abrir el localizador visual, consultar la vista QR de la caja o abrir el reporte del alumno.

## 12. Validaciones de carga masiva

Ruta: `Crear > Carga Masiva`

Antes de procesar, la pantalla muestra cuántos PDFs fueron seleccionados, lista los nombres de archivo, bloquea formatos no PDF, limita el lote a 20 archivos y muestra estado de procesamiento al enviar.
## 13. Bitácora

Ruta: `Buscar > Bitácora`

La bitácora muestra acciones relevantes:

- creación de alumnos,
- creación de archivos,
- eliminación de archivos,
- procesamiento de cargas masivas,
- resolución de pendientes.

Sirve como evidencia de trazabilidad y control institucional.

## 14. Recomendaciones operativas

- Registrar primero los catálogos base: carreras, servicios, fondos, áreas, claves y secciones.
- Crear cajas antes de subir documentos.
- Revisar pendientes después de cada carga masiva.
- Mantener respaldos de base de datos y carpeta `web/archivos`.
- Cambiar credenciales iniciales antes de usar el sistema en producción.
