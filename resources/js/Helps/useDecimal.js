// resources/js/Helps/useDecimal.js
import Decimal from "decimal.js";

// Configurar Decimal
Decimal.set({
    precision: 12,
    rounding: Decimal.ROUND_HALF_EVEN,
});

// Funciones auxiliares
const safeDecimal = (value, fallback = 0) => {
    return new Decimal(
        value === null || value === undefined ? fallback : value
    );
};

const redondear = (num, decimales = 2) => {
    return safeDecimal(num)
        .toDecimalPlaces(decimales, Decimal.ROUND_HALF_EVEN)
        .toNumber();
};

const truncatetoStringDecimals = (number, scale = 2) => {
    const [intPart, decPart = ""] = number.toString().split(".");
    const truncated = `${intPart}.${decPart
        .substring(0, scale)
        .padEnd(scale, "0")}`;
    return parseFloat(truncated);
};

const truncateMathDecimals = (value) => {
    const factor = Math.pow(10, 3);
    return Math.floor(value * factor) / factor;
};

// Exportar todo como objeto
export default {
    Decimal,
    safeDecimal,
    redondear,
    truncatetoStringDecimals,
    truncateMathDecimals,
};
