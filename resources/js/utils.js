import { toEthiopian } from "ethiopian-date"

function formatNumber(num) {
    return num.toLocaleString('en', { useGrouping: true })
}

function formatPrice(num) {

    return Number(parseFloat((num).toFixed(2)))
        .toLocaleString("en", {
            minimumFractionDigits: 2
        })
}

function formatNumberToTwoIntegerPlaces(num, type='number') {

    if (type === 'price') {
        if (num.toString().length < 2) {
            return num.toString() + '0'
        }
        return num
    }

    return parseInt(num).toLocaleString('en-US',
        { minimumIntegerDigits: 2 }
    )
}

function formatDate(dateObject) {
    if (dateObject.date === '') {
        return null
    }
    return `${parseInt(dateObject.date).toLocaleString('en-US', { minimumIntegerDigits: 2 })}-${(parseInt(dateObject.month) + 1).toLocaleString('en-US', { minimumIntegerDigits: 2 })}-${dateObject.year}`
}

function arrayToStrings(array) {
    return array.map(item => {
        return item.toString()
            .replaceAll('_', ' ')
    }).join(', ')
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

    const hundreds = "መቶ"
    const ten_variant = 'አስር'
    const thousands = "ሺህ"
    const millions = "ሚሊዮን"
    const billions = "ቢሊዮን"

    const integer = digit.toString().split('.')[0]
    const decimal = digit.toString().split('.')[1] ?? null

    const integerStack = integer.split('').reverse()

    let integerWritten = ''
    for (let i = 0; i < integerStack.length; i++) {
        
        if (i === 0) {
            integerWritten = integerStack[i] === '0' ? '' : ones[integerStack[i]]  
        } else if (i % 3 === 1) {
            integerWritten = (integerStack[i] === '0' ? '' : integerStack[i] === '1' && integerStack[i-1] === '0' ? ten_variant : (tens[integerStack[i]])) + ' ' + integerWritten
        } else if (i % 3 === 2) {
            integerWritten = (integerStack[i] === '0' ? '' : (ones[integerStack[i]] + ' ' + hundreds)) + ' ' + integerWritten
        } else if (i === 3) {
            integerWritten = (integerStack[i] === '0' ? '' : (ones[integerStack[i]])) + ' ' + thousands + ' ' + integerWritten
        } else if (i === 6) {
            integerWritten = (integerStack[i] === '0' ? '' : (ones[integerStack[i]] + ' ' + millions)) + ' ' + integerWritten
        } else if (i === 9) {
            integerWritten = (integerStack[i] === '0' ? '' : (ones[integerStack[i]] + ' ' + billions)) + ' ' + integerWritten
        }
    }

    return  integerWritten + ' ብር ' + 'ከ ' + (!! decimal ? formatNumberToTwoIntegerPlaces(decimal, 'price') : '00') + ' ሳንቲም'

}

function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}

const ethiopianMonths = [
    '', 'መስከረም', 'ጥቅምት', 'ሕዳር', 'ታሕሳስ',
    'ጥር', 'የካቲት', 'መጋቢት', 'ሚያዝያ',
    'ግንቦት', 'ሰኔ', 'ሐምሌ', 'ነሐሴ', 'ጳጉሜን'
];

const ethiopianDays = [
    'እሁድ', 'ሰኞ', 'ማክሰኞ', 'ረቡዕ',
    'ሃሙስ', 'አርብ', 'ቅዳሜ'
];

const months = [
    'January', 'February', 'March',
    'April', 'May', 'June', 'July',
    'August', 'September', 'October',
    'November', 'December'
]

const days = [
    'Sun', 'Mon', 'Tue', 'Wed',
    'Thu', 'Fri', 'Sat'
]

function ethiopianDate(date) {

    const dayString = ethiopianDays[date.getDay()]

    const [year, month, day] = toEthiopian(date.getFullYear(), date.getMonth()+1, date.getDate())

    return {
        toDateString: () => { return dayString + ' ' + day + '፣ ' + ethiopianMonths[month] + ' ' + year },
        toLocaleDateString: () => { return  day + '/' + month + '/' + year },
        getDate: () => { return day },
        getMonth: () => { return month },
        getFullYear: () => { return year }
    }

}

function footerWords() {

    return [

        'እሪ በከንቱ',
        'ዶሮ ማነቅያ',
        'ጠብመንጃ ያዥ',
        'ጎማ ቁጠባ',
        'ቦምብ ተራ',
        'አመዴ ገበያ',
        'ሰባራ ባቡር',
        'ዉቤ በረሃ',

    ];

}

export {
    ethiopianDate, digitToWritten,
    ethiopianMonths, ethiopianDays, months, days,
    formatDate, getRandomInt, footerWords, formatPrice,
    formatNumber, arrayToStrings, formatNumberToTwoIntegerPlaces
}
