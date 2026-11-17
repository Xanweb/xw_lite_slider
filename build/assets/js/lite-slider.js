/* global Concrete, $, Glide */
import defaults from './defaults'

export default class LiteSlider {
	/**
	 * Construct LiteSlider.
	 *
	 * @param  {Object} element
	 * @param  {Object} options
	 */
	constructor (element, options = {}) {
		const me = this
        me.el = element
        me.$el = $(element)
        me.classesPrefix = 'xw-ls'
        if (me.$el.hasClass(`${me.classesPrefix}--initialized`)) {
            return
        }

        me.options = $.extend({}, defaults, options)
        const showArrows = !!+me.options.arrows
        if (!showArrows) {
            me.$el.find('[data-glide-el="controls"]').remove()
        } else {
            me._arrowControls = {
                previous: me.$el.find('[data-glide-el="controls"] [data-glide-dir*="<"]').get(0),
                next: me.$el.find('[data-glide-el="controls"] [data-glide-dir*=">"]').get(0)
            }
        }

        const showBullets = !!+me.options.bullets
        if (!showBullets) {
            me.$el.find('[data-glide-el="controls[nav]"]').remove()
        }

        me._initSlider()
        me.$el.addClass(`${me.classesPrefix}--initialized`)
	}

	_initSlider () {
		const me = this
        const autoplay = !!+me.options.autoplay
        const animationType = me.options.animationType
        const glide = new Glide(me.el, {
            autoplay: autoplay ? parseInt(me.options.autoplayInterval) : false,
            animationTimingFunc: animationType !== 'fade' ? me.options.animation : 'none',
            animationDuration: animationType !== 'fade' ? parseInt(me.options.animationDuration) : 0,
            gap: animationType !== 'fade' ? 10 : 0,
            hoverpause: !!+me.options.pauseOnHover,
            rewind: !!+me.options.loop,
            classes: {
                direction: {
                    ltr: `${me.classesPrefix}--ltr`,
                    rtl: `${me.classesPrefix}--rtl`
                },
                slider: `${me.classesPrefix}--slider`,
                carousel: `${me.classesPrefix}--carousel`,
                swipeable: `${me.classesPrefix}--swipeable`,
                dragging: `${me.classesPrefix}--dragging`,
                cloneSlide: `${me.classesPrefix}__slide--clone`,
                activeNav: `${me.classesPrefix}__bullet--active`,
                activeSlide: `${me.classesPrefix}__slide--active`,
                disabledArrow: `${me.classesPrefix}__arrow--disabled`
            }
         })

        me._setupArrowDisablingControl(glide)
        if (animationType === 'fade') {
            me._setupFadeTransition(glide)
        }

        glide.mount()
	}

	_setupArrowDisablingControl (glide) {
        const me = this
        glide.on('move.after', () => {
            if (glide.settings.rewind || !+me.options.arrows) {
                return
            }

            const next = me._arrowControls.next
            const previous = me._arrowControls.previous

            this._resetArrowsState(glide, [next, previous])

            if (glide._c.Run.isStart()) {
                me._disableArrow(glide, previous)
            }

            if (glide._c.Run.isEnd()) {
                me._disableArrow(glide, next)
            }
        })
    }

    _resetArrowsState (glide, arrows) {
        const disabledArrowClass = glide.settings.classes.disabledArrow
        arrows.forEach(function (element) {
            element.classList.remove(disabledArrowClass)
        })
    }

    _disableArrow (glide, arrow) {
        const disabledArrowClass = glide.settings.classes.disabledArrow
        arrow.classList.add(disabledArrowClass)
    }

    _setupFadeTransition(glide) {
	    const fadeClass = `${this.classesPrefix}__slides--fade`
        const animation = this.options.animation
        const animationDuration = `${parseInt(this.options.animationDuration)}ms`
        glide.on('mount.after', () => {
            const wrapper = glide._c.Html.wrapper
            if (wrapper) {
                wrapper.classList.add(fadeClass)
            }
        })

        glide.on('run.before', move => {
            const slide = glide._c.Html.slides[glide.index]
            if (slide) {
                $(slide).css({
                    'transition-duration': animationDuration,
                    'transition-timing-function': animation,
                    'opacity': 0
                })
            }
        })

        glide.on('run', move => {
            const slide = glide._c.Html.slides[glide.index]
            if (slide) {
                $(slide).css({
                    'transition-duration': animationDuration,
                    'transition-timing-function': animation,
                    'opacity': 1
                })
            }
        })
    }
}
