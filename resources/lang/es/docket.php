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
