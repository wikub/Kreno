'use strict'

const wikub_lib = {}

wikub_lib.sfCollectionHandler = (() => {
  const init = (config = {}) => {
    if (!('content' in document.createElement('template'))) {
      console.error('HTML template will not working...')
      return
    }

    const {
      collectionsSelector = 'form div[data-prototype]',
      manageRemoveEntry = true,
      lang = {
        add: 'Add',
        remove: 'Remove'
      }
    } = config

    //const collectionsElt = document.querySelectorAll(collectionsSelector)
    const containers = document.querySelectorAll('div[data-container]')
    
    if (!containers.length) {
      return
    }
    
    for(var container of containers) {
      handlerCollectionContainer(container)
    }
    //const buttons = container.querySelectorAll('button[data-entry-action]')
    
    // collectionsElt.forEach(collectionElt => {
    //   handlerCollectionElt(collectionElt, manageRemoveEntry, lang)
    // })
  }
  const handlerCollectionContainer = (
    container
  ) => {
    const containterList = container.querySelector('[data-prototype]')
    const entryPrototype = containterList.getAttribute('data-prototype')
    
    const buttonsRemove = container.querySelectorAll('button[data-entry-action="remove"]')
    for( let button of buttonsRemove) {
      button.addEventListener('click', removeEvent)
    }
    

    const buttonsAdd = container.querySelectorAll('button[data-entry-action="add"]')
    
    for( let button of buttonsAdd) {
      button.addEventListener('click', event => addEvent(event, containterList, entryPrototype) )
    }
  }

  const removeEvent = (event) => {
    event.target.closest('[data-item]').remove()
  }

  const addEvent = (event, containterList, entryPrototype) => {
    const entryIndex = containterList.getAttribute('data-index')
    containterList.setAttribute('data-index', +entryIndex + 1)

    const templateContent = getTemplateContent(entryPrototype, entryIndex)

    const buttonsRemove = templateContent.querySelectorAll('button[data-entry-action="remove"]')
    for( let button of buttonsRemove) {
      button.addEventListener('click', removeEvent)
    }

    containterList.append(templateContent)
  }


  /**
   * HELPERS
   */
  const getTemplateContent = (entryPrototype, entryIndex) => {
    const template = document.createElement('template')

    const entryHtml = entryPrototype
      .replace(/__name__label__/g, `!New! ${entryIndex}`)
      .replace(/__name__/g, entryIndex)

    template.innerHTML = entryHtml.trim()

    return template.content
  }

  return {
    init
  }
})()

export default wikub_lib