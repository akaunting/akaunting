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

function promiseTimeout(time) {
    return new Promise(function(resolve,reject) {
      setTimeout(function(){
          resolve(time);
      }, time);
    });
};



export {getQueryVariable, promiseTimeout}
