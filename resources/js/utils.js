function formatNumber(num) {
    return num.toLocaleString('en', { useGrouping:true })
}

export { formatNumber }
