<template>
    <div class="department-wrap">
        <div class="department-header">
            <div class="item-header">Thông tin linh tinh</div>
            <div class="item-header">
                <q-btn no-caps @click="redirectRouter('ScheduleCreate')" color="secondary" class="q-mr-sm">
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
            <template  class="itemDay" v-for="(day, index) in days">
              <tr  v-for="(schedule, index2) in schedules[formatFullDate(day)]">
              <td :rowspan="schedules[formatFullDate(day)].length" v-if="index2 === 0"  class="borderSolid dateInf">
                {{daysName[index]}}
                <br>
                {{
                  formatDate(day)
                }}
              </td>
              <td :class="index2===schedules[formatFullDate(day)].length-1 ? 'borderSolid contentInf':'borderDotted contentInf'">
                {{schedule.title}}
                </td>
              <td  :class="index2===schedules[formatFullDate(day)].length-1 ? 'borderSolid leaderInf':'borderDotted leaderInf'">
                {{schedule.leader_orther_name}}
                </td>
              </tr>
              <tr v-if="!schedules[formatFullDate(day)]">
                <td class="borderSolid dateInf">
                  {{daysName[index]}}
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
    </div>
</template>

<script lang="ts">
import {defineComponent, onMounted, ref} from "vue";
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

export default defineComponent({
    name: "Department",
    setup() {

      const $q = useQuasar()
      const store = useStore()
        const router = useRouter()
      const loadingRoles = ref<boolean>(false)
      const schedules = ref<IScheduleResult[]>([])
      const days = ref([]);
      const daysName = ref([
          'Thứ hai',
          'Thứ ba',
          'Thứ tư',
          'Thứ năm',
          'Thứ sáu',
          'Thứ bảy',
          'Chủ nhật',
      ])

        const redirectRouter = (nameRoute: string): void => {
            router.push({name: nameRoute})
        }
      const formatDate = (date) : string =>{
        return moment(date).format("DD / MM");
      }
      const formatFullDate = (date) : string =>{
        return moment(date).format("YYYY-MM-DD");
      }
      const getListScheduleByWeek = (): void => {

        loadingRoles.value = true
        const payload: IPayload = {
          page: 1,
        }

        // if (search.value) {
        //   payload.q = search.value
        // }
        //
        // payload.page = page.value.currentPage

        api.getScheduleAcademy<IPaginate<IUserResult[]>>(payload).then(res => {
          schedules.value = _.get(res, 'data.data.schedules')
          console.log(schedules.value)
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
        const date = new Date();
        days.value =  Array(7).fill(new Date(date)).map((el, idx) =>
            new Date(el.setDate(el.getDate() - el.getDay() + idx)))
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
        return {
          redirectRouter,
          loadingRoles,
          days,
          daysName,
          formatDate,
          schedules,
          formatFullDate
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
.weekContainer{
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
  table{
    width: 100%;
    border-spacing: 0;
    tr{
      th{
        padding: 10px 5px;
        border-right: 0.5px solid;
        color: white;
        background-color: #609cdc;
      }
      td{
        padding: 10px 5px;
        border-right: 0.5px solid;
        border-color: #609cdc;
        &:last-of-type{
          border-right: none!important;
        }
      }
    }
    .borderSolid{
      border-bottom: 0.5px solid;
      border-color: #609cdc;
    }
    .borderDotted{
      border-bottom: 0.5px dotted;
      border-color: #609cdc;
    }
    .dateInf{
      width: 120px;
      font-weight: bold;
      text-align: center;
    }
    .leaderInf{
      width: 300px;
      text-align: center;
      font-weight: bold;
    }
  }

}
</style>
