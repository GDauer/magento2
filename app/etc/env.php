<?php
return [
    'MAGE_MODE' => 'production',
    'cache_types' => [
        'compiled_config' => 1,
        'config' => 1,
        'layout' => 1,
        'block_html' => 1,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'eav' => 1,
        'customer_notification' => 1,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'full_page' => 1,
        'target_rule' => 1,
        'config_webservice' => 1,
        'translate' => 1,
        'vertex' => 1
    ],
    'cron' => [],
    'backend' => [
        'frontName' => 'admin'
    ],
    'db' => [
        'connection' => [
            'default' => [
                'username' => 'user',
                'host' => 'database.internal',
                'dbname' => 'main',
                'password' => ''
            ],
            'indexer' => [
                'username' => 'user',
                'host' => 'database.internal',
                'dbname' => 'main',
                'password' => ''
            ]
        ]
    ],
    'crypt' => [
        'key' => 'bf14eb8f51d76bb15da515e5c61edd9b'
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'session' => [
        'save' => 'redis',
        'redis' => [
            'host' => 'redis.internal',
            'port' => 6379,
            'database' => 0,
            'disable_locking' => 1
        ]
    ],
    'lock' => [
        'provider' => 'db',
        'config' => [
            'prefix' => NULL
        ]
    ],
    'downloadable_domains' => [
        'master-7rqtwti-ywbmhcrxu4za2.us-5.magentosite.cloud'
    ],
    'install' => [
        'date' => 'Tue, 25 Aug 2020 14:47:53 +0000'
    ],
    'static_content_on_demand_in_production' => 1,
    'force_html_minification' => 1,
    'cron_consumers_runner' => [
        'cron_run' => false,
        'max_messages' => 10000,
        'consumers' => []
    ],
    'directories' => [
        'document_root_is_pub' => true
    ],
    'cache' => [
        'frontend' => [
            'default' => [
                'backend' => 'Cm_Cache_Backend_Redis',
                'backend_options' => [
                    'server' => 'redis.internal',
                    'port' => 6379,
                    'database' => 1
                ]
            ],
            'page_cache' => [
                'backend' => 'Cm_Cache_Backend_Redis',
                'backend_options' => [
                    'server' => 'redis.internal',
                    'port' => 6379,
                    'database' => 2
                ]
            ]
        ]
    ],
    'system' => [
        'default' => [
            'catalog' => [
                'search' => [
                    'engine' => 'elasticsearch',
                    'elasticsearch5_server_hostname' => 'elasticsearch.internal',
                    'elasticsearch5_server_port' => 9200
                ]
            ],
            'smile_elasticsuite_core_base_settings' => [
                'es_client' => [
                    'servers' => 'elasticsearch.internal:9200'
                ],
                'indices_settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0
                ]
            ]
        ]
    ],
    'static_content_on_demand_in_production' => 1,
    'directories' => [
        'document_root_is_pub' => true,
    ],
    'session' => [
        'save' => 'files'
    ],
];