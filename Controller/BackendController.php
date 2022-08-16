<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Auditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Auditor\Controller;

use Modules\Admin\Models\SettingsEnum;
use Modules\Auditor\Models\AuditMapper;
use Modules\Media\Models\MediaMapper;
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
 * @license OMS License 1.0
 * @link    https://karaka.app
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
    public function viewAuditorList(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Auditor/Theme/Backend/audit-list');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1006201001, $request, $response));

        $searchFieldData = $request->getLike('.*\-p\-.*');
        $searchField     = [];
        foreach ($searchFieldData as $key => $data) {
            if ($data === '1') {
                $split  = \explode('-', $key);
                $member =  \end($split);

                $searchField[] = $member;
            }
        }

        $filterFieldData = $request->getLike('.*\-f\-.*?\-t');
        $filterField     = [];
        foreach ($filterFieldData as $key => $type) {
            $split = \explode('-', $key);
            \end($split);

            $member = \prev($split);

            if (!empty($request->getData('auditlist-f-' . $member . '-f1'))) {
                $filterField[$member] = [
                    'type'   => $type,
                    'value1' => $request->getData('auditlist-f-' . $member . '-f1'),
                    'logic1' => $request->getData('auditlist-f-' . $member . '-o1'),
                    'value2' => $request->getData('auditlist-f-' . $member . '-f2'),
                    'logic2' => $request->getData('auditlist-f-' . $member . '-o2'),
                ];
            }
        }

        $pageLimit = 25;
        $view->addData('pageLimit', $pageLimit);

        $mapper = AuditMapper::getAll()->with('createdBy');
        $list   = AuditMapper::find(
            search: $request->getData('search'),
            mapper: $mapper,
            id: (int) ($request->getData('id') ?? 0),
            secondaryId: (string) ($request->getData('subid') ?? ''),
            type: $request->getData('pType'),
            pageLimit: empty((int) ($request->getData('limit') ?? 0)) ? 100 : ((int) $request->getData('limit')),
            sortBy: $request->getData('sort_by') ?? '',
            sortOrder: $request->getData('sort_order') ?? OrderType::DESC,
            searchFields: $searchField,
            filters: $filterField
        );

        $view->setData('audits', $list['data']);

        /** @var \Model\Setting[] $exportTemplates */
        $exportTemplates = $this->app->appSettings->get(
            names: [
                SettingsEnum::DEFAULT_PDF_EXPORT_TEMPLATE,
                SettingsEnum::DEFAULT_EXCEL_EXPORT_TEMPLATE,
                SettingsEnum::DEFAULT_CSV_EXPORT_TEMPLATE,
                SettingsEnum::DEFAULT_WORD_EXPORT_TEMPLATE,
                SettingsEnum::DEFAULT_EMAIL_EXPORT_TEMPLATE,
            ],
            module: 'Admin'
        );

        $templateIds = [];
        foreach ($exportTemplates as $template) {
            $templateIds[] = (int) $template->content;
        }

        $mediaTemplates = MediaMapper::getAll()
            ->where('id', $templateIds, 'in')
            ->execute();

        $tableView         = new TableView($this->app->l11nManager, $request, $response);
        $tableView->module = 'Auditor';
        $tableView->theme  = 'Backend';
        $tableView->setTitleTemplate('/Web/Backend/Themes/table-title');
        $tableView->setExportTemplate('/Web/Backend/Themes/popup-export-data');
        $tableView->setExportTemplates($mediaTemplates);
        $tableView->setColumnHeaderElementTemplate('/Web/Backend/Themes/header-element-table');
        $tableView->setFilterTemplate('/Web/Backend/Themes/popup-filter-table');
        $tableView->setSortTemplate('/Web/Backend/Themes/sort-table');
        $tableView->setData('hasPrevious', $list['hasPrevious']);
        $tableView->setData('hasNext', $list['hasNext']);

        $view->addData('tableView', $tableView);

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
    public function viewAuditorSingle(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
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
