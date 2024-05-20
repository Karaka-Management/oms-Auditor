<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Auditor\tests\Models;

use Modules\Admin\Models\NullAccount;
use Modules\Auditor\Models\Audit;
use Modules\Auditor\Models\AuditMapper;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Auditor\Models\AuditMapper::class)]
final class AuditMapperTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testCR() : void
    {
        $audit = new Audit(
            new NullAccount(1),
            'old',
            'new',
            1,
            'test-trigger',
            'Admin',
            'test-ref',
            'test-content',
            10000
        );

        $id = AuditMapper::create()->execute($audit);
        self::assertGreaterThan(0, $audit->id);
        self::assertEquals($id, $audit->id);

        $auditR = AuditMapper::get()
            ->where('id', $audit->id)
            ->execute();

        self::assertEquals($audit->type, $auditR->type);
        self::assertEquals($audit->trigger, $auditR->trigger);
        self::assertEquals($audit->module, $auditR->module);
        self::assertEquals($audit->ref, $auditR->ref);
        self::assertEquals($audit->content, $auditR->content);
        self::assertEquals($audit->old, $auditR->old);
        self::assertEquals($audit->new, $auditR->new);
        self::assertEquals($audit->ip, $auditR->ip);
    }
}
