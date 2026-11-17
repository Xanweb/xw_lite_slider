<?php

namespace Xanweb\LiteSlider;

use Concrete\Core\Config\Repository\Repository as Config;
use Illuminate\Support\Arr;
use Xanweb\LsCommon\Traits\SingletonTrait;

class Defaults
{
    use SingletonTrait {
        get as instance;
    }

    protected array $data;
    private static Config $config;

    public function __construct()
    {
        $this->data = [
            'sliderOptions' => [
                'autoplay' => 0,
                'autoplayInterval' => 2000,
                'animationType' => 'slide',
                'animation' => 'cubic-bezier(0.165, 0.840, 0.440, 1.000)',
                'animationDuration' => 800,
                'pauseOnHover' => 0,
                'loop' => 0,
                'arrows' => 1,
                'bullets' => 0,
            ],
            'colorPickerOptions' => [
                'type' => 'color',
                'className' => 'ccm-widget-colorpicker',
                'showInitial' => true,
                'showInput' => true,
                'allowEmpty' => true,
                'cancelText' => t('Cancel'),
                'chooseText' => t('Choose'),
                'togglePaletteMoreText' => t('more'),
                'togglePaletteLessText' => t('less'),
                'noColorSelectedText' => t('No Color Selected'),
                'preferredFormat' => 'rgb',
                'showAlpha' => true,
                'clearText' => t('Clear Color Selection'),
                'appendTo' => '.ui-dialog',
            ],
        ];
    }

    /**
     * Get Config.
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function get(string $key)
    {
        return self::config()->get("xanweb.lite_slider.$key", fn () => Arr::get(static::instance()->data, $key));
    }

    protected static function config(): Config
    {
        return self::$config ??= app('config');
    }
}
