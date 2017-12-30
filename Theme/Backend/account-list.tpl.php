<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
/**
 * @var \phpOMS\Views\View $this
 */

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box wf-100">
            <table class="table red">
                <caption><?= $this->getHtml('Audits') ?></caption>
                <thead>
                <tr>
                    <td><?= $this->getHtml('ID', 0, 0); ?>
                    <td class="wf-100"><?= $this->getHtml('Name') ?>
                    <td class="wf-100"><?= $this->getHtml('Date') ?>
                <tfoot>
                <tr>
                    <td colspan="3">
                <tbody>
                <?php $count = 0; foreach ($audits as $key => $audit) : $count++;
                $url = \phpOMS\Uri\UriFactory::build('/{/lang}/backend/admin/audit/single?{?}&id=' . $audit->getId()); ?>
                    <tr data-href="<?= $url; ?>">
                        <td>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                <tr><td colspan="3" class="empty"><?= $this->getHtml('Empty', 0, 0); ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>