import wikub_lib from './wikubSfCollectionHandler.js';

wikub_lib.sfCollectionHandler.init({
    collectionsSelector: 'form tbody[data-prototype]',
    manageRemoveEntry: true,
    lang: {
      add: 'Add',
      remove: 'Remove'
    }
})