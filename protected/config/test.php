<?php

return CMap::mergeArray(
                require(dirname(__FILE__) . '/main.php'), array(
            'modules' => array(
                // uncomment the following to enable the Gii tool

                'gii' => array(
                    'class' => 'system.gii.GiiModule',
                    'password' => 'admin',
                    // If removed, Gii defaults to localhost only. Edit carefully to taste.
                    'ipFilters' => array('127.0.0.1'),
                    'generatorPaths' => array(
                        'bootstrap.gii'
                    )
                ),
            ),
            'components' => array(
                'fixture' => array(
                    'class' => 'system.test.CDbFixtureManager',
                ),
                //Êý¾Ý¿â
                'db' => array(
                    'connectionString' => 'mysql:host=localhost;dbname=DB_DEMO',
                    'emulatePrepare' => true,
                    'username' => 'root',
                    'password' => 'mihecn',
                    'charset' => 'utf8',
                ),
            ),
                )
);
