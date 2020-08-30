<?php
return array (
    'backend' => array (
        'frontName' => 'painel_ita'
    ),
//    'cache' =>
//        array (
//            'frontend' =>
//                array (
//                    'default' =>
//                        array (
//                            'backend' => 'Cm_Cache_Backend_Redis',
//                            'backend_options' =>
//                                array (
//                                    'server' => 'redis.internal',
//                                    'port' => 6379,
//                                    'database' => 1,
//                                ),
//                        ),
//                    'page_cache' =>
//                        array (
//                            'backend' => 'Cm_Cache_Backend_Redis',
//                            'backend_options' =>
//                                array (
//                                    'server' => 'redis.internal',
//                                    'port' => 6379,
//                                    'database' => 2,
//                                ),
//                        ),
//                ),
//        ),
    'MAGE_MODE' => 'production',
    'cache_types' =>
        array (
            'compiled_config' => '1',
            'config' => '1',
            'layout' => 1,
            'block_html' => 1,
            'collections' => '1',
            'reflection' => '1',
            'db_ddl' => '1',
            'eav' => '1',
            'customer_notification' => '1',
            'config_integration' => '1',
            'config_integration_api' => '1',
            'full_page' => 1,
            'target_rule' => '1',
            'config_webservice' => '1',
            'translate' => '1',
            'google_product' => '1',
            'vertex' => '1',
        ),
    'cron' =>
        array (
        ),
    'backend' =>
        array (
            'frontName' => 'admin',
        ),
    'db' => array(
        'connection' => array(
            'indexer' => array(
                'host' => 'magento-homolog.cue20vbgi0dn.sa-east-1.rds.amazonaws.com',
                'dbname' => 'magento',
                'username' => 'magento',
                'password' => 'bEkUQnAABeZrepzX',
                'active' => '1',
                'persistent' => NULL
            ),
            'default' => array(
                'host' => 'magento-homolog.cue20vbgi0dn.sa-east-1.rds.amazonaws.com',
                'dbname' => 'magento',
                'username' => 'magento',
                'password' => 'bEkUQnAABeZrepzX',
                'active' => '1',
            ),
            'readonly' => array(
                'host' => 'magento-homolog.cue20vbgi0dn.sa-east-1.rds.amazonaws.com',
                'dbname' => 'magento',
                'username' => 'magento',
                'password' => 'bEkUQnAABeZrepzX',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1'
            )
        ),
        'table_prefix' => ''
    ),
    'queue' =>
        array (
            'consumers_wait_for_messages' => 0,
        ),
    'crypt' =>
        array (
            'key' => 'a231477d90da126ac418eb0ebeadb524',
        ),
    'resource' =>
        array (
            'default_setup' =>
                array (
                    'connection' => 'default',
                ),
        ),
    'x-frame-options' => 'SAMEORIGIN',
    'session' =>
        array (
            'save' => 'redis',
            'redis' =>
                array (
                    'host' => 'redis.internal',
                    'port' => 6379,
                    'database' => 0,
                    'disable_locking' => 1,
                ),
        ),
    'lock' =>
        array (
            'provider' => 'db',
            'config' =>
                array (
                    'prefix' => NULL,
                ),
        ),
    'downloadable_domains' =>
        array (
            0 => 'staging-5em2ouy-7aemf3sm432ls.us-2.platformsh.site',
        ),
    'install' =>
        array (
            'date' => 'Fri, 24 Jul 2020 08:32:11 +0000',
        ),
    'static_content_on_demand_in_production' => 1,
    'force_html_minification' => 1,
    'cron_consumers_runner' =>
        array (
            'cron_run' => false,
            'max_messages' => 10000,
            'consumers' =>
                array (
                ),
        ),
    'system' =>
        array (
            'default' =>
                array (
                    'web' =>
                        array (
                            'secure' =>
                                array (
                                    'base_url' => 'https://staging-5em2ouy-7aemf3sm432ls.us-2.platformsh.site/',
                                ),
                            'unsecure' =>
                                array (
                                    'base_url' => 'https://staging-5em2ouy-7aemf3sm432ls.us-2.platformsh.site/',
                                ),
                        ),
                    'admin' =>
                        array (
                            'url' =>
                                array (
                                    'custom' => 'https://staging-5em2ouy-7aemf3sm432ls.us-2.platformsh.site/',
                                    'custom_path' => 'magentoadmin',
                                    'use_custom' => '1',
                                    'use_custom_path' => '1',
                                ),
                        ),
                ),
            'websites' =>
                array (
                    'base' =>
                        array (
                            'web' =>
                                array (
                                    'secure' =>
                                        array (
                                            'base_url' => 'https://staging-5em2ouy-7aemf3sm432ls.us-2.platformsh.site/',
                                            'base_link_url' => 'https://staging-5em2ouy-7aemf3sm432ls.us-2.platformsh.site/',
                                        ),
                                    'unsecure' =>
                                        array (
                                            'base_url' => 'https://staging-5em2ouy-7aemf3sm432ls.us-2.platformsh.site/',
                                            'base_link_url' => 'https://staging-5em2ouy-7aemf3sm432ls.us-2.platformsh.site/',
                                        ),
                                ),
                        ),
                ),
        ),
    'directories' =>
        array (
            'document_root_is_pub' => true,
        ),
);