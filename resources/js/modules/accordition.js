export const accordition = () => {
  const accordions = document.querySelectorAll('.accordion')

  const openAccordion = (accordion) => {
    const content = accordion.querySelector('.accordion__content')
    accordion.classList.add('active')
    content.style.maxHeight = content.scrollHeight + 'px'
  }

  const closeAccordion = (accordion) => {
    const content = accordion.querySelector('.accordion__content')
    accordion.classList.remove('active')
    content.style.maxHeight = null
  }

  accordions.forEach((accordion) => {
    const intro = accordion.querySelector('.accordion__intro')
    const content = accordion.querySelector('.accordion__content')

    intro.onclick = () => {
      if (content.style.maxHeight) {
        closeAccordion(accordion)
      } else {
        accordions.forEach((accordion) => closeAccordion(accordion))
        openAccordion(accordion)
      }
    }
  })
}
