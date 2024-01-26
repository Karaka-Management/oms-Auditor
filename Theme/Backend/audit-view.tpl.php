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

use phpOMS\Message\Http\HttpHeader;
use phpOMS\Uri\UriFactory;
use phpOMS\Views\ViewAbstract;

/** @var \phpOMS\Views\View $this */

/** @var \Modules\Auditor\Models\Audit $audit */
$audit   = $this->data['audit'];
$headers = HttpHeader::getAllHeaders();

/** @var \phpOMS\Views\View $this */
echo $this->data['nav']->render();
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
                        <td><?php if ($audit->old === null) : echo $this->getHtml('CREATE'); ?>
                            <?php elseif ($audit->old !== null && $audit->new !== null) : echo $this->getHtml('UPDATE'); ?>
                            <?php elseif ($audit->new === null) : echo $this->getHtml('DELETE'); ?>
                            <?php else : echo $this->getHtml('UNKNOWN'); ?>
                            <?php endif; ?>
                    <tr>
                        <th><?= $this->getHtml('Type'); ?>
                        <td><?= $audit->type; ?>
                    <tr>
                        <th><?= $this->getHtml('Trigger'); ?>
                        <td><?= $audit->trigger; ?>
                    <tr>
                        <th><?= $this->getHtml('By'); ?>
                        <td><a href="<?= UriFactory::build('{/base}/admin/account/settings?{?}&id=' . $audit->createdBy->id); ?>"><?= $audit->createdBy->name1; ?> <?= $audit->createdBy->name2; ?></a>
                    <tr>
                        <th><?= $this->getHtml('Ref'); ?>
                        <td><?= $this->printHtml((string) $audit->ref); ?>
                    <tr>
                        <th><?= $this->getHtml('Date'); ?>
                        <td><?= $this->getDateTime($audit->createdAt, 'very_long'); ?>
                    <tr>
                        <th><?= $this->getHtml('Module'); ?>
                        <td><a href="<?= UriFactory::build('{/base}/admin/module/settings?{?}&id=' . $audit->module); ?>"><?= $audit->module; ?></a>
                    <tr>
                        <th><?= $this->getHtml('IP'); ?>
                        <td><?= \long2ip($audit->ip); ?>
                </table>
                <article>
                    <pre><?= \phpOMS\Utils\StringUtils::createDiffMarkup(
                            ViewAbstract::html(\stripos($audit->old ?? '', '{') === 0
                                ? \json_encode(\json_decode($audit->old), \JSON_PRETTY_PRINT)
                                : $audit->old ?? ''
                            ),
                            ViewAbstract::html(\stripos($audit->new ?? '', '{') === 0
                                ? \json_encode(\json_decode($audit->new), \JSON_PRETTY_PRINT)
                                : $audit->new ?? ''
                            ),
                            "\n"
                        ); ?>
                    </pre>
                </article>
            </div>
        </div>
    </div>
</div>