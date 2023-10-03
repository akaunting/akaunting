import axios from 'axios';
import config from './../../../../public/money.json';

export default class Currency {
    constructor(currency) {
        this.currencies = {};
        this.currency = (typeof currency == String) ? currency.trim().toUpperCase() : 'USD';
        this.currencies = this.getCurrencies();
       
        if (! Object.keys(this.currencies).includes(this.currency)) {
            throw new Error('Invalid currency "' + this.currency + '"');
        }

        let attributes = this.currencies[this.currency];

        this.name = attributes['name'];
        this.code = attributes['code'];
        this.rate = ((attributes['rate'] !== undefined) ? attributes['rate'] : 1);
        this.precision = attributes['precision'];
        this.subunit = attributes['subunit'];
        this.symbol = attributes['symbol'];
        this.symbolFirst = attributes['symbol_first'];
        this.decimalMark = attributes['decimal_mark'];
        this.thousandsSeparator = attributes['thousands_separator'];
    }

    setCurrencies(currencies) {
        this.currencies = currencies;
    }

    getCurrencies() {       
        return Object.keys(this.currencies).length ? this.currencies : config['currencies'];
    }

    getConfig() {
        return new Promise(function (resolve, reject) {
            axios.get('public/money.json')
            .then(
                (response) => {
                    let result = response.data;

                    resolve(result);
                },
                (error) => {
                    reject(error);
                }
            );
        });
    }

    equals(currency) {
        return this.getCurrency() === currency.getCurrency();
    }

    getCurrency() {
        return this.currency;
    }

    getName() {
        return this.name;
    }

    getCode() {
        return this.code;
    }

    getRate() {
        return this.rate;
    }

    getPrecision() {
        return this.precision;
    }

    getSubunit() {
        return this.subunit;
    }

    getSymbol() {
        return this.symbol;
    }

    isSymbolFirst() {
        return this.symbolFirst;
    }

    getDecimalMark() {
        return this.decimalMark;
    }

    getThousandsSeparator() {
        return this.thousandsSeparator;
    }

    getPrefix() {
        if (!this.symbolFirst) {
            return '';
        }

        return this.symbol;
    }

    getSuffix() {
        if (this.symbolFirst) {
            return '';
        }

        return ' ' . this.symbol;
    }

    render() {
        return this.currency + ' (' + this.name + ')';
    }
}
