import crypto from 'crypto'
import colors from 'vuetify/es5/util/colors'

const getInitial = function (string) {
  return string
    ? string
      .split(/[-, ]+/)
      .map((n) => n[0])
      .splice(0, 2)
      .join('')
      .toUpperCase()
    : ''
}

const getColor = function (string, fallback) {
  const Fallback = fallback ?? colors.primary
  return string !== '' && string !== undefined
    ? '#' +
    crypto
      .createHash('md5')
      .update(getInitial(string))
      .digest('hex')
      .substr(0, 6)
    : Fallback
}

export default ({app}, inject) => {
  inject('getColor', (string, fallback) => getColor(string, fallback))
  inject('getInitial', (string) => getInitial(string))
}
