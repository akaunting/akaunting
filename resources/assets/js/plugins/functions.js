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

//This function wraps setTimeout function in a promise in order to display dom manipulations on root components asynchronously & fast 
const setPromiseTimeout = time => 
    new Promise(resolve => 
        setTimeout(() => 
            resolve(time)
        , time)
    );

export {getQueryVariable, setPromiseTimeout}
