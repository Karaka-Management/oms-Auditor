<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\Auditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use phpOMS\Views\ViewAbstract;

/** @var \Modules\Auditor\Models\Audit $audit */
$audit = $this->getData('audit');

/** @var \phpOMS\Views\View $this */
echo $this->getData('nav')->render();
?>

<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-body">
                <table class="list">
                    <tr>
                        <th>Created By
                        <td><?= $audit->getCreatedBy()->getName1(); ?>
                    <tr>
                        <th>Created At
                        <td><?= $this->getDateTime($audit->getCreatedAt(), 'long'); ?>
                    <tr>
                        <th>Module
                        <td><?= $audit->getModule(); ?>
                    <tr>
                        <th>IP
                        <td><?= \long2ip($audit->getIp()); ?>
                </table>
                <article>
                    <pre><?= \phpOMS\Utils\StringUtils::createDiffMarkup(
                            ViewAbstract::html($audit->getOld() ?? ''),
                            ViewAbstract::html($audit->getNew() ?? ''),
                            "\n"
                        ); ?>
                    </pre>
                </article>
            </div>
        </div>
    </div>
</div>