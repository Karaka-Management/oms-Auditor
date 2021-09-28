<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use Modules\Auditor\Controller\BackendController;
use Modules\Auditor\Models\PermissionState;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/admin/audit/list.*$' => [
        [
            'dest'       => '\Modules\Auditor\Controller\BackendController:viewAuditorList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::AUDIT,
            ],
        ],
    ],
    '^.*/admin/audit/single.*$' => [
        [
            'dest'       => '\Modules\Auditor\Controller\BackendController:viewAuditorSingle',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::AUDIT,
            ],
        ],
    ],
];
