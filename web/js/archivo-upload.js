$(document).ready(function () {
    // --- SELECCIÓN DE ELEMENTOS DEL DOM ---
    const form = $('#archivo-form');
    const fileInput = form.find('#archivo-file');
    const alumnoSelect = form.find('#archivo-arc_alumno_id');
    const formInputsForCode = form.find('.code-component');
    const codigoPreview = form.find('#arc_codigo_preview');
    const codigoHidden = form.find('#archivo-arc_codigo');
    const nombreDocumentoHidden = form.find('#archivo-arc_nombre_documento');
    const spinner = $('#loading-spinner');
    const alumnoFeedback = form.find('#alumno-feedback');
    const alumnoModal = new bootstrap.Modal(document.getElementById('alumno-modal'));
    const modalBody = $('#alumno-modal .modal-body');
    const modalTitle = $('#alumno-modal .modal-title');
    const btnAnalizar = $('#btn-analizar-pdf'); // Botón para analizar el PDF

    let expedienteMatricula = ''; // Almacena la matrícula del alumno seleccionado/creado

    // --- MANEJADORES DE EVENTOS ---
    
    // Habilitar/deshabilitar el botón "Analizar" si se selecciona o no un archivo.
    fileInput.on('change', function() {
        btnAnalizar.prop('disabled', this.files.length === 0);
    });
    
    // **NUEVO:** El análisis se dispara con el clic del botón dedicado.
    btnAnalizar.on('click', handleFileAnalysis);

    // Si se selecciona un alumno del dropdown.
    alumnoSelect.on('change', handleAlumnoSelect);

    // Si cambia cualquier componente del código clasificador.
    form.on('change', '.code-component', generarArcCodigo);

    // Cuando se envía el formulario desde dentro del modal.
    modalBody.on('submit', '#modal-alumno-form', handleModalFormSubmit);

    /**
     * ===================================================================
     * FUNCIÓN PARA GENERAR EL CÓDIGO CLASIFICADOR
     * Extrae los códigos de los dropdowns y construye el código final.
     * ===================================================================
     */
    function generarArcCodigo() {
        // Si no tenemos la matrícula (expediente), no podemos generar el código completo.
        if (!expedienteMatricula) {
            codigoPreview.val('');
            codigoHidden.val('');
            nombreDocumentoHidden.val('');
            return;
        }

        // Función auxiliar para obtener el código del texto "CODIGO - DESCRIPCION"
        const getCodeFromText = (text) => {
            if (!text || text.includes('Seleccionar')) {
                return '00';
            }
            return text.split(' - ')[0].trim();
        };

        const fondo = getCodeFromText(form.find('#archivo-arc_fondo_id option:selected').text());
        const clave = getCodeFromText(form.find('#archivo-arc_clave_programatica_id option:selected').text());
        const area = getCodeFromText(form.find('#archivo-arc_area_generadora_id option:selected').text());
        const seccion = getCodeFromText(form.find('#archivo-arc_seccion_serie_id option:selected').text());
        
        const anio = new Date().getFullYear();

        // Construimos el código final
        const codigoGenerado = `${fondo}/${clave}/${area}/${seccion}/${expedienteMatricula}/${anio}`;
        
        // Actualizamos la vista previa y los campos ocultos que se enviarán con el formulario
        codigoPreview.val(codigoGenerado);
        codigoHidden.val(codigoGenerado);
        nombreDocumentoHidden.val(codigoGenerado);
    }

    // --- FUNCIONES DE LÓGICA Y AJAX ---

    /**
     * Se activa con el clic en el botón "Analizar".
     * Envía el PDF al controlador para ser procesado por la API.
     */
    function handleFileAnalysis() {
        const file = fileInput[0].files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('pdfFile', file);

        spinner.removeClass('d-none'); // Mostrar spinner
        alumnoFeedback.text('Procesando constancia...').removeClass('text-success text-danger text-warning').addClass('text-info');
        btnAnalizar.prop('disabled', true); // Deshabilitar botón durante el proceso

        $.ajax({
            url: window.processPdfUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: handleApiResponse,
            error: (jqXHR, textStatus, errorThrown) => {
                spinner.addClass('d-none');
                btnAnalizar.prop('disabled', false); // Rehabilitar botón en caso de error
                alumnoFeedback.text('Error de comunicación con el servidor.').removeClass('text-info').addClass('text-danger');
                console.error("Error en processPdfUrl:", textStatus, errorThrown, jqXHR.responseText);
            }
        });
    }

    /**
     * Obtiene la matrícula de un alumno ya existente seleccionado en el dropdown.
     */
    function handleAlumnoSelect() {
        const selectedId = $(this).val();
        if (!selectedId) {
            expedienteMatricula = '';
            alumnoFeedback.text('');
            generarArcCodigo();
            return;
        }

        spinner.removeClass('d-none');
        $.ajax({
            url: window.getAlumnoInfoUrl,
            type: 'GET',
            data: { id: selectedId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    expedienteMatricula = response.matricula;
                    alumnoFeedback.text('Alumno seleccionado: ' + response.nombre).removeClass('text-danger text-info').addClass('text-success');
                    generarArcCodigo(); // Generar código con la nueva matrícula
                } else {
                    alumnoFeedback.text(response.message || 'No se pudo encontrar la matrícula.').removeClass('text-success text-info').addClass('text-danger');
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                alumnoFeedback.text('Error al buscar datos del alumno.').removeClass('text-success text-info').addClass('text-danger');
                console.error("Error en getAlumnoInfoUrl:", textStatus, errorThrown, jqXHR.responseText);
            },
            complete: () => spinner.addClass('d-none')
        });
    }

    /**
     * Maneja la respuesta JSON del controlador tras el análisis del PDF.
     * Determina si el alumno existe y carga el formulario en el modal.
     */
    function handleApiResponse(response) {
        console.log("JSON RECIBIDO (Análisis PDF):", response);
        if (response.status !== 'ok') {
            spinner.addClass('d-none');
            alumnoFeedback.text('Error del servidor: ' + response.message).removeClass('text-info').addClass('text-danger');
            return;
        }

        const alumnoDataForForm = response.exists ? response.alumnoData : response.processedData;
        modalTitle.text(response.exists ? 'Revisar Alumno Existente' : 'Registrar Nuevo Alumno');

        const url = new URL(window.createAlumnoUrl);
        Object.keys(alumnoDataForForm).forEach(key => {
            if (alumnoDataForForm[key] !== null) {
                url.searchParams.append(`Alumno[${key}]`, alumnoDataForForm[key]);
            }
        });

        // Petición AJAX para obtener el HTML del formulario del alumno (_form.php)
        $.ajax({
            url: url.href,
            type: 'GET',
            dataType: 'html',
            success: function(formHtml) {
                modalBody.html(formHtml);
                if (response.exists) {
                    modalBody.find('input, select').prop('disabled', true); // Deshabilitar campos si el alumno existe
                    modalBody.find('button[type="submit"]').hide(); // Ocultar botón de guardar
                    modalBody.prepend('<div class="alert alert-warning">Este alumno ya está registrado. Cierre esta ventana para asociarlo al archivo.</div>');
                    expedienteMatricula = response.alumnoData.alu_matricula;
                    updateAlumnoDropdown(response.alumnoData.alu_id, response.alumnoData.alu_nombre + ' ' + response.alumnoData.alu_paterno);
                    generarArcCodigo();
                } else {
                     modalBody.find('button[type="submit"]').show(); // Asegurarse de que el botón de guardar sea visible para nuevos alumnos
                }
                alumnoModal.show(); // Mostrar el modal
                spinner.addClass('d-none');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                spinner.addClass('d-none');
                modalBody.html('<div class="alert alert-danger">Error: No se pudo cargar el formulario del alumno.</div>');
                alumnoModal.show();
                console.error("Error al cargar _form de Alumno:", textStatus, errorThrown, jqXHR.responseText);
            }
        });
    }
    
    /**
     * Maneja el envío del formulario que está DENTRO del modal para crear un nuevo alumno.
     */
    function handleModalFormSubmit(e) {
        e.preventDefault();
        spinner.removeClass('d-none');
        const form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alumnoModal.hide(); // Ocultar modal
                    alumnoFeedback.text('Nuevo alumno creado: ' + response.nombreCompleto).removeClass('text-info').addClass('text-success');
                    updateAlumnoDropdown(response.id, response.nombreCompleto); // Añadir y seleccionar el nuevo alumno en el dropdown
                    expedienteMatricula = response.matricula; // Actualizar la matrícula para el código
                    generarArcCodigo(); // Generar el código con los datos del nuevo alumno
                } else {
                    // Si la creación falla (ej. por validación), recargamos el formulario en el modal con los errores
                    modalBody.html(response.formHtml);
                }
            },
            error: (jqXHR) => {
                alert('Error crítico: No se pudo guardar el alumno.');
                console.error("Respuesta del servidor al crear alumno:", jqXHR.responseText);
            },
            complete: () => spinner.addClass('d-none')
        });
    }

    /**
     * Actualiza el dropdown de alumnos, añadiendo una nueva opción si no existe
     * y seleccionándola.
     */
    function updateAlumnoDropdown(id, nombre) {
        if (alumnoSelect.find("option[value='" + id + "']").length === 0) {
            const newOption = new Option(nombre, id, true, true);
            alumnoSelect.append(newOption);
        }
        alumnoSelect.val(id).trigger('change'); // Seleccionar el valor y disparar el evento 'change'
    }
});