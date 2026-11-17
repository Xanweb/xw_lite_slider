<?php

namespace Xanweb\Ls\JsLocalization\Controller\Frontend;

use Concrete\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Xanweb\Ls\JsLocalization\AssetLocalizationCollection;
use Xanweb\Ls\JsLocalization\Event\FrontendAssetLocalizationLoad;
use Xanweb\Ls\JsLocalization\Traits\AssetLocalizationControllerTrait;

class AssetLocalization extends Controller
{
    use AssetLocalizationControllerTrait;

    public function dispatchEvent(AssetLocalizationCollection $assetLocalization): AssetLocalizationCollection
    {
        $this->app['director']->dispatch(
            FrontendAssetLocalizationLoad::NAME,
            $event = new FrontendAssetLocalizationLoad($assetLocalization)
        );

        return $event->getAssetLocalization();
    }

    public function getJavascript(): Response
    {
        $content = 'window.xw_frontend=' . $this->assetLocalization->toJson() . ';';

        return $this->createJavascriptResponse($content);
    }
}
