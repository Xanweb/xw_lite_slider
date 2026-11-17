<?php

namespace Concrete\Package\XwLiteSlider\Block\XwLiteSlider;

use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\File\File;
use Concrete\Core\File\Image\Thumbnail\Type\Type as ThumbnailType;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Url;
use Illuminate\Support\Str;
use Xanweb\Ls\Block\ItemListBlockController;
use Xanweb\Ls\Block\Traits\BlockControllerTrait;
use Xanweb\Ls\HtmlHelper\Form\LinkType;
use Xanweb\LiteSlider\Defaults;
use Xanweb\Ls\HtmlHelper\Service\Form;

class Controller extends ItemListBlockController
{
    use BlockControllerTrait;

    protected $btTable = 'btXwLiteSlider';
    protected $btExportTables = ['btXwLiteSliderSlider', 'btXwLiteSliderEntries'];
    protected $btInterfaceWidth = '650';
    protected $btInterfaceHeight = '600';
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $helpers = [];
    protected string $sliderOptions;
    protected int $maxWidth = 0;
    protected bool $useCustomThumbnailsSetting = false;
    protected array $deviceResponsiveMap = [
        'largeDesktop' => '1200px',
        'desktop' => '992px',
        'tablet' => '768px',
        'phone' => '0',
    ];

    public function getBlockTypeName(): string
    {
        return t('Lite Slider');
    }

    public function getBlockTypeDescription(): string
    {
        return t('Responsive light slider.');
    }

    public function add(): void
    {
        $this->set('rows', [
            [
                'id' => Str::random(4),
                'fID' => null,
                'linkExtern' => null,
                'internalLinkCID' => null,
                'title' => '',
                'description' => '',
                'imageAlt' => '',
                'titleColor' => null,
                'boxColor' => 'rgba(0, 0, 0, 0.6)',
                'overlayColor' => null,
                'linkType' => LinkType::NO_LINK,
            ],
        ]);

        $this->set('sliderOptions', Defaults::get('sliderOptions'));
        $this->setAddEditCommons();
    }

    /**
     * @noinspection JsonEncodingApiUsageInspection
     */
    public function edit(): void
    {
        $items = $this->getItems(['sortOrder' => 'ASC']);
        foreach ($items as &$row) {
            $row['description'] = LinkAbstractor::translateFromEditMode($row['description']);
            $row['linkType'] = LinkType::getTypeForItem($row);
        }

        unset($row);

        $this->set('rows', $items);
        $this->set('sliderOptions', isset($this->sliderOptions) ? json_decode($this->sliderOptions, true) : []);
        $this->setAddEditCommons();
    }

    public function registerViewAssets($outputContent = '')
    {
        $this->requireAsset('xw/lite-slider');
    }

    public function view()
    {
        $items = $this->getItems(['sortOrder' => 'ASC']);
        foreach ($items as &$item) {
            $item['fileObj'] = File::getByID((int) $item['fID']);
            $item['description'] = LinkAbstractor::translateFrom($item['description']);

            $link = $item['linkExtern'] ?? '';
            $target = '_blank';
            if ($item['internalLinkCID'] > 0 && empty(trim($link))) {
                $page = Page::getByID((int) $item['internalLinkCID'], 'ACTIVE');
                $link = (string) Url::to($page);
                $target = '_self';
            }

            $item['link'] = $link;
            $item['linkTarget'] = $target;
        }

        $this->set('items', $items);
        $this->set('uniqID', $this->getUniqueId());
        $this->set('pictureTag', $pictureMap = $this->getPictureMap());
        $this->set('usePictureTag', true);
    }

    /**
     * {@inheritdoc}
     */
    public function save($args): void
    {
        $args += [
            'useCustomThumbnailsSetting' => 0,
        ];

        /** @noinspection JsonEncodingApiUsageInspection */
        $args['sliderOptions'] = json_encode(
            array_merge([
                'autoplay' => 0,
                'pauseOnHover' => 0,
                'loop' => 0,
                'arrows' => 0,
                'bullets' => 0,
            ], (array) $args['sliderOptions'])
        );

        $args['maxWidth'] = max((int) $args['maxWidth'], 0);

        LinkType::sanitizeData($args);

        parent::save($args);
    }

    protected function _validate(?array $args, ErrorList $e): void
    {
        if (!isset($args['sortOrder'])) {
            $e->add(t('Please add at least one slider entry.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getItemListTable(): string
    {
        return 'btXwLiteSliderEntries';
    }

    protected function setAddEditCommons(): void
    {
        $this->set('form', $this->app->make(Form::class));
        $this->set('togglerWidget', $this->app->make(Form\IosTogglerWidget::class));
        $this->set('uih', $this->app['helper/concrete/ui']);
        $this->set('uniqID', $this->getUniqueId());
        $this->set('animationTypes', [
            'slide' => t('Slide'),
            'fade' => t('Fade'),
        ]);

        $this->set('animations', [
            'linear' => t('Linear'),
            'ease' => t('Ease'),
            'ease-in' => t('Ease In'),
            'ease-out' => t('Ease Out'),
            'ease-in-out' => t('Ease In-Out'),
            'cubic-bezier(0.165, 0.840, 0.440, 1.000)' => t('Ease Out Cubic'),
            'cubic-bezier(0.680, -0.550, 0.265, 1.550)' => t('Bounce'),
        ]);

        $this->set('linkTypes', LinkType::getList());

        //For more customization use options as config
        $this->set('colorPickerOptions', Defaults::get('colorPickerOptions'));
        $this->set('thumbnailOptions', $this->getThumbnailOptions());
        $this->set('maxWidth', $this->maxWidth);
        $this->set('useCustomThumbnailsSetting', $this->useCustomThumbnailsSetting);
        $this->requireEditModeAssets();
    }

    protected function getThumbnailOptions(): array
    {
        $options = [];
        $list = ThumbnailType::getList();
        foreach ($list as $thumbnail) {
            $options[$thumbnail->getHandle()] = $thumbnail->getName();
        }

        return $options;
    }

    protected function getPictureMap(): ?array
    {
        if (!$this->useCustomThumbnailsSetting) {
            return null;
        }

        $map = [];
        foreach ($this->deviceResponsiveMap  as $device => $deviceWidth) {
            if ($this->{"{$device}Thumbnail"}) {
                $map[$deviceWidth] = $this->{"{$device}Thumbnail"};
            }
        }

        return $map;
    }

    protected function requireEditModeAssets(): void
    {
        $this->app['editor']->requireEditorAssets();
        $this->requireAsset('xw-ls/v-item-list');
    }
}
