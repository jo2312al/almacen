<?php

return [
    'adminEmail' => getenv('ADMIN_EMAIL') ?: 'admin@example.com',
    'senderEmail' => getenv('SENDER_EMAIL') ?: 'no-reply@example.com',
    'senderName' => getenv('SENDER_NAME') ?: 'Servicio 2',
    'ocrApiUrl' => getenv('OCR_API_URL') ?: 'http://ec2-18-223-120-47.us-east-2.compute.amazonaws.com/extract',
    'ocrApiKey' => getenv('OCR_API_KEY') ?: '',
    'ocrTipoDocumento' => getenv('OCR_TIPO_DOCUMENTO') ?: 'constancia_servicio',
    'pdfApiUrl' => getenv('PDF_API_URL') ?: getenv('OCR_API_URL') ?: 'http://ec2-18-223-120-47.us-east-2.compute.amazonaws.com/extract',
];
