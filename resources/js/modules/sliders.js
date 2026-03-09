export const sliders = () => {
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

  if (document.querySelector('.gallery-swiper')) {
    // eslint-disable-next-line no-new
    new Swiper('.gallery-swiper', {
      spaceBetween: 16,
      slidesPerView: 1.2,
      loop: false,

      pagination: {
        el: '.gallery-pagination',
        clickable: true
      },

      navigation: {
        nextEl: '.gallery-arrow-next',
        prevEl: '.gallery-arrow-prev'
      },

      breakpoints: {
        480: {
          slidesPerView: 1.4
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 12
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 16
        },
        1440: {
          slidesPerView: 3,
          spaceBetween: 16
        }
      }
    })
  }
}
if (document.querySelector('.repair-examples-slider')) {
  // eslint-disable-next-line no-new
  new Swiper('.repair-examples-slider', {
    spaceBetween: 16,
    slidesPerView: 1.15,
    loop: false,

    pagination: {
      el: '.repair-examples-pagination',
      clickable: true
    },

    navigation: {
      nextEl: '.repair-examples-arrow-next',
      prevEl: '.repair-examples-arrow-prev'
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
