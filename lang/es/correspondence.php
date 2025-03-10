<?php

return [
    'correspondence' => [
        'title' => 'Correspondencia',
        'new_message' => 'Nuevo Mensaje',
        'subject' => 'Asunto',
        'recipients' => 'Destinatarios',
        'message' => 'Mensaje',
        'attachments' => 'Archivos Adjuntos',
        'send' => 'Enviar',
        'save_draft' => 'Guardar Borrador',
        'cancel' => 'Cancelar',
        'confirm_delete' => '¿Está seguro de que desea eliminar este mensaje?',
        'sent_messages' => 'Mensajes Enviados',
        'drafts' => 'Borradores',
        'received_messages' => 'Mensajes Recibidos',
        'search' => 'Buscar Correspondencia',
        'no_messages' => 'No se encontraron mensajes',
        'reply' => 'Responder',
        'forward' => 'Reenviar',
        'mark_as_read' => 'Marcar como Leído',
        'mark_as_unread' => 'Marcar como No Leído',
        'basic_details' => 'Detalles Básicos',
        'type' => 'Tipo',
        'types' => [
            'letter' => 'Carta',
            'email' => 'Correo Electrónico',
            'phone' => 'Llamada Telefónica',
            'other' => 'Otro',
        ],
        'subject' => 'Asunto',
        'content' => 'Contenido',
        'content_label' => 'Contenido de la Comunicación',
        'date_time_sent' => 'Fecha y Hora de Envío',
        'date_time' => 'Fecha y Hora',
        'participants' => 'Participantes',
        'senders_recipients' => 'Remitentes y Destinatarios',
        'search_parties' => 'Buscar participantes...',
        'add_as_sender' => 'Agregar como Remitente',
        'add_as_recipient' => 'Agregar como Destinatario',
        'senders' => 'Remitentes',
        'recipients' => 'Destinatarios',
        'documents' => 'Documentos',
        'search_documents' => 'Buscar documentos...',
        'add_document' => 'Agregar Documento',
        'no_documents_selected' => 'Aún no se han seleccionado documentos',
        'no_documents_found' => 'No se encontraron documentos que coincidan con ":search"',
        'or_upload_new_documents' => 'O subir nuevos documentos',
        'upload_new' => 'Subir nuevo',
        'cancel' => 'Cancelar',
        'save_communication' => 'Guardar comunicación',
        'close_uploader' => 'Cerrar cargador',
        'upload_new_document' => 'Subir nuevo documento',
        // Thread View Specific
        'add_communication' => 'Agregar Comunicación',
        'back_to_case' => 'Volver al Caso',
        'to' => 'Para:',
        'attachments' => 'Archivos Adjuntos:',
        'file_size' => 'Tamaño del archivo',
        'search_communications' => 'Buscar comunicaciones...',
        'no_communications' => 'Sin comunicaciones aún',
        'latest' => 'Último',
        'human_file_size' => ':size',
        'from' => 'De:',
        'document_preview' => 'Vista Previa del Documento',
        'no_communications_found' => 'No se encontraron comunicaciones que coincidan con ":search"',
        'no_communications_yet' => 'No hay comunicaciones en este hilo todavía.',
    ],
    'threads' => 'Conversaciones',
    'new_thread' => 'Nueva Conversación',
    'search_threads_placeholder' => 'Buscar conversaciones...',
    'no_communications' => 'Sin comunicaciones aún',
    'latest' => 'Último',
    'no_threads_found' => 'No se encontraron conversaciones',
    'no_threads_match' => 'Ninguna conversación coincide con tu búsqueda ":search"',
    'no_threads_yet' => 'No hay conversaciones aún',
    'start_new_thread' => "Inicia una nueva conversación haciendo clic en 'Nueva Conversación'",
    'create_new_thread' => 'Crear Nueva Conversación',
    'title' => 'Título',
    'title_placeholder' => 'ej., Seguimiento de Reclamo de Seguro',
    'cancel' => 'Cancelar',
    'create_thread' => 'Crear Conversación',

    'status' => [
        'open' => 'Abierto',
        'closed' => 'Cerrado',
        'archived' => 'Archivado'
    ],
    'tracker' => 'Seguimiento de Correspondencia',
    'back_to_case' => 'Volver al Caso',
    // Top level translations for basic structure
    'thread' => 'Hilo',
    'back_to_threads' => 'Volver a los Hilos',
    'thread_for' => 'Hilo - :title', // For showing thread with case file title
    // Time-related translations
    'time' => [
        'just_now' => 'justo ahora',
        'seconds_ago' => 'hace :count segundo|hace :count segundos',
        'minutes_ago' => 'hace :count minuto|hace :count minutos',
        'hours_ago' => 'hace :count hora|hace :count horas',
        'days_ago' => 'hace :count día|hace :count días',
        'weeks_ago' => 'hace :count semana|hace :count semanas',
        'months_ago' => 'hace :count mes|hace :count meses',
        'years_ago' => 'hace :count año|hace :count años',
    ],

    // Buttons
    'close' => 'Cerrar',
    'save' => 'Guardar',
    'cancel' => 'Cancelar',

    // Validation messages
    'validation' => [
        'type_required' => 'Por favor seleccione un tipo de comunicación',
        'subject_required' => 'El asunto es requerido',
        'subject_max' => 'El asunto no puede exceder los :max caracteres',
        'content_required' => 'El contenido de la comunicación es requerido',
        'date_time_required' => 'La fecha y hora son requeridas',
        'date_time_format' => 'Formato de fecha/hora inválido',
        'date_time_future' => 'La fecha y hora no pueden estar en el futuro',
        'senders_required' => 'Se requiere al menos un remitente',
        'recipients_required' => 'Se requiere al menos un destinatario',
    ],
    'delete_communication' => [
        'title' => 'Eliminar comunicación',
        'confirmation' => '¿Está seguro de que desea eliminar esta comunicación? Esta acción no se puede deshacer.',
        'cancel' => 'Cancelar',
        'confirm' => 'Eliminar',
    ],
    'document_preview' => [
        'title' => 'Vista previa del documento',
        'not_available' => 'Vista previa no disponible para este tipo de archivo',
        'close' => 'Cerrar',
    ],
];
