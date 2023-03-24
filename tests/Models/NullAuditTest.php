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

use Modules\Auditor\Models\NullAudit;

/**
 * @internal
 */
final class NullAuditTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Auditor\Models\NullAudit
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Auditor\Models\Audit', new NullAudit());
    }

    /**
     * @covers Modules\Auditor\Models\NullAudit
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullAudit(2);
        self::assertEquals(2, $null->getId());
    }
}
