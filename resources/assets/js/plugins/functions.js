// Get Url Paramater
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);

    var vars = query.split("&");

    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");

        if (pair[0] == variable) {
            return pair[1];
        }
    }

    return(false);
}

function removeURLParameter(param) {
    let url = window.location.href;
    let regex = new RegExp("[?&]" + param + "(=[^&#]*)?", "g");

    url = url.replace(regex, "");
    url = url.replace(/[?&]$/, "");

    window.history.replaceState({}, document.title, url);
}

const { evaluate } = require('mathjs');

// use the evaluate function to evaluate the expression
function calculationToQuantity(quantity) {
    let expression = quantity.toString().trim();

    // Only plain arithmetic reaches the parser, mirroring calculation_to_quantity() on the
    // server, so no symbol, function or property access can be evaluated in the browser.
    if (! /^[0-9,+\-x*\/().\s]+$/i.test(expression) || expression.length > 255) {
        return NaN;
    }

    return evaluate(expression.replace(/,/g, '.').replace(/x/gi, '*'));
}

//This function wraps setTimeout function in a promise in order to display dom manipulations on root components asynchronously & fast
const setPromiseTimeout = time =>
    new Promise(resolve =>
        setTimeout(() =>
            resolve(time)
        , time)
    );

const DOMPurify = require('dompurify');

// Messages are translations with simple formatting, like <b>:name</b> or a link to a page,
// filled with names people type, so only that formatting is kept. The server side escapes
// the names with escape_html() too.
function sanitizeHtml(html) {
    if (html === null || html === undefined || html === '') {
        return html;
    }

    return DOMPurify.sanitize(String(html), {
        ALLOWED_TAGS: ['a', 'b', 'br', 'em', 'i', 'li', 'ol', 'p', 'span', 'strong', 'u', 'ul'],
        ALLOWED_ATTR: ['class', 'href', 'rel', 'target'],
    });
}

export {getQueryVariable, removeURLParameter, calculationToQuantity, setPromiseTimeout, sanitizeHtml}
