<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,

    // 速率限制配置
    'api.ratelimit.limit' => 3,     // 允许的最大请求数
    'api.ratelimit.window' => 1,    // 窗口的大小，以秒为单位
];
