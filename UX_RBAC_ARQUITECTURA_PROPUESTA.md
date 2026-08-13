# Arquitectura UX y RBAC propuesta para ASSRP

Fecha de análisis: 12 de agosto de 2026  
Alcance: arquitectura de información, navegación, pantallas, formularios, listados, flujos y autorización.  
Este documento no implementa código.

## 1. Diagnóstico actual

El sistema ya resuelve las piezas centrales de la gestión documental, pero su interfaz refleja controladores y tablas más que tareas. La navegación principal agrupa acciones bajo “Crear” y “Buscar”; la navegación operativa usa “Crear” y “Listar”. Esto obliga a conocer la estructura interna y genera duplicidad entre menú, tarjetas y accesos rápidos.

Hallazgos verificados en el repositorio:

1. **Navegación no segmentada por capacidad.** `views/layouts/main.php` muestra a todo usuario autenticado crear archivos, cargas y cajas; buscar alumnos, cajas, anaqueles y bitácora; reportes y escáner. No valida cada opción con permisos. `main-usuario.php` vuelve a exponer alumnos, cajas, archivos y anaqueles como CRUD.
2. **Dos experiencias inconsistentes.** Existen `site/index` e `index-usuario`, dos layouts y un módulo `/admin` separado. El rol `admin` es enviado a `/admin`, pero otras rutas conservan el layout general. La jerarquía, nomenclatura y ubicación de acciones cambian según la pantalla.
3. **Dashboards no realmente definidos por rol.** `site/index` consulta métricas globales, cargas recientes y bitácora; `index-usuario` es un lanzador simple. No existe dashboard verificado para `viewer`; la redirección apunta a `/viewer/home`, controlador/vista ausente en el repositorio inspeccionado. Tampoco existe aún el rol nominal `adminsuperior`; el esquema usa `superadmin`.
4. **Protección backend desigual.** `Alumno`, `Búsqueda`, `Reporte`, `Bitácora`, `Site` y catálogos usan `GhostAccessControl`; `Archivo`, `Caja` y `CargaMasiva` solo declaran `VerbFilter`. Por tanto, ocultar enlaces no resolvería el acceso directo.
5. **Permisos heredados peligrosos.** Un respaldo histórico contiene asignaciones donde roles `prueba`/`usuario` heredan administración de usuarios y un permiso `/*`. Debe auditarse la base activa; no se debe migrar esa jerarquía al nuevo modelo.
6. **Acciones visibles sin validación contextual.** En documentos aparecen siempre Descargar, Actualizar y Eliminar; en alumnos, Actualizar y Eliminar; en caja, Modificar, Eliminar y QR. La búsqueda ofrece siempre Ver, Localizar y Descargar. Los botones no reflejan permisos granulares.
7. **CRUD incompleto.** `AlumnoController::actionUpdate`, `actionDelete` y `actionGenerarQr` son stubs. La UI presenta acciones que no están implementadas de manera funcional.
8. **Exposición pública por QR excesiva.** `/caja/consulta` lista matrícula/alumno, enlaces al detalle interno y descarga de documentos, además de generar/descargar QR. Una consulta sin autenticación no debe revelar datos personales, inventario documental ni archivos.
9. **Listados orientados a columnas internas.** Documentos muestran IDs de caja y alumno en vez de códigos/nombres. Caja y detalles muestran IDs. Bitácora expone IP e identificador de entidad sin separar actividad operativa de auditoría técnica.
10. **Detalles tipo scaffold.** `/archivo/view`, `/caja/view` y `/alumno/view` usan `DetailView` Campo–Valor, muestran IDs y concentran acciones destructivas junto a acciones cotidianas.
11. **Formularios con carga cognitiva evitable.** El registro documental presenta simultáneamente alumno, cuatro clasificadores, caja y PDF. OCR rellena campos ocultos, pero no existe un paso explícito de revisión. Carga masiva obliga a elegir toda la clasificación antes de procesar, aunque parte podría heredarse de caja o valores frecuentes.
12. **Estados y mensajes técnicos.** Estados de lotes/detalles se presentan en valores internos; los errores de procesamiento pueden trasladar mensajes técnicos. No hay una taxonomía de mensajes por audiencia.
13. **Nombres poco comprensibles.** “Archivo” puede significar archivo digital, documento o archivo físico; “Nivelalmacenamiento” y “Sección Serie” reflejan modelo técnico. Se recomienda “Documentos”, “Archivo físico”, “Nivel de ubicación” y “Secciones y series”.

La decisión base es separar tres dominios: trabajo documental, consulta y gobierno técnico. La interfaz se debe renderizar a partir de permisos efectivos, no solo del nombre del rol.

## 2. Arquitectura propuesta

```text
ASSRP — Gestión documental
├── Inicio (dashboard específico por rol)
├── Buscar (búsqueda global; principal para usuario y viewer)
├── Documentos
│   ├── Explorar documentos
│   ├── Registrar documento
│   ├── Pendientes de revisión
│   └── Detalle del documento
│       ├── Datos documentales
│       ├── Persona / expediente académico
│       ├── Ubicación física
│       ├── Documento digital
│       └── Historial (según permiso)
├── Archivo físico
│   ├── Cajas
│   ├── Ubicaciones
│   ├── Localizar documento
│   └── Escanear QR
├── Procesamiento por lote
│   ├── Nueva carga
│   ├── Cargas recientes
│   ├── Pendientes de revisión
│   ├── Errores
│   └── Detalle de carga
├── Personas y expedientes (secundario; admin/adminsuperior)
│   ├── Buscar alumno
│   ├── Detalle del expediente
│   └── Registrar/corregir alumno
├── Reportes
│   ├── Inventario de cajas
│   └── Expedientes por alumno
├── Catálogos documentales
│   ├── Organización documental
│   │   ├── Fondos
│   │   ├── Secciones y series
│   │   ├── Áreas generadoras
│   │   └── Claves programáticas
│   ├── Ubicación física
│   │   ├── Anaqueles
│   │   └── Niveles de ubicación
│   └── Datos académicos
│       ├── Carreras
│       └── Generaciones
├── Actividad documental (admin/adminsuperior)
└── Administración del sistema (solo adminsuperior)
    ├── Usuarios
    ├── Roles y permisos
    ├── Auditoría del sistema
    ├── Configuración [futura]
    ├── Integraciones y OCR [futura]
    ├── Estado del sistema [futura]
    └── Mantenimiento [futura]
```

“Buscar” puede estar en el sidebar de roles de consulta y como buscador persistente en el encabezado de todos los roles autenticados. El encabezado contiene solo búsqueda global, ayuda contextual y menú de cuenta. El sidebar contiene módulos. Las acciones de pantalla viven en el encabezado de contenido; no se duplican en tarjetas.

## 3. Matriz rol × módulo

| Módulo | adminsuperior | admin | usuario | viewer |
|---|---|---|---|---|
| Inicio | Administrar/supervisar | Administrar operación | Crear/consultar | Solo lectura |
| Búsqueda global | Administrar | Administrar | Consultar | Solo lectura |
| Documentos | Administrar | Crear/editar | Crear/editar limitado | Solo lectura |
| Archivo físico | Administrar | Crear/editar | Consultar | Solo lectura |
| Procesamiento por lote | Administrar | Crear/editar/revisar | Sin acceso por defecto | Sin acceso |
| Personas y expedientes | Administrar | Crear/editar | Consulta contextual durante captura | Solo lectura contextual |
| Reportes | Administrar | Consultar/exportar | Sin acceso | Sin acceso |
| Catálogos documentales | Administrar | Crear/editar | Consulta contextual | Sin acceso directo |
| Actividad documental | Administrar | Consultar | Sin acceso | Sin acceso |
| Administración del sistema | Administrar | Sin acceso | Sin acceso | Sin acceso |

Nota: “usuario” puede recibir `carga.crear` o `archivo.editar` como excepción, pero esas capacidades no pertenecen a su perfil base.

## 4. Matriz de acciones

### Documentos

| Acción | adminsuperior | admin | usuario | viewer |
|---|---|---|---|---|
| Ver/listar | Sí | Sí | Sí | Sí |
| Crear | Sí | Sí | Sí | No |
| Editar datos | Sí | Sí | Propios/pendientes, si tiene permiso | No |
| Eliminar | Sí | Sí, con permiso explícito | No | No |
| Descargar | Sí | Sí | Si tiene `archivo.descargar` | Si tiene `archivo.descargar` |
| Procesar OCR | Sí | Sí | Sí durante registro | No |
| Reprocesar OCR | Sí | Sí | Solo propios/pendientes | No |
| Revisar | Sí | Sí | Propios/pendientes | No |
| Localizar | Sí | Sí | Sí | Sí |

### Cajas y ubicación

| Acción | adminsuperior | admin | usuario | viewer |
|---|---|---|---|---|
| Ver/listar/localizar | Sí | Sí | Sí | Sí |
| Crear/editar | Sí | Sí | No | No |
| Eliminar | Sí | Sí con `caja.eliminar` | No | No |
| Generar/descargar QR | Sí | Sí | No por defecto | No |
| Escanear/consultar QR | Sí | Sí | Sí | Sí |

### Alumnos

| Acción | adminsuperior | admin | usuario | viewer |
|---|---|---|---|---|
| Ver/buscar | Sí | Sí | Contextual | Contextual, solo lectura |
| Crear | Sí | Sí | Durante captura con `alumno.crear` | No |
| Editar/corregir | Sí | Sí | No por defecto | No |
| Eliminar | Sí | Solo con permiso excepcional | No | No |
| Ver expediente documental | Sí | Sí | Sí | Sí |

### Cargas masivas

| Acción | adminsuperior | admin | usuario | viewer |
|---|---|---|---|---|
| Ver | Sí | Sí | Solo con permiso excepcional | No |
| Crear/procesar | Sí | Sí | Solo con `carga.crear` | No |
| Revisar/corregir | Sí | Sí | Solo con `carga.revisar` | No |
| Eliminar/cancelar lote | Sí | No por defecto | No | No |

### Reportes, catálogos y administración

| Acción | adminsuperior | admin | usuario | viewer |
|---|---|---|---|---|
| Ver reportes | Sí | Sí | No | No |
| Exportar | Sí | Con `reporte.exportar` | No | No |
| Ver catálogos | Sí | Sí | Solo como selectores | No directo |
| Administrar catálogos | Sí | Sí con `catalogo.administrar` | No | No |
| Ver actividad documental | Sí | Sí | No | No |
| Ver auditoría técnica | Sí | No | No | No |
| Usuarios/roles/permisos | Sí | No | No | No |
| Configuración/mantenimiento | Sí | No | No | No |

## 5. Menú definitivo de cada rol

### adminsuperior

- Inicio
- Documentos
- Archivo físico
- Procesamiento por lote
- Personas y expedientes
- Reportes
- Catálogos documentales
- Actividad documental
- Administración del sistema

### admin

- Inicio
- Documentos
- Archivo físico
- Procesamiento por lote
- Personas y expedientes
- Reportes
- Catálogos documentales
- Actividad documental

### usuario

- Inicio
- Registrar documento
- Buscar
- Escanear QR

“Consultar documentos” y “Localizar” se resuelven desde Buscar; no deben ser entradas adicionales.

### viewer

- Inicio
- Buscar
- Consultar QR

El menú de cuenta para todos: Mi cuenta, Cambiar contraseña y Cerrar sesión. “Administración del sistema” no se repite allí; permanece en el sidebar de `adminsuperior`.

## 6. Dashboard de cada rol

### adminsuperior

**Objetivo:** supervisar gobierno, seguridad y continuidad sin mezclar tareas técnicas con operación diaria.  
**Componentes:** franja compacta de alertas críticas; resumen de documentos/cargas con problemas; actividad administrativa reciente; accesos a usuarios, permisos y auditoría; estado de integraciones solo cuando esa función exista.  
**Accesos rápidos:** Buscar, Administración del sistema, Auditoría, Documentos pendientes.  
**Alertas:** errores repetidos, intentos de acceso, lotes bloqueados, servicio OCR no disponible.  
**No mostrar:** ocho gráficas decorativas, métricas sin periodo, logs crudos, variables de entorno, endpoints o secretos.

### admin

**Objetivo:** gestionar trabajo documental pendiente.  
**Componentes:** cola “Requiere atención” (pendientes de revisión y errores); cargas recientes; cajas sin ubicación o con incidencias; actividad documental reciente; resumen con periodo (“registrados hoy/esta semana”).  
**Accesos rápidos:** Registrar documento, Nueva carga, Revisar pendientes, Buscar, Crear caja.  
**Alertas:** documentos sin caja, cargas con errores, matrículas no reconocidas.  
**No mostrar:** servidor, API, versión, permisos, usuarios, IP, logs técnicos.

### usuario

**Objetivo:** iniciar una tarea en un clic.  
**Componentes:** encabezado “¿Qué deseas hacer?”; cuatro acciones: Registrar documento, Buscar, Escanear QR, Localizar archivo; lista breve “Mis pendientes” y “Mis últimos registros” solo si existe autoría confiable.  
**Alertas:** únicamente pendientes propios y fallas expresadas en lenguaje operativo.  
**No mostrar:** métricas globales, gráficas, reportes, catálogos, bitácora, administración ni acciones destructivas.

### viewer

**Objetivo:** encontrar información autorizada.  
**Componentes:** buscador dominante con ejemplos; búsqueda avanzada plegable; documentos consultados recientemente (preferencia local o historial permitido); botón Consultar QR.  
**No mostrar:** estadísticas, altas, cargas, edición, exportación, procesamiento, catálogos o botones deshabilitados.

## 7. Pantallas necesarias

| Clasificación | Pantallas |
|---|---|
| Mantener | Login; escáner QR; localización visual; reportes de cajas y alumno; pantallas CRUD de catálogos, subordinadas al hub |
| Rediseñar | Los cuatro dashboards; búsqueda global; lista/detalle/registro/edición de documentos; lista/detalle de cajas; carga masiva; detalle de alumno; bitácora |
| Fusionar | `menucrear` en dashboards/acciones contextuales; `menubuscar` en búsqueda global; reportes de cajas/alumnos bajo un hub; ocho índices de catálogos bajo un hub; `archivo/index` y `busqueda/index` como una experiencia coherente de explorar/buscar |
| Modal/drawer | Alta rápida de alumno dentro de registro; filtros avanzados; edición rápida de ubicación de caja; vista de metadatos secundarios; confirmaciones destructivas |
| Ocultar de navegación | `/archivo/process-pdf`; `/alumno/get-alumno-info`; `/carga-masiva/view` y `/revisar`; `/busqueda/localizar`; `/caja/generar-qr`; acciones CRUD individuales de catálogos |
| Exclusiva de adminsuperior | Usuarios, roles, permisos; auditoría técnica; futura configuración/OCR/API/estado/mantenimiento |
| Eliminar si es redundante | `site/menucrear`, `site/menubuscar`; navegación `main-usuario` basada en Crear/Listar; acción de QR de alumno mientras siga vacía y sin caso de uso aprobado |

## 8. Especificación individual de pantallas

### 8.1 Inicio — adminsuperior

- **Objetivo:** supervisión y acceso a excepciones.
- **Roles:** adminsuperior.
- **Información:** alertas, trabajo bloqueado, actividad administrativa resumida, métricas con periodo.
- **Acciones:** abrir auditoría, permisos, pendientes; secundarias: ir a operación.
- **Filtros:** periodo y severidad.
- **Estados:** normal, advertencias, incidentes; vacío: “No hay alertas críticas”.
- **Permisos:** `sistema.supervisar`.
- **No mostrar:** logs/secrets en el dashboard.
- **Ruta actual:** `/site/index` y `/admin`.
- **Ruta propuesta:** `/inicio` con vista por rol; `/sistema` para administración.

### 8.2 Inicio — admin

- **Objetivo:** priorizar revisión y organización documental.
- **Roles:** admin.
- **Información:** pendientes, errores, cargas recientes, actividad documental.
- **Acciones:** registrar, cargar lote, revisar, buscar, crear caja.
- **Filtros:** periodo/responsable si existe trazabilidad.
- **Estados:** todo al día; pendientes; errores.
- **Permiso:** `dashboard.operacion`.
- **No mostrar:** telemetría o administración de usuarios.
- **Ruta actual/propuesta:** `/site/index` → `/inicio`.

### 8.3 Inicio — usuario

- **Objetivo:** lanzar captura/consulta.
- **Roles:** usuario.
- **Información:** cuatro acciones, pendientes propios, últimos registros propios.
- **Acciones:** registrar, buscar, escanear, localizar.
- **Estados:** sin pendientes: “No tienes revisiones pendientes”.
- **Permisos:** cada acceso se renderiza con su permiso.
- **No mostrar:** datos globales.
- **Ruta actual/propuesta:** `/site/index-usuario` → `/inicio`.

### 8.4 Inicio — viewer

- **Objetivo:** consulta inmediata.
- **Roles:** viewer.
- **Información/acciones:** buscador, filtros plegables, QR.
- **Estados:** ayudas de búsqueda y recientes.
- **Permisos:** `archivo.ver`, `caja.ver`.
- **No mostrar:** mutaciones ni descargas sin permiso.
- **Ruta actual:** `/viewer/home` ausente.
- **Ruta propuesta:** `/inicio`.

### 8.5 Explorar y buscar documentos

- **Objetivo:** localizar sin escoger entidad/tabla.
- **Roles:** todos autenticados.
- **Información:** resultados de documento con matrícula, alumno, documento, código, caja, ubicación y estado.
- **Acción principal:** Ver documento; secundarias condicionadas: Localizar, Descargar.
- **Filtros:** texto; tipo/clasificación; caja; ubicación; estado; fecha. Filtros avanzados plegables.
- **Estados:** inicial con ejemplos; sin resultados con sugerencias; error recuperable.
- **Permisos:** `archivo.ver`; descarga y ubicación por separado; aplicar alcance de datos si existe.
- **No mostrar:** IDs, rutas, hashes, OCR.
- **Ruta actual:** `/busqueda/index`, `/archivo/index`.
- **Ruta propuesta:** `/documentos` (el parámetro `q` se conserva).

### 8.6 Registrar/editar documento

- **Objetivo:** cargar PDF y confirmar datos extraídos.
- **Roles:** adminsuperior, admin, usuario con permiso.
- **Información:** PDF, alumno, datos documentales, clasificación y caja en pasos.
- **Acción principal:** Guardar documento; secundarias: guardar borrador/captura manual, cancelar.
- **Filtros:** no aplica; selectores con búsqueda.
- **Estados:** cargando, procesando, requiere revisión, listo, error OCR, guardado.
- **Permisos:** `archivo.crear`, `archivo.editar`, `archivo.procesar`; validar propiedad/estado.
- **No mostrar:** ruta, ID, endpoint o JSON OCR.
- **Ruta actual:** `/archivo/create`, `/archivo/update`, `/archivo/process-pdf`.
- **Ruta propuesta:** `/documentos/registrar`, `/documentos/{id}/editar`; OCR interno.

### 8.7 Detalle del documento

- **Objetivo:** comprender documento, alumno y ubicación.
- **Roles:** todos con `archivo.ver`.
- **Información:** jerarquía descrita en sección 11.
- **Acción principal:** Localizar; según permiso: Descargar, Editar. Eliminar en menú “Más” solo para permiso explícito.
- **Filtros:** historial por tipo/fecha solo si aplica.
- **Estados:** digital disponible/no disponible; ubicación asignada/sin asignar.
- **No mostrar:** ID como título ni ruta física del PDF.
- **Ruta actual:** `/archivo/view`.
- **Ruta propuesta:** `/documentos/{id}`.

### 8.8 Archivo físico — cajas

- **Objetivo:** consultar y organizar contenedores físicos.
- **Roles:** todos para consulta; admin/adminsuperior para edición.
- **Información:** código, ubicación, cantidad, estado de ubicación.
- **Acciones:** Ver; con permisos: Nueva caja, editar ubicación.
- **Filtros:** código, anaquel, nivel, con/sin documentos, sin ubicación.
- **Estados:** vacío guiado; caja sin ubicación; sin resultados.
- **Permisos:** `caja.ver/crear/editar`.
- **No mostrar:** IDs.
- **Ruta actual:** `/caja/index`.
- **Ruta propuesta:** `/archivo-fisico/cajas`.

### 8.9 Crear/editar caja

- **Objetivo:** asignar un código y ubicación válidos.
- **Roles:** adminsuperior/admin.
- **Información:** código, anaquel, nivel; capacidad/estado solo si se implementan en datos.
- **Acciones:** Guardar; secundaria: guardar y generar QR.
- **Estados:** ubicación ocupada/inválida; código duplicado.
- **Permisos:** `caja.crear/editar`.
- **No mostrar:** IDs de FK.
- **Ruta actual:** `/caja/create`; actualización no implementada en controlador actual.
- **Ruta propuesta:** `/archivo-fisico/cajas/nueva`, `/archivo-fisico/cajas/{id}/editar`.

### 8.10 Detalle de caja

- **Objetivo:** conocer ubicación y contenido; sección 12.
- **Roles:** todos con consulta; acciones según permiso.
- **Acciones:** Escanear/localizar, Ver documento; admin: Editar ubicación, Generar QR; eliminar separado.
- **Filtros:** contenido por matrícula/documento/estado.
- **Estados:** vacía, sin ubicación, con incidencias.
- **Permisos:** `caja.ver`, `caja.editar`, `caja.generarQr`, `caja.eliminar`.
- **No mostrar:** ID interno, descargas no permitidas.
- **Ruta actual/propuesta:** `/caja/view` → `/archivo-fisico/cajas/{id}`.

### 8.11 Localizar documento

- **Objetivo:** guiar físicamente hasta anaquel/nivel/caja.
- **Roles:** todos con `archivo.localizar`.
- **Información:** ruta textual y visual: zona (si existe) → anaquel → nivel → caja; documento.
- **Acciones:** Ver caja, volver al documento.
- **Estados:** ubicación incompleta con instrucción de escalamiento.
- **No mostrar:** descargas como acción dominante.
- **Ruta actual:** `/busqueda/localizar`.
- **Ruta propuesta:** `/documentos/{id}/ubicacion`.

### 8.12 Escanear/consultar QR

- **Objetivo:** identificar una caja y abrir su consulta segura.
- **Roles:** todos autenticados; invitado solo resumen público mínimo.
- **Información autenticada:** código, ubicación, cantidad y lista permitida. Pública: código institucional de caja y mensaje de verificación; ubicación exacta y contenido solo si política institucional lo aprueba.
- **Acciones:** Consultar caja; autenticado: localizar/ver contenido.
- **Estados:** cámara bloqueada, QR inválido, caja inexistente.
- **Permisos:** respuesta del servidor diferente por audiencia.
- **No mostrar público:** alumno, matrícula, nombre documental, archivos, detalle interno, descargar/generar QR.
- **Ruta actual:** `/site/scan`, `/caja/consulta`.
- **Ruta propuesta:** `/qr/escanear`, `/qr/caja/{token}` con token no secuencial/revocable.

### 8.13 Procesamiento por lote — inicio/historial

- **Objetivo:** ver cargas recientes, pendientes y errores en una sola entrada.
- **Roles:** adminsuperior/admin; excepciones explícitas.
- **Información:** caja, fecha, responsable, progreso/resultado, pendientes, errores.
- **Acciones:** Nueva carga, abrir pendientes, reintentar elegibles.
- **Filtros:** estado, periodo, caja, responsable.
- **Estados:** todo revisado; procesando; error parcial.
- **Permisos:** `carga.ver/crear/revisar`.
- **No mostrar:** ruta temporal, JSON OCR.
- **Ruta actual:** `/carga-masiva/index`.
- **Ruta propuesta:** `/procesamiento-lotes`.

### 8.14 Nueva carga y detalle/revisión

- **Objetivo:** subir PDFs, procesarlos y resolver excepciones.
- **Roles:** adminsuperior/admin.
- **Información:** caja, clasificación heredable, archivos; detalle con resultado humano por PDF.
- **Acciones:** Procesar; revisar pendiente; reintentar; finalizar.
- **Filtros:** detalle por guardado/pendiente/error.
- **Estados:** en espera, procesando, parcialmente completado, completado, requiere atención.
- **Permisos:** `carga.crear`, `carga.revisar`; no aceptar IDs fuera del alcance.
- **No mostrar:** datos técnicos salvo panel de diagnóstico exclusivo y separado.
- **Ruta actual:** `/carga-masiva/create`, `/view`, `/revisar`.
- **Ruta propuesta:** `/procesamiento-lotes/nuevo`, `/{id}`, `/{id}/pendientes/{detalle}`.

### 8.15 Personas y expedientes

- **Objetivo:** administrar identidad académica como soporte documental, no como módulo operativo principal.
- **Roles:** adminsuperior/admin; consulta contextual para usuario/viewer.
- **Información:** matrícula, nombre, carrera, ingreso/servicio, número de documentos.
- **Acciones:** Ver expediente; admin: registrar/corregir.
- **Filtros:** matrícula/nombre, carrera, ingreso.
- **Estados:** sin documentos: “Aún no hay documentos asociados”.
- **Permisos:** `alumno.ver/crear/editar`; eliminar excepcional.
- **No mostrar:** IDs; QR de alumno sin caso de uso.
- **Ruta actual:** `/alumno/index/create/view`.
- **Ruta propuesta:** `/personas`, `/personas/{id}`; alta rápida como modal en captura.

### 8.16 Reportes

- **Objetivo:** seleccionar y ejecutar reportes existentes desde un hub.
- **Roles:** adminsuperior/admin.
- **Información:** dos reportes existentes: inventario de cajas y expediente por alumno.
- **Acciones:** abrir, filtrar; exportar con permiso.
- **Filtros:** reporte de cajas por ubicación; alumnos por búsqueda/carrera; periodo cuando el modelo lo soporte.
- **Estados:** sin datos con explicación.
- **Permisos:** `reporte.ver/exportar`.
- **No mostrar:** un enlace de sidebar por reporte.
- **Ruta actual:** `/reporte/cajas`, `/alumnos`, `/alumno`, exportaciones.
- **Ruta propuesta:** `/reportes`, subrutas actuales normalizadas.

### 8.17 Catálogos documentales

- **Objetivo:** administrar datos de referencia sin saturar navegación.
- **Roles:** adminsuperior/admin con permiso.
- **Información:** hub por tres grupos; cada índice muestra código/nombre/uso/estado si existe.
- **Acciones:** abrir catálogo; crear/editar; eliminar solo si no está usado.
- **Filtros:** texto y estado cuando exista.
- **Estados:** catálogo vacío; elemento en uso no eliminable.
- **Permisos:** `catalogo.ver/administrar` y, recomendable, permiso por grupo.
- **No mostrar:** ocho enlaces permanentes.
- **Ruta actual:** controladores individuales.
- **Ruta propuesta:** `/catalogos` como hub; conservar subrutas inicialmente.

### 8.18 Actividad documental

- **Objetivo:** rastrear acciones funcionales.
- **Roles:** admin/adminsuperior.
- **Información:** fecha, usuario, acción humana, objeto, descripción.
- **Acciones:** filtrar y abrir objeto; exportar solo si se autoriza.
- **Filtros:** periodo, usuario, acción, entidad.
- **Estados:** sin actividad en el periodo.
- **Permisos:** `actividad.ver`.
- **No mostrar a admin:** IP, autenticación, cambios de permisos, errores internos.
- **Ruta actual:** `/bitacora/index`.
- **Ruta propuesta:** `/actividad` y `/sistema/auditoria` como fuentes/vistas separadas.

### 8.19 Administración del sistema

- **Objetivo:** gobierno técnico exclusivo.
- **Roles:** adminsuperior.
- **Información/acciones:** sección 17.
- **Permisos:** permisos administrativos explícitos; no depender solo de bandera `superadmin`.
- **No mostrar:** secretos completos; operaciones destructivas sin reautenticación/confirmación.
- **Ruta actual:** `/user-management/*`, `/admin` básico.
- **Ruta propuesta:** `/sistema/*`.

## 9. Campos de cada formulario

### Documento

| Campo | Visible | Obligatorio | Automático | Rol | Observación |
|---|---|---|---|---|---|
| PDF | Sí | Sí al crear | No | creador | Primero; validar tipo/tamaño |
| Alumno (matrícula/nombre) | Sí | Sí | Búsqueda/autorrelleno OCR | creador/editor | Selector buscable, no lista enorme |
| Alta rápida de alumno | Condicional | Según caso | Prellenada por OCR | con `alumno.crear` | Modal/drawer |
| Nombre/tipo de documento | Sí en revisión | Sí | OCR/sugerencia | creador/editor | No mantenerlo solo oculto |
| Código clasificador | Sí en revisión | Sí | Calculado desde clasificación cuando sea posible | creador/editor | Mostrar código legible |
| Fondo | Avanzado/condicional | Según política | Predeterminado | creador/editor | Agrupar “Clasificación documental” |
| Clave programática | Avanzado/condicional | Según política | Predeterminada | creador/editor | Selector dependiente |
| Área generadora | Avanzado/condicional | Según política | Predeterminada | creador/editor | Filtrar opciones válidas |
| Sección/serie | Sí/condicional | Según política | Sugerida | creador/editor | Dependiente de fondo/área |
| Caja | Sí | Sí | Sugerida/última usada | creador/editor | Mostrar ubicación y capacidad si existe |
| Ruta, IDs internos, JSON OCR | No | Técnico | Sí | ninguno en UI | Persistencia interna |

### Caja

| Campo | Visible | Obligatorio | Automático | Rol | Observación |
|---|---|---|---|---|---|
| Código de caja | Sí | Sí | Sugerible | admin/adminsuperior | Único y legible |
| Anaquel | Sí | Según política | No | admin/adminsuperior | Selector buscable |
| Nivel de ubicación | Sí | Según política | Filtrado por anaquel | admin/adminsuperior | No mostrar ID |
| QR | No como campo | No | Generado después | admin/adminsuperior | Acción posterior |
| ID | No | Técnico | Sí | ninguno | Interno |

### Alumno

| Campo | Visible | Obligatorio | Automático | Rol | Observación |
|---|---|---|---|---|---|
| Matrícula | Sí | Sí | OCR/prellenado | autorizado | Validación inmediata de duplicado |
| Nombre(s) | Sí | Sí | OCR/prellenado | autorizado | Capitalización sugerida |
| Apellido paterno | Sí | Sí según modelo | OCR | autorizado | Revisar política para casos sin apellido |
| Apellido materno | Sí | Sí según modelo | OCR | autorizado | Igual consideración |
| Carrera | Sí | Recomendado | Por expediente previo | autorizado | Buscable |
| Año de ingreso | Sí | Opcional | Derivable si hay fuente | autorizado | Validar rango |
| Servicio/periodo | Secundario | Opcional | No | admin | Mostrar si aporta a clasificación |
| Generación | No mientras no se capture | Opcional | Calculable desde ingreso | sistema/admin | Evitar duplicar ingreso |
| ID | No | Técnico | Sí | ninguno | Interno |

### Carga masiva

| Campo | Visible | Obligatorio | Automático | Rol | Observación |
|---|---|---|---|---|---|
| Caja | Sí | Sí | Última usada opcional | admin/adminsuperior | Mostrar ubicación |
| PDFs | Sí | Sí | No | admin/adminsuperior | Cola, tamaño y validación previa |
| Fondo/clave/área/serie | Sección avanzada | Según política | Heredar de caja/lote/preset | admin/adminsuperior | Mostrar resumen editable |
| Responsable/fechas/estado/contadores | No editables | Técnico | Sí | vista posterior | Nunca pedirlos |
| Rutas temporales/datos OCR | No | Técnico | Sí | sistema | Solo diagnóstico protegido |

### Catálogos

| Campo | Visible | Obligatorio | Automático | Rol | Observación |
|---|---|---|---|---|---|
| Código | Sí cuando exista | Sí | No | admin/adminsuperior | Validar único |
| Nombre/Descripción | Sí | Sí | No | admin/adminsuperior | Etiqueta humana |
| Relación padre | Condicional | Según catálogo | Filtrada | admin/adminsuperior | Dependencias claras |
| ID/timestamps | No | Técnico | Sí | ninguno | Interno |

## 10. Columnas de cada listado

| Listado | Columnas visibles |
|---|---|
| Documentos/búsqueda | Documento o tipo; código/expediente; matrícula; alumno; caja; ubicación; estado; fecha de registro si se incorpora; acciones permitidas |
| Cajas | Código; ubicación (anaquel · nivel); documentos; estado de ubicación; última actualización si existe; acción Ver |
| Contenido de caja | Documento; matrícula; alumno; código; estado; acción Ver/Localizar según permiso |
| Alumnos | Matrícula; nombre completo; carrera; ingreso/generación (una sola representación); documentos; acción Ver expediente |
| Cargas | Referencia de lote; caja; fecha; responsable; estado; total; pendientes; errores; acción Ver |
| Detalle de carga | Archivo; matrícula detectada; alumno vinculado; resultado humano; mensaje operativo; acción Revisar/Ver |
| Reporte cajas | Caja; anaquel; nivel; documentos; estado; Ver caja |
| Reporte alumnos | Matrícula; alumno; carrera; documentos; Ver reporte; Exportar solo con permiso |
| Actividad documental | Fecha; usuario; acción; elemento; descripción; enlace contextual |
| Auditoría sistema | Fecha; actor; evento; resultado; origen/IP; severidad; detalles protegidos |
| Catálogos con código | Código; nombre/descripcion; relaciones relevantes; uso/estado si existe; acciones permitidas |
| Carreras/generaciones/ubicaciones simples | Nombre; uso/estado si existe; acciones permitidas |

No usar columna serial ni PK como identificación principal. En móvil conservar documento/código, persona o caja, estado y acción principal; el resto va en expansión.

## 11. Diseño del detalle del documento

1. **Encabezado:** nombre/tipo del documento, código clasificador, estado humano y contexto “Matrícula · Alumno”. Acción primaria Localizar; Descargar solo con permiso; Editar condicional; Eliminar dentro de “Más acciones”.
2. **Resumen esencial:** alumno, caja y ruta física legible. Sin IDs.
3. **Datos del documento:** tipo/nombre, clasificación completa en formato jerárquico Fondo → Sección/serie → Área → Clave.
4. **Alumno y expediente:** matrícula, nombre, carrera e ingreso; enlace “Ver expediente”.
5. **Archivo físico:** Caja → Anaquel → Nivel, con representación visual compacta y “Cómo llegar/Localizar”.
6. **Documento digital:** visor PDF o miniatura; descargar según permiso; si falta, mensaje y acción permitida.
7. **Historial:** colapsado y solo para `actividad.ver`; eventos humanos, no logs técnicos.
8. **Metadatos técnicos:** ausentes. Solo `adminsuperior`, desde auditoría/diagnóstico separado, puede consultar resultado OCR técnico.

Estados vacíos: “Este documento aún no tiene una ubicación física. Solicita a un administrador que lo asigne” o, para admin, botón “Asignar caja”. Confirmación destructiva: “Eliminar [código — nombre]. Se quitará el registro y el PDF asociado; no podrá consultarse ni descargarse. La actividad quedará auditada.”

## 12. Diseño del detalle de caja

1. **Encabezado:** “Caja [código]”, estado de ubicación, acciones permitidas.
2. **Ubicación:** ruta Anaquel → Nivel; visualización espacial; editar ubicación solo para admin.
3. **Resumen:** cantidad de documentos y, si existe, incidencias. No usar tarjetas numéricas gigantes.
4. **Contenido:** buscador dentro de caja y tabla definida en sección 10.
5. **QR:** vista previa y descargar/generar solo para admin/adminsuperior; explicar que abre una consulta segura.
6. **Actividad:** movimientos y cambios de ubicación, si hay trazabilidad y permiso.

Caja vacía: “Esta caja todavía no contiene documentos. Asigna documentos durante el registro o la edición.” Caja sin ubicación: “La caja no tiene anaquel y nivel asignados”; admin ve “Asignar ubicación”, los demás ven una explicación. Eliminar: “Eliminar la caja [código]. Contiene N documentos. Primero debes reasignarlos”; el backend debe impedir eliminación con dependencias salvo flujo formal de reasignación.

## 13. Flujo de registro

1. Inicio → **Registrar documento**.
2. Subir/arrastrar PDF; validar tipo, tamaño y lectura antes de enviar.
3. Iniciar OCR automáticamente, mostrando “Estamos leyendo el documento”.
4. Si OCR responde, buscar matrícula y alumno automáticamente.
5. Si hay coincidencia única, vincular y mostrar confirmación editable. Si no existe, ofrecer alta rápida solo a quien tenga `alumno.crear`; si no, crear pendiente para admin.
6. Presentar revisión en dos bloques: Datos detectados y Clasificación/ubicación. Resaltar solo campos dudosos o faltantes.
7. Calcular/sugerir nombre, código y clasificación; el usuario no captura IDs ni ruta.
8. Seleccionar caja con búsqueda y ubicación visible; sugerir última caja válida si la política lo permite.
9. Ejecutar validación final de duplicados, permisos, relaciones y disponibilidad.
10. Guardar registro, almacenar PDF de forma segura y escribir actividad.
11. Mostrar éxito con código, caja y acciones: Ver documento, Registrar otro. No redirigir a una tabla genérica.

Si OCR falla: “No fue posible leer el documento automáticamente. Intenta nuevamente o captura los datos manualmente.” Detalle técnico correlacionado solo en auditoría de `adminsuperior`.

## 14. Flujo de búsqueda

1. Inicio/encabezado → escribir matrícula, nombre, expediente, documento, caja o ubicación.
2. Resultados unificados priorizados por coincidencia; la primera versión puede devolver documentos y señalar el tipo de coincidencia.
3. Aplicar filtros solo si hay muchos resultados.
4. Abrir detalle del documento.
5. Elegir Localizar para ver Caja → Anaquel → Nivel.
6. Desde ubicación, abrir detalle de caja o volver al documento.

Sin resultados: “No encontramos documentos con ‘X’. Prueba con matrícula, nombre, código de documento o caja.” No sugerir entidades a las que el rol no tiene acceso.

## 15. Flujo de carga masiva

1. Procesamiento por lote → Nueva carga.
2. Seleccionar caja y validar ubicación; establecer clasificación común con valores sugeridos.
3. Agregar hasta el límite permitido; validar cada PDF antes de iniciar.
4. Mostrar resumen y comenzar procesamiento; no bloquear la navegación si se implementa trabajo en segundo plano.
5. Por archivo: leer OCR, identificar alumno, validar duplicado, clasificar y guardar.
6. Clasificar resultados como **Guardado**, **Requiere revisión** o **No procesado**; evitar “error” cuando hay acción humana posible.
7. Cola de revisión prioriza pendientes; formulario muestra PDF/datos detectados y decisión requerida.
8. Permitir reintentar fallos transitorios; la captura manual resuelve fallos de lectura.
9. Finalizar cuando no existan pendientes o registrar explícitamente excepciones autorizadas.
10. Mostrar resumen final y conservar actividad de cada resolución.

## 16. Flujo QR

1. Abrir Escanear QR y solicitar cámara con explicación.
2. Leer token firmado/no secuencial; validar vigencia y caja en backend.
3. Si el usuario está autenticado, aplicar `caja.ver` y alcance; mostrar código, ubicación y contenido permitido.
4. Si es invitado, mostrar solo “Caja institucional [código]” y estado de identificación. No PII, inventario, ubicación sensible ni descargas por defecto.
5. Acción Consultar abre el detalle adecuado a la audiencia.
6. Localizar muestra anaquel/nivel solo a usuarios autorizados.
7. QR inválido: “No reconocimos este código. Verifica que pertenezca al sistema o solicita ayuda.” Registrar detalle técnico sin mostrarlo.

## 17. Administración del sistema

| Área | Estado actual | Contenido |
|---|---|---|
| Usuarios | Existe | Módulo `/user-management`; solo adminsuperior |
| Roles y permisos | Existe | Módulo Webvimark; requiere reconstruir jerarquía y permisos granulares |
| Auditoría documental | Parcial | `bitacora_accion`; separar vista funcional |
| Auditoría de sistema/seguridad | Parcial/futura | El módulo puede tener visitas/eventos; diseñar vista consolidada protegida |
| Configuración general/parámetros | Futura recomendada | No se verificó UI existente; no inventar controles |
| Integraciones/OCR/API | Futura recomendada | Existe servicio de OCR en código, no una consola de configuración verificada |
| Estado de servicios | Futura recomendada | Salud de OCR/almacenamiento, sin secretos |
| Información del sistema | Futura recomendada | Versión, entorno y diagnóstico sanitizado |
| Mantenimiento | Futura recomendada | Tareas explícitas, auditadas y con reautenticación |

La portada de Administración del sistema debe ser un índice sobrio, no otro dashboard saturado. Operaciones destructivas requieren permiso específico, POST, CSRF, reautenticación para alto impacto, confirmación contextual y bitácora inmutable. Secretos nunca se muestran completos.

## 18. Información que debe desaparecer por rol

Leyenda: **Visible**, **Contextual**, **Oculto**.

| Elemento | adminsuperior | admin | usuario | viewer |
|---|---|---|---|---|
| ID interno | Contextual solo diagnóstico | Oculto | Oculto | Oculto |
| Ruta física digital/temporal | Contextual protegida | Oculto | Oculto | Oculto |
| JSON/confianza OCR | Contextual en diagnóstico | Oculto; solo resultado humano | Oculto | Oculto |
| API/endpoints/servidor/versión | Administración del sistema | Oculto | Oculto | Oculto |
| Configuración técnica | Visible en área exclusiva cuando exista | Oculto | Oculto | Oculto |
| Usuarios/roles/permisos | Visible | Oculto | Oculto | Oculto |
| Botón eliminar documento/caja | Con permiso | Solo permiso explícito | Oculto | Oculto |
| Botón editar | Con permiso | Visible donde corresponda | Limitado a flujo propio | Oculto |
| Descargar | Visible | Visible | Contextual por permiso | Contextual por permiso |
| Exportaciones | Visible | Contextual por permiso | Oculto | Oculto |
| Actividad documental | Visible | Visible | Oculto | Oculto |
| IP/autenticaciones/eventos técnicos | Visible en auditoría | Oculto | Oculto | Oculto |
| Métricas globales | Contextual | Contextual operativa | Oculto | Oculto |
| Logs/errores crudos | Diagnóstico protegido | Oculto | Oculto | Oculto |

## 19. Seguridad pendiente

Prioridad crítica:

1. Añadir autorización servidor a `/archivo/*`, `/caja/*` y `/carga-masiva/*`; hoy no tienen `GhostAccessControl` ni verificación granular.
2. No tratar `/caja/consulta?caj_id=N` como pública en su forma actual: usa ID enumerable y expone alumnos, documentos, enlaces internos y descargas. Sustituir por token no predecible y DTO público mínimo.
3. Proteger `/archivo/download` por `archivo.descargar` y autorización al documento; no basta conocer el ID.
4. Proteger `/archivo/process-pdf`, restaurar CSRF en AJAX o usar autenticación/token apropiado; ahora desactiva CSRF. Limitar tamaño, tipo, frecuencia y duración.
5. Auditar base RBAC activa y eliminar comodines/herencias históricas (`/*`, permisos de usuarios/roles en operativos). Crear `adminsuperior`, migrar `superadmin` de forma controlada y no usar nombres de usuario como autorización.
6. Implementar denegación por defecto: si la ruta no tiene permiso asignado, no debe quedar abierta.

Por grupo:

| Rutas | Verificación backend requerida |
|---|---|
| `/archivo/index/view` | `archivo.ver` + alcance |
| `/archivo/create/update` | `archivo.crear/editar`; ownership/estado para usuario |
| `/archivo/delete` | `archivo.eliminar`, POST+CSRF, dependencias, auditoría |
| `/archivo/download` | `archivo.descargar`, archivo permitido, nombre seguro |
| `/archivo/process-pdf` | `archivo.procesar`, autenticación, CSRF/rate limit, validación PDF |
| `/caja/index/view` | `caja.ver` |
| `/caja/create/update/generar-qr` | permisos separados |
| `/caja/delete` | `caja.eliminar`, impedir si contiene documentos |
| `/caja/consulta` | política pública mínima/token; o autenticación |
| `/carga-masiva/*` | `carga.ver/crear/revisar`; validar detalle pertenece a lote visible |
| `/alumno/get-alumno-info` | `alumno.ver`, minimizar JSON y evitar enumeración |
| `/alumno/create/update/delete` | permisos separados; implementar stubs antes de exponerlos |
| `/busqueda/*` | `archivo.ver/localizar`; filtrar resultados y joins por alcance |
| `/reporte/*` | `reporte.ver`; exportaciones con `reporte.exportar` y límites |
| `/bitacora/*` | separar `actividad.ver` de `auditoria.ver`; proteger PII/IP |
| Catálogos | `catalogo.ver/administrar`; impedir borrar referencias usadas |
| `/user-management/*` | exclusivamente permisos de adminsuperior; revisar todos los comodines |
| `/admin`, debug, Gii, `phpinfo.php` | adminsuperior/red interna; retirar `phpinfo.php` de web accesible; debug/Gii solo desarrollo/local |

Además: registrar quién crea/modifica documentos y cajas; política de descargas; protección contra IDOR; headers y almacenamiento PDF fuera del webroot cuando sea posible; análisis de malware; límites de carga; mensajes sin trazas; pruebas automatizadas de matriz rol×ruta. La UI pregunta `can()` por acción, pero el controlador repite la misma comprobación.

Permisos base propuestos: `archivo.ver/crear/editar/eliminar/descargar/procesar/revisar/localizar`; `caja.ver/crear/editar/eliminar/generarQr`; `alumno.ver/crear/editar/eliminar`; `carga.ver/crear/revisar`; `reporte.ver/exportar`; `catalogo.ver/administrar`; `actividad.ver`; `auditoria.ver`; `usuarios.administrar`; `roles.administrar`; `permisos.administrar`; `configuracion.administrar`; `sistema.diagnosticar`; `mantenimiento.ejecutar`.

## 20. Propuesta final consolidada

### Navegación adminsuperior

Inicio · Documentos · Archivo físico · Procesamiento por lote · Personas y expedientes · Reportes · Catálogos documentales · Actividad documental · Administración del sistema.

### Navegación admin

Inicio · Documentos · Archivo físico · Procesamiento por lote · Personas y expedientes · Reportes · Catálogos documentales · Actividad documental.

### Navegación usuario

Inicio · Registrar documento · Buscar · Escanear QR.

### Navegación viewer

Inicio · Buscar · Consultar QR.

### Pantallas compartidas

Login, búsqueda, detalle documental, localización, caja en modo consulta y QR autenticado; su información y acciones se calculan con permisos efectivos.

### Pantallas exclusivas

Procesamiento por lote, reportes y catálogos para perfiles administrativos; actividad para admin/adminsuperior; Administración del sistema solo para adminsuperior.

### Pantallas eliminadas o fusionadas

Eliminar `menucrear` y `menubuscar`; fusionar la exploración de archivo con búsqueda documental; agrupar reportes y catálogos; retirar QR de alumno hasta definir necesidad; reemplazar layouts divergentes por un shell común que recibe navegación por rol.

### Pantallas que deben simplificarse

Dashboards, listas y detalles de documento/caja/alumno, formulario de documento, carga masiva, búsqueda, bitácora y consulta QR pública. Quitar IDs, metadatos técnicos, rutas, acciones no autorizadas y estadísticas sin decisión asociada.

### Funciones que deben moverse a Administración del sistema

Usuarios, roles, permisos, auditoría técnica y, cuando existan, configuración global, OCR/API, integraciones, versión/entorno, diagnóstico, estado de servicios y mantenimiento.

### Orden recomendado de diseño e implementación

1. **Modelo RBAC y política QR/descargas.** Define qué puede existir en todas las pantallas y cierra riesgos antes del rediseño visual.
2. **Shell de navegación por rol + dashboards usuario/viewer.** Produce la mayor reducción inmediata de complejidad y resuelve la ruta `viewer` ausente.
3. **Búsqueda global, detalle documental y localización.** Es el núcleo compartido de consulta.
4. **Registro documental con OCR y revisión.** Es el flujo operativo más frecuente y concentra mayor carga cognitiva.
5. **Archivo físico: lista/detalle de caja y QR seguro.** Completa la promesa de localizar documentos y corrige exposición pública.
6. **Procesamiento por lote y cola de revisión.** Organiza excepciones y trabajo administrativo.
7. **Personas/expedientes.** Se integra como entidad secundaria y elimina navegación basada en tabla.
8. **Reportes, catálogos y actividad documental.** Se agrupan sin multiplicar enlaces.
9. **Administración del sistema.** Consolidar lo existente y solo después diseñar funciones técnicas futuras verificadas.

La prueba de aceptación final debe recorrer cada ruta con los cuatro roles y confirmar dos condiciones independientes: la acción no se renderiza en la interfaz y el servidor devuelve denegación cuando se invoca directamente sin permiso.
