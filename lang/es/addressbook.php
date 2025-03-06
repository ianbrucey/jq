<?php

return [
    // Header
    'title' => 'Directorio',

    // Buttons
    'hide_form' => 'Ocultar Formulario',
    'new_contact' => 'Nuevo Contacto',

    // Tabs
    'manual_entry' => 'Entrada Manual',
    'voice_input' => 'Entrada por Voz',

    // Form Labels and Placeholders
    'name' => 'Nombre',
    'relationship' => 'Relación',
    'address_line1' => 'Dirección Línea 1',
    'address_line2' => 'Dirección Línea 2',
    'city' => 'Ciudad',
    'state' => 'Estado',
    'zip_code' => 'Código Postal',
    'email' => 'Correo Electrónico',
    'phone' => 'Teléfono',
    'search_placeholder' => 'Buscar contactos por nombre o dirección...',

    // Relationship Options
    'select_relationship' => 'Seleccionar Relación',
    'relationships' => [
        'attorney' => 'Abogado',
        'opposing_council' => 'Abogado Contrario',
        'next_friend' => 'Amigo Próximo',
        'court' => 'Tribunal',
        'opponent' => 'Oponente',
        'neutral' => 'Neutral',
        'self' => 'Uno Mismo',
    ],

    // Table Headers
    'name_relationship' => 'Nombre y Relación',
    'address' => 'Dirección',
    'contact_information' => 'Información de Contacto',
    'added' => 'Agregado',
    'actions' => 'Acciones',

    // Form States
    'edit_contact' => 'Editar Contacto',
    'new_contact' => 'Nuevo Contacto',
    'save_contact' => 'Guardar Contacto',
    'cancel' => 'Cancelar',

    // Empty States
    'no_contacts' => 'No se encontraron contactos',
    'add_contact_prompt' => 'Haga clic en \'Nuevo Contacto\' para agregar alguien a su directorio',

    // Delete Modal
    'confirm_deletion' => 'Confirmar Eliminación',
    'delete_confirmation_message' => '¿Está seguro de que desea eliminar este contacto? Esta acción no se puede deshacer.',
    'delete_contact' => 'Eliminar Contacto',

    // Voice Input Section
    'voice_input_title' => 'Entrada por Voz',
    'speaking_instructions_title' => 'Instrucciones de Voz',
    'speaking_instructions' => 'Hable naturalmente y enumere tantos nombres y direcciones como desee. No se preocupe por los errores, le ayudaremos a ordenar todo después.',
    'process_contacts' => 'Procesar Contactos',
    'processing' => 'Procesando...',

    // Notifications
    'contacts_processed' => ':count contactos procesados exitosamente',
    'voice_error' => 'No hay texto para procesar',

    // Validation Messages
    'validation' => [
        'name_required' => 'El nombre es obligatorio',
        'address_required' => 'La dirección es obligatoria',
        'city_required' => 'La ciudad es obligatoria',
        'state_required' => 'El estado es obligatorio',
        'zip_required' => 'El código postal es obligatorio',
        'email_invalid' => 'Por favor ingrese un correo electrónico válido',
    ],

    // Additional translations
    'select_state' => 'Seleccionar Estado',
    'edit' => 'Editar',
    'delete' => 'Eliminar',
    'created_at' => 'Creado',
    'contact_details' => 'Detalles de Contacto',
    'voice_processing' => 'Procesando entrada de voz',
    'voice_processing_complete' => 'Procesamiento completado',
    'voice_processing_error' => 'Error al procesar la entrada de voz',
    'search_results' => 'Resultados de búsqueda',
    'no_results' => 'No se encontraron resultados',
    'loading' => 'Cargando...',
    'edit_mode' => 'Modo de edición',
    'create_mode' => 'Modo de creación',
];
