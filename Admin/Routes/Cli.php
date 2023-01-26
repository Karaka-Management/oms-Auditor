<?php
declare(strict_types=1);

use phpOMS\Router\RouteVerb;

return [
    '^/admin/audit/blockchain/create.*$' => [
        [
            'dest' => '\Modules\Admin\Controller\CliController:cliGenerateBlockchain',
            'verb' => RouteVerb::ANY,
        ],
    ],
];
