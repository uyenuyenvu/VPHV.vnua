<template>
  <div class="department-wrap">
    <div class="department-header">
      <div class="item-header">
        <input type="date" name="start_time" v-model="searchDate"
               style="padding: 5px 10px;
    border-radius: 5px;
    border: 1px solid #609cdc;
    outline: none;
width: 200px;
    text-align: center;
    font-weight: bold;"
        >
      </div>
      <div class="item-header">
        <q-btn no-caps @click="redirectRouter('ScheduleCreate')" color="secondary" class="q-mr-sm" v-if="auth.full_name">
          <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
          Thêm lịch
        </q-btn>
      </div>
    </div>
    <div class="scheduleWrap">
      <div class="weekContainer">
        <table cellspacing="0" cellpadding="0">
          <tr>
            <th>Ngày/Tháng</th>
            <th>Nội dung</th>
            <th>Chủ trì</th>
          </tr>
          <tbody>
          <template class="itemDay" v-for="(day, index) in days">
            <tr v-for="(schedule, index2) in schedules[formatFullDate(day)]"
            :class="formatDate(day) === formatDate(new Date()) ? 'today' : ''"
            >
              <td :rowspan="schedules[formatFullDate(day)].length" v-if="index2 === 0" class="borderSolid dateInf">
                {{ daysName[index] }}
                <br>
                {{
                  formatDate(day)
                }}
              </td>
              <td :class="{
                'borderSolid':index2===schedules[formatFullDate(day)].length-1,
                'borderDotted': index2!==schedules[formatFullDate(day)].length-1,
                'accepted': schedule.status.toString() === '1',
                'closed': schedule.close_by !== null
              }"
              class="contentInf">
                <div class="iconMenu"  v-if="schedule.status.toString() !== '1' && schedule.close_by === null && auth.full_name">
                  <q-btn  color="white" text-color="black" icon="menu">
                    <q-menu
                        transition-show="rotate"
                        transition-hide="rotate"
                    >
                      <q-list style="min-width: 100px">
                        <q-item clickable v-if="auth.id === schedule.create_by || auth.is_super_admin.toString() === '1'"
                                @click="redirectRouter('ScheduleUpdate', {id: schedule.id})" >
                          <q-item-section>Chỉnh sửa</q-item-section>
                        </q-item>
                        <q-item clickable @click="confirmAccept(schedule.id)" v-if="auth.is_super_admin.toString() === '1'">
                          <q-item-section>Phê duyệt</q-item-section>
                        </q-item>
                        <q-item clickable  @click="confirmCancel(schedule.id)" v-if="auth.id === schedule.create_by || auth.is_super_admin.toString() === '1'">
                          <q-item-section>Hủy lịch</q-item-section>
                        </q-item>
                      </q-list>
                    </q-menu>
                  </q-btn>
                </div>
                  <strike v-if="schedule.close_by !== null">
                    <p>
                      <q-icon color="grey" name="fa-solid fa-clock" style="margin-right: 10px"/>
                      <b>
                        <i>
                          {{ formatTime(schedule.start_time) }}
                          -
                          {{ formatTime(schedule.end_time) }}
                        </i>
                        :
                        {{ schedule.title }}
                      </b>
                    </p>
                    <p>
                      <q-icon color="grey" name="fa-solid fa-location-dot" style="margin-right: 10px"/>
                      {{ schedule.location_other_name }}
                    </p>
                    <p v-if="schedule.elements?.length > 0">
                      <q-icon color="grey" name="fa-solid fa-users"/>
                      <span v-for="(element, index) in schedule.elements" style="margin-left: 10px"
                            :class="element.user_id === auth.id?'ofMe' : 'member'">
                    {{ element.name }} <span v-if="index < schedule.elements.length - 1">,</span></span>
                    </p>
                  </strike>
                <template v-else>
                  <p>
                    <q-icon color="grey" name="fa-solid fa-clock" style="margin-right: 10px"/>
                    <b>
                      <i>
                        {{ formatTime(schedule.start_time) }}
                        -
                        {{ formatTime(schedule.end_time) }}
                      </i>
                      :
                      {{ schedule.title }}
                    </b>
                  </p>
                  <p>
                    <q-icon color="grey" name="fa-solid fa-location-dot" style="margin-right: 10px"/>
                    {{ schedule.location_other_name }}
                  </p>
                  <p v-if="schedule.elements?.length > 0">
                    <q-icon color="grey" name="fa-solid fa-users"/>
                    <span v-for="(element, index) in schedule.elements" style="margin-left: 10px"
                          :class="element.user_id === auth.id?'ofMe' : 'member'">
                    {{ element.name }} <span v-if="index < schedule.elements.length - 1">,</span></span>
                  </p>
                </template>
              </td>
              <td :class="index2===schedules[formatFullDate(day)].length-1 ? 'borderSolid leaderInf':'borderDotted leaderInf'">
                <span :class="schedule.leader_id === auth.id?'ofMe' : 'member'">{{ schedule.leader_orther_name }}</span>
              </td>
            </tr>
            <tr v-if="!schedules[formatFullDate(day)]">
              <td class="borderSolid dateInf">
                {{ daysName[index] }}
                <br>
                {{
                  formatDate(day)
                }}
              </td>
              <td class="borderSolid"></td>
              <td class="borderSolid"></td>
            </tr>
          </template>
          </tbody>
        </table>
      </div>
    </div>
    <q-dialog v-model="visibleDialogConfirmAccept" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <span class="q-ml-sm">Bạn có chắc chắn phê duyệt lịch này?</span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Đóng" color="primary" @click="visibleDialogConfirmAccept = false" v-close-popup/>
          <q-btn label="Đồng ý" color="green" @click="handleAccept" v-close-popup/>
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="visibleDialogConfirmCancel" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <span class="q-ml-sm">Bạn có chắc chắn hủy lịch này?</span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Đóng" color="primary" @click="visibleDialogConfirmCancel = false" v-close-popup/>
          <q-btn label="Đồng ý" color="red" @click="handleCancel" v-close-popup/>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script lang="ts">
import {defineComponent, onMounted, ref, watch} from "vue";
import {useRouter} from "vue-router/dist/vue-router";
import {IPayload} from "../../models/IPage";
import api from "../../api";
import IPaginate from "../../models/IPaginate";
import IUserResult from "../../models/IUserResult";
import _ from "lodash";
import IScheduleResult from "../../models/IScheduleResult";
import {useQuasar} from "quasar";
import {useStore} from "vuex";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import eventBus from "../../utils/eventBus";
import moment from 'moment'
import {useRoute} from "vue-router/dist/vue-router"
import {date} from "quasar/dist/types/utils/date";

export default defineComponent({
  name: "Department",
  setup() {

    const $q = useQuasar()
    moment.updateLocale('en', {
      week: {
        dow: 1, // Monday is the first day of the week.
      }
    });
    const store = useStore()
    const route = useRoute()
    const router = useRouter()
    const loadingRoles = ref<boolean>(false)
    const visibleDialogConfirmAccept = ref<boolean>(false)
    const visibleDialogConfirmCancel = ref<boolean>(false)
    const schedules = ref<IScheduleResult[]>([])
    const days = ref([]);
    const idScheduleHandle = ref('');
    const auth = store.getters["auth/getAuthUser"]
    const daysName = ref([
      'Thứ hai',
      'Thứ ba',
      'Thứ tư',
      'Thứ năm',
      'Thứ sáu',
      'Thứ bảy',
      'Chủ nhật',
    ])
    const searchDate = ref(moment().format('YYYY-MM-DD'))

    const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
      console.log(params)
      router.push({name: nameRoute, params: params})
    }
    const formatDate = (date): string => {
      return moment(date).format("DD / MM");
    }
    const formatTime = (date): string => {
      return moment(date).format("hh:mm");
    }
    const formatFullDate = (date): string => {
      return moment(date).format("YYYY-MM-DD");
    }
    const getListScheduleByWeek = (): void => {

      loadingRoles.value = true
      const payload: any = {}

      if (searchDate.value){
        payload.start_time = moment(searchDate.value, 'YYYY-MM-DD').startOf('week').isoWeekday(1).format("YYYY-MM-DD")
        payload.end_time = moment(searchDate.value, 'YYYY-MM-DD').startOf('week').isoWeekday(1).add(6, 'days').format("YYYY-MM-DD")
      }else{
        payload.start_time = moment().startOf('week').isoWeekday(1).format("YYYY-MM-DD")
        payload.end_time = moment().startOf('week').isoWeekday(1).add(6, 'days').format("YYYY-MM-DD")
      }
      if (route.params?.id){
        payload.department_id = route.params?.id
      }

      api.getScheduleAcademy<IPaginate<IUserResult[]>>(payload).then(res => {
        schedules.value = _.get(res, 'data.data.schedules')
      }).catch(() => {
        $q.notify({
          icon: 'report_problem',
          message: 'Không tải được danh sách lịch!',
          color: 'negative',
          position: 'top-right'
        })
      }).finally(() => loadingRoles.value = false)
    }
    const selectWeek = (): void => {
      days.value = []
      let dateTemp = moment().startOf('week').isoWeekday(1)
      for (let i = 0; i < 7; i++) {
        days.value.push(dateTemp)
        dateTemp = moment(dateTemp).add(1, 'days');
      }
    }
    const confirmAccept = (id): void => {
      visibleDialogConfirmAccept.value = true
      idScheduleHandle.value = id

    }
    const confirmCancel = (id): void => {
      visibleDialogConfirmCancel.value = true
      idScheduleHandle.value = id

    }

    const handleAccept = (): void => {
      loadingRoles.value = true
      api.acceptSchedule(idScheduleHandle.value).then(res => {
        eventBus.$emit('notify-success', 'Phê duyệt lịch thành công')
        getListScheduleByWeek()
      }).catch(() => {
        $q.notify({
          icon: 'report_problem',
          message: 'Không tải được danh sách lịch!',
          color: 'negative',
          position: 'top-right'
        })
      }).finally(() => loadingRoles.value = false)
    }
    const handleCancel = (): void => {
      loadingRoles.value = true
      api.cancelSchedule(idScheduleHandle.value).then(res => {
        eventBus.$emit('notify-success', 'Hủy lịch thành công')
        getListScheduleByWeek()
      }).catch(() => {
        $q.notify({
          icon: 'report_problem',
          message: 'Không tải được danh sách lịch!',
          color: 'negative',
          position: 'top-right'
        })
      }).finally(() => loadingRoles.value = false)
    }

    onMounted((): void => {
      eventBus.$on('notify-success', (message: string) => {
        $q.notify({
          icon: 'check',
          message: message,
          color: 'positive',
          position: 'top-right'
        })
      })
      selectWeek()
      getListScheduleByWeek()
    })
    watch(route, () => {
      getListScheduleByWeek()
    })
    watch(searchDate, (value) => {
      days.value = []
      const today = new Date(value);
      for (let i = 1; i <= 7; i++) {
        let day =  new Date(today.setDate(today.getDate() - today.getDay() + i))
        days.value.push(moment(day))
      }
        getListScheduleByWeek()
    })
    return {
      redirectRouter,
      loadingRoles,
      days,
      router,
      route,
      daysName,
      formatDate,
      schedules,
      formatFullDate,
      formatTime,
      auth,
      searchDate,
      confirmAccept,
      visibleDialogConfirmAccept,
      visibleDialogConfirmCancel,
      handleAccept,
      handleCancel,
      idScheduleHandle,
      confirmCancel
    }
  }
})
</script>

<style scoped lang="scss">
.department-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.weekContainer {
  margin: 20px 10px 0 0;
  border: 1px solid;
  border-color: #4684d594;
  background: white;
  border-radius: 10px;
  height: calc(100vh - 175px);
  overflow: auto;

  &::-webkit-scrollbar {
    width: 9px;
  }

  &::-webkit-scrollbar-track {
    background-color: transparent;
  }

  &::-webkit-scrollbar-thumb {
    background-color: #609cdc;
    border-radius: 10px;
    border: 0px solid transparent;
    background-clip: content-box;
  }

  &::-webkit-scrollbar-thumb:hover {
    background-color: #a8bbbf;
  }

  table {
    width: 100%;
    border-spacing: 0;

    tr {
      th {
        padding: 10px 5px;
        border-right: 0.5px solid;
        color: white;
        background-color: #609cdc;
      }

      td {
        padding: 10px 5px;
        border-right: 0.5px solid;
        border-color: #609cdc;

        &:last-of-type {
          border-right: none !important;
        }
      }
    }

    .borderSolid {
      border-bottom: 0.5px solid;
      border-color: #609cdc;
    }

    .borderDotted {
      border-bottom: 0.5px dotted;
      border-color: #609cdc;
    }

    .dateInf {
      width: 120px;
      font-weight: bold;
      text-align: center;
    }

    .leaderInf {
      width: 300px;
      text-align: center;
      font-weight: bold;
    }
  }

}

.ofMe {
  font-weight: bold;
  padding: 5px 10px;
  background: pink;
  border-radius: 5px;
}

.contentInf {
  position: relative;

  .iconMenu {
    position: absolute;
    top: 10px;
    right: 10px;
  }
}
.today{
  background: #cdedff8a;
}
.accepted{
  background: #ddf4dd;
}
.closed{
  background: #b7b7b7;
}
</style>
