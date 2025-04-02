<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Auditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Auditor\Controller;

use Modules\Auditor\Models\AuditMapper;
use phpOMS\Contract\RenderableInterface;
use phpOMS\DataStorage\Database\Query\OrderType;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;
use Web\Backend\Views\TableView;

/**
 * Calendar controller class.
 *
 * @package Modules\Auditor
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewAuditorList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Auditor/Theme/Backend/audit-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1006201001, $request, $response);

        $searchFieldData = $request->getLike('.*\-p\-.*');
        $searchField     = [];
        foreach ($searchFieldData as $key => $data) {
            if ($data === '1') {
                $split  = \explode('-', $key);
                $member = \end($split);

                $searchField[] = $member;
            }
        }

        $filterFieldData = $request->getLike('.*\-f\-.*?\-t');
        $filterField     = [];
        foreach ($filterFieldData as $key => $type) {
            $split = \explode('-', $key);
            \end($split);

            $member = \prev($split);

            if ($request->hasData('auditlist-f-' . $member . '-f1')) {
                $filterField[$member] = [
                    'type'   => $type,
                    'value1' => $request->getData('auditlist-f-' . $member . '-f1'),
                    'logic1' => $request->getData('auditlist-f-' . $member . '-o1'),
                    'value2' => $request->getData('auditlist-f-' . $member . '-f2'),
                    'logic2' => $request->getData('auditlist-f-' . $member . '-o2'),
                ];
            }
        }

        $pageLimit               = 25;
        $view->data['pageLimit'] = $pageLimit;

        $mapper = AuditMapper::getAll()->with('createdBy');

        /** @var \Modules\Auditor\Models\Audit[] $list */
        $list = AuditMapper::find(
            search: $request->getDataString('search'),
            mapper: $mapper,
            id: $request->getDataInt('id') ?? 0,
            secondaryId: $request->getDataString('subid') ?? '',
            type: $request->getDataString('pType'),
            pageLimit: empty($request->getDataInt('limit') ?? 0) ? 100 : $request->getDataInt('limit'),
            sortBy: $request->getDataString('sort_by') ?? '',
            sortOrder: $request->getDataString('sort_order') ?? OrderType::DESC,
            searchFields: $searchField,
            filters: $filterField
        );

        $view->data['audits'] = $list['data'];

        $tableView         = new TableView($this->app->l11nManager, $request, $response);
        $tableView->module = 'Auditor';
        $tableView->theme  = 'Backend';
        $tableView->setTitleTemplate('/Web/Backend/Themes/table-title');
        $tableView->setExportTemplate('/Web/Backend/Themes/popup-export-data');
        $tableView->setExportTemplates([]);
        $tableView->setColumnHeaderElementTemplate('/Web/Backend/Themes/header-element-table');
        $tableView->setFilterTemplate('/Web/Backend/Themes/popup-filter-table');
        $tableView->setSortTemplate('/Web/Backend/Themes/sort-table');
        $tableView->setData('hasPrevious', $list['hasPrevious']);
        $tableView->setData('hasNext', $list['hasNext']);

        $view->data['tableView'] = $tableView;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewAuditorView(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Auditor/Theme/Backend/audit-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1006201001, $request, $response);

        /** @var \Modules\Auditor\Models\Audit $audit */
        $audit = AuditMapper::get()
            ->with('createdBy')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $view->data['audit'] = $audit;

        return $view;
    }
}
