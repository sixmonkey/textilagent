import pluralize from 'pluralize'

export default ({ app }, inject) => {
  inject('pluralize', function (word) {
    return pluralize.plural(word)
  })
  inject('singularize', function (word) {
    return pluralize.singular(word)
  })
}
