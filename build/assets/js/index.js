import LiteSlider from './lite-slider'

// Define JQuery Function
$.fn.xwLiteSlider = function (options) {
    return $(this).each(function () {
        new LiteSlider(this, options)
    })
}

// Setup AutoStart
$(function () {
    const initSliders = () => {
        $('div[data-autostart="true"].xw-ls:not(.xw-ls--initialized)').each(function () {
            const $el = $(this)
            $el.xwLiteSlider($el.data('options'))
        })
    }

    initSliders()

    if (CCM_EDIT_MODE) {
        // Refresh Sliders after update
        const evtHandler = (event, args) => {
            if (args === undefined || args.block === undefined || args.block.getHandle() === 'xw_lite_slider') {
                initSliders()
            }
        }

        Concrete.event.on(['EditModeAddBlockComplete', 'EditModeUpdateBlockComplete'], evtHandler)

        // Refresh Sliders on inline edit mode
        Concrete.event.on(['EditModeBlockEditInline', 'EditModeInlineEditLoaded', 'EditModeExitInline', 'EditModeExitInlineComplete'], evtHandler)

        // Refresh Sliders on add from clipboard
        Concrete.event.on(['PanelLoad', 'EditModeAddClipboardComplete'], evtHandler)
    }
})
