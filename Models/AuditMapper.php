<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Auditor\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Auditor\Models;

use Modules\Admin\Models\AccountMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\Auditor\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Audit
 * @extends DataMapperFactory<T>
 */
final class AuditMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'auditor_audit_id'                => ['name' => 'auditor_audit_id',         'type' => 'int',               'internal' => 'id'],
        'auditor_audit_created_by'        => ['name' => 'auditor_audit_created_by', 'type' => 'int',               'internal' => 'createdBy', 'readonly' => true],
        'auditor_audit_created_at'        => ['name' => 'auditor_audit_created_at', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
        'auditor_audit_ip'                => ['name' => 'auditor_audit_ip',         'type' => 'int',               'internal' => 'ip', 'annotations' => ['gdpr' => true]],
        'auditor_audit_module'            => ['name' => 'auditor_audit_module',     'type' => 'string',            'internal' => 'module'],
        'auditor_audit_ref'               => ['name' => 'auditor_audit_ref',        'type' => 'string',            'internal' => 'ref'],
        'auditor_audit_type'              => ['name' => 'auditor_audit_type',       'type' => 'int',               'internal' => 'type'],
        'auditor_audit_trigger'           => ['name' => 'auditor_audit_trigger',    'type' => 'string',            'internal' => 'trigger'],
        'auditor_audit_content'           => ['name' => 'auditor_audit_content',    'type' => 'compress',          'internal' => 'content'],
        'auditor_audit_old'               => ['name' => 'auditor_audit_old',        'type' => 'compress',          'internal' => 'old'],
        'auditor_audit_new'               => ['name' => 'auditor_audit_new',        'type' => 'compress',          'internal' => 'new'],
        'auditor_audit_blockchain'        => ['name' => 'auditor_audit_blockchain',        'type' => 'string',          'internal' => 'blockchain', 'readonly' => true],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:class-string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'createdBy' => [
            'mapper'   => AccountMapper::class,
            'external' => 'auditor_audit_created_by',
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = Audit::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'auditor_audit';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'auditor_audit_id';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'auditor_audit_created_at';
}
