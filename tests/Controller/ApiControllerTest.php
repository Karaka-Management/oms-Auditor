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
use phpOMS\Localization\L11nManager;
use phpOMS\Module\ModuleAbstract;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\Utils\TestUtils;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Auditor\Controller\ApiController::class)]
#[\PHPUnit\Framework\Attributes\TestDox('Modules\tests\Auditor\Controller\ApiControllerTest: Auditor api controller')]
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
        $this->app->unitId         = 1;
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings();
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->l11nManager    = new L11nManager();
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../../Web/Api/Hooks.php');

        $account = new Account();
        TestUtils::setMember($account, 'id', 1);

        $permission       = new AccountPermission();
        $permission->unit = 1;
        $permission->app  = 2;
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

    #[\PHPUnit\Framework\Attributes\Group('module')]
    #[\PHPUnit\Framework\Attributes\TestDox('Audit logs for create statements can be created')]
    public function testLogCreate() : void
    {
        $this->module->eventLogCreate(1, null, ['id' => 1, 'test' => true], 1, 'test-trigger', 'Auditor', 'abc', 'def');
        $logs = AuditMapper::getAll()
            ->sort('id', 'DESC')
            ->limit(100)
            ->executeGetArray();

        foreach($logs as $log) {
            if ($log->id > 0
                && $log->type === 1
                && $log->trigger === 'test-trigger'
                && $log->module === 'Auditor'
                && $log->ref === 'abc'
                && \strlen($log->content) > 0
                && $log->old === null
                && $log->new === null // null because the object itself is the data, no additional logging required
            ) {
                self::assertTrue(true);
                return;
            }
        }

        self::assertTrue(false);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    #[\PHPUnit\Framework\Attributes\TestDox('Audit logs for update statements can be created')]
    public function testLogUpdate() : void
    {
        $this->module->eventLogUpdate(1, ['id' => 2, 'test' => true], ['id' => 1, 'test' => true], 1, 'test-trigger', 'Auditor', 'abc', 'def');
        $logs = AuditMapper::getAll()->executeGetArray();

        $found = false;
        foreach($logs as $log) {
            if ($log->id > 0
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

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testLogUpdateWithoutChange() : void
    {
        $logs = AuditMapper::getAll()->executeGetArray();
        $this->module->eventLogUpdate(1, ['id' => 2, 'test' => true], ['id' => 2, 'test' => true], 1, 'test-trigger', 'Auditor', 'abc', 'def');
        $logs2 = AuditMapper::getAll()->executeGetArray();

        self::assertGreaterThan(0, \count($logs));
        self::assertEquals(\count($logs), \count($logs2));
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    #[\PHPUnit\Framework\Attributes\TestDox('Audit logs for delete statements can be created')]
    public function testLogDelete() : void
    {
        $this->module->eventLogDelete(1, ['id' => 1, 'test' => true], null, 1, 'test-trigger', 'Auditor', 'abc', 'def');
        $logs = AuditMapper::getAll()->executeGetArray();

        foreach($logs as $log) {
            if ($log->id > 0
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
