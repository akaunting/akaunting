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
    return evaluate(quantity.toString());
}

//This function wraps setTimeout function in a promise in order to display dom manipulations on root components asynchronously & fast 
const setPromiseTimeout = time => 
    new Promise(resolve => 
        setTimeout(() => 
            resolve(time)
        , time)
    );

export {getQueryVariable, removeURLParameter, calculationToQuantity, setPromiseTimeout}
