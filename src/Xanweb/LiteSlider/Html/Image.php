<?php

namespace Xanweb\LiteSlider\Html;

use Concrete\Core\Entity\File\File;
use Concrete\Core\File\Image\Thumbnail\Type\Type;
use Concrete\Core\Html\Object\JavaScriptLazyImage;
use Concrete\Core\Html\Object\Picture;
use Concrete\Core\Page\Page;
use HtmlObject\Image as HtmlObjectImage;

class Image extends \Concrete\Core\Html\Image
{
    /**
     * Image constructor.
     *
     * @param File|null $f
     * @param null $options
     */
    public function __construct(File $f = null, $options = null)
    {
        if ($f === null) {
            return;
        }

        if ($options === null) {
            $options = [];
        } elseif (!is_array($options)) {
            $options = [
                'usePictureTag' => $options,
            ];
        }

        $options += [
            'usePictureTag' => null,
            'lazyLoadNative' => null,
            'lazyLoadJavaScript' => null,
            'pictureTag' => null,
        ];

        if ($options['usePictureTag'] !== null) {
            $this->usePictureTag = $options['usePictureTag'];
        } else {
            $this->loadPictureSettingsFromTheme();
        }

        if ($this->usePictureTag) {
            if (!isset($this->theme)) {
                $c = Page::getCurrentPage();
                $this->theme = $c->getCollectionThemeObject();
            }

            $sources = [];
            $fallbackSrc = $f->getRelativePath();
            $widthAttribute = $f->getAttribute('width');
            $heightAttribute = $f->getAttribute('height');
            if (!$fallbackSrc) {
                $fallbackSrc = $f->getURL();
            }

            if (is_array($options['pictureTag'])) {
                $imageMap = $options['pictureTag'];
            } else {
                $themeResponsiveImageMap = $this->theme->getThemeResponsiveImageMap();
                $imageMap = [];
                foreach ($themeResponsiveImageMap as $key => $value) {
                    $imageMap[$value] = $key;
                }
            }

            foreach ($imageMap as $breakpointWidth => $thumbnail) {
                $type = Type::getByHandle($thumbnail);
                if ($type !== null) {
                    $src = $f->getThumbnailURL($type->getBaseVersion());
                    $sources[] = ['src' => $src, 'width' => $breakpointWidth, 'widthAttribute' => $widthAttribute, 'heightAttribute' => $heightAttribute];
                    if ($breakpointWidth == 0) {
                        $fallbackSrc = $src;
                    }
                }
            }
            $this->tag = Picture::create($sources, $fallbackSrc, [], $options['lazyLoadNative'], $options['lazyLoadJavaScript']);
        } else {
            if (!$path = $f->getRelativePath()) {
                $path = $f->getURL();
            }

            if ($options['lazyLoadJavaScript']) {
                // Return a simple img element wrapped in "<noscript></noscript>" and an img element with the
                // image file path set to "data-src". Both img elements have the "loading" attribute optionally set to "lazy".
                $this->tag = JavaScriptLazyImage::create($path, [], $options['lazyLoadNative']);
            } else {
                // Return a simple img element.
                $this->tag = HtmlObjectImage::create($path);
                $this->tag->width((string) $f->getAttribute('width'));
                $this->tag->height((string) $f->getAttribute('height'));

                if ($options['lazyLoadNative']) {
                    $this->tag->loading('lazy');
                }
            }
        }
    }
}
