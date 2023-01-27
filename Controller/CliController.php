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
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Auditor\Controller;

use Modules\Auditor\Models\AuditMapper;
use Modules\Auditor\Models\NullAudit;
use phpOMS\Contract\RenderableInterface;
use phpOMS\DataStorage\Database\Query\OrderType;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * Auditor controller class.
 *
 * This class is responsible for the basic admin activities such as managing accounts, groups, permissions and modules.
 *
 * @package Modules\Auditor
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class CliController extends Controller
{
    /**
     * Method which generates the general settings view.
     *
     * In this view general settings for the entire application can be seen and adjusted. Settings which can be modified
     * here are localization, password, database, etc.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function cliGenerateBlockchain(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Auditor/Theme/Cli/blockchain');

        /** @var \Modules\Auditor\Models\Audit $first */
        $first = AuditMapper::get()
            ->where('blockchain', null)
            ->sort('id', OrderType::ASC)
            ->limit(1)
            ->execute();

        if ($first->getId() === 1) {
            /** @var \Modules\Auditor\Models\Audit $first */
            $first = AuditMapper::get()
                ->where('id', $first->getId() + 1)
                ->execute();
        }

        $count = 0;
        if (!($first instanceof NullAudit)) {
            /** @var \Modules\Auditor\Models\Audit $last */
            $last = AuditMapper::get()
                ->sort('id', OrderType::DESC)
                ->limit(1)
                ->execute();

            /** @var \Modules\Auditor\Models\Audit $previous */
            $previous = AuditMapper::get()
                ->where('id', $first->getId() - 1)
                ->execute();

            $current        = $first;
            $endLastBatchId = $first->getId() - 1;

            while ($current->getId() !== 0 && $current->getId() <= $last->getId()) {
                /** @var \Modules\Auditor\Models\Audit[] $batch */
                $batch = AuditMapper::getAll()
                    ->where('id', $endLastBatchId, '>')
                    ->sort('id', OrderType::ASC)
                    ->limit(50)
                    ->execute();

                foreach ($batch as $audit) {
                    $current = $audit;

                    $current->blockchain = \md5(
                        $previous->blockchain
                        . $current->getId()
                        . $current->createdBy->getId()
                        . $current->createdAt->format('Y-m-d H:i:s')
                        . $current->type
                        . $current->trigger
                        . $current->module
                        . $current->ref
                        . $current->old
                        . $current->new
                        . $current->content
                    );

                    AuditMapper::update()->with('blockchain')->execute($current);
                    ++$count;

                    $previous = $current;
                }

                $endLastBatchId = $current->getId();
            }

        }

        $view->setData('count', $count);

        return $view;
    }
}
