<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Auditor\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Auditor\Models;

use phpOMS\Stdlib\Base\Enum;

/**
 * Default settings enum.
 *
 * @package Modules\Auditor\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class SettingsEnum extends Enum
{
    public const REPORT_PDF = '1006200001';
}
