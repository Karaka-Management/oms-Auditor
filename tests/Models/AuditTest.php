<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
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
        self::assertEquals(0, $audit->getType());
        self::assertEquals('', $audit->getTrigger());
        self::assertNull($audit->getModule());
        self::assertNull($audit->getRef());
        self::assertNull($audit->getContent());
        self::assertNull($audit->getOld());
        self::assertNull($audit->getNew());
        self::assertEquals(0, $audit->getIp());
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

        self::assertEquals(1, $audit->getType());
        self::assertEquals('trigger', $audit->getTrigger());
        self::assertEquals(3, $audit->getModule());
        self::assertEquals('test', $audit->getRef());
        self::assertEquals('content', $audit->getContent());
        self::assertEquals('old', $audit->getOld());
        self::assertEquals('new', $audit->getNew());
        self::assertEquals(\ip2long('127.0.0.1'), $audit->getIp());
        self::assertEquals(0, $audit->createdBy->getId());
    }
}
