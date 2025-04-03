<?php
return [
    'debug' => true,
    'secret_key' => bin2hex(random_bytes(32)),
    // 'app_name' => 'Microframework PHP',
    // 'jwt' => [
    //     'secret' => bin2hex(random_bytes(32)),
    //     'algorithm' => 'HS512', 
    //     'expiration' => 3600, 
    //     'leeway' => 60,
    //     'issuer' => 'microframework-php' 
    // ]
];