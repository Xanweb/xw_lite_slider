<?php

namespace Xanweb\Ls\JsLocalization\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Xanweb\Ls\JsLocalization\AssetLocalizationCollection;

class FrontendAssetLocalizationLoad extends Event
{
    /**
     * Event Name
     *
     * @var string
     */
    public const NAME = 'on_frontend_asset_localization_load';

    private AssetLocalizationCollection $assetLocalization;

    public function __construct(AssetLocalizationCollection $assetLocalization)
    {
        $this->assetLocalization = $assetLocalization;
    }

    /**
     * @return AssetLocalizationCollection
     */
    public function getAssetLocalization(): AssetLocalizationCollection
    {
        return $this->assetLocalization;
    }
}
