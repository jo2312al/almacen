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
    const alumnoModalElement = document.getElementById('alumno-modal');
    const alumnoModal = new bootstrap.Modal(alumnoModalElement);
    const modalBody = $('#alumno-modal .modal-body');
    const modalTitle = $('#alumno-modal .modal-title');

    let expedienteMatricula = '';

    // --- MANEJADORES DE EVENTOS ---
    fileInput.on('change', handleFileSelect);
    alumnoSelect.on('change', handleAlumnoSelect);
    form.on('change', '.code-component', generarArcCodigo);
    modalBody.on('submit', '#modal-alumno-form', handleModalFormSubmit);


    // --- FUNCIONES LÓGICAS ---

    /**
     * Maneja la selección de un archivo PDF.
     */
    function handleFileSelect(e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('pdfFile', file);
        
        spinner.removeClass('d-none');
        alumnoFeedback.text('Procesando constancia...').removeClass('text-success text-danger text-warning').addClass('text-info');

        $.ajax({
            url: window.processPdfUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: handleApiResponse,
            error: (jqXHR, textStatus, errorThrown) => {
                spinner.addClass('d-none'); // Asegurarse de ocultar el spinner en caso de error
                alumnoFeedback.text('Error de comunicación con el servidor.').removeClass('text-info').addClass('text-danger');
                console.error("Error en processPdfUrl:", textStatus, errorThrown, jqXHR.responseText);
            }
            // El complete se quita de aquí porque ahora se maneja dentro de la carga del modal
        });
    }

    /**
     * Maneja la selección manual de un alumno del dropdown.
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
                    generarArcCodigo();
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
     * ===================================================================
     * FUNCIÓN 'handleApiResponse' CON LA CORRECCIÓN DEFINITIVA
     * - Se usa $.ajax en lugar de .load() para cargar el formulario,
     * lo que permite un manejo de errores robusto.
     * ===================================================================
     */
    function handleApiResponse(response) {
        if (response.status !== 'ok') {
            spinner.addClass('d-none');
            alumnoFeedback.text('Error del servidor: ' + response.message).removeClass('text-info').addClass('text-danger');
            return;
        }

        let alumnoDataForForm;
        if (response.exists) {
            modalTitle.text('Revisar Alumno Existente');
            alumnoDataForForm = response.alumnoData;
        } else {
            modalTitle.text('Registrar Nuevo Alumno');
            alumnoDataForForm = {
                alu_matricula: (response.apiData.alu_matricula?.value || '').replace(/,/g, '').trim(),
                alu_nombre: (response.apiData.alu_nombre?.value || '').replace(/,/g, '').trim(),
                alu_paterno: (response.apiData.alu_paterno?.value || '').replace(/,/g, '').trim(),
                alu_materno: (response.apiData.alu_materno?.value || '').replace(/,/g, '').trim(),
            };
        }

        // --- INICIO DE LA CORRECCIÓN ---
        // Construimos una URL con parámetros GET para cargar el formulario
        const url = new URL(window.createAlumnoUrl);
        Object.keys(alumnoDataForForm).forEach(key => url.searchParams.append(`Alumno[${key}]`, alumnoDataForForm[key]));

        // Hacemos la llamada AJAX para obtener el HTML del formulario
        $.ajax({
            url: url.href,
            type: 'GET',
            dataType: 'html',
            success: function(formHtml) {
                // Inyectamos el HTML del formulario en el cuerpo del modal
                modalBody.html(formHtml);

                if (response.exists) {
                    modalBody.find('input, select').prop('disabled', true);
                    modalBody.find('button[type="submit"]').hide();
                    modalBody.prepend('<div class="alert alert-warning">Este alumno ya está registrado. Los datos son de solo lectura. Cierre esta ventana para asociarlo al archivo.</div>');
                    expedienteMatricula = response.alumnoData.alu_matricula;
                    updateAlumnoDropdown(response.alumnoData.alu_id, response.alumnoData.alu_nombre + ' ' + response.alumnoData.alu_paterno);
                    generarArcCodigo();
                } else {
                     modalBody.find('button[type="submit"]').show();
                }
                // Mostramos el modal y ocultamos el spinner
                alumnoModal.show();
                spinner.addClass('d-none');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Si falla la carga del formulario, lo mostramos en el modal
                spinner.addClass('d-none');
                modalBody.html('<div class="alert alert-danger">Error: No se pudo cargar el formulario del alumno. Revise la consola (F12) para más detalles.</div>');
                alumnoModal.show();
                console.error("Error al cargar el formulario del alumno:", textStatus, errorThrown, jqXHR.responseText);
            }
        });
        // --- FIN DE LA CORRECCIÓN ---
    }
    
    /**
     * Maneja el envío del formulario del modal (sólo para alumnos nuevos).
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
                    alumnoModal.hide();
                    alumnoFeedback.text('Nuevo alumno creado: ' + response.nombreCompleto).removeClass('text-info').addClass('text-success');
                    updateAlumnoDropdown(response.id, response.nombreCompleto);
                    expedienteMatricula = response.matricula;
                    generarArcCodigo();
                } else {
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
     * Genera el código clasificador en el campo de vista previa.
     */
    function generarArcCodigo() {
        if (!expedienteMatricula) {
            codigoPreview.val('');
            codigoHidden.val('');
            nombreDocumentoHidden.val('');
            return;
        }
        const fondo = form.find('#archivo-arc_fondo_id option:selected').text().substring(0, 5).toUpperCase().replace(/[^A-Z0-9]/g, '') || 'XXX';
        const clave = form.find('#archivo-arc_clave_programatica_id option:selected').text().substring(0, 3).toUpperCase().replace(/[^A-Z0-9]/g, '') || 'XXX';
        const area = form.find('#archivo-arc_area_generadora_id option:selected').text().substring(0, 2).toUpperCase().replace(/[^A-Z0-9]/g, '') || 'XX';
        const seccion = form.find('#archivo-arc_seccion_serie_id option:selected').text().split('.')[0].replace(/[^A-Z0-9]/g, '') || 'XXX';
        const anio = new Date().getFullYear();
        const codigoGenerado = `${fondo}/${clave}/${area}/${seccion}/${expedienteMatricula}/${anio}`;
        codigoPreview.val(codigoGenerado);
        codigoHidden.val(codigoGenerado);
        nombreDocumentoHidden.val(codigoGenerado);
    }

    /**
     * Actualiza el dropdown de alumnos.
     */
    function updateAlumnoDropdown(id, nombre) {
        if (alumnoSelect.find("option[value='" + id + "']").length === 0) {
            const newOption = new Option(nombre, id, true, true);
            alumnoSelect.append(newOption);
        }
        alumnoSelect.val(id).trigger('change');
    }
});
