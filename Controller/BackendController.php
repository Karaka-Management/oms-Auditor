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

        $pageLimit = 25;
        $view->addData('pageLimit', $pageLimit);

        $mapper = AuditMapper::getAll()->with('createdBy');
        $list   = AuditMapper::getDataList(
            mapper: $mapper,
            id: (int) ($request->getData('id') ?? 0),
            secondaryId: (string) ($request->getData('subid') ?? ''),
            type: $request->getData('pType'),
            pageLimit: empty((int) ($request->getData('limit') ?? 0)) ? 100 : ((int) $request->getData('limit')),
            sortBy: $request->getData('sort_by') ?? '',
            sortOrder: $request->getData('sort_order') ?? OrderType::DESC,
            search: $request->getData('search'),
            searchFields: $request->getDataList('search_fields')
        );

        $view->setData('hasPrevious', $list['hasPrevious']);
        $view->setData('hasNext', $list['hasNext']);
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
        $tableView->exportUri = '{/api}auditor/list/export';

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
