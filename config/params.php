<?php

return [
    'adminEmail' => getenv('ADMIN_EMAIL') ?: 'admin@example.com',
    'senderEmail' => getenv('SENDER_EMAIL') ?: 'no-reply@example.com',
    'senderName' => getenv('SENDER_NAME') ?: 'Servicio 2',
    'pdfApiUrl' => getenv('PDF_API_URL') ?: 'http://127.0.0.1:5000/extract',
];
