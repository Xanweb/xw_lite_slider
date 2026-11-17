<?php
/** @noinspection JsonEncodingApiUsageInspection */
/** @noinspection PhpUnhandledExceptionInspection */
defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Core\Application\Service\UserInterface $uih
 * @var Concrete\Core\Block\View\BlockView $view
 * @var Concrete\Core\Form\Service\Form $form
 * @var array $rows
 * @var array $colorPickerOptions
 * @var array $bgPositions
 * @var array $linkTypes
 * @var string $editorInitJSFunction
 * @var string $uniqID
 * @var bool $useCustomThumbnailsSetting
 */
echo $uih->tabs([
    ["slides$uniqID", t('Slides'), true],
    ["slider-options$uniqID", t('Slider Options')],
    ["settings$uniqID", t('Settings')],
]);
?>

<div id="xwlsform<?php echo $uniqID ?>" class="tab-content">
    <div id="slides<?php echo $uniqID ?>" class="tab-pane show active" role="tabpanel">
        <?php $view->inc('form/slides.php'); ?>
    </div>

    <div id="slider-options<?php echo $uniqID ?>" class="tab-pane" role="tabpanel">
        <?php $view->inc('form/options.php'); ?>
    </div>

    <div id="settings<?php echo $uniqID ?>" class="tab-pane" role="tabpanel">
        <?php $view->inc('form/settings.php'); ?>
    </div>
</div>

<script>
    $(function() {
        Concrete.Vue.activateContext('itemList', function (Vue, config) {
            new Vue({
                el: '#xwlsform<?php echo $uniqID ?>',
                components: config.components,
                mounted() {
                    $(this.$el).find('.launch-tooltip').tooltip({ container: '#ccm-tooltip-holder' });
                },
                methods: {
                    onAddItem($item) {
                        $item.find('.launch-tooltip').tooltip({ container: '#ccm-tooltip-holder' });
                    }
                },
                data: {
                    drag: false,
                    items: <?php echo json_encode($rows, JSON_NUMERIC_CHECK) ?>,
                    colorPickerOptions: <?php echo json_encode($colorPickerOptions, JSON_NUMERIC_CHECK) ?>,
                    useCustomThumbnailsSettings: <?php echo json_encode($useCustomThumbnailsSetting, JSON_NUMERIC_CHECK) ?>,
                    defaultItem: {
                        get id() {
                            return _.uniqueId('item')
                        },
                        title: '',
                        fID: null,
                        description: '',
                        boxColor: 'rgba(0, 0, 0, 0.6)',
                        linkType: <?php echo json_encode(array_key_first($linkTypes)) ?>,
                        internalLinkCID: 0,
                        linkExtern: ''
                    }
                },
                computed: {
                    dragOptions() {
                        return {
                            animation: 200,
                            group: 'description',
                            disabled: false,
                            ghostClass: 'ghost'
                        };
                    }
                }
            })
        });
    });
</script>
