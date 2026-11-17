<?php

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Core\Block\View\BlockView $view
 * @var Xanweb\Ls\HtmlHelper\Service\Form $form
 * @var array $rows
 * @var array $colorPickerOptions
 * @var array $bgPositions
 * @var array $linkTypes
 * @var string $editorInitJSFunction
 * @var string $uniqID
 */
?>
<div class="xw-lite-slider-form">
    <div class="spacer-row-1"></div>
    <item-list v-slot="slotProps" :items="items" :default-item="defaultItem" @add-item="onAddItem">
        <template>
            <draggable v-model="items" handle=".drag-handle" v-bind="dragOptions" @start="drag = true" @end="drag = false">
                <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                    <item class="card xw-item-list__item" v-for="(item, index) in items" :index="index" :key="item.id">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <div class="float-start" data-concrete-file-input="<?php echo $uniqID ?>">
                                        <concrete-file-input :file-id="item.fID" v-model="item.fID" input-name="<?php echo $view->field('fID') ?>[]" choose-text="<?php echo t('Choose Image') ?>"></concrete-file-input>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-5 text-end pe-5">
                                    <div class="btn-group-sm">
                                        <button type="button" class="btn btn-secondary xw-item-list__edit-item xw-item-list__item-expander" data-bs-toggle="collapse" :data-bs-target="`.item--${index}`"><?php echo t('Edit') ?></button>
                                        <button type="button" class="btn btn-outline-danger xw-item-list__remove-item"  @click="slotProps.deleteEvent(index)"><?php echo t('Remove') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div :class="`collapse item--${index}`">
                            <div class="card-body">
                                <div class="clearfix">
                                    <div class="form-group">
                                        <?php echo $form->label('', t('Title')) ?>
                                        <?php echo $form->text($view->field('title') . '[]', ['id' => '', 'v-model' => 'item.title', 'maxlength' => 255]) ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php echo $form->label('', t('Title Color')) ?>
                                                <color-picker input-name="<?php echo $view->field('titleColor') ?>[]" v-model="item.titleColor" :custom-options="colorPickerOptions"></color-picker>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php echo $form->label('', t('Overlay Color')) ?>
                                                <color-picker input-name="<?php echo $view->field('overlayColor') ?>[]" v-model="item.overlayColor" :custom-options="colorPickerOptions"></color-picker>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <?php echo $form->label('', t('Description')) ?>
                                        <rich-text-editor input-name="<?php echo $view->field('description') ?>[]" v-model="item.description" height="160"></rich-text-editor>
                                    </div>

                                    <div class="form-group">
                                        <?php echo $form->label('', t('Image Alt')) ?>
                                        <?php echo $form->text($view->field('imageAlt') . '[]', ['id' => '', 'v-model' => 'item.imageAlt', 'maxlength' => 255]) ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php echo $form->label('', t('Box Color')) ?>
                                                <color-picker input-name="<?php echo $view->field('boxColor') ?>[]" v-model="item.boxColor" :custom-options="colorPickerOptions"></color-picker>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php echo $form->label('', t('Link Type')) ?>
                                                <?php echo $form->select($view->field('linkType') . '[]', $linkTypes, ['id' => '', 'v-model' => 'item.linkType', 'data-choice' => 'link-type', 'classes' => 'form-select', 'class' => 'form-select']) ?>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div style="display: none;" class="form-group form-group-sm" data-choice-group="link-type" data-choice-value="1">
                                                <?php echo $form->label('', t('Page')) ?>
                                                <concrete-page-input :page-id="item.internalLinkCID"
                                                                     :include-system-pages="false" :ask-include-system-pages="false"
                                                                     input-name="<?php echo $view->field('internalLinkCID') ?>[]"
                                                                     choose-text="<?php echo t('Choose Page') ?>"  >
                                                </concrete-page-input>
                                            </div>
                                            <div style="display: none;" class="form-group form-group-sm" data-choice-group="link-type" data-choice-value="2">
                                                <?php echo $form->label('', t('URL')) ?>
                                                <?php echo $form->url($view->field('linkExtern') . '[]', ['id' => '', 'v-model' => 'item.linkExtern', 'maxlength' => 255]) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <input class="xw-item-entry-sort" type="hidden" name="<?php echo $view->field('sortOrder') ?>[]" :value="index"/>
                                </div>
                            </div>
                        </div>
                    </item>
                </transition-group>
            </draggable>
            <div class="floating-block-actions">
                <button type="button" class="btn btn-outline-info w-100" @click="slotProps.addNewItem"><?php echo t('Add Slide') ?></button>
            </div>
        </template>
    </item-list>
</div>
