<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\Auditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/**
 * @var \Web\Backend\Views\TableView  $this
 * @var \Modules\Audit\Models\Audit[] $audits
 */
$audits = $this->data['audits'] ?? [];

$tableView            = $this->data['tableView'];
$tableView->id        = 'auditList';
$tableView->baseUri   = '{/base}/admin/audit/list';
$tableView->exportUri = '{/api}auditor/list/export';
$tableView->setObjects($audits);

$previous = $tableView->getPreviousLink(
    $this->request,
    empty($this->objects) || !$this->getData('hasPrevious') ? null : \reset($this->objects)
);

$next = $tableView->getNextLink(
    $this->request,
    empty($this->objects) ? null : \end($this->objects),
    $this->getData('hasNext') ?? false
);

echo $this->data['nav']->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-head"><?= $tableView->renderTitle($this->getHtml('Audits')); ?></div>
            <div class="slider">
            <table id="<?= $tableView->id; ?>" class="default sticky">
                <thead>
                <tr>
                    <td><?= $tableView->renderHeaderElement('id',  $this->getHtml('ID', '0', '0'), 'number'); ?>
                    <td><?= $tableView->renderHeaderElement('module', $this->getHtml('Module'), 'text'); ?>
                    <td><?= $tableView->renderHeaderElement('action', $this->getHtml('Action'), 'select',
                        [
                            'create' => $this->getHtml('CREATE'),
                            'modify' => $this->getHtml('UPDATE'),
                            'delete' => $this->getHtml('DELETE'),
                        ],
                        false // don't render sort
                    ); ?>
                    <td><?= $tableView->renderHeaderElement('type', $this->getHtml('Type'), 'number'); ?>
                    <td class="wf-100"><?= $tableView->renderHeaderElement('trigger', $this->getHtml('Trigger'), 'text'); ?>
                    <td><?= $tableView->renderHeaderElement('createdBy', $this->getHtml('By'), 'text'); ?>
                    <td><?= $tableView->renderHeaderElement('ref', $this->getHtml('Ref'), 'text', [], true, true, false); ?>
                    <td><?= $tableView->renderHeaderElement('createdAt', $this->getHtml('Date'), 'date'); ?>
                <tbody>
                <?php
                $count = 0;
                foreach ($audits as $key => $audit) : ++$count;
                    $url = UriFactory::build('{/base}/admin/audit/single?id=' . $audit->id); ?>
                    <tr tabindex="0" data-href="<?= $url; ?>">
                        <td><?= $audit->id; ?>
                        <td><?= $this->printHtml($audit->module); ?>
                        <td><?php if ($audit->old === null) : echo $this->getHtml('CREATE'); ?>
                            <?php elseif ($audit->old !== null && $audit->new !== null) : echo $this->getHtml('UPDATE'); ?>
                            <?php elseif ($audit->new === null) : echo $this->getHtml('DELETE'); ?>
                            <?php else : echo $this->getHtml('UNKNOWN'); ?>
                            <?php endif; ?>
                        <td><?= $this->printHtml((string) $audit->type); ?>
                        <td><?= $this->printHtml($audit->trigger); ?>
                        <td><a class="content" href="<?= UriFactory::build('{/base}/admin/account/settings?id=' . $audit->createdBy->id); ?>"><?= $this->printHtml(
                                $this->renderUserName('%3$s %2$s %1$s', [$audit->createdBy->name1, $audit->createdBy->name2, $audit->createdBy->name3, $audit->createdBy->login])
                            ); ?></a>
                        <td><?= $this->printHtml((string) $audit->ref); ?>
                        <td><?= $audit->createdAt->format('Y-m-d H:i:s'); ?>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="8" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
            <?php if ($this->getData('hasPrevious') || $this->getData('hasNext')) : ?>
                <div class="portlet-foot">
                    <?php if ($this->getData('hasPrevious')) : ?>
                        <a tabindex="0" class="button" href="<?= UriFactory::build($previous); ?>"><i class="fa fa-chevron-left"></i></a>
                    <?php endif; ?>
                    <?php if ($this->getData('hasNext')) : ?>
                        <a tabindex="0" class="button" href="<?= UriFactory::build($next); ?>"><i class="fa fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
