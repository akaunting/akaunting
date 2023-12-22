import Currency from './currency';

export default class Money {
    constructor(currency, amount, format) {
        this.currency = new Currency(currency);
        this.amount = amount;
        this.format = format;

        this.mutable = false;
    }

    isZero() {
        return this.amount == 0;
    }

    isPositive() {
        return this.amount > 0;
    }

    isNegative() {
        return this.amount < 0;
    }

    format() {
        let negative = this.isNegative();
        let value = this.getValue();
        let amount = negative ? -value : value;
        let thousands = this.currency.getThousandsSeparator();
        let decimals = this.currency.getDecimalMark();
        let prefix = this.currency.getPrefix();
        let suffix = this.currency.getSuffix();

        value = this.number_format(amount, this.currency.getPrecision(), decimals, thousands);

        return (negative ? '-' : '') + prefix + value + suffix;
    }

    getValue() {
        return this.round(this.amount / this.currency.getSubunit);
    }

    getAmount(rounded = false) {
        return this.amount;

        return rounded ? this.getRoundedAmount() : this.amount;
    }

    getRoundedAmount() {
        return this.round(this.amount);
    }

    number_format(number, decimals, decPoint, thousandsSep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');

        const n = ! isFinite(+number) ? 0 : +number;
        const prec = ! isFinite(+decimals) ? 0 : Math.abs(decimals);
        const sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
        const dec = (typeof decPoint === 'undefined') ? '.' : decPoint;

        let s = '';

        const toFixedFix = function (n, prec) {
            if (('' + n).indexOf('e') === -1) {
                return +(Math.round(n + 'e+' + prec) + 'e-' + prec);
            } else {
                const arr = ('' + n).split('e');
                let sig = '';

                if (+arr[1] + prec > 0) {
                    sig = '+';
                }
        
                return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec);
            }
        };

        // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.');

        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }

        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }

        return s.join(dec);
    }

    multiply(multiplier, roundingMode) {
        let amount = this.round(this.amount * multiplier, roundingMode);

        if (this.isImmutable()) {
            return new Money(this.currency, amount);
        }

        this.amount = amount;

        return this;
    }

    divide(divisor, roundingMode) {
        this.assertDivisor(divisor);

        let amount = this.round(this.amount / divisor, roundingMode);

        if (this.isImmutable()) {
            return new Money(this.currency, amount);
        }

        this.amount = amount;

        return this;
    }

    round(amount, mode) {
        let precision = this.currency.getPrecision();
        var amount_sign = amount >= 0 ? 1 : -1;

        return parseFloat((Math.round((amount * Math.pow(10, precision)) + (amount_sign * 0.0001)) / Math.pow(10, precision)).toFixed(precision));
    }

    assertDivisor(divisor) {
        if (divisor == 0) {
            throw new Error('Division by zero');
        }
    }

    isMutable() {
        return this.mutable === true;
    }

    isImmutable() {
        return ! this.isMutable();
    }
}
