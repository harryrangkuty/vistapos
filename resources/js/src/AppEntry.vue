<script>
import { theme } from 'ant-design-vue'

export default {
  props: {
    menu: {
      type: Object,
      default: null
    }
  },

  data() {
    return {
      collapsed: false,
      drawerVisible: false,
      openKeys: [],
      selectedKeys: [],
      isMobile: window.innerWidth <= 768,
      notifications: [],
      isDark: false,

      darkAlgorithm: theme.darkAlgorithm,
      defaultAlgorithm: theme.defaultAlgorithm
    };
  },

  watch: {
    collapsed(value) {
      this.setLocalStorage('sidebar-collapsed', value)
    }
  },

  created() {
    this.openKeys = [this.route.substr(1)]
    this.selectedKeys = [this.fullRoute]

    if (this.isMobile) {
      this.collapsed = true // mobile default collapsed
    } else {
      this.collapsed = this.getLocalStorage('sidebar-collapsed', true)
    }

    const darkSaved = localStorage.getItem("darkmode")
    this.isDark = darkSaved === "true"
  },

  mounted() {
    window.addEventListener("resize", this.handleResize)
  },

  beforeUnmount() {
    window.removeEventListener("resize", this.handleResize)
  },

  computed: {
    antTheme() {
      const light = {
        colorPrimary: '#581c87',
        colorBgLayout: '#ddd6fe',
      }

      const dark = {
        colorPrimary: '#7C3AED',
        colorBgBase: '#1a1528',
        colorTextBase: '#f5f3ff',
        colorBorder: '#4338CA'
      }

      // Components
      const lightComponents = {
        Card: {
          colorBorderSecondary: 'transparent',
        },
        Modal: {
          colorBorder: 'transparent'
        }
      }

      const darkComponents = {
        Card: {
          colorBorderSecondary: '#7C3AED',
        },
        Modal: {
          colorBorder: '#4338CA'
        }
      }

      return {
        algorithm: this.isDark ? this.darkAlgorithm : this.defaultAlgorithm,
        token: this.isDark ? dark : light,
        components: this.isDark ? darkComponents : lightComponents
      }
    },

    headerStyle() {
      return {
        background: this.isDark
          ? `linear-gradient(90deg, #0b132b, #3a0ca3, #240046)`
          : `linear-gradient(90deg, #3b82f6 , #5145cd, #22023a)`,
        color: '#fff',
        transition: 'background 0.3s ease'
      }
    },

    footerStyle() {
      return {
        backgroundImage: this.isDark
          ? `linear-gradient(
            90deg,
            rgba(36, 0, 70, 0) 0%,
            rgba(58, 12, 163, 0.65) 33%,
            rgba(11, 19, 43, 0.5) 66%,
            rgba(36, 0, 70, 0) 100%
          )`
          : `linear-gradient(
            90deg,
              rgba(135, 150, 210, 0) 0%,
              rgba(135, 150, 210, 0.6) 33%,
              rgba(135, 150, 210, 0.3) 66%,
              rgba(135, 150, 210, 0) 100%
          )`,
        color: this.isDark ? '#f8fafc' : '#374151',
        transition: 'all 0.3s ease'
      }
    }
  },

  methods: {

    handleResize() {
      this.isMobile = window.innerWidth <= 768
      if (this.isMobile) {
        this.collapsed = true
      }
    },

    accordion() {
      if (this.openKeys.length > 1)
        this.openKeys.shift()
    },

    toggleLogoClick() {
      if (this.isMobile) {
        this.drawerVisible = true
      } else {
        this.collapsed = !this.collapsed
      }
    },

    async switchRole(id) {
      const vm = this;
      vm.loadingTrue()

      if (vm.user.active_role_id === id) return;

      const params = {
        id: vm.user.id,
        req: 'switch_role',
        role_id: id
      };

      const response = await vm.axios.post(`/profile/write`, params).catch((e) => vm.$onAjaxError(e))
      if (response && response.data) {
        vm.openNotification('Berhasil mengubah Role, refreshing page...', 'success');
        setTimeout(() => {
          window.location.replace('/dashboard')
        }, 500)
        vm.showModal = false
      }
    },

    handleCancel() {
      if (!this.user.fiscal_year) {
        this.$message.warning('Fiscal Year harus diisi terlebih dahulu!')
        this.showModal = true
        return
      }
      this.showModal = false
    },

    disableYear(date) {
      const year = date.year()
      if (!Array.isArray(this.tahun_perolehan_aktif)) {
        this.tahun_perolehan_aktif = [new Date().getFullYear()]
      }
      return !this.tahun_perolehan_aktif.includes(year)
    },

    toggleDark() {
      this.isDark = !this.isDark
      localStorage.setItem("darkmode", this.isDark ? "true" : "false")
    }
  }
};
</script>
<template>
  <a-config-provider :theme="antTheme">
    <a-layout class="min-h-screen h-full" style="background-image: url('/images/bg_VISTA POS_purple.webp');">
      <!-- Menu Desktop -->
      <a-layout-sider v-model:collapsed="collapsed" :trigger="null" :theme="isDark ? 'dark' : 'light'" width="250"
        collapsed-width="65" class="shadow-md overflow-auto h-screen !fixed left-0 top-0 bottom-0" collapsible>
        <a class="flex flex-col mt-4 mb-1 cursor-pointer" @click.prevent="toggleLogoClick">
          <div class="flex flex-col items-center">
            <div class="relative inline-block p-2">
              <template v-if="!collapsed">
                <span
                  class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-purple-400 rounded-tl-md transition duration-300"></span>
                <span
                  class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-purple-400 rounded-tr-md transition duration-300"></span>
                <span
                  class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-purple-400 rounded-bl-md transition duration-300"></span>
                <span
                  class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-purple-400 rounded-br-md transition duration-300"></span>
              </template>
              <img :src="publicPath('images/vista_logo.webp')" :class="[
                'transition-all duration-500 ease-in-out',
                collapsed
                  ? ''
                  : 'h-20 w-20'
              ]" />
            </div>
            <div v-if="!collapsed" class="mt-5 text-center space-y-1">
              <h1
                class="text-2xl font-extrabold bg-gradient-to-r from-purple-700 via-purple-500 to-pink-500 bg-clip-text text-transparent tracking-wide drop-shadow-sm">
                VISTA POS
              </h1>
              <p class="text-xs font-medium uppercase tracking-wider">
                <span class="text-purple-400">PT </span>
                <span class="text-yellow-500 font-extrabold">Hartech</span>
              </p>
            </div>
          </div>
          <div v-if="!collapsed" class="mt-4 h-px bg-gradient-to-r from-yellow-400/70 via-yellow-300/80 to-transparent">
          </div>
        </a>
        <AppMenu :theme="isDark ? 'dark' : 'light'" :menu="menu" v-model:selectedKeys="selectedKeys"
          v-model:openKeys="openKeys" :collapsed="collapsed" :accordion="accordion" />
        <div class="p-3 text-center text-xs font-bold text-purple-700 border-t border-purple-200">
          versi 1.0
        </div>
      </a-layout-sider>
      <!-- Menu Mobile -->
      <a-drawer v-model:open="drawerVisible" placement="left" :width="250" :closable="false"
        :body-style="{ padding: 0 }" :class="isDark ? 'a-drawer-dark' : 'a-drawer-light'">
        <template #title>
          <div class="flex items-center justify-between">
            <span class="font-bold text-yellow-500">📂 Menu Aplikasi</span>
            <button @click="drawerVisible = false" class="p-2 rounded-full hover:bg-purple-200 transition">
              <Icon icon="bi:x-lg" class="text-xl text-purple-900" />
            </button>
          </div>
        </template>
        <AppMenu :theme="isDark ? 'dark' : 'light'" :menu="menu" v-model:selectedKeys="selectedKeys"
          v-model:openKeys="openKeys" :collapsed="collapsed" :accordion="accordion" />
        <template #footer>
          <div class="text-center font-bold text-purple-700">versi 1.0</div>
        </template>
      </a-drawer>
      <a-layout :class="[collapsed ? 'ml-[65px]' : 'ml-[250px]', 'transition-all duration-400 ease-in-out']">
        <a-layout-header :style="headerStyle"
          class="!px-2 md:!px-10 !h-[52px] !text-white sticky top-0 z-50 flex gap-x-2 justify-between items-center shadow-lg ml-1.5 rounded-bl-md mb-2 relative">
          <div class="flex items-center justify-between w-full">
            <div class="flex md:gap-x-6 items-center">
              <Icon :icon="collapsed ? 'line-md:menu-fold-right' : 'line-md:menu-fold-left'"
                @click.prevent="toggleLogoClick" class="!z-10 text-2xl cursor-pointer" />
              <a-tag v-if="!isMobile"
                class="from-blue-500 to-blue-800 !bg-gradient-to-r hover:!bg-yellow-500 hover:!bg-none !rounded-full !text-white !text-xs sm:!text-sm !px-4 !py-0.5 hover:scale-110">
                <span class="hidden sm:inline-block">{{ isDark ? 'Dark Mode' : 'Light Mode' }}</span>
              </a-tag>
            </div>
            <div class="flex items-center min-w-0 gap-x-4">
              <a class="relative cursor-pointer" @click="toggleDark">
                <Icon :icon="isDark ? 'line-md:sunny-twotone-loop' : 'line-md:moon-twotone'"
                  class="text-2xl text-white hover:scale-110 transition duration-300" />
              </a>
              <a-dropdown>
                <a class="relative cursor-pointer ant-dropdown-link">
                  <Icon icon="line-md:bell-twotone-loop" class="text-2xl text-white hover:scale-110 transition" />
                  <span
                    class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                </a>
                <template #overlay>
                  <a-menu class="w-56">
                    <div class="px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                      <span class="font-semibold text-gray-800">Notifikasi</span>
                      <a class="text-xs text-blue-600 hover:underline cursor-pointer">Tandai semua dibaca</a>
                    </div>

                    <a-menu-item v-if="notifications.length === 0" class="text-center text-gray-400 py-6">
                      Tidak ada notifikasi baru
                    </a-menu-item>

                    <a-menu-item v-for="(notif, i) in notifications" :key="i" class="!p-0 hover:!bg-gray-50">
                      <div class="flex items-start px-4 py-3 gap-x-3 cursor-pointer">
                        <Icon :icon="notif.icon" class="text-lg text-purple-600 mt-0.5" />
                        <div class="flex-1">
                          <div class="text-sm font-semibold text-gray-800">{{ notif.title }}</div>
                          <div class="text-xs text-gray-500">{{ notif.message }}</div>
                          <div class="text-[11px] text-gray-400 mt-0.5">{{ notif.time }}</div>
                        </div>
                        <span v-if="!notif.read" class="w-2.5 h-2.5 bg-blue-500 rounded-full mt-1"></span>
                      </div>
                    </a-menu-item>
                  </a-menu>
                </template>
              </a-dropdown>
              <a-dropdown>
                <a class="ant-dropdown-link">
                  <div v-if="!isMobile" class=" flex items-center">
                    <img :src="user.photo ? user.photo : '/images/user-icon.webp'"
                      class="h-10 w-10 rounded-full mr-2.5 object-cover object-center ring-2 ring-white/30 hover:ring-white/60 transition" />
                    <div class="flex flex-wrap items-center gap-x-1">
                      <span
                        class="md:text-lg text-base text-white font-semibold whitespace-nowrap overflow-hidden text-ellipsis">
                        {{ user.name }}
                      </span>
                      <span class="text-xs text-white bg-white/20 px-2 py-0.5 rounded-full font-medium">
                        {{ user.division }}
                      </span>
                      <span v-if="user.active_role"
                        class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full font-medium">
                        {{ user.active_role?.display_name }}
                      </span>
                    </div>
                  </div>
                  <div v-else>
                    <div
                      class="flex items-center gap-x-2 max-w-[280px] px-2 py-1 rounded-lg  hover:bg-white/20 transition-all duration-300">
                      <span class="text-sm text-white truncate font-medium tracking-wide">
                        {{ user.name }}
                      </span>
                      <img :src="user.photo ? user.photo : '/images/user-icon.webp'"
                        class="h-9 w-9 rounded-full object-cover object-center ring-2 ring-white/30 hover:ring-white/60 transition" />
                    </div>
                  </div>
                </a>
                <template #overlay>
                  <a-menu class="w-56">
                    <div class="px-3 py-4 text-center border-b border-gray-200">
                      <img :src="user.photo ? user.photo : '/images/user-icon.webp'"
                        class="h-16 w-16 mx-auto rounded-full object-cover object-center ring-2 ring-purple-300" />
                      <div class="mt-2">
                        <div class="text-sm font-semibold text-gray-800 truncate">
                          {{ user.name }}
                        </div>
                        <div class="text-xs text-gray-500">
                          {{ user.division }}
                        </div>
                        <div v-if="user.active_role"
                          class="mt-1 inline-block text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full font-medium">
                          {{ user.active_role?.display_name }}
                        </div>
                      </div>
                    </div>
                    <a-menu-item key="profile">
                      <a href="/profile" class="flex items-center font-medium">
                        <Icon icon="line-md:account" class="mr-2 text-blue-500" />
                        Profil
                      </a>
                    </a-menu-item>
                    <a-divider v-if="user.roles.length > 1" class="my-2" />
                    <a-sub-menu v-if="user.roles.length > 1" key="switch-role" class="flex items-center asas">
                      <template #title class="flex items-center asas">
                        <span class="flex items-center">
                          <Icon icon="line-md:watch" class="mr-2 text-blue-500" />
                          Ganti Hak Akses
                        </span>
                      </template>
                      <a-menu-item v-for="(role, k) in user.roles" :key="role.id" @click="switchRole(role.id)" :style="user.active_role_id === role.id ? {
                        backgroundColor: '#bfdbfe',   // Tailwind bg-blue-200
                        color: '#2563eb',             // Tailwind text-blue-600
                        fontWeight: '600'             // Tailwind font-semibold
                      } : {}">
                        {{ k + 1 }}. {{ role.display_name }}
                      </a-menu-item>
                    </a-sub-menu>
                    <a-divider v-if="user.roles.length > 1" class="my-2" />
                    <a-menu-item key="logout">
                      <a href="/logout" class="flex items-center font-medium text-red-500 hover:text-red-600">
                        <Icon icon="ant-design:logout-outlined" class="mr-2 text-red-500" />
                        Logout
                      </a>
                    </a-menu-item>
                  </a-menu>
                </template>
              </a-dropdown>
            </div>
          </div>
        </a-layout-header>
        <a-layout-content class="px-2">
          <slot></slot>
        </a-layout-content>
        <a-layout-footer class="!p-0 !bg-transparent">
          <div class="w-full text-center text-sm md:text-base" :style="footerStyle">
            <div class="px-3 py-2 md:py-2.5 flex flex-col md:flex-row justify-center items-center gap-y-1 md:gap-x-2"
              :class="isDark ? 'text-slate-100' : 'text-gray-700'">
              <span><b>VISTA POS</b> ©2025 Designed and Programmed by</span>
              <span class="font-semibold">Harry Rangkuti, A.Md.Kom</span>
            </div>
          </div>
        </a-layout-footer>
      </a-layout>
    </a-layout>
  </a-config-provider>
</template>

<style scoped>
::-webkit-scrollbar {
  display: none;
}

@keyframes zoomIn {
  0% {
    transform: scale(0.5);
    opacity: 0;
  }

  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.icon-animate {
  animation: zoomIn 1s ease-out forwards;
}

:deep(.ant-dropdown-menu-submenu-title) {
  display: flex !important;
  align-items: center !important;
}
</style>