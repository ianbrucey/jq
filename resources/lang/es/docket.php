<?php

return [
    'title' => 'Expediente',
    'dashboard' => [
        'title' => 'Entradas del Expediente',
    ],
    'search' => [
        'placeholder' => 'Buscar entradas del expediente...',
    ],
    'filter' => [
        'all_types' => 'Todos los Tipos de Entrada',
        'all_statuses' => 'Todos los Estados',
    ],
    'empty_state' => 'No se encontraron entradas en el expediente',
    'actions' => [
        'create' => 'Agregar Entrada',
        'edit' => 'Editar Entrada',
        'delete' => 'Eliminar Entrada',
        'back_to_case' => 'Volver al Caso',
    ],
    'entry' => [
        'create_new' => 'Crear Nueva Entrada de Expediente',
        'create' => 'Crear Entrada',
        'fields' => [
            'date' => 'Fecha',
            'type' => 'Tipo',
            'title' => 'Título',
            'description' => 'Descripción',
            'filing_party' => 'Parte Presentante',
            'judge' => 'Juez',
            'docket_number' => 'Número de Expediente',
            'status' => 'Estado',
        ],
        'types' => [
            'filing' => 'Presentación',
            'order' => 'Orden',
            'hearing' => 'Audiencia',
            'notice' => 'Notificación',
            'motion' => 'Moción',
            'judgment' => 'Sentencia',
            'other' => 'Otro'
        ],
        'status' => [
            'pending' => 'Pendiente',
            'granted' => 'Concedido',
            'denied' => 'Denegado',
            'heard' => 'Escuchado',
            'continued' => 'Continuado',
            'withdrawn' => 'Retirado'
        ],
        'search_filing_party' => 'Buscar parte presentante...',
        'no_parties_found' => 'No se encontraron partes coincidentes',
        'search_judge' => 'Buscar juez...',
        'no_judges_found' => 'No se encontraron jueces coincidentes',
        'upload_documents' => 'Subir Documentos',
        'documents' => [
            'title' => 'Documentos Relacionados',
            'add' => 'Agregar Documento',
            'remove' => 'Eliminar Documento',
            'none_selected' => 'No hay documentos seleccionados',
            'search' => 'Buscar documentos...',
            'no_results' => 'No se encontraron documentos'
        ],
        'validation' => [
            'date_required' => 'La fecha de entrada es requerida.',
            'type_required' => 'El tipo de entrada es requerido.',
            'title_required' => 'El título de la entrada es requerido.',
            'description_required' => 'La descripción es requerida.',
            'filing_party_required' => 'La parte presentante es requerida.',
            'judge_required' => 'El juez es requerido.'
        ],
        'actions' => [
            'save' => 'Guardar Entrada',
            'cancel' => 'Cancelar',
            'clear_search' => 'Limpiar Búsqueda'
        ]
    ],
    'messages' => [
        'created' => 'Entrada de expediente creada exitosamente.',
        'updated' => 'Entrada de expediente actualizada exitosamente.',
        'deleted' => 'Entrada de expediente eliminada exitosamente.',
    ],
    'filters' => [
        'date_range' => 'Rango de Fechas',
        'type' => 'Tipo de Entrada',
        'status' => 'Estado',
        'search' => 'Buscar entradas de expediente...',
        'apply' => 'Aplicar Filtros',
        'clear' => 'Limpiar Filtros',
    ],
];
