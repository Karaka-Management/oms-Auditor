<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\Auditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Auditor\Controller;

use Modules\Admin\Models\NullAccount;
use Modules\Auditor\Models\Audit;
use Modules\Auditor\Models\AuditMapper;
use phpOMS\Utils\StringUtils;

/**
 * Auditor api controller class.
 *
 * @package Modules\Auditor
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Log model creation
     *
     * @param int    $account Account who created the model
     * @param mixed  $old     Old value (always null)
     * @param mixed  $new     New value
     * @param int    $type    Module model type
     * @param int    $subtype Module model subtype
     * @param string $module  Module name
     * @param string $ref     Reference to other model
     * @param string $content Message
     * @param string $ip      Ip
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiLogCreate(
        int $account,
        $old,
        $new,
        int $type = 0,
        int $subtype = 0,
        string $module = null,
        string $ref = null,
        string $content = null,
        string $ip = null
    ) : void
    {
        $newString = StringUtils::stringify($new, \JSON_PRETTY_PRINT);
        $audit     = new Audit(new NullAccount($account), null, $newString, $type, $subtype, $module, $ref, $content, \ip2long($ip ?? '127.0.0.1'));

        AuditMapper::create($audit);
    }

    /**
     * Log model update
     *
     * @param int    $account Account who created the model
     * @param mixed  $old     Old value
     * @param mixed  $new     New value
     * @param int    $type    Module model type
     * @param int    $subtype Module model subtype
     * @param string $module  Module name
     * @param string $ref     Reference to other model
     * @param string $content Message
     * @param string $ip      Ip
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiLogUpdate(
        int $account,
        $old,
        $new,
        int $type = 0,
        int $subtype = 0,
        string $module = null,
        string $ref = null,
        string $content = null,
        string $ip = null
    ) : void
    {
        $oldString = StringUtils::stringify($old, \JSON_PRETTY_PRINT);
        $newString = StringUtils::stringify($new, \JSON_PRETTY_PRINT);

        if ($oldString === $newString) {
            return;
        }

        $audit = new Audit(new NullAccount($account), $oldString, $newString, $type, $subtype, $module, $ref, $content, \ip2long($ip ?? '127.0.0.1'));

        AuditMapper::create($audit);
    }

    /**
     * Log model delete
     *
     * @param int    $account Account who created the model
     * @param mixed  $old     Old value
     * @param mixed  $new     New value (always null)
     * @param int    $type    Module model type
     * @param int    $subtype Module model subtype
     * @param string $module  Module name
     * @param string $ref     Reference to other model
     * @param string $content Message
     * @param string $ip      Ip
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiLogDelete(
        int $account,
        $old,
        $new,
        int $type = 0,
        int $subtype = 0,
        string $module = null,
        string $ref = null,
        string $content = null,
        string $ip = null
    ) : void
    {
        $oldString = StringUtils::stringify($old, \JSON_PRETTY_PRINT);
        $audit     = new Audit(new NullAccount($account), $oldString, null, $type, $subtype, $module, $ref, $content, \ip2long($ip ?? '127.0.0.1'));

        AuditMapper::create($audit);
    }
}
