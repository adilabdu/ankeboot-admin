import { toEthiopian } from 'ethiopian-date'

function delay(time) {
  return new Promise((resolve) => setTimeout(resolve, time))
}

function formatNumber(num) {
  return num.toLocaleString('en', { useGrouping: true })
}

function formatPrice(num) {
  return Number(parseFloat(num.toFixed(2))).toLocaleString('en', {
    minimumFractionDigits: 2
  })
}

function formatNumberToTwoIntegerPlaces(num, type = 'number') {
  if (type === 'price') {
    if (num.toString().length < 2) {
      return num.toString() + '0'
    }
    return num
  }

  return parseInt(num).toLocaleString('en-US', { minimumIntegerDigits: 2 })
}

function formatDate(dateObject) {
  if (dateObject.date === '') {
    return null
  }
  return `${parseInt(dateObject.date).toLocaleString('en-US', { minimumIntegerDigits: 2 })}-${(parseInt(dateObject.month) + 1).toLocaleString('en-US', { minimumIntegerDigits: 2 })}-${dateObject.year}`
}

function arrayToStrings(array) {
  return array
    .map((item) => {
      return item.toString().replaceAll('_', ' ')
    })
    .join(', ')
}

function digitToWritten(digit) {
  const ones = {
    0: 'ዜሮ',
    1: 'አንድ',
    2: 'ሁለት',
    3: 'ሶስት',
    4: 'አራት',
    5: 'አምስት',
    6: 'ስድስት',
    7: 'ሰባት',
    8: 'ስምንት',
    9: 'ዘጠኝ'
  }

  const tens = {
    0: 'ዜሮ',
    1: 'አስራ',
    2: 'ሃያ',
    3: 'ሰላሳ',
    4: 'አርባ',
    5: 'ሃምሳ',
    6: 'ስልሳ',
    7: 'ሰባ',
    8: 'ሰማንያ',
    9: 'ዘጠና'
  }

  const hundreds = 'መቶ'
  const ten = 'አስር'
  const thousands = 'ሺህ'
  const millions = 'ሚሊዮን'
  const billions = 'ቢሊዮን'
  const trillions = 'ትሪልዮን'
  const quadrillions = 'ክዋድሪሊዮን'
  const quintillions = 'ክዊንቲልዮን'
  const sextillions = 'ሴክስቲልዮን'
  const septillions = 'ሴፕቲልዮን'
  const octillions = 'ኦክቲልዮን'
  const nonillions = 'ኖኒልዮን'
  const decillions = 'ዲሲልዮን'
  const undecillions = 'አንድዲሲልዮን'
  const duodecillions = 'ድዮዲሲልዮን'

  const integer = digit.toString().split('.')[0]
  const decimal = digit.toString().split('.')[1] ?? null

  const integerStack = integer.split('').reverse()

  let integerWritten = ''
  for (let i = 0; i < integerStack.length; i++) {
    if (i === 0) {
      integerWritten = integerStack[i] === '0' ? '' : ones[integerStack[i]]
    } else if (i % 3 === 1) {
      integerWritten =
        (integerStack[i] === '0'
          ? ''
          : integerStack[i] === '1' && integerStack[i - 1] === '0'
            ? ten
            : tens[integerStack[i]]) +
        ' ' +
        integerWritten
    } else if (i % 3 === 2) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + hundreds) +
        ' ' +
        integerWritten
    } else if (i === 3) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]]) +
        ' ' +
        thousands +
        ' ' +
        integerWritten
    } else if (i === 6) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + millions) +
        ' ' +
        integerWritten
    } else if (i === 9) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + billions) +
        ' ' +
        integerWritten
    } else if (i === 12) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + trillions) +
        ' ' +
        integerWritten
    } else if (i === 15) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + quadrillions) +
        ' ' +
        integerWritten
    } else if (i === 18) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + quintillions) +
        ' ' +
        integerWritten
    } else if (i === 21) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + sextillions) +
        ' ' +
        integerWritten
    } else if (i === 24) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + septillions) +
        ' ' +
        integerWritten
    } else if (i === 27) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + octillions) +
        ' ' +
        integerWritten
    } else if (i === 30) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + nonillions) +
        ' ' +
        integerWritten
    } else if (i === 33) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + decillions) +
        ' ' +
        integerWritten
    } else if (i === 36) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + undecillions) +
        ' ' +
        integerWritten
    } else if (i === 39) {
      integerWritten =
        (integerStack[i] === '0' ? '' : ones[integerStack[i]] + ' ' + duodecillions) +
        ' ' +
        integerWritten
    }
  }

  return (
    integerWritten +
    ' ብር ' +
    'ከ ' +
    (!!decimal ? formatNumberToTwoIntegerPlaces(decimal, 'price') : '00') +
    ' ሳንቲም'
  )
}

function getRandomInt(max) {
  return Math.floor(Math.random() * max)
}

const ethiopianMonths = [
  '',
  'መስከረም',
  'ጥቅምት',
  'ሕዳር',
  'ታሕሳስ',
  'ጥር',
  'የካቲት',
  'መጋቢት',
  'ሚያዝያ',
  'ግንቦት',
  'ሰኔ',
  'ሐምሌ',
  'ነሐሴ',
  'ጳጉሜን'
]

const ethiopianDays = ['እሁድ', 'ሰኞ', 'ማክሰኞ', 'ረቡዕ', 'ሃሙስ', 'አርብ', 'ቅዳሜ']

const months = [
  'January',
  'February',
  'March',
  'April',
  'May',
  'June',
  'July',
  'August',
  'September',
  'October',
  'November',
  'December'
]

const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

function ethiopianDate(date) {
  const dayString = ethiopianDays[date.getDay()]

  const [year, month, day] = toEthiopian(date.getFullYear(), date.getMonth() + 1, date.getDate())

  return {
    toDateString: () => {
      return dayString + ' ' + day + '፣ ' + ethiopianMonths[month] + ' ' + year
    },
    toLocaleDateString: () => {
      return day + '/' + month + '/' + year
    },
    getDate: () => {
      return day
    },
    getMonth: () => {
      return month
    },
    getFullYear: () => {
      return year
    }
  }
}

function footerWords() {
  return ['እሪ በከንቱ', 'ዶሮ ማነቅያ', 'ጠብመንጃ ያዥ', 'ጎማ ቁጠባ', 'ቦምብ ተራ', 'አመዴ ገበያ', 'ሰባራ ባቡር', 'ዉቤ በረሃ']
}

const local_time_ago = (number, index, totalSec) => {
  // number: the timeago / timein number;
  // index: the index of array below;
  // totalSec: total seconds between date to be formatted and today's date;
  return [
    ['just now', 'right now'],
    ['%s secs ago', 'in %s secs'],
    ['1 min ago', 'in 1 min'],
    ['%s mins ago', 'in %s mins'],
    ['1 hour ago', 'in 1 hour'],
    ['%s hours ago', 'in %s hours'],
    ['1 day ago', 'in 1 day'],
    ['%s days ago', 'in %s days'],
    ['1 week ago', 'in 1 week'],
    ['%s weeks ago', 'in %s weeks'],
    ['1 month ago', 'in 1 mnt'],
    ['%s months ago', 'in %s months'],
    ['1 year ago', 'in 1 year'],
    ['%s years ago', 'in %s years']
  ][index]
}

export {
  delay,
  local_time_ago,
  ethiopianDate,
  digitToWritten,
  ethiopianMonths,
  ethiopianDays,
  months,
  days,
  formatDate,
  getRandomInt,
  footerWords,
  formatPrice,
  formatNumber,
  arrayToStrings,
  formatNumberToTwoIntegerPlaces
}
