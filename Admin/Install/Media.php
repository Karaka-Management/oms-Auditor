<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\Auditor\Admin\Install
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Auditor\Admin\Install;

use Model\Setting;
use Model\SettingMapper;
use phpOMS\Application\ApplicationAbstract;

/**
 * Media class.
 *
 * @package Modules\Auditor\Admin\Install
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class Media
{
    /**
     * Install media providing
     *
     * @param ApplicationAbstract $app  Application
     * @param string              $path Module path
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function install(ApplicationAbstract $app, string $path) : void
    {
        $media = \Modules\Media\Admin\Installer::installExternal($app, ['path' => __DIR__ . '/Media.install.json']);

        $defaultTemplate = \reset($media['upload'][0]);

        $setting = new Setting();
        SettingMapper::create()->execute($setting->with(0, 'default_pdf_template', (string) $defaultTemplate->getId(), '\\d+', 1, 'Auditor'));
    }
}
