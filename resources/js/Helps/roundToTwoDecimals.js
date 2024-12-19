export default function roundToTwoDecimals(value) {
    return parseFloat(parseFloat(value).toFixed(2));
}
