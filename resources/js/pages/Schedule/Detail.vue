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
  </div>
</template>

<script lang="ts">
import {defineComponent, onMounted, ref} from "vue";
import {useRouter} from "vue-router/dist/vue-router";
import api from "../../api";
import IUserResult from "../../models/IUserResult";
import _ from "lodash";
import {useQuasar} from "quasar";
import IScheduleResult from "../../models/IScheduleResult";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import {useRoute} from "vue-router";


export default defineComponent({
  name: "DetailSchedule",
  setup() {
    const router = useRouter()
    const route = useRoute()
    const $q = useQuasar();
    const schedule = ref<any>()
    const idSchedule = ref("")

    const redirectRouter = (nameRoute: string): void => {
      router.push({name: nameRoute})
    }
    const handleGetSchedule = (id: string): void => {
      $q.loading.show()
      api.getSchedule<IScheduleResult>(id).then(res => {
        schedule.value = _.get(res, 'data.data.schedule', '')
        // for (const key in user) {
        //   if (['is_leader', 'is_super_admin'].includes(key))
        //     user[key].value = data[key] ? true : false
        //   else user[key].value = data[key]
        // }
      }).catch(() => {
        $q.notify({
          icon: 'report_problem',
          message: 'Không tải được dữ liệu lịch!',
          color: 'negative',
          position: 'top-right'
        })
      }).finally(() => $q.loading.hide())
    }
    onMounted(() => {
      idSchedule.value = <string>route.params.id
      if (idSchedule.value) {
        handleGetSchedule(idSchedule.value)
      }
    });
    return {
      redirectRouter,
      handleGetSchedule,
      schedule
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
</style>
