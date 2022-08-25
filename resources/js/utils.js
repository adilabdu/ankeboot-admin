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

function formatNumberToTwoIntegerPlaces(num) {
    return parseInt(num).toLocaleString('en-US',
        { minimumIntegerDigits: 2 }
    )
}

function formatDate(dateObject) {
    return `${parseInt(dateObject.date).toLocaleString('en-US', { minimumIntegerDigits: 2 })}-${(parseInt(dateObject.month) + 1).toLocaleString('en-US', { minimumIntegerDigits: 2 })}-${dateObject.year}`
}

function arrayToStrings(array) {
    return array.map(item => {
        return item.toString()
            .replaceAll('_', ' ')
    }).join(', ')
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
    ethiopianDate,
    ethiopianMonths, ethiopianDays, months, days,
    formatDate, getRandomInt, footerWords, formatPrice,
    formatNumber, arrayToStrings, formatNumberToTwoIntegerPlaces
}
