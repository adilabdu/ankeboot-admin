function formatNumber(num) {
    return num.toLocaleString('en', { useGrouping:true })
}

function arrayToStrings(array) {
    return array.map(item => {
        return item.toString().replaceAll('_', ' ')
    }).join(', ')
}

export { formatNumber, arrayToStrings }
