import Sidebar from './SideBar.vue';
import SidebarItem from './SidebarItem.vue';

const SidebarStore = {
  showSidebar: true,
  sidebarLinks: [],
  isMinimized: false,
  breakpoint: 1200,
  displaySidebar(value) {
    if (window.innerWidth > this.breakpoint) {
      return;
    }
    this.showSidebar = value;
    let docClasses = document.body.classList
    if (value) {
      docClasses.add('g-sidenav-pinned')
      docClasses.add('g-sidenav-show')
      docClasses.remove('g-sidenav-hidden')
    } else {
      docClasses.add('g-sidenav-hidden')
      docClasses.remove('g-sidenav-pinned')
    }
  },
  toggleMinimize() {
    this.isMinimized = !this.isMinimized;
    let docClasses = document.body.classList
    if (this.isMinimized) {
      docClasses.add('g-sidenav-hidden')
      docClasses.remove('g-sidenav-pinned')
    } else {
      docClasses.add('g-sidenav-pinned')
      docClasses.remove('g-sidenav-hidden')
    }
  },
  onMouseEnter() {
    if (this.isMinimized) {
      document.body.classList.add('g-sidenav-show')
      document.body.classList.remove('g-sidenav-hidden')
    }
  },
  onMouseLeave() {
    if (this.isMinimized) {
      let docClasses = document.body.classList
      docClasses.remove('g-sidenav-show')
      docClasses.add('g-sidenav-hide')
      setTimeout(() => {
        docClasses.remove('g-sidenav-hide')
        docClasses.add('g-sidenav-hidden')
      }, 300)
    }
  }
};

const SidebarPlugin = {
  install(Vue, options) {
    if (options && options.sidebarLinks) {
      SidebarStore.sidebarLinks = options.sidebarLinks;
    }
    let app = new Vue({
      data: {
        sidebarStore: SidebarStore
      }
    });
    Vue.prototype.$sidebar = app.sidebarStore;
    Vue.component('side-bar', Sidebar);
    Vue.component('sidebar-item', SidebarItem);
  }
};

export default SidebarPlugin;
