//import a2lix_lib from '@a2lix/symfony-collection/src/a2lix_sf_collection';

import wikub_lib from './wikubSfCollectionHandler.js';

wikub_lib.sfCollectionHandler.init({
    collectionsSelector: 'form tbody[data-prototype]',
    manageRemoveEntry: true,
    lang: {
      add: 'Add',
      remove: 'Remove'
    }
})