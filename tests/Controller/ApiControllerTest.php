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
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Auditor\tests\Controller;

use Model\CoreSettings;
use Modules\Admin\Models\AccountPermission;
use Modules\Auditor\Models\AuditMapper;
use phpOMS\Account\Account;
use phpOMS\Account\AccountManager;
use phpOMS\Account\PermissionType;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Module\ModuleAbstract;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\Utils\TestUtils;

/**
 * @testdox Modules\tests\Auditor\Controller\ApiControllerTest: Auditor api controller
 *
 * @internal
 */
final class ApiControllerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;

    /**
     * @var \Modules\Auditor\Controller\ApiController
     */
    protected ModuleAbstract $module;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Api';
        };

        $this->app->dbPool         = $GLOBALS['dbpool'];
        $this->app->orgId          = 1;
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings();
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../../Web/Api/Hooks.php');

        $account = new Account();
        TestUtils::setMember($account, 'id', 1);

        $permission = new AccountPermission();
        $permission->setUnit(1);
        $permission->setApp('backend');
        $permission->setPermission(
            PermissionType::READ
            | PermissionType::CREATE
            | PermissionType::MODIFY
            | PermissionType::DELETE
            | PermissionType::PERMISSION
        );

        $account->addPermission($permission);

        $this->app->accountManager->add($account);
        $this->app->router = new WebRouter();

        $this->module = $this->app->moduleManager->get('Auditor');

        TestUtils::setMember($this->module, 'app', $this->app);
    }

    /**
     * @testdox Audit logs for create statements can be created
     * @covers Modules\Auditor\Controller\ApiController
     * @group module
     */
    public function testLogCreate() : void
    {
        $this->module->eventLogCreate(1, null, ['id' => 1, 'test' => true], 1, 'test-trigger', 'Auditor', 'abc', 'def');
        $logs = AuditMapper::getAll()->execute();

        foreach($logs as $log) {
            if ($log->getId() > 0
                && $log->type === 1
                && $log->trigger === 'test-trigger'
                && $log->module === 'Auditor'
                && $log->ref === 'abc'
                && $log->content === 'def'
                && $log->old === null
                && $log->new === \json_encode(['id' => 1, 'test' => true], \JSON_PRETTY_PRINT)
            ) {
                self::assertTrue(true);
                return;
            }
        }

        self::assertTrue(false);
    }

    /**
     * @testdox Audit logs for update statements can be created
     * @covers Modules\Auditor\Controller\ApiController
     * @group module
     */
    public function testLogUpdate() : void
    {
        $this->module->eventLogUpdate(1, ['id' => 2, 'test' => true], ['id' => 1, 'test' => true], 1, 'test-trigger', 'Auditor', 'abc', 'def');
        $logs = AuditMapper::getAll()->execute();

        $found = false;
        foreach($logs as $log) {
            if ($log->getId() > 0
                && $log->type === 1
                && $log->trigger === 'test-trigger'
                && $log->module === 'Auditor'
                && $log->ref === 'abc'
                && $log->content === 'def'
                && $log->old === \json_encode(['id' => 2, 'test' => true], \JSON_PRETTY_PRINT)
                && $log->new === \json_encode(['id' => 1, 'test' => true], \JSON_PRETTY_PRINT)
            ) {
                $found = true;
                break;
            }
        }

        self::assertTrue($found);
    }

    /**
     * @covers Modules\Auditor\Controller\ApiController
     * @group module
     */
    public function testLogUpdateWithoutChange() : void
    {
        $logs = AuditMapper::getAll()->execute();
        $this->module->eventLogUpdate(1, ['id' => 2, 'test' => true], ['id' => 2, 'test' => true], 1, 'test-trigger', 'Auditor', 'abc', 'def');
        $logs2 = AuditMapper::getAll()->execute();

        self::assertGreaterThan(0, \count($logs));
        self::assertEquals(\count($logs), \count($logs2));
    }

    /**
     * @testdox Audit logs for delete statements can be created
     * @covers Modules\Auditor\Controller\ApiController
     * @group module
     */
    public function testLogDelete() : void
    {
        $this->module->eventLogDelete(1, ['id' => 1, 'test' => true], null, 1, 'test-trigger', 'Auditor', 'abc', 'def');
        $logs = AuditMapper::getAll()->execute();

        foreach($logs as $log) {
            if ($log->getId() > 0
                && $log->type === 1
                && $log->trigger === 'test-trigger'
                && $log->module === 'Auditor'
                && $log->ref === 'abc'
                && $log->content === 'def'
                && $log->old === \json_encode(['id' => 1, 'test' => true], \JSON_PRETTY_PRINT)
                && $log->new === null
            ) {
                self::assertTrue(true);
                return;
            }
        }

        self::assertTrue(false);
    }
}
