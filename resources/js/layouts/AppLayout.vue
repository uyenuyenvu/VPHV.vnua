<template>
  <q-layout view="lHh Lpr lFf" class="main bg-grey-2">
    <q-header elevated style="
        left: 0;
        height: 80px;
        background-image: linear-gradient(to bottom, #a3d9ec, #4218b836);
">
      <q-toolbar style="height: 80px;">
        <q-btn
            flat
            dense
            round
            @click="toggleLeftDrawer"
            icon="menu"
            aria-label="Menu"
        />
        <q-toolbar-title>
          <div class="content-toolbar">
            <img src="/images/FITA.png" alt="">
            <div class="title-div">
              <div class="title-page">HỆ THỐNG ĐIỀU HÀNH TRỰC TUYẾN</div>
              <div class="sub-title">eLive</div>
            </div>
          </div>
        </q-toolbar-title>
        <q-space/>
        <div class="q-gutter-sm row items-center no-wrap" v-if="auth.full_name">
          <q-btn round flat>
            <q-avatar size="26px">
              <img src="https://cdn.quasar.dev/img/avatar4.jpg">
            </q-avatar>
            <q-menu style="max-width: 300px!important; right: 20px; left: auto!important;">
              <div class="row no-wrap q-pa-md">
                <div class="column items-center">
                  <div class="setting-div text-subtitle1 q-mt-md q-mb-xs">{{ auth.full_name }}</div>
                  <div class="setting-div">
                    <span style="margin-bottom: 10px"><q-icon color="grey" name="fa-solid fa-gear"  style="margin-right: 20px"/>Cài đặt</span>
                    <div class="item-menu-right">
                    <router-link class="textLink" :to='{ name: "Role" }'
                      style="text-decoration: none; color: black;text-transform: capitalize; margin-right: 5px;">
                        <q-icon color="grey" name="fa-solid fa-pen-ruler" style="margin-right: 10px; margin-left: 20px"/>Cài đặt quyền hạn</router-link>
                    </div>
                  </div>
                  <div class="setting-div">
                    <span style="margin-bottom: 10px"><q-icon color="grey" name="fa-solid fa-bars-progress"  style="margin-right: 20px"/>Quản lý</span>
                    <div class="item-menu-right">
                    <router-link class="textLink" :to='{ name: "User" }'
                      style="text-decoration: none; color: black;text-transform: capitalize; margin-right: 5px;">
                        <q-icon color="grey" name="fa-solid fa-user" style="margin-right: 10px; margin-left: 20px"/>Người dùng</router-link>
                    </div>
                    <div class="item-menu-right">
                    <router-link class="textLink" :to='{ name: "Department" }'
                      style="text-decoration: none; color: black;text-transform: capitalize; margin-right: 5px;">
                        <q-icon color="grey" name="fa-solid fa-building-user" style="margin-right: 10px; margin-left: 20px"/>Phòng ban</router-link>
                    </div>
                    <div class="item-menu-right">
                    <router-link class="textLink" :to='{ name: "Location" }'
                      style="text-decoration: none; color: black;text-transform: capitalize; margin-right: 5px;">
                        <q-icon color="grey" name="fa-solid fa-location-dot" style="margin-right: 10px; margin-left: 20px"/>Địa điểm</router-link>
                    </div>
                  </div>
                  <q-btn
                      color="primary"
                      label="Đăng xuất"
                      push
                      size="sm"
                      v-close-popup
                      @click="logout"
                  />
                </div>
              </div>
            </q-menu>
          </q-btn>
        </div>
        <div class="q-gutter-sm row items-center no-wrap" v-else>
          <q-btn flat>
            <router-link class="textLink" :to='{ name: "Login" }'
            style="text-decoration: none; color: white; font-weight:bold;text-transform: capitalize; margin-right: 5px">Đăng nhập</router-link>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
        v-model="leftDrawerOpen"
        show-if-above
        bordered
        class="bg-white main-drawer"
        :width="240"
    >
      <q-scroll-area class="fit" style="padding-top: 80px">
        <q-list padding>
          <q-item v-ripple clickable
                  @click="redirectRouteName('AcademySchedule')">
            <q-item-section avatar>
              <q-icon color="grey" name="fa-solid fa-home"/>
            </q-item-section>
            <q-item-section>
              <q-item-label>Lịch học viện</q-item-label>
            </q-item-section>
          </q-item>

          <q-separator class="q-my-md"/>

          <q-item v-ripple clickable>
            <q-item-section avatar>
              <q-icon color="grey" name="fa-solid fa-building-user"/>
            </q-item-section>
            <q-item-section>
              <q-item-label>Lịch phòng ban</q-item-label>
            </q-item-section>
          </q-item>
            <div
                v-for="department in departments" :key="department.id"
                :class="$route.params?.id?.toString() === department.id.toString() ? 'item-department active' :'item-department'"
            >
                <router-link class="textLink" :to='{ name: "DepartmentSchedule", params:{id: department.id} }'
                             style="text-decoration: none; color: black;text-transform: capitalize; margin-right: 5px;">
                    <q-icon color="grey" name="fa-solid fa-chevron-right" style="margin-right: 10px; margin-left: 20px"/><span>{{department.name}}</span></router-link>
            </div>
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-page-container>
      <div class="page-content-wrapper">
        <div class="page-content">
          <router-view/>
        </div>
      </div>
    </q-page-container>
  </q-layout>
</template>

<script lang="ts">
import {computed, defineComponent, onMounted, ref} from 'vue'
import {fabYoutube} from '@quasar/extras/fontawesome-v6'
import {useRouter} from "vue-router/dist/vue-router";
import {useStore} from "vuex";
import {AuthActionTypes} from "../store/modules/auth/actions-type";
import {permissionHelper} from "../utils/permissionHelper";
import {IDepartmentResult} from "../models/IDepartmentResult";
import {IPayload} from "../models/IPage";
import api from "../api";
import IPaginate from "../models/IPaginate";
import _ from "lodash";
import {HomeMutationTypes} from "../store/modules/home/mutation-types";
import eventBus from "../utils/eventBus";
import {useQuasar} from "quasar";

export default defineComponent({
  name: 'AppLayout',

  setup() {
    const store = useStore()
    const leftDrawerOpen = ref(false)
    const router = useRouter()
      const departments = ref<Array<IDepartmentResult>>([]);
      const loadingDepartments = ref<boolean>(false);
      const $q = useQuasar();
      const {checkPermission} = permissionHelper()

    const auth = store.getters["auth/getAuthUser"]
      const getListDepartment = (): void => {
          loadingDepartments.value = true;
          const payload: IPayload = {
              page: 1,
          };

          api
              .getDepartments<IPaginate<IDepartmentResult[]>>(payload)
              .then((res) => {
                  departments.value = _.get(res, "data.data.department.data");
                })
              .catch(() => {
                  generateNotify("Không tải được danh sách phòng ban")
              })
              .finally(() => (loadingDepartments.value = false));
      };
      const generateNotify = (message: string, isSuccess=false) => {
          isSuccess ? $q.notify({icon: "check",
                  message: message,
                  color: "positive",
                  position: "top-right",}) :
              $q.notify({ icon: "report_problem",
                  message: message,
                  color: "negative",
                  position: "top-right"})
      }
    const logout = (): void => {
      store.dispatch(`auth/${AuthActionTypes.LOGOUT_ACTION}`)
    }

    function toggleLeftDrawer(): void {
      leftDrawerOpen.value = !leftDrawerOpen.value
    }

    const redirectRouteName = (routeName: string): void => {
      router.push({name: routeName})
    }

    const title = computed(() => {
      return store.state.home.title
    })

      onMounted(() => {
          getListDepartment();

      });
    return {
      fabYoutube,
      auth,
      leftDrawerOpen,
      title,
      logout,
      toggleLeftDrawer,
      redirectRouteName,

        departments,
        loadingDepartments,
      links1: [
        {icon: 'fa-solid fa-home', text: 'Bảng điều khiển', routeName: 'Home'},
        {icon: 'fa-solid fa-building-user', text: 'Quản lý phòng ban', routeName: 'Department'},
        {icon: 'fa-solid fa-users', text: 'Quản lý lớp học', routeName: 'Classes'},
      ],

      links2: [
        {icon: 'fa-solid fa-address-card', text: 'Quản lý sinh viên', routeName: 'StudentIndex'},
        {icon: 'fa-solid fa-rectangle-list', text: 'Danh sách xét duyệt', routeName: 'ReviewListIndex'},
        {icon: 'fa-solid fa-flag', text: 'Báo cáo sinh viên', routeName: 'ReportStudent'},
      ],
      linksSystem: [
        {icon: 'fa-solid fa-user', text: 'Người dùng', routeName: 'User'},
        {icon: 'fa-solid fa-user-shield', text: 'Nhóm và phân quyền', routeName: 'Role'},
      ],
    }
  }
})
</script>

<style lang="scss" scoped>
.logoWrapper {
  .logo {
    text-align: center;
    padding-top: 20px;

    a {

      img {
        width: 100px;
      }
    }
  }
}

.main {
  //background-color: #F3F5F8;
}

.main-drawer {
  //background-color: #F5F5F5!important;
}

.page-content-wrapper {
  .page-content {
    margin-top: 0;
    min-height: 600px;
    padding: 20px 20px 10px 20px;
  }
}

.title-page {
  text-shadow: 4px 4px 0px rgba(0, 0, 0, 0.2);
  font-size: 30px;
  font-weight: bold;
}

.title-div {
  width: max-content;
}

.sub-title {
  transform: translateX(100%);
  margin-top: -15px;
  font-weight: bold;
  text-shadow: 2px 4px 3px rgba(0, 0, 0, 0.3);
}

.content-toolbar {
  display: flex;

  img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 20px;
  }
}
.setting-div{
  width: 100%;
  text-align: left;
  border-bottom: 1px solid darkgray;
  margin-bottom: 20px;
  padding-bottom: 10px;
}
.item-menu-right{
    padding: 10px 20px 10px 0;
    margin-right: 10px;
    a{
        white-space: nowrap;
    }
    &:hover{
        background-color: #ebf3ff;
    }
}
.item-department{
    padding: 10px 10px 10px 0;
    border-bottom: 0.1px;
    border-style: none none dashed;
    margin-left: 30px;
    border-color: #8f8f8f;
    &:hover{
        background-color: #ebf3ff;
    }
    &.active{
        background-color: #ebf3ff;
    }
    a{
        display: flex;
        align-items: center;
    }
}
</style>
