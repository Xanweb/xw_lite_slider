<?php

use HtmlObject\Link;
use Xanweb\LiteSlider\Html\Image;

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Package\XwLiteSlider\Block\XwLiteSlider\Controller $controller
 * @var string $sliderOptions JSON string
 * @var string $uniqID
 * @var string $maxWidth
 * @var array $items
 * @var array $pictureTag
 * @var bool $usePictureTag
 */
if ($items === []) {
    if ($controller->isInEditMode()): ?>
        <div class="col-sm-12">
            <div class="alert alert-info"><?php echo t('%s: Empty Block.', $controller->getBlockTypeName()) ?></div>
        </div>
    <?php
    endif;

    return;
}
?>
<div class="xw-module xw-ls-module">
    <div class="xw-ls" data-glide="glide<?php echo $uniqID ?>" data-autostart="true" data-options='<?php echo $sliderOptions ?>' <?php echo $maxWidth ? 'style="max-width: ' . $maxWidth . 'px;margin: auto"' : '' ?>>
        <div data-glide-el="track" class="xw-ls__track">
            <ul class="xw-ls__slides">
                <?php foreach ($items as $item):
                    $linkTag = null;
                    if (!empty($item['link'])) {
                        $linkTag = Link::create($item['link'], '', [
                            'class' => 'stretched-link', 'target' => $item['linkTarget'],
                        ]);
                    }

                    $thumbnailTag = null;
                    if (is_object($item['fileObj'])) {
                        $thumbnailTag = (new Image($item['fileObj'], [
                            'usePictureTag' => $usePictureTag,
                            'pictureTag' => $pictureTag,
                        ]))->getTag();

                        $thumbnailTag->addClass(['img-fluid', 'xw-ls__slide-img']);

                        if (trim($item['imageAlt'])) {
                            $thumbnailTag->alt($item['imageAlt']);
                        }
                    }
                    ?>
                    <li class="xw-ls__slide">
                        <?php if ($item['overlayColor']) { ?>
                            <div class="xw-ls__slide-overlay" style="background-color: <?php echo $item['overlayColor'] ?>"></div>
                        <?php } ?>
                        <?php echo $thumbnailTag ?>
                        <?php if (trim($item['title']) !== '' || trim($item['description']) !== ''): ?>
                            <div class="xw-ls__banner" <?php echo $item['boxColor'] ? "style='background-color:" . $item['boxColor'] . "'" : ''?>>
                                <h3 class="xw-ls__banner-title" <?php echo $item['titleColor'] ? "style='color:" . $item['titleColor'] . "'" : ''?>><?php echo h($item['title']) ?></h3>
                                <div class="xw-ls__banner-text">
                                    <?php echo $item['description'] ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php echo $linkTag ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="xw-ls__arrows" data-glide-el="controls">
            <button class="xw-ls__arrow xw-ls__arrow--left" data-glide-dir="<">
                <svg class="xw-ls__arrow-icon" viewBox="0 0 24 24">
                    <path d="M0 12l10.975 11 2.848-2.828-6.176-6.176H24v-3.992H7.646l6.176-6.176L10.975 1 0 12z"></path>
                </svg>
            </button>
            <button class="xw-ls__arrow xw-ls__arrow--right" data-glide-dir=">">
                <svg  class="xw-ls__arrow-icon" viewBox="0 0 24 24">
                    <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z"></path>
                </svg>
            </button>
        </div>

        <div class="xw-ls__bullets" data-glide-el="controls[nav]">
            <?php for ($idx = 0,$idxMax = count($items); $idx < $idxMax; $idx++): ?>
                <button class="xw-ls__bullet" data-glide-dir="=<?php echo $idx ?>"></button>
            <?php endfor; ?>
        </div>
    </div>
</div>
