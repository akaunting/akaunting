let shortcuts;

axios.get('public/shortcuts-config.json')
  .then(function (response) {
    shortcuts = response.data
  })

const handlePageEvent = (event, routeData) => {
    const hotkeys = Object.keys(routeData);

    hotkeys.includes([event.code])
        ? routeData[event.code]() //type of function - to execute when the event happens
        : {}
};

const handlePrint = () => {
    window.location.replace(window.location.href + '/print');
};

const handleKeydown = (event) => {
    const keyName = event.key;
    const urlPath = window.location.href;
    const constainsDocID = !isNaN(urlPath.substr(-1));

    if (keyName === ('Meta' || 'Control' || 'Alt')) {
        return;
    }

    if (event.metaKey || event.ctrlKey) {
        const action = shortcuts.ctrlKey[event.code];

        action
            ? (event.preventDefault(), handleShortCuts(action))
            : {};
    }

    if (event.altKey) {
        const action = shortcuts.altKey[event.code];

        action
            ? handleShortCuts(action)
            : {};
    }

    const matchingRoute = Object.keys(shortcuts.pages).filter(route => urlPath.includes(route));

    matchingRoute 
        ? constainsDocID && event.code === 'KeyP'
            ? handlePrint()
            : handlePageEvent(event, matchingRoute)
        : {};
};

const handleShortCuts = (target) => {
    let targetURL = url + target;

    window.location.replace(targetURL);
};

document.addEventListener('keydown', (event) => {
    handleKeydown(event)
}, false);
