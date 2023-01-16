// remove dropdown menu when mouseleave in index more actions
document.querySelectorAll("[data-table-list]").forEach((row) => {
  row.addEventListener("mouseleave", function() {
      if (row.querySelector("[data-dropdown-actions]")) {
          row.querySelector("[data-dropdown-actions]").classList.remove("block");
          row.querySelector("[data-dropdown-actions]").classList.add("hidden");
      }
  });
});
// remove dropdown menu when mouseleave in index more actions

//redirect edit or show page for table row click
document.querySelectorAll("[data-table-body]").forEach((table) => {
    if (document.body.clientWidth < 768 || document.body.clientWidth > 1200) {
        let rows = table.querySelectorAll("tr");

        rows.forEach((row) => {
            let row_href = row.getAttribute("href");

            if (!row_href) {
                return;
            }

            let td = row.getElementsByTagName("td");
            let first_selector = row.querySelector("[data-bulkaction]") && row.querySelector("[data-bulkaction]") !== null ? 1 : 0;

            if (row_href) {
                for (let i = first_selector; i < td.length - 1; i++) {
                    let td_item = td[i];

                    td_item.addEventListener("click", (event) => {
                        if (document.body.clientWidth < 768 && event.target.closest('[overflow-x-hidden]')) {
                            return;
                        }
                        // click disabled when preview dialog is open
                        if (event.target.closest('[data-tooltip-target]')) {
                            return;
                        }
                        // click disabled when preview dialog is open
                        window.location.href = row_href;
                    });

                    // added target blank for click mouse middle button
                    td_item.addEventListener('mousedown', (event) => {
                        if (event.button == 1 || event.buttons == 4) {
                            window.open(row_href, "_blank");
                        }
                     });
                     // added target blank for click mouse middle button
                }
            }
        });
    }

    if (document.body.clientWidth <= 768) {
        table.querySelectorAll('[data-table-list]').forEach((actions) => {
            let actions_html = actions.querySelector('[data-mobile-actions]');
            if (actions_html) {
                actions_html.addEventListener('click', function() {
                    this.closest('td').querySelector('[data-mobile-actions-modal]').classList.add('show');
                    this.closest('td').querySelector('[data-mobile-actions-modal]').classList.remove('opacity-0', 'invisible');
            
                    this.closest('td').querySelector('[data-mobile-actions-modal]').addEventListener('click', function() {
                        this.classList.add('opacity-0', 'invisible');
                        this.classList.remove('show');
                    });
                });
            }
        });
    }
});
//redirect edit or show page for table row click

//collapse accordion
function toggleSub(key, event) {
  let isExpanded =
      document.querySelectorAll(
          `[data-collapse="${key}"]` + ".active-collapse"
      ).length > 0;

  if (isExpanded) {
      this.collapseSub(key, event);
  } else {
      this.expandSub(key, event);
  }
}

function collapseSub(key, event) {
  event.stopPropagation();
  event.target.classList.add("rotate-90");

  document
      .querySelectorAll(`[data-collapse="${key}"]` + ".active-collapse")
      .forEach(function(element) {
          element.classList.toggle("active-collapse");
          element.classList.toggle("collapse-sub");
      });

  // if collapsed key has childs(table row constantly), they will be collapsed as well
  document
      .querySelectorAll(`[data-collapse="${key}"]` + " button[node|='child']")
      .forEach(function(element) {
              element.childNodes[0].classList.add("rotate-90")

              this.collapseSub(element.getAttribute("node"), event);
          }.bind(this)
      );
}

function expandSub(key, event) {
  event.stopPropagation();
  event.target.classList.remove("rotate-90");

  document
      .querySelectorAll(`[data-collapse="${key}"]`)
      .forEach(function(element) {
          if (element.getAttribute("data-animation")) {
              element.classList.toggle("active-collapse-animation");
          }

          element.classList.toggle("active-collapse");
          element.classList.toggle("collapse-sub");
      });
}
//collapse accordion

// run dropdown and tooltip functions for Virtual DOM
document.addEventListener("DOMContentLoaded", () => {    
    const triggers = [
        { event: "mouseover", checker: isHoverable },
        { event: "mouseout", checker: isHoverable },
        { event: "click", checker: isClickable },
    ];

    triggers.forEach(({ event, checker, fn }) => {
        document.addEventListener(
            event,
            (e) => {
                const dropdownToggleEl = e.target.closest(
                    "[data-dropdown-toggle]"
                );
                const tooltipToggleEl = e.target.closest(
                    "[data-tooltip-target]"
                );
                if (dropdownToggleEl !== null && event == "click") {
                    runDropdown(dropdownToggleEl);
                }

                if (tooltipToggleEl !== null && event == "mouseover") {
                  runTooltip(tooltipToggleEl);
              }
            },
            false
        );
    });
});

function isClickable(dropdownToggleEl) {
    return dropdownToggleEl.getAttribute("data-dropdown-toggle") === "click";
}

function isHoverable(tooltipToggleEl) {
    return tooltipToggleEl.getAttribute("data-tooltip-target") === "hover";
}
//run dropdown and tooltip functions for Virtual DOM

// Toggle dropdown elements using [data-dropdown-toggle]
function runDropdown(dropdownToggleEl) {
    const dropdownMenuId = dropdownToggleEl.getAttribute(
        "data-dropdown-toggle"
    );
    const dropdownMenuEl = document.getElementById(dropdownMenuId); // options

    const placement = dropdownToggleEl.getAttribute("data-dropdown-placement");

    var element = dropdownToggleEl;

    while (element.nodeName !== "BUTTON") {
        element = element.parentNode;
    }

    Popper.createPopper(element, dropdownMenuEl, {
        placement: placement ? placement : "bottom-start",
        modifiers: [
            {
                name: "offset",
                options: {
                    offset: [0, 10],
                },
            },
        ],
    }); // toggle when click on the button

    if (dropdownMenuEl !== null) {
        dropdownMenuEl.classList.toggle("hidden");
        dropdownMenuEl.classList.toggle("block");

        function handleDropdownOutsideClick(event) {
            var targetElement = event.target; // clicked element
    
            if (
                targetElement !== dropdownMenuEl &&
                targetElement !== dropdownToggleEl &&
                !dropdownToggleEl.contains(targetElement)
            ) {
                dropdownMenuEl.classList.add("hidden");
                dropdownMenuEl.classList.remove("block");
                document.body.removeEventListener(
                    "click",
                    handleDropdownOutsideClick,
                    true
                );
            }
        } // hide popper when clicking outside the element
    
        document.body.addEventListener("click", handleDropdownOutsideClick, true);
    
        if (dropdownMenuEl.getAttribute("data-click-outside-none") != null) {
            if (event.target.getAttribute("data-click-outside") != null || event.target.parentElement.getAttribute("data-click-outside") != null) {
                dropdownMenuEl.classList.add("hidden");
                dropdownMenuEl.classList.remove("block");
                return;
            }
            
            dropdownMenuEl.classList.add("block");
            dropdownMenuEl.classList.remove("hidden");
        }
    }
}
// Toggle dropdown elements using [data-dropdown-toggle]

// Tooltip elements using [data-tooltip-target], [data-tooltip-placement]
function runTooltip(tooltipToggleEl) {
    const tooltipEl = document.getElementById(
        tooltipToggleEl.getAttribute("data-tooltip-target")
    );
    const placement = tooltipToggleEl.getAttribute("data-tooltip-placement");
    const trigger = tooltipToggleEl.getAttribute("data-tooltip-trigger");
    const popperInstance = Popper.createPopper(tooltipToggleEl, tooltipEl, {
        placement: placement ? placement : "top",
        modifiers: [
            {
                name: "offset",
                options: {
                    offset: [0, 8],
                },
            },
        ],
    });

    function show() {
        // Make the tooltip visible
        if (tooltipEl !== null) {
            if (tooltipEl.classList.contains("opacity-0", "invisible")) {
                tooltipEl.classList.remove("opacity-0", "invisible");
            } else {
                tooltipEl.classList.add("opacity-100", "visible");
            }

             // Enable the event listeners

            popperInstance.setOptions((options) => ({
                ...options,
                modifiers: [
                    ...options.modifiers,
                    {
                        name: "eventListeners",
                        enabled: true,
                    },
                ],
            })); // Update its position

            popperInstance.update();
        }
    }

    function hide() {
        if (tooltipEl !== null) {
            // Hide the tooltip
            if (tooltipEl.classList.contains("opacity-100", "visible")) {
                tooltipEl.classList.remove("opacity-100", "visible");
            } else {
                tooltipEl.classList.add("opacity-0", "invisible");
            }
            // Disable the event listeners

            popperInstance.setOptions((options) => ({
                ...options,
                modifiers: [
                    ...options.modifiers,
                    {
                        name: "eventListeners",
                        enabled: false,
                    },
                ],
            }));
        }
    }

    var showEvents = [];
    var hideEvents = [];

    switch (trigger) {
        case "hover":
            showEvents = ["mouseenter", "focus"];
            hideEvents = ["mouseleave", "blur"];
            break;

        case "click":
            showEvents = ["click", "focus"];
            hideEvents = ["focusout", "blur"];
            break;

        default:
            showEvents = ["mouseenter", "focus"];
            hideEvents = ["mouseleave", "blur"];
    }

    showEvents.forEach((event) => {
        tooltipToggleEl.addEventListener(event, show);
    });
    hideEvents.forEach((event) => {
        tooltipToggleEl.addEventListener(event, hide);
    });
}
// Tooltip elements using [data-tooltip-target], [data-tooltip-placement]

//Auto Height for Textarea
const tx = document.querySelectorAll('[textarea-auto-height]');
for (let i = 0; i < tx.length; i++) {
  tx[i].setAttribute('style', 'height:' + (tx[i].scrollHeight) + 'px;overflow-y:hidden;');
  tx[i].addEventListener('input', OnInput, false);
}

function OnInput() {
  this.style.height = 'auto';
  this.style.height = (this.scrollHeight) + 'px';
}
//Auto Height for Textarea

//Loading scenario for href links
document.querySelectorAll('[data-link-loading]').forEach((href) => {
    let target_link_html = href.parentElement;
    target_link_html.classList.add('relative');

    target_link_html.addEventListener('click', function () {
        this.classList.add('disabled-link');

        this.querySelector('[data-link-spin]').classList.remove('hidden');
        this.querySelector('[data-link-text]').classList.add('opacity-0');
        this.querySelector('[data-link-text]').classList.remove('opacity-1');
    });
});
//Loading scenario for href links

//margue animation for truncated text
function marqueeAnimation(truncate) {
    if (truncate.closest('[disable-marquee]') !== null) {
        truncate.parentElement.classList.add('truncate');
        truncate.closest('[disable-marquee]').setAttribute('disable-marquee', 'data-disable-marquee');
        return;
    }
    // offsetwidth = width of the text, clientWidth = width of parent text (div)
    // because some index page has icons, we use two time parent element

    if (truncate.children.length < 1 && truncate.offsetWidth > truncate.parentElement.clientWidth || truncate.offsetWidth > truncate.parentElement.parentElement.parentElement.clientWidth) {
        truncate.addEventListener('mouseover', function () {
            truncate.parentElement.style.animationPlayState = 'running';

            if (truncate.offsetWidth > 400 && truncate.parentElement.clientWidth < 150) {
                truncate.parentElement.classList.remove('animate-marquee');
                truncate.parentElement.classList.add('animate-marquee_long');
            } else {
                truncate.parentElement.classList.remove('animate-marquee_long');
                truncate.parentElement.classList.add('animate-marquee');
            }
    
            if (truncate.parentElement.classList.contains('truncate')) {
                truncate.parentElement.classList.remove('truncate');
            }
        });
    
        truncate.addEventListener('mouseout', function () {
            truncate.parentElement.style.animationPlayState = 'paused';
            truncate.parentElement.classList.remove('animate-marquee');
            truncate.parentElement.classList.remove('animate-marquee_long');
            truncate.parentElement.classList.add('truncate');
        });

        truncate.classList.add('truncate');

        // if truncate has truncate class, text marquee animate doesn't pretty work
        if (truncate.querySelector('.truncate') !== null && truncate.querySelector('.truncate').classList.contains('truncate')) {
            let old_element = truncate.querySelector('.truncate');
            let parent = old_element.parentNode;
            
            let new_element = document.createElement('span');
            new_element.innerHTML = old_element.innerHTML;
            new_element.classList = old_element.classList;

            parent.replaceChild(new_element, old_element);
        }
        // if truncate has truncate class, text marquee animate doesn't pretty work
        
        // There needs to be two div for disable/enable icons. If I don't create this div, animation will work with disable/enable icons.-->
        let animate_element = document.createElement('div');
        animate_element.classList.add('truncate');
        truncate.parentElement.append(animate_element);
        animate_element.append(truncate);
        // There needs to be two div for disable/enable icons. If I don't create this div, animation will work with disable/enable icons.-->

        //there is overflow class for the animation does not overflow the width
        truncate.parentElement.parentElement.classList.add('overflow-x-hidden');
        truncate.parentElement.parentElement.setAttribute('overflow-x-hidden', true);
    }
}

document.querySelectorAll('[data-truncate-marquee]').forEach((truncate) => {
    marqueeAnimation(truncate);
});

//disable/enable icons ejected from data-truncate-marquee, HTML of icons ejected from parent element (data-truncate-marquee)
document.querySelectorAll('[data-index-icon]').forEach((defaultText) => {
    if (defaultText.closest('[data-table-list]')) {
        let icon_parents_element = defaultText.parentElement.parentElement.parentElement;
    
        if (icon_parents_element.classList.contains('flex')) {
            icon_parents_element.appendChild(defaultText);
            icon_parents_element.classList.remove('truncate');
        } else {
            if (icon_parents_element.classList.contains('overflow-x-hidden')) {
                icon_parents_element.parentElement.appendChild(defaultText);
            } else {
                defaultText.parentElement.appendChild(defaultText);
            }
        }
    }
});
//disable/enable icons ejected from data-truncate-marquee

//margue animation for truncated text

// set with for page header
document.querySelectorAll('[data-page-title-first]').forEach((first) => {
    document.querySelectorAll('[data-page-title-second]').forEach((second) => {
        let title_truncate = first.querySelector('[data-title-truncate]');

        if (title_truncate !== null) {
            //added for equalize h1 width and parent element width. Because parent element has -ml-0.5 so didn't equalize
            first.querySelector('h1').classList.add('mr-0.5');
            //added for equalize h1 width and parent element width. Because parent element has -ml-0.5 so didn't equalize
            
            if (first.clientWidth < title_truncate.clientWidth && second.clientHeight > 0) {
                // added specific width styling for truncate text
                title_truncate.style.width = first.clientWidth + 'px';
                let subtract = title_truncate.clientWidth - 40;
                title_truncate.style.width = subtract + 'px';
                title_truncate.classList.add('truncate');
                // added specific width styling for truncate text
    
                // added specific width styling into the parent title element for truncate text
                first.classList.add('w-full', 'sm:w-6/12');
                // added specific width styling into the parent title element for truncate text
    
                title_truncate.parentNode.classList.add('overflow-x-hidden', 'hide-scroll-bar');
        
                // added truncate animation for truncated text
                title_truncate.addEventListener('mouseover', function () {
                    this.classList.add('animate-marquee');
                    this.classList.remove('truncate');
                    this.style.animationPlayState = 'running';
                });
        
                title_truncate.addEventListener('mouseout', function () {
                    this.style.animationPlayState = 'paused';
                    this.classList.remove('animate-marquee');
                    this.classList.add('truncate');
                });
                // added truncate animation for truncated text

                first.querySelector('h1').classList.remove('mr-0.5');
            }
        }

        // remove width class name for extend the right side
        first.classList.remove('w-full', 'sm:w-6/12');
        // remove width class name for extend the right side
    });
});
// set with for page header
