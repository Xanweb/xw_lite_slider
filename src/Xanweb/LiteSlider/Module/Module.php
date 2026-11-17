<?php

namespace Xanweb\LiteSlider\Module;

use Xanweb\Ls\VueItemList\ServiceProvider as ItemListServiceProvider;
use Xanweb\LiteSlider\Asset\Provider;
use Xanweb\LsModule\Module as AbstractModule;

class Module extends AbstractModule
{
    /**
     * {@inheritdoc}
     *
     * @see AbstractModule::pkgHandle()
     */
    public static function pkgHandle(): string
    {
        return 'xw_lite_slider';
    }

    /**
     * {@inheritdoc}
     *
     * @see AbstractModule::getServiceProviders()
     */
    protected static function getServiceProviders(): array
    {
        return [
            ItemListServiceProvider::class,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see AbstractModule::getAssetProviders()
     */
    protected static function getAssetProviders(): array
    {
        return [
            Provider::class,
        ];
    }
}
