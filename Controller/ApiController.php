<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Auditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
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
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @question The logging functions sometimes have unused parameters.
 *      They could be removed if the event triggers in the ModuleAbstract
 *      and some manual function calls get adjusted.
 *      The reason why this is not done at the moment is that this way
 *      the logging functions/event triggers have the same structure
 *      and therefore are slightly easier/brain off to use.
 *
 * @todo Create printable reports based on specific changes
 *      https://github.com/Karaka-Management/oms-Auditor/issues/3
 */
final class ApiController extends Controller
{
    /**
     * Log model creation
     *
     * @param int    $account Account who created the model
     * @param mixed  $old     Old value (unused, should be null)
     * @param mixed  $new     New value (unused, should be null)
     * @param int    $type    Module model type
     * @param string $trigger What triggered this log?
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
    public function eventLogCreate(
        int $account,
        mixed $old,
        mixed $new,
        int $type = 0,
        string $trigger = '',
        ?string $module = null,
        ?string $ref = null,
        ?string $content = null,
        ?string $ip = null
    ) : void
    {
        if (!$this->active) {
            return;
        }

        // Using empty string as the data is represented by the current model
        $newString = null; //StringUtils::stringify($new, \JSON_PRETTY_PRINT);
        $audit     = new Audit(
            new NullAccount($account),
            null,
            $newString,
            $type,
            $trigger,
            $module,
            $ref,
            $content,
            (int) \ip2long($ip ?? '127.0.0.1')
        );

        AuditMapper::create()->execute($audit);
    }

    /**
     * Log model update
     *
     * @param int    $account Account who created the model
     * @param mixed  $old     Old value
     * @param mixed  $new     New value
     * @param int    $type    Module model type
     * @param string $trigger What triggered this log?
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
    public function eventLogUpdate(
        int $account,
        mixed $old,
        mixed $new,
        int $type = 0,
        string $trigger = '',
        ?string $module = null,
        ?string $ref = null,
        ?string $content = null,
        ?string $ip = null
    ) : void
    {
        if (!$this->active) {
            return;
        }

        $oldString = StringUtils::stringify($old, \JSON_PRETTY_PRINT);
        $newString = StringUtils::stringify($new, \JSON_PRETTY_PRINT);

        if ($oldString === $newString) {
            return;
        }

        $audit = new Audit(
            new NullAccount($account),
            $oldString,
            $newString,
            $type,
            $trigger,
            $module,
            $ref,
            $content,
            (int) \ip2long($ip ?? '127.0.0.1')
        );

        AuditMapper::create()->execute($audit);
    }

    /**
     * Log model delete
     *
     * @param int    $account Account who created the model
     * @param mixed  $old     Old value
     * @param mixed  $new     New value (unused, should be null)
     * @param int    $type    Module model type
     * @param string $trigger What triggered this log?
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
    public function eventLogDelete(
        int $account,
        mixed $old,
        mixed $new,
        int $type = 0,
        string $trigger = '',
        ?string $module = null,
        ?string $ref = null,
        ?string $content = null,
        ?string $ip = null
    ) : void
    {
        if (!$this->active) {
            return;
        }

        $oldString = StringUtils::stringify($old, \JSON_PRETTY_PRINT);
        $audit     = new Audit(
            new NullAccount($account),
            $oldString,
            null,
            $type,
            $trigger,
            $module,
            $ref,
            $content,
            (int) \ip2long($ip ?? '127.0.0.1')
        );

        AuditMapper::create()->execute($audit);
    }

    /**
     * Log relation creation
     *
     * @param int    $account Account who created the model
     * @param mixed  $old     Old value (unused, should be null)
     * @param mixed  $new     New value (unused, should be null)
     * @param int    $type    Module model type
     * @param string $trigger What triggered this log?
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
    public function eventLogRelationCreate(
        int $account,
        mixed $old,
        mixed $new,
        int $type = 0,
        string $trigger = '',
        ?string $module = null,
        ?string $ref = null,
        ?string $content = null,
        ?string $ip = null
    ) : void
    {
        if (!$this->active) {
            return;
        }

        // Using empty string as the data is represented by the current model
        $newString = StringUtils::stringify($new, \JSON_PRETTY_PRINT);
        $audit     = new Audit(
            new NullAccount($account),
            null,
            $newString,
            $type,
            $trigger,
            $module,
            $ref,
            $content,
            (int) \ip2long($ip ?? '127.0.0.1')
        );

        AuditMapper::create()->execute($audit);
    }

    /**
     * Log relation delete
     *
     * @param int    $account Account who created the model
     * @param mixed  $old     Old value
     * @param mixed  $new     New value (unused, should be null)
     * @param int    $type    Module model type
     * @param string $trigger What triggered this log?
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
    public function eventLogRelationDelete(
        int $account,
        mixed $old,
        mixed $new,
        int $type = 0,
        string $trigger = '',
        ?string $module = null,
        ?string $ref = null,
        ?string $content = null,
        ?string $ip = null
    ) : void
    {
        if (!$this->active) {
            return;
        }

        $oldString = StringUtils::stringify($old, \JSON_PRETTY_PRINT);
        $audit     = new Audit(
            new NullAccount($account),
            $oldString,
            null,
            $type,
            $trigger,
            $module,
            $ref,
            $content,
            (int) \ip2long($ip ?? '127.0.0.1')
        );

        AuditMapper::create()->execute($audit);
    }
}
