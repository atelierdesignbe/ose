const ajaxPage = document.querySelector('[js-ajax]')

function initPublicationAnimation() {
  const publications = document.querySelectorAll('.publication')
  if (publications.length > 0) {
    publications.forEach(el => {
      el.addEventListener('mouseenter', () => {
        el.classList.remove('is-leaving')
        el.classList.add('is-entering')
      })
  
      el.addEventListener('mouseleave', () => {
        el.classList.remove('is-entering')
        el.classList.add('is-leaving')
      })
    })
  }
}

function initProjectsAnimation() {  
  const projects = document.querySelectorAll('.project')
  if (projects.length > 0) {
    projects.forEach(el => {
      el.addEventListener('mouseenter', () => {
        el.classList.remove('is-leaving')
        el.classList.add('is-entering')
      })

      el.addEventListener('mouseleave', () => {
        el.classList.remove('is-entering')
        el.classList.add('is-leaving')
      })
    })
  }
}

initPublicationAnimation()
initProjectsAnimation()

if (ajaxPage) {
  const custom_post = ajaxPage.getAttribute('js-ajax')
  const data = {
    page: 1,
    filters: {},
    hasPagination: false,
    type: custom_post,
    action: `filter_${custom_post}`,
    none: ajax.nonce,
  }


  const pagination = ajaxPage.querySelector('[js-ajax-pagination]')
  const resetWrapper = ajaxPage.querySelector('[js-ajax-reset]')
  const results = ajaxPage.querySelector('[js-ajax-results]')
  const filters = ajaxPage.querySelectorAll('[js-ajax-filter]')

  const iconClose = `<svg class="@@:size-[12px] stroke-current stroke-[1px]" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg"><path d="M8.04289 0.5L0.5 8.04289M0.542893 0.5L8.08579 8.04289" stroke-linecap="round"></path></svg>`

  function searchData(reload = false) {
    if (reload) {
      results.style.transition = `all .3s ease-out`
      results.style.transform = `translateY(20px)`
      results.style.opacity = `0`
    }

    const nData = {
      ...data,
      filters: JSON.stringify(data.filters),
    }

    fetch(ajax.url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams(nData)
    })
    .then(res => res.json())
      .then(dataRes => {
        if (reload) {
          results.innerHTML = dataRes.html
          results.style.transform = ``
          results.style.opacity = 1
        } else {
          results.innerHTML = `${results.innerHTML} ${dataRes.html}`
        }

        const aosItems = results.querySelectorAll('.aos')
        aosItems.forEach(item => {
          if (!item.classList.contains('animated')) {
            item.classList.add('animated')
          }
        })

        data.hasPagination = dataRes.hasPagination

        console.log(dataRes.hasPagination)

        if (!pagination.querySelector('button')) { }
        else if (dataRes.hasPagination) {
          pagination.querySelector('button').style = ''
        } else {

          pagination.querySelector('button').style.display = 'none'
        }

        for(const tax of Object.keys(dataRes.filter)) {
          Object.entries(dataRes.filter[tax]).forEach(([termId, count]) => {
            const termItem =  ajaxPage.querySelector(`[js-ajax-filter] [data-id="${termId}"]`)
            if(termItem) {
              if(count === 0) {
                termItem.classList.add( 'is-disabled')
              } else termItem.classList.remove('is-disabled')
            }
          })
        }

        initPublicationAnimation()
        initProjectsAnimation()
    })
  }

  data.hasPagination = !!pagination.querySelector('button')

  if (data.hasPagination) {
    pagination.querySelector('button').addEventListener('click', () => {
      data.page += 1
      searchData()
    })
  }

  filters.forEach((filter, index) => {
    const filterType = filter.getAttribute('js-ajax-filter')
    const buttonExpand = filter.parentElement.parentElement.querySelector('[js-expand-button]')
    const expand = filter.parentElement.parentElement.querySelector('[js-expand]')
    data.filters[filterType] = ''

    const items = filter.querySelectorAll('button')

    for (const item of items) {
      item.addEventListener('click', () => {
        const oldElt = data.filters[filterType]
        if (data.filters[filterType]) {
          ajaxPage.querySelector(`[js-ajax-filter="${filterType}"] [data-id="${data.filters[filterType]}"]`).classList.remove('is-active')
          resetWrapper.querySelector(`button[data-filter="${filterType}"]`).remove()
        }

        if (oldElt !== item.dataset.id) {
          const nButton = document.createElement('button')

          nButton.innerHTML = `${item.dataset.name} ${iconClose}`
          nButton.classList.add('reset-label')
          nButton.dataset.id = item.dataset.id
          nButton.dataset.filter = filterType
          resetWrapper.appendChild(nButton)
          buttonExpand.classList.add('is-filtered')
          data.filters[filterType] = item.dataset.id
          item.classList.add('is-active')
  
          if(window.innerWidth < 600) {
            expand.classList.remove('is-open')
            buttonExpand.classList.remove('is-open')
            setTimeout(() => {
              expand.style.display = ''
            }, 300)
          }
  
          data.page = 1
  
          searchData(true)
  
          nButton.addEventListener('click', () => {
            ajaxPage.querySelector(`[js-ajax-filter] [data-id="${nButton.dataset.id}"]`).classList.remove('is-active')
            data.filters[nButton.dataset.filter] = ''
            ajaxPage.querySelector(`[js-ajax-filter="${nButton.dataset.filter}"]`).parentElement.parentElement.querySelector('[js-expand-button]').classList.remove('is-filtered')
            data.page = 1
            searchData(true)
            nButton.remove()
          })
        } else {
          data.filters[filterType] = ''
          buttonExpand.classList.remove('is-filtered')
          data.page = 1
          searchData(true)
        }
      })
    }
  })

  function getUrlParams() {
    const params = new URLSearchParams(window.location.search)

    // Injecte le filtre venant de l'URL SEO friendly
    if (window.ajax?.activeFilterType && window.ajax?.activeFilterValue) {
        params.set(window.ajax.activeFilterType, window.ajax.activeFilterValue)
    }

    console.log(window.ajax.activeFilterValue, window.ajax.activeFilterType)

    filters.forEach((filter) => {
        const filterType = filter.getAttribute('js-ajax-filter')
        const paramValue = params.get(filterType)

        if (paramValue) {
            const targetItem = filter.querySelector(`[data-id="${paramValue}"]`)
            if (targetItem) {
                targetItem.click()
            }
        }
    })
  }

  getUrlParams()
}