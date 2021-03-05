<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Auditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

return [
    '/POST:Module:.*?\-create/' => [
        'callback' => ['\Modules\Auditor\Controller\ApiController:eventLogCreate'],
    ],
    '/POST:Module:.*?\-update/' => [
        'callback' => ['\Modules\Auditor\Controller\ApiController:eventLogUpdate'],
    ],
    '/POST:Module:.*?\-delete/' => [
        'callback' => ['\Modules\Auditor\Controller\ApiController:eventLogDelete'],
    ],
];
