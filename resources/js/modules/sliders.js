export const sliders = () => {
  if (document.querySelector('.blog-section-slider')) {
    // eslint-disable-next-line no-new
    new Swiper('.blog-section-slider', {
      spaceBetween: 16,

      breakpoints: {
        320: {
          slidesPerView: 1
        },
        630: {
          slidesPerView: 2
        },
        1024: {
          slidesPerView: 3
        },
        1440: {
          slidesPerView: 4
        }
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
      }
    })
  }

  if (document.querySelector('.installing-section__slider')) {
    // eslint-disable-next-line no-new
    new Swiper('.installing-section__slider', {
      spaceBetween: 16,
      slidesPerView: 1,
      loop: true,
      navigation: {
        nextEl: '.installing-swiper-button-next',
        prevEl: '.installing-swiper-button-prev'
      }
    })
  }

  if (document.querySelector('.repair-examples-slider')) {
    // eslint-disable-next-line no-new
    new Swiper('.repair-examples-slider', {
      spaceBetween: 0,
      slidesPerView: 1,
      loop: false,
      observer: true,
      observeParents: true,
      resizeObserver: true,

      pagination: {
        el: '.repair-examples-pagination',
        clickable: true
      },

      breakpoints: {
        576: {
          slidesPerView: 1.4
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 16
        },
        1200: {
          slidesPerView: 2,
          spaceBetween: 24
        }
      }
    })
  }

  const compareBlocks = document.querySelectorAll('[data-compare]')

  compareBlocks.forEach((compare) => {
    const overlay = compare.querySelector('[data-compare-overlay]')
    const handle = compare.querySelector('[data-compare-handle]')

    if (!overlay || !handle) return

    const swiperEl = compare.closest('.swiper')
    const swiperInstance = swiperEl && swiperEl.swiper ? swiperEl.swiper : null

    let isDragging = false
    let activePointerId = null

    const setPosition = (clientX) => {
      const rect = compare.getBoundingClientRect()
      let percent = ((clientX - rect.left) / rect.width) * 100

      if (percent < 0) percent = 0
      if (percent > 100) percent = 100

      compare.style.setProperty('--compare-position', `${percent}%`)
      handle.style.left = `${percent}%`
    }

    const startDragging = (e) => {
      isDragging = true
      activePointerId = e.pointerId

      if (swiperInstance) {
        swiperInstance.allowTouchMove = false
      }

      compare.classList.add('is-dragging')

      if (handle.setPointerCapture) {
        handle.setPointerCapture(e.pointerId)
      }

      setPosition(e.clientX)
      e.preventDefault()
      e.stopPropagation()
    }

    const moveDragging = (e) => {
      if (!isDragging) return
      if (activePointerId !== null && e.pointerId !== activePointerId) return

      setPosition(e.clientX)
      e.preventDefault()
    }

    const stopDragging = (e) => {
      if (!isDragging) return
      if (activePointerId !== null && e.pointerId !== activePointerId) return

      isDragging = false
      activePointerId = null

      compare.classList.remove('is-dragging')

      if (swiperInstance) {
        swiperInstance.allowTouchMove = true
      }
    }

    handle.addEventListener('pointerdown', startDragging)
    compare.addEventListener('pointerdown', (e) => {
      if (e.target.closest('a')) return
      startDragging(e)
    })

    window.addEventListener('pointermove', moveDragging)
    window.addEventListener('pointerup', stopDragging)
    window.addEventListener('pointercancel', stopDragging)
  })
}
