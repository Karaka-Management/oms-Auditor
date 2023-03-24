<?php
/**
 * Karaka
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
 * @testdox Modules\tests\Auditor\Models\AuditTest: Audit model
 *
 * @internal
 */
final class AuditTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The model has the expected default values after initialization
     * @covers Modules\Auditor\Models\Audit
     * @group module
     */
    public function testDefault() : void
    {
        $audit = new Audit();
        self::assertEquals(0, $audit->getId());
        self::assertEquals(0, $audit->type);
        self::assertEquals('', $audit->trigger);
        self::assertNull($audit->module);
        self::assertNull($audit->ref);
        self::assertNull($audit->content);
        self::assertNull($audit->old);
        self::assertNull($audit->new);
        self::assertEquals(0, $audit->ip);
        self::assertEquals(0, $audit->createdBy->getId());
        self::assertInstanceOf('\DateTimeImmutable', $audit->createdAt);
    }

    /**
     * @testdox The model can be initialized correctly
     * @covers Modules\Auditor\Models\Audit
     * @group module
     */
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
        self::assertEquals(0, $audit->createdBy->getId());
    }
}
