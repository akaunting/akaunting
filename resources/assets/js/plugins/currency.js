import axios from 'axios';
import config from './../../../../public/money.json';

export default class Currency {
    constructor(currency) {
        this.currencies = {};

        this.currency = (typeof currency === 'string') ? currency.trim().toUpperCase() : 'USD';

        this.currencies = this.getCurrencies();

        // Unknown codes keep falling back to USD, as they always did before the typeof fix
        if (! Object.keys(this.currencies).includes(this.currency)) {
            console.warn('Invalid currency "' + this.currency + '", falling back to USD');

            this.currency = 'USD';
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
        if (Object.keys(this.currencies).length) {
            return this.currencies;
        }

        let currencies = Object.assign({}, config['currencies']);

        // Company settings override the bundled defaults, the same way
        // App\Utilities\Overrider::loadCurrencies() does on the server
        this.getCompanyCurrencies().forEach(function (currency) {
            if (! currency || typeof currency.code !== 'string') {
                return;
            }

            let code = currency.code.trim().toUpperCase();
            let attributes = Object.assign({}, currencies[code] || { code: code });

            ['name', 'precision', 'symbol', 'symbol_first', 'decimal_mark', 'thousands_separator'].forEach(function (key) {
                if (currency[key] !== undefined && currency[key] !== null) {
                    attributes[key] = currency[key];
                }
            });

            attributes['precision'] = parseInt(attributes['precision'], 10) || 0;

            currencies[code] = attributes;
        });

        return currencies;
    }

    // Reads the currency lists the layouts already print, nothing new is injected
    getCompanyCurrencies() {
        if (typeof window === 'undefined') {
            return [];
        }

        let company_currencies = [];

        [window.all_currencies, window.document_currencies].forEach(function (list) {
            if (list && typeof list === 'object') {
                company_currencies = company_currencies.concat(Object.values(list));
            }
        });

        return company_currencies;
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
