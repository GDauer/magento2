<?php
return array (
    'cache' =>
        array (
            'frontend' =>
                array (
                    'default' =>
                        array (
                            'backend' => 'Cm_Cache_Backend_Redis',
                            'backend_options' =>
                                array (
                                    'server' => 'localhost',
                                    'port' => '6370',
                                    'load_from_slave' =>
                                        array (
                                            'server' => 'localhost',
                                            'port' => '26370',
                                        ),
                                    'read_timeout' => 1,
                                    'retry_reads_on_master' => 1,
                                    'database' => 1,
                                ),
                            'frontend_options' =>
                                array (
                                    'write_control' => false,
                                ),
                        ),
                    'page_cache' =>
                        array (
                            'backend' => 'Cm_Cache_Backend_Redis',
                            'backend_options' =>
                                array (
                                    'server' => 'localhost',
                                    'port' => '6370',
                                    'load_from_slave' =>
                                        array (
                                            'server' => 'localhost',
                                            'port' => '26370',
                                        ),
                                    'read_timeout' => 1,
                                    'retry_reads_on_master' => 1,
                                    'database' => 2,
                                ),
                            'frontend_options' =>
                                array (
                                    'write_control' => false,
                                ),
                        ),
                ),
        ),
    'MAGE_MODE' => 'production',
    'cache_types' =>
        array (
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
            'vertex' => 1,
        ),
    'cron' =>
        array (
        ),
    'backend' =>
        array (
            'frontName' => 'painel_ita',
        ),
    'db' =>
        array (
            'connection' =>
                array (
                    'default' =>
                        array (
                            'host' => '127.0.0.1',
                            'username' => 'ywbmhcrxu4za2_stg',
                            'dbname' => 'ywbmhcrxu4za2_stg',
                            'password' => 'JCfPA64xQYqwBdQ',
                        ),
                    'indexer' =>
                        array (
                            'host' => '127.0.0.1',
                            'username' => 'ywbmhcrxu4za2_stg',
                            'dbname' => 'ywbmhcrxu4za2_stg',
                            'password' => 'JCfPA64xQYqwBdQ',
                        ),
                    'readonly' =>
                        array (
                            'host' => '127.0.0.1',
                            'username' => 'ywbmhcrxu4za2_stg',
                            'dbname' => 'ywbmhcrxu4za2_stg',
                            'password' => 'JCfPA64xQYqwBdQ',
                        ),
                ),
            'slave_connection' =>
                array (
                    'default' =>
                        array (
                            'host' => '127.0.0.1:3304',
                            'username' => 'ywbmhcrxu4za2_stg',
                            'dbname' => 'ywbmhcrxu4za2_stg',
                            'password' => 'JCfPA64xQYqwBdQ',
                            'model' => 'mysql4',
                            'engine' => 'innodb',
                            'initStatements' => 'SET NAMES utf8;',
                            'active' => '1',
                        ),
                ),
        ),
    'queue' =>
        array (
            'consumers_wait_for_messages' => 0,
        ),
    'crypt' =>
        array (
            'key' => 'bf14eb8f51d76bb15da515e5c61edd9b',
        ),
    'resource' =>
        array (
            'default_setup' =>
                array (
                    'connection' => 'default',
                ),
            'readonly' =>
                array (
                    'connection' => 'readonly',
                ),
        ),
    'x-frame-options' => 'SAMEORIGIN',
    'session' =>
        array (
            'save' => 'redis',
            'redis' =>
                array (
                    'host' => 'localhost',
                    'port' => '6370',
                    'database' => 0,
                    'disable_locking' => 1,
                ),
        ),
    'lock' =>
        array (
            'provider' => 'file',
            'config' =>
                array (
                    'path' => '/run/ywbmhcrxu4za2_stg/locks',
                ),
        ),
    'downloadable_domains' =>
        array (
            0 => 'mcstaging.minhaitatiaia.com.br',
        ),
    'install' =>
        array (
            'date' => 'Mon, 14 Sep 2020 19:40:02 +0000',
        ),
    'static_content_on_demand_in_production' => 1,
    'force_html_minification' => 1,
    'cron_consumers_runner' =>
        array (
            'cron_run' => true,
            'max_messages' => 10000,
            'consumers' =>
                array (
                ),
        ),
    'system' =>
        array (
            'default' =>
                array (
                    'catalog' =>
                        array (
                            'search' =>
                                array (
                                    'engine' => 'elasticsuite',
                                ),
                        ),
                    'smile_elasticsuite_core_base_settings' =>
                        array (
                            'es_client' =>
                                array (
                                    'servers' => '127.0.0.1:9200',
                                    'indices_alias' => 'magento2_stg',
                                ),
                            'indices_settings' =>
                                array (
                                    'number_of_shards' => 3,
                                    'number_of_replicas' => 2,
                                ),
                        ),
                ),
        ),
    'directories' =>
        array (
            'document_root_is_pub' => true,
        ),
);
