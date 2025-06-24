$(document).ready(function() {
    console.log("JavaScript cargado correctamente");

    window.uploadFile = function() {
        console.log("Función uploadFile ejecutada");

        var fileInput = $('#file')[0];
        if (fileInput.files.length === 0) {
            alert('Por favor, selecciona un archivo.');
            console.log("No se seleccionó archivo");
            return;
        }

        var formData = new FormData();
        formData.append('file', fileInput.files[0]);

        console.log("Enviando archivo a la API...");
        $.ajax({
            url: 'http://localhost:5000/process-document',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log("Respuesta de la API:", response);
                if (response.error_message) {
                    alert('Error: ' + response.error_message);
                } else {
                    var matricula = response.alu_matricula || 'No encontrada';
                    $('#matricula-value').text(matricula);
                    $('#matricula-extracted').show();

                    var fondo = $('#arc_fondo_id option:selected').text() || 'TecNM';
                    var claveProgramatica = $('#arc_clave_programatica_id option:selected').text() || 'M00';
                    var areaGeneradora = $('#arc_area_generadora_id option:selected').text() || 'RH';
                    var seccionSerie = $('#arc_seccion_serie_id option:selected').text() || '11C.20';
                    var year = new Date().getFullYear(); // 2025

                    var filename = `${fondo}/${claveProgramatica}/${areaGeneradora}/${seccionSerie}/01-(II)/${year}_${matricula}.pdf`;
                    $('#filename-value').text(filename);
                    $('#filename-generated').show();
                    $('#arc_nombre_archivo').val(filename);
                }
            },
            error: function(xhr, status, error) {
                var responseText = xhr.responseText ? JSON.parse(xhr.responseText).error_message : error;
                console.error("Error al procesar el archivo:", responseText);
                alert('Error al procesar el archivo: ' + responseText);
            }
        });
    };

    $('#file').on('change', uploadFile);
    $('#upload-button').on('click', uploadFile);
});