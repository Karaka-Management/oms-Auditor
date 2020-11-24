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

use phpOMS\Message\Http\HttpHeader;
use phpOMS\Views\ViewAbstract;

/** @var \phpOMS\Views\View $this */

/** @var \Modules\Auditor\Models\Audit $audit */
$audit   = $this->getData('audit');
$headers = HttpHeader::getAllHeaders();

/** @var \phpOMS\Views\View $this */
echo $this->getData('nav')->render();
?>

<?php if (isset($headers['Referer'])) : ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <a tabindex="0" class="button" href="<?= $headers['Referer']; ?>"><?= $this->getHtml('Back', '0', '0'); ?></a>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-body">
                <table class="list">
                    <tr>
                        <th>Type
                        <td>
                            <?php if ($audit->getOld() === null) : echo 'CREATE'; ?>
                            <?php elseif ($audit->getOld() !== null && $audit->getNew() !== null) : echo 'UPDATE'; ?>
                            <?php elseif ($audit->getNew() === null) : echo 'DELETE'; ?>
                            <?php else : echo 'UNKNOWN'; ?>
                            <?php endif; ?>

                    <tr>
                        <th>Created By
                        <td><?= $audit->createdBy->name1; ?>
                    <tr>
                        <th>Created At
                        <td><?= $this->getDateTime($audit->createdAt, 'long'); ?>
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