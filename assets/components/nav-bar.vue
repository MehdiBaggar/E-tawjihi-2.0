<script>
import { layoutMethods } from "@/state/helpers";
import img1 from "../styles/images/products/img-1.png"
import img2 from "../styles/images/products/img-2.png"
import img3 from "../styles/images/products/img-3.png"
import img4 from "../styles/images/products/img-6.png"
import img5 from "../styles/images/products/img-5.png"

import simplebar from "simplebar-vue";

import i18n from "../i18n";
import { mapGetters, mapActions } from 'vuex';

/**
 * Nav-bar Component
 */
export default {
  data() {
    return {
      cartItems: [
        {
          id: 1,
          productImage: img1,
          productName: "Branded T-Shirts",
          productLink: "/ecommerce/product-details",
          quantity: "10 x $32",
          itemPrice: "320",
        },
        {
          id: 2,
          productImage: img2,
          productName: "Bentwood Chair",
          productLink: "/ecommerce/product-details",
          quantity: "5 x $18",
          itemPrice: "89",
        },
        {
          id: 3,
          productImage: img3,
          productName: "Borosil Paper Cup",
          productLink: "/ecommerce/product-details",
          quantity: "3 x $250",
          itemPrice: "750",
        },
        {
          id: 4,
          productImage: img4,
          productName: "Gray Styled T-Shirt",
          productLink: "/ecommerce/product-details",
          quantity: "1 x $1250",
          itemPrice: "1250",
        },
        {
          id: 5,
          productImage: img5,
          productName: "Stillbird Helmet",
          productLink: "/ecommerce/product-details",
          quantity: "2 x $495",
          itemPrice: "990",
        },
      ],

      languages: [{
        flag: require("@/styles/images/flags/us.svg"),
        language: "en",
        title: "English",
      },
      {
        flag: require("@/styles/images/flags/spain.svg"),
        language: "sp",
        title: "Española",
      },
      {
        flag: require("@/styles/images/flags/germany.svg"),
        language: "gr",
        title: "Deutsche",
      },
      {
        flag: require("@/styles/images/flags/italy.svg"),
        language: "it",
        title: "italiana",
      },
      {
        flag: require("@/styles/images/flags/russia.svg"),
        language: "ru",
        title: "русский",
      },
      {
        flag: require("@/styles/images/flags/china.svg"),
        language: "ch",
        title: "中國人",
      },
      {
        flag: require("@/styles/images/flags/french.svg"),
        language: "fr",
        title: "Français",
      },
      {
        flag: require("@/styles/images/flags/ae.svg"),
        language: "ar",
        title: "Arabic",
      },
      ],
      lan: i18n.locale,
      text: null,
      flag: null,
      value: null,
      myVar: 1,
    };
  },
  components: {
    simplebar
  },

  methods: {
    ...mapActions('user', ['fetchUser']),
    ...layoutMethods,
    isCustomDropdown() {
      //Search bar
      var searchOptions = document.getElementById("search-close-options");
      var dropdown = document.getElementById("search-dropdown");
      var searchInput = document.getElementById("search-options");



      document.body.addEventListener("click", (e) => {
        if (e.target.getAttribute("id") !== "search-options") {
          dropdown.classList.remove("show");
          searchOptions.classList.add("d-none");
        }
      });
    },
    toggleHamburgerMenu() {
      var windowSize = document.documentElement.clientWidth;
      let layoutType = document.documentElement.getAttribute("data-layout");

      document.documentElement.setAttribute("data-sidebar-visibility", "show");
      let visiblilityType = document.documentElement.getAttribute("data-sidebar-visibility");

      if (windowSize > 767)
        document.querySelector(".hamburger-icon").classList.toggle("open");

      //For collapse horizontal menu
      if (
        document.documentElement.getAttribute("data-layout") === "horizontal"
      ) {
        document.body.classList.contains("menu") ?
          document.body.classList.remove("menu") :
          document.body.classList.add("menu");
      }

      //For collapse vertical menu

      if (visiblilityType === "show" && (layoutType === "vertical" || layoutType === "semibox")) {
        if (windowSize < 1025 && windowSize > 767) {
          document.body.classList.remove("vertical-sidebar-enable");
          document.documentElement.getAttribute("data-sidebar-size") == "sm" ?
            document.documentElement.setAttribute("data-sidebar-size", "") :
            document.documentElement.setAttribute("data-sidebar-size", "sm");
        } else if (windowSize > 1025) {
          document.body.classList.remove("vertical-sidebar-enable");
          document.documentElement.getAttribute("data-sidebar-size") == "lg" ?
            document.documentElement.setAttribute("data-sidebar-size", "sm") :
            document.documentElement.setAttribute("data-sidebar-size", "lg");
        } else if (windowSize <= 767) {
          document.body.classList.add("vertical-sidebar-enable");
          document.documentElement.setAttribute("data-sidebar-size", "lg");
        }
      }

      //Two column menu
      if (document.documentElement.getAttribute("data-layout") == "twocolumn") {
        document.body.classList.contains("twocolumn-panel") ?
          document.body.classList.remove("twocolumn-panel") :
          document.body.classList.add("twocolumn-panel");
      }
    },
    async logout() {
      try {
        await fetch('/user/logout', {
          method: 'GET', // or POST if needed
          credentials: 'include'
        });

        // Clear frontend state if needed
        localStorage.clear();

        // Redirect manually to homepage or login
        this.$router.push('/');
      } catch (error) {
        console.error('Logout failed:', error);
      }
    },
    toggleMenu() {
      this.$parent.toggleMenu();
    },
    toggleRightSidebar() {
      this.$parent.toggleRightSidebar();
    },
    initFullScreen() {
      document.body.classList.toggle("fullscreen-enable");
      if (
        !document.fullscreenElement &&
        /* alternative standard method */
        !document.mozFullScreenElement &&
        !document.webkitFullscreenElement
      ) {
        // current working methods
        if (document.documentElement.requestFullscreen) {
          document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
          document.documentElement.webkitRequestFullscreen(
            Element.ALLOW_KEYBOARD_INPUT
          );
        }
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        }
      }
    },
    setLanguage(locale, country, flag) {
      this.lan = locale;
      this.text = country;
      this.flag = flag;
      document.getElementById("header-lang-img").setAttribute("src", flag);
      i18n.global.locale = locale;
    },
    toggleDarkMode() {

      if (document.documentElement.getAttribute("data-bs-theme") == "dark") {
        document.documentElement.setAttribute("data-bs-theme", "light");
      } else {
        document.documentElement.setAttribute("data-bs-theme", "dark");
      }

      const mode = document.documentElement.getAttribute("data-bs-theme")
      this.changeMode({
        mode: mode,
      });
    },
    removeItem(cartItem) {
      this.cartItems = this.cartItems.filter(item => item.id !== cartItem.id)
      this.$emit("cart-item-price", this.cartItems.length);
    },
  },

  computed: {
    ...mapGetters('user', ['isAuthenticated', 'userFullName', 'userEmail',"userRole"]),
    calculateTotalPrice() {
      return this.cartItems.reduce((total, item) => total + parseFloat(item.itemPrice), 0).toFixed(2);
    },
    // You can also directly access state for debugging (not for template generally)
  },
  mounted() {
    this.fetchUser();
    if (process.env.VUE_APP_I18N_LOCALE) {
      this.flag = process.env.VUE_APP_I18N_LOCALE;
      this.languages.forEach((item) => {
        if (item.language == this.flag) {
          document.getElementById("header-lang-img").setAttribute("src", item.flag);
        }
      });
    }

    document.addEventListener("scroll", function () {
      var pageTopbar = document.getElementById("page-topbar");
      if (pageTopbar) {
        document.body.scrollTop >= 50 || document.documentElement.scrollTop >= 50 ? pageTopbar.classList.add(
          "topbar-shadow") : pageTopbar.classList.remove("topbar-shadow");
      }
    });
    if (document.getElementById("topnav-hamburger-icon"))
      document
        .getElementById("topnav-hamburger-icon")
        .addEventListener("click", this.toggleHamburgerMenu);

    this.isCustomDropdown();
  },
};
</script>

<template>
  <header id="page-topbar">
    <div class="layout-width">
      <div class="navbar-header">
        <div class="d-flex">
          <!-- LOGO -->
          <div class="navbar-brand-box horizontal-logo">
            <router-link to="/" class="logo logo-dark">
              <span class="logo-sm">
                <img src="@/styles/images/brand/white-logo.png" alt="" height="40" />
              </span>
              <span class="logo-lg">
                <img src="@/styles/images/brand/white-logo.png" alt="" height="40" />
              </span>
            </router-link>

            <router-link to="/" class="logo logo-light">
              <span class="logo-sm">
                <img src="@/styles/images/brand/white-logo.png" alt="" height="40" />
              </span>
              <span class="logo-lg">
                <img src="@/styles/images/brand/white-logo.png" alt="" height="40" />
              </span>
            </router-link>
          </div>

          <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
            id="topnav-hamburger-icon">
            <span class="hamburger-icon">
              <span></span>
              <span></span>
              <span></span>
            </span>
          </button>

          <!-- App Search-->
          <form class="app-search d-none d-md-block">
            <div class="position-relative">
              <input type="text" class="form-control" placeholder="Search..." autocomplete="off" id="search-options"
                value="" />
              <span class="mdi mdi-magnify search-widget-icon"></span>
              <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                id="search-close-options"></span>
            </div>
            <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
              <simplebar data-simplebar style="max-height: 320px">
                <div class="dropdown-header">
                  <h6 class="text-overflow text-muted mb-0 text-uppercase">
                    Recent Searches
                  </h6>
                </div>

                <div class="dropdown-item bg-transparent text-wrap">
                  <router-link to="/" class="btn btn-soft-secondary btn-sm rounded-pill">how to setup <i
                      class="mdi mdi-magnify ms-1"></i></router-link>
                  <router-link to="/" class="btn btn-soft-secondary btn-sm rounded-pill">buttons <i
                      class="mdi mdi-magnify ms-1"></i></router-link>
                </div>
                <div class="dropdown-header mt-2">
                  <h6 class="text-overflow text-muted mb-1 text-uppercase">
                    Pages
                  </h6>
                </div>

                <BLink href="javascript:void(0);" class="dropdown-item notify-item">
                  <i class=" ri-bubble-chart-line align-middle fs-18 text-muted me-2"></i>
                  <span>Analytics Dashboard</span>
                </BLink>

                <BLink href="javascript:void(0);" class="dropdown-item notify-item">
                  <i class="ri-lifebuoy-line align-middle fs-18 text-muted me-2"></i>
                  <span>Help Center</span>
                </BLink>

                <BLink href="javascript:void(0);" class="dropdown-item notify-item">
                  <i class=" ri-user-settings-line align-middle fs-18 text-muted me-2"></i>
                  <span>My account settings</span>
                </BLink>

                <div class="dropdown-header mt-2">
                  <h6 class="text-overflow text-muted mb-2 text-uppercase">
                    Members
                  </h6>
                </div>

                <div class="notification-list">
                  <BLink href="javascript:void(0);" class="d-flex dropdown-item notify-item py-2">
                    <img src="@/styles/images/users/avatar-2.jpg" class="me-3 rounded-circle avatar-xs" alt="user-pic" />
                    <div class="flex-grow-1">
                      <h6 class="m-0">Angela Bernier</h6>
                      <span class="fs-11 mb-0 text-muted">Manager</span>
                    </div>
                  </BLink>
                  <BLink href="javascript:void(0);" class="d-flex dropdown-item notify-item py-2">
                    <img src="@/styles/images/users/avatar-3.jpg" class="me-3 rounded-circle avatar-xs" alt="user-pic" />
                    <div class="flex-grow-1">
                      <h6 class="m-0">David Grasso</h6>
                      <span class="fs-11 mb-0 text-muted">Web Designer</span>
                    </div>
                  </BLink>
                  <BLink href="javascript:void(0);" class="d-flex dropdown-item notify-item py-2">
                    <img src="@/styles/images/users/avatar-5.jpg" class="me-3 rounded-circle avatar-xs" alt="user-pic" />
                    <div class="flex-grow-1">
                      <h6 class="m-0">Mike Bunch</h6>
                      <span class="fs-11 mb-0 text-muted">React Developer</span>
                    </div>
                  </BLink>
                </div>
              </simplebar>

              <div class="text-center pt-3 pb-1">
                <router-link to="/pages/search-results" class="btn btn-primary btn-sm">View All Results <i
                    class="ri-arrow-right-line ms-1"></i></router-link>
              </div>
            </div>
          </form>
        </div>

        <div class="d-flex align-items-center">
          <BDropdown class="dropdown d-md-none topbar-head-dropdown header-item" variant="ghost-secondary" dropstart
            :offset="{ alignmentAxis: 55, crossAxis: 15, mainAxis: 0 }"
            toggle-class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle show  arrow-none"
            menu-class="dropdown-menu-lg dropdown-menu-end p-0">
            <template #button-content>
              <i class="bx bx-search fs-22"></i>
            </template>
            <BDropdownItem aria-labelledby="page-header-search-dropdown">
              <form class="p-3">
                <div class="form-group m-0">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username" />
                    <BButton variant="primary" type="submit">
                      <i class="mdi mdi-magnify"></i>
                    </BButton>
                  </div>
                </div>
              </form>
            </BDropdownItem>
          </BDropdown>

          <BDropdown class="dropdown" variant="ghost-secondary" dropstart
            :offset="{ alignmentAxis: 55, crossAxis: 15, mainAxis: -50 }"
            toggle-class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle arrow-none"
            menu-class="dropdown-menu-end">
            <template #button-content> <img id="header-lang-img" src="@/styles/images/flags/us.svg" alt="Header Language"
                height="20" class="rounded">
            </template>
            <BLink href="javascript:void(0);" class="dropdown-item notify-item language py-2"
              v-for="(entry, key) in languages" :data-lang="entry.language" :title="entry.title"
              @click="setLanguage(entry.language, entry.title, entry.flag)" :key="key">
              <img :src="entry.flag" alt="user-image" class="me-2 rounded" height="18">
              <span class="align-middle">{{ entry.title }}</span>
            </BLink>
          </BDropdown>

          <BDropdown class="dropdown" variant="ghost-secondary" dropstart
            :offset="{ alignmentAxis: 57, crossAxis: 0, mainAxis: -42 }"
            toggle-class="btn-icon btn-topbar rounded-circle mode-layout ms-1 arrow-none"
            menu-class="p-0 dropdown-menu-end">
            <template #button-content>
              <i class="bx bx-category-alt fs-22"></i>
            </template>
            <div class="p-3 border-top-0 dropdown-head border-start-0 border-end-0 border-dashed border dropdown-menu-lg">
              <BRow class="align-items-center">
                <BCol>
                  <h6 class="m-0 fw-semibold fs-15">Web Apps</h6>
                </BCol>
                <BCol cols="auto">
                  <BLink href="#!" class="btn btn-sm btn-soft-info">
                    View All Apps
                    <i class="ri-arrow-right-s-line align-middle"></i>
                  </BLink>
                </BCol>
              </BRow>
            </div>

            <div class="p-2">
              <BRow class="g-0">
                <BCol>
                  <BLink class="dropdown-icon-item" href="#!">
                    <img src="@/styles/images/brands/github.png" alt="Github" />
                    <span>GitHub</span>
                  </BLink>
                </BCol>
                <BCol>
                  <BLink class="dropdown-icon-item" href="#!">
                    <img src="@/styles/images/brands/bitbucket.png" alt="bitbucket" />
                    <span>Bitbucket</span>
                  </BLink>
                </BCol>
                <BCol>
                  <BLink class="dropdown-icon-item" href="#!">
                    <img src="@/styles/images/brands/dribbble.png" alt="dribbble" />
                    <span>Dribbble</span>
                  </BLink>
                </BCol>
              </BRow>

              <BRow class="g-0">
                <BCol>
                  <BLink class="dropdown-icon-item" href="#!">
                    <img src="@/styles/images/brands/dropbox.png" alt="dropbox" />
                    <span>Dropbox</span>
                  </BLink>
                </BCol>
                <BCol>
                  <BLink class="dropdown-icon-item" href="#!">
                    <img src="@/styles/images/brands/mail_chimp.png" alt="mail_chimp" />
                    <span>Mail Chimp</span>
                  </BLink>
                </BCol>
                <BCol>
                  <BLink class="dropdown-icon-item" href="#!">
                    <img src="@/styles/images/brands/slack.png" alt="slack" />
                    <span>Slack</span>
                  </BLink>
                </BCol>
              </BRow>
            </div>
          </BDropdown>


          <div class="ms-1 header-item d-none d-sm-flex">
            <BButton type="button" variant="ghost-secondary" class="btn-icon btn-topbar rounded-circle"
              data-toggle="fullscreen" @click="initFullScreen">
              <i class="bx bx-fullscreen fs-22"></i>
            </BButton>
          </div>

          <div class="ms-1 header-item d-none d-sm-flex">
            <BButton type="button" variant="ghost-secondary" class="btn-icon btn-topbar rounded-circle light-dark-mode"
              @click="toggleDarkMode">
              <i class="bx bx-moon fs-22"></i>
            </BButton>
          </div>

          <BDropdown variant="ghost-dark" dropstart class="ms-1 dropdown"
            :offset="{ alignmentAxis: 57, crossAxis: 0, mainAxis: -42 }"
            toggle-class="btn-icon btn-topbar rounded-circle arrow-none" id="page-header-notifications-dropdown"
            menu-class="dropdown-menu-lg dropdown-menu-end p-0" auto-close="outside">
            <template #button-content>
              <i class='bx bx-bell fs-22'></i>
              <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger"><span
                  class="notification-badge">3</span><span class="visually-hidden">unread
                  messages
                </span>
              </span>
            </template>
            <div class="dropdown-head bg-primary bg-pattern rounded-top dropdown-menu-lg">
              <div class="p-3">
                <BRow class="align-items-center">
                  <BCol>
                    <h6 class="m-0 fs-16 fw-semibold text-white">
                      Notifications
                    </h6>
                  </BCol>
                  <BCol cols="auto" class="dropdown-tabs">
                    <BBadge variant="light-subtle" class="bg-light-subtle text-body fs-13"> 4 New</BBadge>
                  </BCol>
                </BRow>
              </div>
            </div>
            <BTabs nav-class="dropdown-tabs nav-tab-custom bg-primary px-2 pt-2">
              <BTab title=" All (4) " class="tab-pane fade py-2 ps-2 show" id="all-noti-tab" role="tabpanel">
                <simplebar data-simplebar style="max-height: 300px" class="pe-2">
                  <div class="text-reset notification-item d-block dropdown-item position-relative">
                    <div class="d-flex">
                      <div class="avatar-xs me-3 flex-shrink-0">
                        <span class="avatar-title bg-info-subtle text-info rounded-circle fs-16">
                          <i class="bx bx-badge-check"></i>
                        </span>
                      </div>
                      <div class="flex-grow-1">
                        <BLink href="#!" class="stretched-link">
                          <h6 class="mt-0 mb-2 lh-base">
                            Your <b>Elite</b> author Graphic Optimization
                            <span class="text-secondary">reward</span> is
                            ready!
                          </h6>
                        </BLink>
                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                          <span><i class="mdi mdi-clock-outline"></i> Just 30 sec ago</span>
                        </p>
                      </div>
                      <div class="px-2 fs-15">
                        <input class="form-check-input" type="checkbox" />
                      </div>
                    </div>
                  </div>

                  <div class="text-reset notification-item d-block dropdown-item position-relative">
                    <div class="d-flex">
                      <img src="@/styles/images/users/avatar-2.jpg" class="me-3 rounded-circle avatar-xs"
                        alt="user-pic" />
                      <div class="flex-grow-1">
                        <BLink href="#!" class="stretched-link">
                          <h6 class="mt-0 mb-1 fs-13 fw-semibold">
                            Angela Bernier
                          </h6>
                        </BLink>
                        <div class="fs-13 text-muted">
                          <p class="mb-1">
                            Answered to your comment on the cash flow forecast's graph 🔔.
                          </p>
                        </div>
                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                          <span><i class="mdi mdi-clock-outline"></i> 48 min ago</span>
                        </p>
                      </div>
                      <div class="px-2 fs-15">
                        <input class="form-check-input" type="checkbox" />
                      </div>
                    </div>
                  </div>

                  <div class="text-reset notification-item d-block dropdown-item position-relative">
                    <div class="d-flex">
                      <div class="avatar-xs me-3">
                        <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-16">
                          <i class="bx bx-message-square-dots"></i>
                        </span>
                      </div>
                      <div class="flex-grow-1">
                        <BLink href="#!" class="stretched-link">
                          <h6 class="mt-0 mb-2 fs-13 lh-base">
                            You have received <b class="text-success">20</b> new messages in the conversation
                          </h6>
                        </BLink>
                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                          <span><i class="mdi mdi-clock-outline"></i> 2 hrs
                            ago</span>
                        </p>
                      </div>
                      <div class="px-2 fs-15">
                        <input class="form-check-input" type="checkbox" />
                      </div>
                    </div>
                  </div>

                  <div class="text-reset notification-item d-block dropdown-item position-relative">
                    <div class="d-flex">
                      <img src="@/styles/images/users/avatar-8.jpg" class="me-3 rounded-circle avatar-xs"
                        alt="user-pic" />
                      <div class="flex-grow-1">
                        <BLink href="#!" class="stretched-link">
                          <h6 class="mt-0 mb-1 fs-13 fw-semibold">
                            Maureen Gibson
                          </h6>
                        </BLink>
                        <div class="fs-13 text-muted">
                          <p class="mb-1">
                            We talked about a project on linkedin.
                          </p>
                        </div>
                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                          <span><i class="mdi mdi-clock-outline"></i> 4 hrs
                            ago</span>
                        </p>
                      </div>
                      <div class="px-2 fs-15">
                        <input class="form-check-input" type="checkbox" />
                      </div>
                    </div>
                  </div>

                  <div class="my-3 text-center">
                    <BButton type="button" variant="soft-success">
                      View All Notifications
                      <i class="ri-arrow-right-line align-middle"></i>
                    </BButton>
                  </div>
                </simplebar>
              </BTab>

              <BTab title="Messages" class="tab-pane fade py-2 ps-2" id="messages-tab" role="tabpanel"
                aria-labelledby="messages-tab">
                <simplebar data-simplebar style="max-height: 300px" class="pe-2">
                  <div class="text-reset notification-item d-block dropdown-item">
                    <div class="d-flex">
                      <img src="@/styles/images/users/avatar-3.jpg" class="me-3 rounded-circle avatar-xs"
                        alt="user-pic" />
                      <div class="flex-grow-1">
                        <BLink href="#!" class="stretched-link">
                          <h6 class="mt-0 mb-1 fs-13 fw-semibold">
                            James Lemire
                          </h6>
                        </BLink>
                        <div class="fs-13 text-muted">
                          <p class="mb-1">
                            We talked about a project on linkedin.
                          </p>
                        </div>
                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                          <span><i class="mdi mdi-clock-outline"></i> 30 min ago</span>
                        </p>
                      </div>
                      <div class="px-2 fs-15">
                        <input class="form-check-input" type="checkbox" />
                      </div>
                    </div>
                  </div>

                  <div class="text-reset notification-item d-block dropdown-item">
                    <div class="d-flex">
                      <img src="@/styles/images/users/avatar-2.jpg" class="me-3 rounded-circle avatar-xs"
                        alt="user-pic" />
                      <div class="flex-grow-1">
                        <BLink href="#!" class="stretched-link">
                          <h6 class="mt-0 mb-1 fs-13 fw-semibold">
                            Angela Bernier
                          </h6>
                        </BLink>
                        <div class="fs-13 text-muted">
                          <p class="mb-1">
                            Answered to your comment on the cash flow
                            forecast's graph 🔔.
                          </p>
                        </div>
                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                          <span><i class="mdi mdi-clock-outline"></i> 2 hrs
                            ago</span>
                        </p>
                      </div>
                      <div class="px-2 fs-15">
                        <input class="form-check-input" type="checkbox" />
                      </div>
                    </div>
                  </div>

                  <div class="text-reset notification-item d-block dropdown-item">
                    <div class="d-flex">
                      <img src="@/styles/images/users/avatar-6.jpg" class="me-3 rounded-circle avatar-xs"
                        alt="user-pic" />
                      <div class="flex-grow-1">
                        <BLink href="#!" class="stretched-link">
                          <h6 class="mt-0 mb-1 fs-13 fw-semibold">
                            Kenneth Brown
                          </h6>
                        </BLink>
                        <div class="fs-13 text-muted">
                          <p class="mb-1">
                            Mentionned you in his comment on 📃 invoice
                            #12501.
                          </p>
                        </div>
                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                          <span><i class="mdi mdi-clock-outline"></i> 10 hrs
                            ago</span>
                        </p>
                      </div>
                      <div class="px-2 fs-15">
                        <input class="form-check-input" type="checkbox" />
                      </div>
                    </div>
                  </div>

                  <div class="text-reset notification-item d-block dropdown-item">
                    <div class="d-flex">
                      <img src="@/styles/images/users/avatar-8.jpg" class="me-3 rounded-circle avatar-xs"
                        alt="user-pic" />
                      <div class="flex-grow-1">
                        <BLink href="#!" class="stretched-link">
                          <h6 class="mt-0 mb-1 fs-13 fw-semibold">
                            Maureen Gibson
                          </h6>
                        </BLink>
                        <div class="fs-13 text-muted">
                          <p class="mb-1">
                            We talked about a project on linkedin.
                          </p>
                        </div>
                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                          <span><i class="mdi mdi-clock-outline"></i> 3 days
                            ago</span>
                        </p>
                      </div>
                      <div class="px-2 fs-15">
                        <input class="form-check-input" type="checkbox" />
                      </div>
                    </div>
                  </div>

                  <div class="my-3 text-center">
                    <BButton type="button" variant="soft-success">
                      View All Messages
                      <i class="ri-arrow-right-line align-middle"></i>
                    </BButton>
                  </div>
                </simplebar>
              </BTab>

              <BTab title="Alerts" class="p-4">
                <simplebar data-simplebar style="max-height: 300px" class="pe-2">
                  <div class="w-25 w-sm-50 pt-3 mx-auto">
                    <img src="@/styles/images/svg/bell.svg" class="img-fluid" alt="user-pic" />
                  </div>
                  <div class="text-center pb-5 mt-2">
                    <h6 class="fs-18 fw-semibold lh-base">
                      Hey! You have no any notifications
                    </h6>
                  </div>
                </simplebar>
              </BTab>
            </BTabs>
          </BDropdown>

          <BDropdown variant="link" class="ms-sm-3 header-item topbar-user" toggle-class="rounded-circle arrow-none"
            menu-class="dropdown-menu-end" :offset="{ alignmentAxis: -14, crossAxis: 0, mainAxis: 0 }">
            <template #button-content>
              <span class="d-flex align-items-center">
                <img class="rounded-circle header-profile-user" src="@/styles/images/noImage.png"
                  alt="Header Avatar">
                <span class="text-start ms-xl-2">
                  <span class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text">
                    {{ userFullName }}</span>
                  <span class="d-none d-xl-block ms-1 fs-13 user-name-sub-text">{{ userRole }}</span>
                </span>
              </span>
            </template>
            <h6 class="dropdown-header">Welcome {{ userFullName }}!</h6>
            <router-link class="dropdown-item" to="/profile"><i
                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
              <span class="align-middle"> Profile</span>
            </router-link>
            <a href="/user/logout" class="dropdown-item" @click.prevent="logout">
              <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
              <span class="align-middle" data-key="t-logout">Logout</span>
            </a>
          </BDropdown>
        </div>
      </div>
    </div>
  </header>
</template>