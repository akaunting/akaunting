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
    if (document.body.clientWidth >= 991) {
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
                    td_item.addEventListener("click", () => {
                        window.location.href = row_href;
                    });
                }
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
        tooltipEl.classList.remove("opacity-0");
        tooltipEl.classList.add("opacity-100");
        tooltipEl.classList.remove("invisible");
        tooltipEl.classList.add("visible"); // Enable the event listeners

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

    function hide() {
        // Hide the tooltip
        tooltipEl.classList.remove("opacity-100");
        tooltipEl.classList.add("opacity-0");
        tooltipEl.classList.remove("visible");
        tooltipEl.classList.add("invisible"); // Disable the event listeners

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