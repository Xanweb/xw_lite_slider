<?php

namespace Concrete\Package\XwLiteSlider;

use Concrete\Core\Database\EntityManager\Provider\ProviderInterface;
use Concrete\Core\Entity\Package;
use Concrete\Core\Package\Package as PackageController;
use Xanweb\LiteSlider\Module\Module;
use Xanweb\LsModule\Installer;
use Xanweb\LsModule\Uninstaller;

class Controller extends PackageController implements ProviderInterface
{
    protected $pkgHandle = 'xw_lite_slider';
    protected $appVersionRequired = '9.0.0';
    protected $pkgVersion = '2.0.2';
    protected $pkgAutoloaderRegistries = [
        'vendor/xanweb/ls-common' => 'Xanweb\LsCommon',
        'vendor/xanweb/ls-js-localization' => 'Xanweb\Ls\JsLocalization',
        'vendor/xanweb/ls-html-helpers/src' => 'Xanweb\Ls\HtmlHelper',
        'vendor/xanweb/ls-v-item-list/src' => 'Xanweb\Ls\VueItemList',
        'vendor/xanweb/ls-request' => 'Xanweb\Ls\Request',
        'vendor/xanweb/ls-block' => 'Xanweb\Ls\Block',
        'vendor/xanweb/ls-module' => 'Xanweb\LsModule',
        'src/Xanweb/LiteSlider' => 'Xanweb\LiteSlider',
    ];

    public function getPackageDescription()
    {
        return t('Responsive light slider');
    }

    public function getPackageName()
    {
        return t('Light Slider');
    }

    public function on_start()
    {
        Module::boot();
    }

    public function validate_install()
    {
        if (PHP_VERSION_ID < 70400) {
            return $this->app['error']->addError(t('This package requires PHP version %s or greater.', '7.4.0'));
        }
    }

    public function install()
    {
        $pkg = parent::install();

        $this->installComponents($pkg);

        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();

        $this->installComponents($this->getPackageEntity());
    }

    public function uninstall()
    {
        parent::uninstall();

        Uninstaller::dropTables('btXwLiteSlider', 'btXwLiteSliderEntries');
    }

    /**
     * {@inheritdoc}
     */
    public function getDrivers()
    {
        return [];
    }

    /**
     * Install or Update package components.
     *
     * @param Package $pkg
     */
    private function installComponents(Package $pkg)
    {
        $installer = new Installer($pkg);
        $installer->installBlockType('xw_lite_slider', 'multimedia');
    }
}
