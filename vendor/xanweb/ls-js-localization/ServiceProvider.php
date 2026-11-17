<?php

namespace Xanweb\Ls\JsLocalization;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Support\Facade\Route;
use Illuminate\Support\Str;
use Xanweb\Ls\JsLocalization\Controller\Backend\AssetLocalization as BackendAssetLocalization;
use Xanweb\Ls\JsLocalization\Controller\Frontend\AssetLocalization as FrontendAssetLocalization;
use Xanweb\LsCommon\Routing\Middleware\LocalizedMiddleware;
use Xanweb\LsCommon\Service\Provider as FoundationProvider;

class ServiceProvider extends FoundationProvider
{
    protected function _register(): void
    {
        $router = Route::getFacadeRoot();
        $router->get('/xw-ls/backend/js', BackendAssetLocalization::class . '::getJavascript');

        $router
            ->get('/xw-ls/{_locale}/frontend/js', FrontendAssetLocalization::class . '::getJavascript')
            ->addMiddleware(LocalizedMiddleware::class);

        $this->registerAssets();
    }

    private function registerAssets(): void
    {
        $_locale = Str::lower(str_replace('_', '-', Localization::activeLocale()));

        $al = AssetList::getInstance();
        $al->register('javascript-localized', 'xw-ls/backend', '/xw-ls/backend/js', ['combine' => false, 'minify' => false]);
        $al->register('javascript-localized', 'xw-ls/frontend', "/xw-ls/$_locale/frontend/js", ['combine' => false, 'minify' => false]);
    }
}
