<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.33.27;dbname=mockProject',
            'username' => 'vagrant',
            'password' => 'vagrant',
            'charset' => 'utf8',

            // common configuration for slaves
            'slaveConfig' => [
                'username' => 'vagrant',
                'password' => 'vagrant',
                'charset' => 'utf8',
                'attributes' => [
                    // use a smaller connection timeout
                    PDO::ATTR_TIMEOUT => 10,
                ],
            ],

            // list of slave configurations
            'slaves' => [
                ['dsn' => 'mysql:host=192.168.33.28;dbname=mockProject'],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
