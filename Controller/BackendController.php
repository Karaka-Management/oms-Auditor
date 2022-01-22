<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Auditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Auditor\Controller;

use Modules\Auditor\Models\AuditMapper;
use phpOMS\Contract\RenderableInterface;
use phpOMS\DataStorage\Database\Query\OrderType;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * Calendar controller class.
 *
 * @package Modules\Auditor
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewAuditorList(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Auditor/Theme/Backend/audit-list');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1006201001, $request, $response));

        if ($request->getData('ptype') === 'p') {
            $data = AuditMapper::getAll()->with('createdBy')->sort('id', OrderType::ASC)->where('id', (int) ($request->getData('id') ?? 0), '>')->limit(25)->execute();

            if (empty($data)) {
                $data = AuditMapper::getAll()->with('createdBy')->sort('id', OrderType::DESC)->where('id', 0, '>')->limit(25)->execute();
            } else {
                $data = \array_reverse($data);
            }

            $view->setData('audits', $data);
        } elseif ($request->getData('ptype') === 'n') {
            $view->setData('audits', AuditMapper::getAll()->with('createdBy')->sort('id', OrderType::DESC)->where('id', (int) ($request->getData('id') ?? 0), '<')->limit(25)->execute());
        } else {
            $view->setData('audits', AuditMapper::getAll()->with('createdBy')->sort('id', OrderType::DESC)->where('id', 0, '>')->limit(25)->execute());
        }

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewAuditorSingle(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Auditor/Theme/Backend/audit-single');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1006201001, $request, $response));

        /** @var \Modules\Auditor\Models\Audit $audit */
        $audit = AuditMapper::get()->with('createdBy')->where('id', (int) $request->getData('id'))->execute();
        $view->setData('audit', $audit);

        return $view;
    }
}
