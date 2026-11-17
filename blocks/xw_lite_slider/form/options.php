<?php

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Core\Block\View\BlockView $view
 * @var Xanweb\Ls\HtmlHelper\Service\Form $form
 * @var Xanweb\Ls\HtmlHelper\Service\Form\IosTogglerWidget $togglerWidget
 * @var array $animations
 * @var array $animationTypes
 * @var array $sliderOptions
 */
?>

<fieldset>
    <legend><?php echo t('Animation') ?></legend>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <?php echo $form->label($view->field('sliderOptions[animationType]'), t('Animation Type')) ?>
                <?php echo $form->select($view->field('sliderOptions[animationType]'), $animationTypes, $sliderOptions['animationType'], ['classes' => 'form-select', 'class' => 'form-select']) ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <?php echo $form->label($view->field('sliderOptions[animation]'), t('Animation Effect')) ?>
                <?php echo $form->select($view->field('sliderOptions[animation]'), $animations, $sliderOptions['animation'], ['classes' => 'form-select', 'class' => 'form-select']) ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->label($view->field('sliderOptions[animationDuration]'), t('Animation Transition Speed')) ?>
        <div class="input-group" style="width: 180px">
            <?php echo $form->number($view->field('sliderOptions[animationDuration]'), $sliderOptions['animationDuration'], ['min' => '1', 'max' => '99999']) ?>
            <span class="input-group-text"><?php echo t('ms') ?></span>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo t('Autoplay') ?></legend>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group ios__toggler-form-group">
                <?php echo $form->label($view->field('sliderOptions[autoplay]'), t('Autoplay')) ?>
                <div class="float-end">
                    <?php $togglerWidget->output($view->field('sliderOptions[autoplay]'), 1, $sliderOptions['autoplay']); ?>
                </div>
            </div>
            <div class="form-group ios__toggler-form-group">
                <?php echo $form->label($view->field('sliderOptions[pauseOnHover]'), t('Pause Autoplay On Hover')) ?>
                <div class="float-end">
                    <?php $togglerWidget->output($view->field('sliderOptions[pauseOnHover]'), 1, $sliderOptions['pauseOnHover']); ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <?php echo $form->label($view->field('sliderOptions[autoplayInterval]'), t('Autoplay Pause Duration')) ?>
                <div class="input-group" style="width: 180px">
                    <?php echo $form->number($view->field('sliderOptions[autoplayInterval]'), $sliderOptions['autoplayInterval'], ['min' => '1', 'max' => '99999']) ?>
                    <span class="input-group-text"><?php echo t('ms') ?></span>
                </div>
            </div>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo t('Navigation') ?></legend>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group ios__toggler-form-group">
                <?php echo $form->label($view->field('sliderOptions[arrows]'), t('Arrows'), ['tooltip' => t('Display navigation arrows')]) ?>
                <div class="float-end">
                    <?php $togglerWidget->output($view->field('sliderOptions[arrows]'), 1, $sliderOptions['arrows']); ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group ios__toggler-form-group">
                <?php echo $form->label($view->field('sliderOptions[loop]'), t('Loop'), ['tooltip' => t('Enable continuous loop mode')]) ?>
                <div class="float-end">
                    <?php $togglerWidget->output($view->field('sliderOptions[loop]'), 1, $sliderOptions['loop']); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group ios__toggler-form-group">
                <?php echo $form->label($view->field('sliderOptions[bullets]'), t('Bullets'), ['tooltip' => t('Display navigation bullets')]) ?>
                <div class="float-end">
                    <?php $togglerWidget->output($view->field('sliderOptions[bullets]'), 1, $sliderOptions['bullets']); ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">

        </div>
    </div>
</fieldset>


