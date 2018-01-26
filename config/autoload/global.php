<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
     'db' => array(
         'driver'         => 'Pdo',
         'dsn'            => 'mysql:dbname=stellu_db1;host=dedi1612.your-server.de',
         'driver_options' => array(
             PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1"
         ),
     ),
     'service_manager' => array(
         'factories' => array(
             'Zend\Db\Adapter\Adapter'
                     => 'Zend\Db\Adapter\AdapterServiceFactory',
         ),
     ),
    'mail' => array(
        'transport' => array(
            'options' => array(
                'host'              => 'mail.dedi1612.your-server.de',
                'connection_class'  => 'plain',
                'connection_config' => array(
                    'username' => 'no-reply@stellenanzeigen-texten.de',
                    'password' => '56he8K249UpE95sf',
                    'ssl' => 'tls'
                ),
            ),
        ),
    ),
 );
