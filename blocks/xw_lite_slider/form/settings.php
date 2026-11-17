<?php

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Core\Block\View\BlockView $view
 * @var Xanweb\Ls\HtmlHelper\Service\Form $form
 * @var Xanweb\Ls\HtmlHelper\Service\Form\IosTogglerWidget $togglerWidget
 * @var int $maxWidth
 * @var array $thumbnailOptions
 * @var bool $useCustomThumbnailsSetting
 * @var string $largeDesktopThumbnail
 * @var string $desktopThumbnail
 * @var string $tabletThumbnail
 * @var string $phoneThumbnail
 */
?>

<fieldset>
    <legend><?php echo t('Settings') ?></legend>

    <div class="row">
        <div class="col-7">
            <div class="form-group">
                <?php echo $form->label('maxWidth', t('Maximum Slide Width (0 means no limit)')) ?>
                <div class="input-group" style="width: 150px">
                    <?php echo $form->number('maxWidth', $maxWidth, ['min' => 0, 'max' => 9999]) ?>
                    <span class="input-group-text"><?php echo t('px') ?></span>
                </div>
            </div>
        </div>
    </div>

    <h5><?php echo t('Thumbnails') ?></h5>
    <div class="row">
        <div class="col-12 col-sm-9">
            <div class="form-group ios__toggler-form-group">
                <?php echo $form->label($view->field('useCustomThumbnailsSetting'), t('Use custom thumbnails settings')) ?>
                <div class="float-end">
                    <?php $togglerWidget->output($view->field('useCustomThumbnailsSetting'), 1, $useCustomThumbnailsSetting, ['v-model' => 'useCustomThumbnailsSettings']) ?>
                </div>
            </div>
        </div>
    </div>
    <transition name="concrete-nav-tab-content-switch">
        <table class="table table-bordered table-condensed" v-show="useCustomThumbnailsSettings">
            <tbody>
                <tr>
                    <td style="vertical-align: middle;width: 230px;"><i class="fas fa-desktop fa-lg" style="padding: 0 1px;" aria-hidden="true"></i> <?php echo t('Large Desktop') ?></td>
                    <td><?php echo $form->select('largeDesktopThumbnail', $thumbnailOptions, $largeDesktopThumbnail ?? 'large', ['classes' => 'form-select w-100', 'class' => 'form-select w-100']) ?></td>
                </tr>
                <tr>
                    <td style="vertical-align: middle;"><i class="fas fa-laptop fa-lg" style="padding: 0 1px;" aria-hidden="true"></i> <?php echo t('Desktop') ?></td>
                    <td><?php echo $form->select('desktopThumbnail', $thumbnailOptions, $desktopThumbnail ?? 'large', ['classes' => 'form-select w-100', 'class' => 'form-select w-100']) ?></td>
                </tr>
                <tr>
                    <td style="vertical-align: middle;"><i class="fas fa-tablet fa-lg" style="padding: 0 6px;" aria-hidden="true"></i> <?php echo t('Tablet') ?></td>
                    <td><?php echo $form->select('tabletThumbnail', $thumbnailOptions, $tabletThumbnail ?? 'medium', ['classes' => 'form-select w-100', 'class' => 'form-select w-100']) ?></td>
                </tr>
                <tr>
                    <td style="vertical-align: middle;"><i class="fas fa-mobile fa-lg" style="padding: 0 8px;" aria-hidden="true"></i> <?php echo t('Phone') ?></td>
                    <td><?php echo $form->select('phoneThumbnail', $thumbnailOptions, $phoneThumbnail ?? 'small', ['classes' => 'form-select w-100', 'class' => 'form-select w-100']) ?></td>
                </tr>
            </tbody>
        </table>
    </transition>
</fieldset>
