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

use phpOMS\Message\Http\HttpHeader;
use phpOMS\Uri\UriFactory;
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
                        <th><?= $this->getHtml('Action'); ?>
                        <td><?php if ($audit->getOld() === null) : echo $this->getHtml('CREATE'); ?>
                            <?php elseif ($audit->getOld() !== null && $audit->getNew() !== null) : echo $this->getHtml('UPDATE'); ?>
                            <?php elseif ($audit->getNew() === null) : echo $this->getHtml('DELETE'); ?>
                            <?php else : echo $this->getHtml('UNKNOWN'); ?>
                            <?php endif; ?>
                    <tr>
                        <th><?= $this->getHtml('Type'); ?>
                        <td><?= $audit->getType(); ?>
                    <tr>
                        <th><?= $this->getHtml('By'); ?>
                        <td><a href="<?= UriFactory::build('{/prefix}admin/account/settings?{?}&id=' . $audit->createdBy->getId()); ?>"><?= $audit->createdBy->name1; ?></a>
                    <tr>
                        <th><?= $this->getHtml('Date'); ?>
                        <td><?= $this->getDateTime($audit->createdAt, 'very_long'); ?>
                    <tr>
                        <th><?= $this->getHtml('Module'); ?>
                        <td><a href="<?= UriFactory::build('{/prefix}admin/module/settings?{?}&id=' . $audit->getModule()); ?>"><?= $audit->getModule(); ?></a>
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