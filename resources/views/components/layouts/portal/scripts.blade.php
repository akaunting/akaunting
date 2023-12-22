<!-- Core -->
<script src="{{ asset('public/vendor/js-cookie/js.cookie.js') }}"></script>

<script type="text/javascript">
    var company_currency_code = '{{ default_currency() }}';
</script>

@stack('scripts_start')

@apexchartsScripts

@stack('charts')

<!-- <script type="text/javascript" src="{{ asset('public/akaunting-js/hotkeys.js') }}" defer></script> -->
<script type="text/javascript" src="{{ asset('public/akaunting-js/generalAction.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/akaunting-js/popper.js') }}"></script>

<script type="text/javascript">
    "use strict";

    var Layout = (function() {

        const toggleButton = document.querySelector(".toggle-button");
        const sideBar = document.querySelector(".js-main-menu");
        const navbarMenu = document.querySelector(".js-menu");
        const mainContent = document.querySelector(".main-menu");
        const menus = document.querySelectorAll(".user-menu");
        const menuButtons = document.querySelectorAll(".menu-button");
        const detailsEL = mainContent.getElementsByTagName("details");
        const sectionContent = document.querySelector(".main-content");
        const menuBackground = document.querySelector(".js-menu-background");
        const menuClose = document.querySelector("[data-menu-close]");

        //animation for notification icon
        if (document.querySelector('[data-menu="notifications-menu"]')) {
            setTimeout(function() {
                document.querySelector('[data-menu="notifications-menu"]').classList.remove("animate-vibrate");
            }, 6000);
        }

        //chevron active class action
        Array.from(detailsEL).forEach((el) => {
                el.addEventListener("toggle", function(e) {
                    if(e.target.querySelector(".material-icons-outlined")) {
                        e.target.querySelector(".material-icons").classList.toggle("rotate-180");
                    } else {
                        e.target.querySelectorAll(".material-icons")[1].classList.toggle("rotate-180");
                    }
                })
            }
        );

        //container animation when left menu shrinking
        function contentTransitionLeft() {
            sectionContent.classList.add("xl:ltr:ml-0", "xl:rtl:mr-0");
            sectionContent.classList.remove("xl:ltr:ml-64", "xl:rtl:mr-64");
            toggleButton.querySelector("span").classList.remove("ltr:rotate-90", "rtl:-rotate-90");
            toggleButton.querySelector("span").classList.add("ltr:-rotate-90", "rtl:rotate-90");
        }

        //container animation when left menu unshrinking
        function contentTransitionRight() {
            sectionContent.classList.remove("xl:ltr:ml-0", "xl:rtl:mr-0");
            sectionContent.classList.add("xl:ltr:ml-64", "xl:rtl:mr-64");
            toggleButton.querySelector("span").classList.remove("ltr:-rotate-90", "rtl:rotate-90");
            toggleButton.querySelector("span").classList.add("ltr:rotate-90", "rtl:-rotate-90");
        }

        function hiddenSidebar() {
            sideBar.classList.add("menu-list-hidden");
            toggleButton.classList.add("ltr:left-12", "rtl:right-12");
        }

        function unHiddenSidebar() {
            toggleButton.classList.remove("ltr:left-12", "rtl:right-12");
            sideBar.classList.remove("menu-list-hidden");
        }

        //slide menu actions together responsive version
        function slideMenu() {
            if (document.body.clientWidth <= 1280) {
                mobileMenuHidden();
            } else {
                if (sideBar.classList.contains("menu-list-hidden")) {
                    unHiddenSidebar();

                    if (document.body.clientWidth > "991") {
                        contentTransitionRight();
                    }
                } else {
                    hiddenSidebar();

                    if (document.body.clientWidth > "991") {
                        contentTransitionLeft();
                    }
                }
            }

        }

        toggleButton.addEventListener("click", function() {
            slideMenu();
        });

        //general left menu actions (show settings menu etc..)
        function toggleMenu(iconButton, event) {
            const menuRef = iconButton.getAttribute("data-menu");
            const icon = iconButton.children[0].getAttribute("name");

            //if event target, profile
            if (iconButton.getAttribute("data-menu") === "profile-menu") {
                if (iconButton.children[0].textContent != "cancel") {
                    iconButton.children[0].classList.remove("hidden");
                    iconButton.children[1].classList.add("hidden");
                } else {
                    iconButton.children[0].classList.add("hidden");
                    iconButton.children[1].classList.remove("hidden");
                }
            }

            //remove active (cancel text) class form target icon
            menuButtons.forEach((button) => {
                if (icon) {
                    if (button.getAttribute("data-menu") !== menuRef && iconButton.children[0].textContent != "cancel") {
                        button.children[0].textContent = button.children[0].getAttribute("name");
                        button.children[0].classList.remove("active"); // inactive icon

                        let split_id = button.children[0].id.split("-cancel");
                        button.children[0].id = split_id[0];
                    }
                }
            });

            menus.forEach((menu) => {
                //add active (cancel text) class form target icon
                if (menu.classList.contains(menuRef) && iconButton.children[0].textContent != "cancel") {
                    iconButton.children[0].textContent = "cancel";
                    iconButton.children[0].classList.add("active");
                    iconButton.children[0].id += "-cancel";

                    menu.classList.remove("ltr:-left-80", "rtl:-right-80");
                    menu.classList.add("ltr:left-14", "rtl:right-14");
                    mainContent.classList.add("hidden");
                    toggleButton.classList.add("invisible");
                    menuClose.classList.remove("hidden");

                    //for hidden menu, show close icon scenario
                    if (sideBar.classList.contains("menu-list-hidden")) {
                        menuClose.classList.add("ltr:-right-57", "rtl:right-59");
                        menuClose.classList.remove("ltr:-right-2", "rtl:right-12");
                    } else {
                        menuClose.classList.add("ltr:-right-2", "rtl:right-12");
                    }

                    sectionContent.classList.remove("xl:ltr:ml-0", "xl:rtl:mr-0");
                    sectionContent.classList.add("xl:ltr:ml-64", "xl:rtl:mr-64");
                     //for hidden menu, show close icon scenario

                //remove active (cancel text) class form target icon
                } else if (menu.classList.contains(menuRef) && iconButton.children[0].textContent == "cancel") {
                    iconButton.children[0].textContent = icon;
                    iconButton.children[0].classList.remove("active");

                    let split_id = iconButton.children[0].id.split("-cancel");
                    iconButton.children[0].id = split_id[0];

                    menu.classList.add("ltr:-left-80", "rtl:-right-80");
                    menu.classList.remove("ltr:left-14", "rtl:right-14");
                    mainContent.classList.remove("hidden");
                    toggleButton.classList.remove("invisible");
                    menuClose.classList.add("hidden");
                //left menu slide to left
                } else {
                    menu.classList.add("ltr:-left-80", "rtl:-right-80");
                    menu.classList.remove("ltr:left-14", "rtl:right-14");
                }

                //close icon click event
                menuClose.addEventListener("click", function() {
                    menu.classList.add("ltr:-left-80", "rtl:-right-80");
                    iconButton.children[0].textContent = icon;
                    iconButton.children[0].classList.remove("active");
                    mainContent.classList.remove("hidden");
                    this.classList.add("hidden");
                    toggleButton.classList.remove("invisible");

                    //for hidden menu, show close icon scenario
                    if (sideBar.classList.contains("menu-list-hidden")) {
                        sectionContent.classList.add("xl:ltr:ml-0", "xl:rtl:mr-0");
                        sectionContent.classList.remove("xl:ltr:ml-64", "xl:rtl:mr-64");
                    }
                    //for hidden menu, show close icon scenario
                });
            });
        }

        if (document.body.clientWidth >= 1280) {
            //if url have profile menu, profile menu show
            if (is_profile_menu == 1) {
                let profile_menu_html = document.querySelector(".profile-menu");
                let profile_icon_html = document.querySelector("[data-menu='profile-menu']");

                profile_menu_html.classList.add("ltr:left-14", "rtl:right-14");
                profile_menu_html.classList.remove("ltr:-left-80", "rtl:-right-80");

                profile_icon_html.children[0].textContent = "cancel";
                profile_icon_html.children[0].classList.add("active");

                profile_icon_html.children[0].classList.remove("hidden");
                profile_icon_html.children[1].classList.add("hidden");
                toggleButton.classList.add("invisible");
                menuClose.classList.remove("hidden");
            }
        }

        //if mobile menu, menu is active
        function mobileMenuActive() {
            navbarMenu.classList.add("ltr:left-0", "rtl:right-0");
            navbarMenu.classList.remove("ltr:-left-80", "rtl:-right-80");

            menuBackground.classList.add("visible");
            menuBackground.classList.remove("invisible");
        }

        //if mobile menu, menu is hidden
        function mobileMenuHidden() {
            navbarMenu.classList.remove("ltr:left-0", "rtl:right-0");
            navbarMenu.classList.add("ltr:-left-80", "rtl:-right:80");
            mainContent.classList.remove("hidden");

            menus.forEach((menu) => {
                menu.classList.remove("ltr:left-14", "rtl:right-14");
                menu.classList.add("ltr:-left-80", "rtl:-right-80");
            });

            menuButtons.forEach((iconButton) => {
                iconButton.children[0].classList.remove("active");
                iconButton.children[0].textContent = iconButton.children[0].getAttribute("name");
            });

            menuBackground.classList.remove("visible");
            menuBackground.classList.add("invisible");
        }

        document.querySelector(".js-hamburger-menu").addEventListener("click", function() {
            mobileMenuActive();
        });

        menuBackground.addEventListener("click", function() {
            mobileMenuHidden();
        });

        menuButtons.forEach((iconButton) =>
            iconButton.addEventListener("click", function() {
                toggleMenu(iconButton, event);
            })
        );
    })(500);
</script>

@stack('body_css')

@stack('body_stylesheet')

@stack('body_js')

@stack('body_scripts')

@livewireScripts

@stack('scripts_end')
