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

    let expedienteMatricula = '';

    // --- MANEJADORES DE EVENTOS ---
    fileInput.on('change', handleFileSelect);
    alumnoSelect.on('change', handleAlumnoSelect);
    form.on('change', '.code-component', generarArcCodigo);
    modalBody.on('submit', '#modal-alumno-form', handleModalFormSubmit);

    /**
     * ===================================================================
     * FUNCIÓN 'generarArcCodigo' SIMPLIFICADA
     * - Ya no hace una llamada AJAX.
     * - Extrae el código directamente del texto del dropdown.
     * ===================================================================
     */
    function generarArcCodigo() {
        // Si no tenemos la matrícula, no podemos generar el código.
        if (!expedienteMatricula) {
            codigoPreview.val('');
            codigoHidden.val('');
            nombreDocumentoHidden.val('');
            return;
        }

        // Función auxiliar para obtener el código del texto "CODIGO - DESCRIPCION"
        const getCodeFromText = (text) => {
            // Si el texto incluye la palabra "Seleccionar", es el prompt. Usamos '00'.
            if (!text || text.includes('Seleccionar')) {
                return '00';
            }
            // Divide el texto por el guion y toma la primera parte (el código)
            return text.split(' - ')[0].trim();
        };

        const fondo = getCodeFromText(form.find('#archivo-arc_fondo_id option:selected').text());
        const clave = getCodeFromText(form.find('#archivo-arc_clave_programatica_id option:selected').text());
        const area = getCodeFromText(form.find('#archivo-arc_area_generadora_id option:selected').text());
        const seccion = getCodeFromText(form.find('#archivo-arc_seccion_serie_id option:selected').text());
        
        const anio = new Date().getFullYear();

        // Construimos el código
        const codigoGenerado = `${fondo}/${clave}/${area}/${seccion}/${expedienteMatricula}/${anio}`;
        
        // Actualizamos la vista previa y los campos ocultos
        codigoPreview.val(codigoGenerado);
        codigoHidden.val(codigoGenerado);
        nombreDocumentoHidden.val(codigoGenerado);
    }

    // --- OTRAS FUNCIONES ---

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
                spinner.addClass('d-none');
                alumnoFeedback.text('Error de comunicación con el servidor.').removeClass('text-info').addClass('text-danger');
                console.error("Error en processPdfUrl:", textStatus, errorThrown, jqXHR.responseText);
            }
        });
    }

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

    function handleApiResponse(response) {
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
        $.ajax({
            url: url.href,
            type: 'GET',
            dataType: 'html',
            success: function(formHtml) {
                modalBody.html(formHtml);
                if (response.exists) {
                    modalBody.find('input, select').prop('disabled', true);
                    modalBody.find('button[type="submit"]').hide();
                    modalBody.prepend('<div class="alert alert-warning">Este alumno ya está registrado. Cierre esta ventana para asociarlo al archivo.</div>');
                    expedienteMatricula = response.alumnoData.alu_matricula;
                    updateAlumnoDropdown(response.alumnoData.alu_id, response.alumnoData.alu_nombre + ' ' + response.alumnoData.alu_paterno);
                    generarArcCodigo();
                } else {
                     modalBody.find('button[type="submit"]').show();
                }
                alumnoModal.show();
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

    function updateAlumnoDropdown(id, nombre) {
        if (alumnoSelect.find("option[value='" + id + "']").length === 0) {
            const newOption = new Option(nombre, id, true, true);
            alumnoSelect.append(newOption);
        }
        alumnoSelect.val(id).trigger('change');
    }
});