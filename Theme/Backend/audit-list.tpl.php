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

use phpOMS\Uri\UriFactory;

/**
 * @var \phpOMS\Views\View            $this
 * @var \Modules\Audit\Models\Audit[] $audits
 */
$audits = $this->getData('audits') ?? [];

$previous = empty($audits) ? '{/prefix}admin/audit/list' : '{/prefix}admin/audit/list?{?}&id=' . \reset($audits)->getId() . '&ptype=p';
$next     = empty($audits) ? '{/prefix}admin/audit/list' : '{/prefix}admin/audit/list?{?}&id=' . \end($audits)->getId() . '&ptype=n';

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Audits'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <div class="slider">
            <table id="auditList" class="default sticky">
                <thead>
                <tr>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <label for="auditList-sort-1">
                            <input type="radio" name="auditList-sort" id="auditList-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="auditList-sort-2">
                            <input type="radio" name="auditList-sort" id="auditList-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Module'); ?>
                        <label for="auditList-sort-3">
                            <input type="radio" name="auditList-sort" id="auditList-sort-3">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="auditList-sort-4">
                            <input type="radio" name="auditList-sort" id="auditList-sort-4">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Action'); ?>
                        <label for="auditList-sort-5">
                            <input type="radio" name="auditList-sort" id="auditList-sort-5">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="auditList-sort-6">
                            <input type="radio" name="auditList-sort" id="auditList-sort-6">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Type'); ?>
                        <label for="auditList-sort-7">
                            <input type="radio" name="auditList-sort" id="auditList-sort-7">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="auditList-sort-8">
                            <input type="radio" name="auditList-sort" id="auditList-sort-8">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td class="wf-100"><?= $this->getHtml('Trigger'); ?>
                        <label for="auditList-sort-9">
                            <input type="radio" name="auditList-sort" id="auditList-sort-9">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="auditList-sort-10">
                            <input type="radio" name="auditList-sort" id="auditList-sort-10">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('By'); ?>
                        <label for="auditList-sort-13">
                            <input type="radio" name="auditList-sort" id="auditList-sort-13">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="auditList-sort-14">
                            <input type="radio" name="auditList-sort" id="auditList-sort-14">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Ref'); ?>
                        <label for="auditList-sort-15">
                            <input type="radio" name="auditList-sort" id="auditList-sort-15">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="auditList-sort-16">
                            <input type="radio" name="auditList-sort" id="auditList-sort-16">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Date'); ?>
                        <label for="auditList-sort-17">
                            <input type="radio" name="auditList-sort" id="auditList-sort-17">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="auditList-sort-18">
                            <input type="radio" name="auditList-sort" id="auditList-sort-18">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                <tbody>
                <?php $count = 0; foreach ($audits as $key => $audit) : ++$count;
                $url         = UriFactory::build('{/prefix}admin/audit/single?{?}&id=' . $audit->getId()); ?>
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
                        <td><a class="content" href="<?= UriFactory::build('{/prefix}admin/account/settings?id=' . $audit->createdBy->getId()); ?>"><?= $this->printHtml($audit->createdBy->login); ?></a>
                        <td><?= $this->printHtml($audit->getRef()); ?>
                        <td><?= $audit->createdAt->format('Y-m-d H:i:s'); ?>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="8" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
            <div class="portlet-foot">
                <a tabindex="0" class="button" href="<?= UriFactory::build($previous); ?>"><?= $this->getHtml('Previous', '0', '0'); ?></a>
                <a tabindex="0" class="button" href="<?= UriFactory::build($next); ?>"><?= $this->getHtml('Next', '0', '0'); ?></a>
            </div>
        </div>
    </div>
</div>
