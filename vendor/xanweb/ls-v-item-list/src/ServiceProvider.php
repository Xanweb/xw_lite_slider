<?php

namespace Xanweb\Ls\VueItemList;

use Concrete\Core\Asset\AssetList as CoreAssetList;
use Xanweb\LiteSlider\Module\Module;
use Xanweb\Ls\JsLocalization\Event\BackendAssetLocalizationLoad;
use Xanweb\Ls\JsLocalization\ServiceProvider as JsLocalizationServiceProvider;
use Xanweb\LsCommon\Service\Provider as FoundationProvider;

class ServiceProvider extends FoundationProvider
{
    public function _register(): void
    {
        $jsLocalizationSvcProvider = new JsLocalizationServiceProvider($this->app);
        $jsLocalizationSvcProvider->register();

        $this->registerListeners();
        $this->registerAssets();
    }

    private function registerListeners(): void
    {
        $this->app['director']->addListener(BackendAssetLocalizationLoad::NAME, function (BackendAssetLocalizationLoad $event) {
            $event->getAssetLocalization()->mergeWith([
                'i18n' => [
                    'confirm' => t('Are you sure?'),
                    'maxItemsExceeded' => t('Max items exceeded, you cannot add any more items.'),
                    'pageNotFound' => t('Page not found'),
                    'colorPicker' => [
                        'cancelText' => t('Cancel'),
                        'chooseText' => t('Choose'),
                        'togglePaletteMoreText' => t('more'),
                        'togglePaletteLessText' => t('less'),
                        'noColorSelectedText' => t('No Color Selected'),
                        'clearText' => t('Clear Color Selection'),
                    ]
                ],
                'editor' => [
                    'initRichTextEditor' => $this->getInitRichTextEditorJSFunction(),
                    'destroyRichTextEditor' => $this->getDestroyRichTextEditorJSFunction(),
                ],
            ]);
        });
    }

    private function getDestroyRichTextEditorJSFunction()
    {
        return $this->app['config']->get('xanweb.item_list.editor.destroyRichTextEditorJSFunc', function () {
            return <<<EOT
function (editor) {
    var id = editor.attr('id');
    if (CKEDITOR.instances[id] !== undefined) {
        CKEDITOR.instances[id].destroy();
    }

    editor.remove();
    $('#cke_' + id).remove();
}
EOT;
        });
    }

    private function getInitRichTextEditorJSFunction()
    {
        return $this->app['config']->get('xanweb.item_list.editor.initRichTextEditorJSFunc', function () {
            return $this->app['editor']->getEditorInitJSFunction();
        });
    }

    protected function registerAssets(): void
    {
        $al = CoreAssetList::getInstance();
        $al->registerMultiple([
            'xw-ls/v-item-list' => [
                ['javascript', 'vendor/xanweb/ls-v-item-list/js/v-item-list.js', ['minify' => false], Module::pkg()],
                ['css', 'vendor/xanweb/ls-v-item-list/css/v-item-list.css', ['minify' => false], Module::pkg()],
            ],
        ]);
        $al->registerGroupMultiple([
            'xw-ls/v-item-list' => [
                [
                    ['javascript', 'xw-ls/v-item-list'],
                    ['javascript-localized', 'xw-ls/backend'],
                    ['css', 'xw-ls/v-item-list'],
                ],
            ],
        ]);
    }
}
