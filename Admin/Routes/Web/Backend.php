<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Auditor\Controller\BackendController;
use Modules\Auditor\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^/admin/audit/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Auditor\Controller\BackendController:viewAuditorList',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::AUDIT,
            ],
        ],
    ],
    '^/admin/audit/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Auditor\Controller\BackendController:viewAuditorView',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::AUDIT,
            ],
        ],
    ],
];
