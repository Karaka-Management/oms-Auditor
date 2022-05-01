<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Auditor\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Auditor\Models;

use Modules\Admin\Models\AccountMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;
use phpOMS\DataStorage\Database\Query\OrderType;

/**
 * Mapper class.
 *
 * @package Modules\Auditor\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
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
        'auditor_audit_id'         => ['name' => 'auditor_audit_id',         'type' => 'int',               'internal' => 'id'],
        'auditor_audit_created_by' => ['name' => 'auditor_audit_created_by', 'type' => 'int',               'internal' => 'createdBy', 'readonly' => true],
        'auditor_audit_created_at' => ['name' => 'auditor_audit_created_at', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
        'auditor_audit_ip'         => ['name' => 'auditor_audit_ip',         'type' => 'int',               'internal' => 'ip', 'annotations' => ['gdpr' => true]],
        'auditor_audit_module'     => ['name' => 'auditor_audit_module',     'type' => 'string',            'internal' => 'module'],
        'auditor_audit_ref'        => ['name' => 'auditor_audit_ref',        'type' => 'string',            'internal' => 'ref'],
        'auditor_audit_type'       => ['name' => 'auditor_audit_type',       'type' => 'int',               'internal' => 'type'],
        'auditor_audit_trigger'    => ['name' => 'auditor_audit_trigger',    'type' => 'string',            'internal' => 'trigger'],
        'auditor_audit_content'    => ['name' => 'auditor_audit_content',    'type' => 'string',            'internal' => 'content'],
        'auditor_audit_old'        => ['name' => 'auditor_audit_old',        'type' => 'string',            'internal' => 'old'],
        'auditor_audit_new'        => ['name' => 'auditor_audit_new',        'type' => 'string',            'internal' => 'new'],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:string, external:string, column?:string, by?:string}>
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
     * @var string
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
    public const PRIMARYFIELD ='auditor_audit_id';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'auditor_audit_created_at';

    public static function getAuditList(mixed $mapper, int $id, string $type = null, int $pageLimit) : array
    {
        /** @var \Modules\Auditor\Models\Audit[] $data */
        $data = [];

        $hasPrevious = false;
        $hasNext     = false;

        if ($type === 'p') {
            $cloned = clone $mapper;
            $data   = $mapper->sort('id', OrderType::ASC)
                ->where('id', $id, '>=')
                ->limit($pageLimit + 2)
                ->execute();

            if (($count = \count($data)) < 2) {
                $data = $cloned->sort('id', OrderType::DESC)
                    ->where('id', 0, '>')
                    ->limit($pageLimit + 1)
                    ->execute();

                $hasNext = $count > $pageLimit;
                if ($hasNext) {
                    \array_pop($data);
                    --$count;
                }
            } else {
                if (\reset($data)->getId() === $id) {
                    \array_shift($data);
                    $hasNext = true;
                    --$count;
                }

                if ($count > $pageLimit) {
                    \array_pop($data);
                    $hasNext = true;
                    --$count;

                    if ($count > $pageLimit) {
                        $hasPrevious = true;
                        \array_pop($data);
                    }
                }

                $data = \array_reverse($data);
            }
        } elseif ($type === 'n') {
            $data = $mapper->sort('id', OrderType::DESC)
                ->where('id', $id, '<=')
                ->limit($pageLimit + 2)
                ->execute();

            if (!empty($data)) {
                if (\reset($data)->getId() === $id) {
                    \array_shift($data);
                    $hasPrevious = true;
                }

                if (\count($data) > $pageLimit) {
                    \array_pop($data);
                    $hasNext = true;
                }
            }
        } else {
            $data = $mapper->sort('id', OrderType::DESC)
                ->where('id', 0, '>')
                ->limit($pageLimit + 1)
                ->execute();

            $hasNext = ($count = \count($data)) > $pageLimit;
            if ($hasNext) {
                \array_pop($data);
            }
        }

        return [
            'hasPrevious' => $hasPrevious,
            'hasNext'     => $hasNext,
            'data'        => $data,
        ];
    }
}
