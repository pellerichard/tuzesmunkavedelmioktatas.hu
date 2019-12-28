import { formatFileSize, isDefinedGlobally } from './utils';

const messages = {
  _default: (field) => `A(z) ${field} értéke nem valós.`,
  after: (field, [target]) => `A(z) ${field} legalább ${target} utáni dátum kell, hogy legyen.`,
  alpha_dash: (field) => `A(z) ${field} kizárólag betűket, számokat, kötőjeleket és alulvonásokat tartalmazhat.`,
  alpha_num: (field) => `A(z) ${field} kizárólag betűket és számokat tartalmazhat.`,
  alpha_spaces: (field) => `A(z) ${field} kizárólag betűket és szóközöket tartalmazhat.`,
  alpha: (field) => `A(z) ${field} kizárólag betűket tartalmazhat.`,
  before: (field, [target]) => `A(z) ${field} legalább ${target} előtti dátum kell, hogy legyen.`,
  between: (field, [min, max]) => `A(z) ${field} ${min} és ${max} között kell, hogy legyen.`,
  confirmed: (field) => `A(z) ${field} nem egyezik a megerősítéssel.`,
  credit_card: (field) => `A(z) ${field} nem érvényes.`,
  date_between: (field, [min, max]) => `A(z) ${field} ${min} és ${max} közötti dátum kell, hogy legyen.`,
  date_format: (field, [format]) => `A(z) ${field} nem egyezik az alábbi dátum formátummal ${format}.`,
  decimal: (field, [decimals = '*'] = []) => `The ${field} must be numeric and may contain ${decimals === '*' ? '' : decimals} decimal points.`,
  digits: (field, [length]) => `A(z) ${field} ${length} számjegyű kell, hogy legyen.`,
  dimensions: (field, [width, height]) => `A(z) ${field} felbontása ${width} és ${height} pixel között kell, hogy legyen.`,
  email: (field) => `A(z) ${field} nem érvényes email formátum.`,
  ext: (field) => `A(z) ${field} nem érvényes fájl.`,
  image: (field) => `A(z) ${field} képfálj kell, hogy legyen.`,
  included: (field) => `A kiválaszott ${field} érvénytelen.`,
  integer: (field) => `A(z) ${field} mező számból kell hogy álljon.`,
  ip: (field) => `A(z) ${field} érvényes IP cím kell, hogy legyen.`,
  length: (field, [length, max]) => {
    if (max) {
      return `A(z) ${field} hossza ${length} és ${max} között kell lennie.`;
    }

    return `A(z) ${field} hosszának ${length} értéknek kell lennie.`;
  },
  max: (field, [length]) => `A(z) ${field} értéke nem lehet hosszabb mint ${length}.`,
  max_value: (field, [max]) => `A(z) ${field} értéke ${max} vagy kevesebb lehet.`,
  mimes: (field) => `A(z) ${field} kizárólag érvényes fájlformátumok egyike lehet.`,
  min: (field, [length]) => `A(z) ${field} értéke nem lehet rövidebb mint ${length}.`,
  min_value: (field, [min]) => `A(z) ${field} értéke ${min} vagy több lehet.`,
  excluded: (field) => `A(z) ${field} értéke érvénytelen.`,
  numeric: (field) => `A(z) ${field} értéke szám kell, hogy legyen.`,
  regex: (field) => `A(z) ${field} formátuma érvénytelen.`,
  required: (field) => `A(z) ${field} megadása kötelező.`,
  size: (field, [size]) => `A(z) ${field} méretének ${size} kilobájtnál kisebbnek kell lennie.`,
  url: (field) => `A(z) ${field} érvénytelen link.`
};

const locale = {
  name: 'hu',
  messages,
  attributes: {}
};

if (isDefinedGlobally()) {
  VeeValidate.Validator.localize({ [locale.name]: locale });
}

export default locale;