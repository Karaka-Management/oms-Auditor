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

namespace Modules\tests\Audit\Controller;

require_once __DIR__ . '/../../Autoloader.php';

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
class ApiControllerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;
    protected ModuleAbstract $module;

    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Api';
        };

        $this->app->dbPool         = $GLOBALS['dbpool'];
        $this->app->orgId          = 1;
        $this->app->appName        = 'Backend';
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings($this->app->dbPool->get());
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../../Modules');
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
        $this->module->apiLogCreate(1, null, ['id' => 1, 'test' => true], 1, 2, 'Auditor', 'abc', 'def');
        $logs = AuditMapper::getAll();

        foreach($logs as $log) {
            if ($log->getId() > 0
                && $log->getType() === 1
                && $log->getSubtype() === 2
                && $log->getModule() === 'Auditor'
                && $log->getRef() === 'abc'
                && $log->getContent() === 'def'
                && $log->getOld() === null
                && $log->getNew() === \json_encode(['id' => 1, 'test' => true], \JSON_PRETTY_PRINT)
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
        $this->module->apiLogUpdate(1, ['id' => 2, 'test' => true], ['id' => 1, 'test' => true], 1, 2, 'Auditor', 'abc', 'def');
        $logs = AuditMapper::getAll();

        foreach($logs as $log) {
            if ($log->getId() > 0
                && $log->getType() === 1
                && $log->getSubtype() === 2
                && $log->getModule() === 'Auditor'
                && $log->getRef() === 'abc'
                && $log->getContent() === 'def'
                && $log->getOld() === \json_encode(['id' => 2, 'test' => true], \JSON_PRETTY_PRINT)
                && $log->getNew() === \json_encode(['id' => 1, 'test' => true], \JSON_PRETTY_PRINT)
            ) {
                self::assertTrue(true);
                return;
            }
        }

        self::assertTrue(false);
    }

    /**
     * @testdox Audit logs for delete statements can be created
     * @covers Modules\Auditor\Controller\ApiController
     * @group module
     */
    public function testLogDelete() : void
    {
        $this->module->apiLogDelete(1, ['id' => 1, 'test' => true], null, 1, 2, 'Auditor', 'abc', 'def');
        $logs = AuditMapper::getAll();

        foreach($logs as $log) {
            if ($log->getId() > 0
                && $log->getType() === 1
                && $log->getSubtype() === 2
                && $log->getModule() === 'Auditor'
                && $log->getRef() === 'abc'
                && $log->getContent() === 'def'
                && $log->getOld() === \json_encode(['id' => 1, 'test' => true], \JSON_PRETTY_PRINT)
                && $log->getNew() === null
            ) {
                self::assertTrue(true);
                return;
            }
        }

        self::assertTrue(false);
    }
}
