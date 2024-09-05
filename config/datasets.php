<?php

return [
    'base_url' => 'https://data.boston.gov/datastore/dump',
    'datasets' => [
        [
            //311 Service Requests 2024
            'name' => '311-service-requests-2024',
            'resource_id' => 'dff4d804-5031-443a-8409-8344efd0e5c8',
            'format' => 'csv',
        ],
        // Add more datasets as needed
        [
            'name' => 'construction-off-hours',
            'resource_id' => 'c66524ea-36f5-43b1-aa9c-da36d7cb8744',
            'format' => 'csv',
        ],
        [
            'name' => 'building-permits',
            'resource_id' => '6ddcd912-32a0-43df-9908-63574f8c7e77',
            'format' => 'csv',
        ],
        [
            'name' => 'crime-incident-reports',
            'resource_id' => 'b973d8cb-eeb2-4e7e-99da-c92938efc9c0',
            'format' => 'csv',
        ],
        [
            'name' => 'trash-schedules-by-address',
            'resource_id' => 'fee8ee07-b8b5-4ee5-b540-5162590ba5c1',
            'format' => 'csv',
        ],
    ],
];
