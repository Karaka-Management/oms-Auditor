<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\Auditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
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
