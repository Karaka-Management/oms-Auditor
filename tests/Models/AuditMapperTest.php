<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Auditor\tests\Models;

use Modules\Auditor\Models\AuditMapper;

/**
 * @internal
 */
class AuditMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Auditor\Models\AuditMapper
     * @group module
     */
    public function testInvalidDelete() : void
    {
        self::assertEquals(-1, AuditMapper::delete(null));
    }

    /**
     * @covers Modules\Auditor\Models\AuditMapper
     * @group module
     */
    public function testInvalidUpdate() : void
    {
        self::assertEquals(-1, AuditMapper::update(null));
    }

    /**
     * @covers Modules\Auditor\Models\AuditMapper
     * @group module
     */
    public function testInvalidUpdateArray() : void
    {
        $arr = [];
        self::assertEquals(-1, AuditMapper::updateArray($arr));
    }
}
