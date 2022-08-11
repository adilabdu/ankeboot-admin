function formatNumber(num) {
    return num.toLocaleString('en', { useGrouping:true })
}

function formatNumberToTwoIntegerPlaces(num) {
    return parseInt(num).toLocaleString('en-US',
        { minimumIntegerDigits: 2 }
    )
}

function arrayToStrings(array) {
    return array.map(item => {
        return item.toString().replaceAll('_', ' ')
    }).join(', ')
}

export { formatNumber, arrayToStrings, formatNumberToTwoIntegerPlaces }
