import Tagify from '@yaireo/tagify'
import '@yaireo/tagify/dist/tagify.css'

var inputElm = document.querySelector('.user-tags')

var lstTags = inputElm.getAttribute('data-tags');
console.log(JSON.parse(lstTags));
var tagify = new Tagify(inputElm, {
    originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(','),
    whitelist: JSON.parse(lstTags)
  })

//tagify.addTags(["banana", "orange", "apple"])

