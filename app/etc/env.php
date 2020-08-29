<?php
return [
    'backend' => [
        'frontName' => 'painel_ita'
    ],
    'db' => [
        'connection' => [
            'indexer' => [
                'host' => 'magento-homolog.cue20vbgi0dn.sa-east-1.rds.amazonaws.com',
                'dbname' => 'magento',
                'username' => 'magento',
                'password' => 'bEkUQnAABeZrepzX',
                'active' => '1',
                'persistent' => NULL
            ],
            'default' => [
                'host' => 'magento-homolog.cue20vbgi0dn.sa-east-1.rds.amazonaws.com',
                'dbname' => 'magento',
                'username' => 'magento',
                'password' => 'bEkUQnAABeZrepzX',
                'active' => '1',
            ],
            'readonly' => [
                'host' => 'magento-homolog.cue20vbgi0dn.sa-east-1.rds.amazonaws.com',
                'dbname' => 'magento',
                'username' => 'magento',
                'password' => 'bEkUQnAABeZrepzX',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1'
            ]
        ],
        'table_prefix' => ''
    ],
    'crypt' => [
        'key' => 'a231477d90da126ac418eb0ebeadb524'
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => 'production',
    'session' => [
        'save' => 'memcached',
        'save_path' => 'jeicmjjp3prhkjd5mv5sc5stji.cache.service._.us-2.platformsh.site'
    ],
    'cache_types' => [
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
        'target_rule' => 1,
        'full_page' => 1,
        'config_webservice' => 1,
        'translate' => 1,
        'compiled_config' => 1
    ],
    'install' => [
        'date' => 'Mon, 19 Nov 2018 15:34:13 +0000'
    ],
    'system' => [
        'default' => [
            'smile_elasticsuite_core_base_settings' => [
                'es_client' => [
                    'servers' => '172.30.20.198:9200',
                    'enable_https_mode' => '0',
                    'enable_http_auth' => '0',
                    'http_auth_user' => '',
                    'http_auth_pwd' => ''
                ]
            ]
        ]
    ],
    'cache' => [
        'frontend' => [
            'default' => [
                'backend' => 'Cm_Cache_Backend_Redis',
                'backend_options' => [
                    'server' => 'jeicmjjp3prhkjd5mv5sc5stji.cache.service._.us-2.platformsh.site',
                    'database' => '0',
                    'port' => '6379'
                ]
            ],
            'page_cache' => [
                'backend' => 'Cm_Cache_Backend_Redis',
                'backend_options' => [
                    'server' => 'jeicmjjp3prhkjd5mv5sc5stji.cache.service._.us-2.platformsh.site',
                    'port' => '6379',
                    'database' => '1',
                    'compress_data' => '0'
                ]
            ]
        ]
    ],
    'db_logger' => [
        'output' => 'disabled',
        'log_everything' => 1,
        'query_time_threshold' => '0.001',
        'include_stacktrace' => 1
    ]
];