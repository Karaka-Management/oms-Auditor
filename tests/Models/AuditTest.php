<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Auditor\tests\Models;

use Modules\Auditor\Models\Audit;
use phpOMS\Account\Account;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Auditor\Models\Audit::class)]
#[\PHPUnit\Framework\Attributes\TestDox('Modules\tests\Auditor\Models\AuditTest: Audit model')]
final class AuditTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('module')]
    #[\PHPUnit\Framework\Attributes\TestDox('The model has the expected default values after initialization')]
    public function testDefault() : void
    {
        $audit = new Audit();
        self::assertEquals(0, $audit->id);
        self::assertEquals(0, $audit->type);
        self::assertEquals('', $audit->trigger);
        self::assertNull($audit->module);
        self::assertNull($audit->ref);
        self::assertNull($audit->content);
        self::assertNull($audit->old);
        self::assertNull($audit->new);
        self::assertEquals(0, $audit->ip);
        self::assertEquals(0, $audit->createdBy->id);
        self::assertInstanceOf('\DateTimeImmutable', $audit->createdAt);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    #[\PHPUnit\Framework\Attributes\TestDox('The model can be initialized correctly')]
    public function testConstructorInputOutput() : void
    {
        $audit = new Audit(
            new Account(),
            'old', 'new',
            1, 'trigger',
            '3',
            'test',
            'content',
            \ip2long('127.0.0.1')
        );

        self::assertEquals(1, $audit->type);
        self::assertEquals('trigger', $audit->trigger);
        self::assertEquals(3, $audit->module);
        self::assertEquals('test', $audit->ref);
        self::assertEquals('content', $audit->content);
        self::assertEquals('old', $audit->old);
        self::assertEquals('new', $audit->new);
        self::assertEquals(\ip2long('127.0.0.1'), $audit->ip);
        self::assertEquals(0, $audit->createdBy->id);
    }
}
