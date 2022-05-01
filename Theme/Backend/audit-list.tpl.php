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

use phpOMS\Uri\UriFactory;

/**
 * @var \phpOMS\Views\View            $this
 * @var \Modules\Audit\Models\Audit[] $audits
 */
$audits = $this->getData('audits') ?? [];

$previous = empty($audits) || !$this->getData('hasPrevious')
    ? '{/prefix}admin/audit/list'
    : '{/prefix}admin/audit/list?{?}&id='
        . \reset($audits)->getId()
        . '&ptype=p';
$next     = empty($audits)
    ? '{/prefix}admin/audit/list'
    : '{/prefix}admin/audit/list?{?}&id='
        . ($this->getData('hasNext') ? \end($audits)->getId() : $this->request->getData('id'))
        . '&ptype=n';

$tableView     = $this->getData('tableView');
$tableView->id = 'auditList';

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-head">
                <span>
                    <a href="<?= UriFactory::build($previous); ?>"><i class="fa fa-chevron-left btn"></i></a>
                    <?= $this->getHtml('Audits'); ?>
                    <a href="<?= UriFactory::build($next); ?>"><i class="fa fa-chevron-right btn"></i></a>
                </span>
                <?= $tableView->renderExport(); ?>
            </div>
            <div class="slider">
            <table id="<?= $tableView->id; ?>" class="default sticky">
                <thead>
                <tr>
                    <td><?= $tableView->renderHeaderElement(
                        $this->getHtml('ID', '0', '0'),
                        'number'
                    ); ?>
                    <td><?= $tableView->renderHeaderElement(
                        $this->getHtml('Module'),
                        'text'
                    ); ?>
                    <td><?= $tableView->renderHeaderElement(
                        $this->getHtml('Action'),
                        'select',
                        [
                            'create' => $this->getHtml('CREATE'),
                            'modify' => $this->getHtml('UPDATE'),
                            'delete' => $this->getHtml('DELETE'),
                        ]
                    ); ?>
                    <td><?= $tableView->renderHeaderElement(
                        $this->getHtml('Type'),
                        'number'
                    ); ?>
                    <td class="wf-100"><?= $tableView->renderHeaderElement(
                        $this->getHtml('Trigger'),
                        'text'
                    ); ?>
                    <td><?= $tableView->renderHeaderElement(
                        $this->getHtml('By'),
                        'text'
                    ); ?>
                    <td><?= $tableView->renderHeaderElement(
                        $this->getHtml('Ref'),
                        'text'
                    ); ?>
                    <td><?= $tableView->renderHeaderElement(
                        $this->getHtml('Date'),
                        'date'
                    ); ?>
                <tbody>
                <?php $count = 0;
                foreach ($audits as $key => $audit) : ++$count;
                    $url = UriFactory::build('{/prefix}admin/audit/single?{?}&id=' . $audit->getId()); ?>
                    <tr tabindex="0" data-href="<?= $url; ?>">
                        <td><?= $audit->getId(); ?>
                        <td><?= $this->printHtml($audit->getModule()); ?>
                        <td><?php if ($audit->getOld() === null) : echo $this->getHtml('CREATE'); ?>
                            <?php elseif ($audit->getOld() !== null && $audit->getNew() !== null) : echo $this->getHtml('UPDATE'); ?>
                            <?php elseif ($audit->getNew() === null) : echo $this->getHtml('DELETE'); ?>
                            <?php else : echo $this->getHtml('UNKNOWN'); ?>
                            <?php endif; ?>
                        <td><?= $audit->getType(); ?>
                        <td><?= $audit->getTrigger(); ?>
                        <td><a class="content" href="<?= UriFactory::build('{/prefix}admin/account/settings?id=' . $audit->createdBy->getId()); ?>"><?= $this->printHtml(
                                $this->renderUserName('%3$s %2$s %1$s', [$audit->createdBy->name1, $audit->createdBy->name2, $audit->createdBy->name3, $audit->createdBy->login])
                            ); ?></a>
                        <td><?= $this->printHtml($audit->getRef()); ?>
                        <td><?= $audit->createdAt->format('Y-m-d H:i:s'); ?>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="8" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
            <div class="portlet-foot">
                <a tabindex="0" class="button" href="<?= UriFactory::build($previous); ?>"><i class="fa fa-chevron-left"></i></a>
                <a tabindex="0" class="button" href="<?= UriFactory::build($next); ?>"><i class="fa fa-chevron-right"></i></a>
            </div>
        </div>
    </div>
</div>
