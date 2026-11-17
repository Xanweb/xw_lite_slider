<?php

namespace Xanweb\LiteSlider\Asset;

use Xanweb\LsModule\Asset\Provider as AssetProvider;

class Provider extends AssetProvider
{
    /**
     * {@inheritdoc}
     *
     * @see AssetProvider::getAssets()
     */
    public function getAssets(): array
    {
        $options = ['combine' => true, 'minify' => false];

        return [
            'xan/glideJS' => [
                ['javascript', 'js/glide.js', $options, $this->pkg],
            ],
            'xw/lite-slider' => [
                ['javascript', 'js/lite-slider.js', $options, $this->pkg],
                ['css', 'css/lite-slider.css', $options, $this->pkg],
            ],
            'xw/lite-slider/theme' => [
                ['css', 'css/lite-slider-theme.css', $options, $this->pkg],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see AssetProvider::getAssetGroups()
     */
    public function getAssetGroups(): array
    {
        return [
            'xw/lite-slider' => [
                [
                    ['javascript', 'jquery'],
                    ['javascript', 'xan/glideJS'],
                    ['javascript', 'xw/lite-slider'],
                    ['css', 'xw/lite-slider'],
                    ['css', 'xw/lite-slider/theme'],
                ],
            ],
        ];
    }
}
