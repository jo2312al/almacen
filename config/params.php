<?php

return [
    'adminEmail' => getenv('ADMIN_EMAIL') ?: 'admin@example.com',
    'senderEmail' => getenv('SENDER_EMAIL') ?: 'no-reply@example.com',
    'senderName' => getenv('SENDER_NAME') ?: 'Servicio 2',
];
