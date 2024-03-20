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

use Modules\Admin\Models\NullAccount;
use phpOMS\Account\Account;

/**
 * Audit class.
 *
 * @package Modules\Auditor\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Audit
{
    /**
     * Audit id.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Audit type.
     *
     * @var int
     * @since 1.0.0
     */
    public int $type;

    /**
     * Audit trigger.
     *
     * @var string
     * @since 1.0.0
     */
    public string $trigger;

    /**
     * Audit module.
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $module;

    /**
     * Audit reference.
     *
     * This could be used to reference other model ids
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $ref;

    /**
     * Audit content.
     *
     * Additional audit information
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $content;

    /**
     * Old value.
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $old;

    /**
     * New value.
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $new;

    /**
     * Account.
     *
     * @var Account
     * @since 1.0.0
     */
    public Account $createdBy;

    /**
     * Created at.
     *
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    public \DateTimeImmutable $createdAt;

    /**
     * Ip of creator.
     *
     * @var int
     * @since 1.0.0
     */
    public int $ip = 0;

    /**
     * Blockchain.
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $blockchain = null;

    /**
     * Constructor.
     *
     * @param Account     $account Account of the creator
     * @param null|string $old     Old value
     * @param null|string $new     New value
     * @param int         $type    Type of the audit
     * @param string      $trigger Subtype of the audit
     * @param null|string $module  Module id
     * @param null|string $ref     Dynamic reference to model
     * @param null|string $content Additional audit information
     * @param int         $ip      Ip
     *
     * @since 1.0.0
     */
    public function __construct(
        ?Account $account = null,
        ?string $old = null,
        ?string $new = null,
        int $type = 0,
        string $trigger = '',
        ?string $module = null,
        ?string $ref = null,
        ?string $content = null,
        int $ip = 0
    ) {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->createdBy = $account ?? new NullAccount();
        $this->old       = $old;
        $this->new       = $new;
        $this->type      = $type;
        $this->trigger   = $trigger;
        $this->module    = $module;
        $this->ref       = $ref;
        $this->content   = $content;
        $this->ip        = $ip;
    }
}
