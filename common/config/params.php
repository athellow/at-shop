<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,

    'loacalFileDomain' => 'http://f.athellow.com',

    // 速率限制配置
    'api.ratelimit.limit' => 3,     // 允许的最大请求数
    'api.ratelimit.window' => 1,    // 窗口的大小，以秒为单位

    

    'storage' => [
        'type' => 'local',
        'drives' => [
            'local' => [
                'basePath' => Yii::getAlias('@frontend') . '/web',
                'uploadFolder' => '/uploads',
                'uploadTmpFolder' => '/uploads/tmp',
                'uploadThumbFolder' => '/uploads/thumbs',
                'domain' => 'http://f.athellow.com',
            ]
            
        ],
    ]
];
