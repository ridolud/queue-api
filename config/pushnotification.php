<?php
/**
 * @see https://github.com/Edujugon/PushNotification
 */

return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
    ],
    'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/QueueAPNSCertificates.pem',
        'passPhrase' => env('APN_PASS_CER', ''), //Optional
        //'passFile' => __DIR__ . '/iosCertificates/aps_development.cer', //Optional
        'dry_run' => true,
    ],
];
