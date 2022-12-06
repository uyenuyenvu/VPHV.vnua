<template>
    <q-dialog v-model="isShow" persistent @hide="closeDialog">
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="fa-solid fa-trash" color="red" text-color="white" />
          {{listIdLocation ? true : false}}
        <span v-if="listIdLocation?.length ? true : false" class="q-ml-sm"
            >Bạn có chắc chắn muốn xóa {{ listIdLocation?.length }} bản ghi! Dữ
            liệu không thể phục hồi!
        </span>
        <span v-else class="q-ml-sm"
            >Bạn có chắc chắn muốn xóa! Dữ liệu không thể phục hồi!
        </span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn
            flat
            label="Đóng"
            color="primary"
            @click="closeDialog"
            v-close-popup
          />
          <q-btn
            label="Đồng ý"
            color="red"
            @click="handleDelete"
            v-close-popup
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

</template>
<script>
import { ref } from 'vue'
import api from "../../api";
import { useQuasar } from "quasar";

export default ({
    name: "DeleteLocation",
    props: {
        getListLocation: {
            type: Function,
        },
        resetListIdDelete: {
            type: Function
        }
    },
    setup({getListLocation, resetListIdDelete}) {
        const isShow = ref(false);
        const locationId = ref();
        const listIdLocation = ref([])

        const $q = useQuasar();

        const setLocationId = (id) => {
            locationId.value = id
        }

        const setListIdLocation = (listId) => {
            listIdLocation.value = listId
        }

        const closeDialog = () => {
            resetFrom([locationId, listIdLocation]);
        };

        const handleDelete = () => {
            if(listIdLocation.value?.length) {
                $q.loading.show();
                const data = {
                    id: listIdLocation.value,
                };
                api
                .deleteLocationSelected(data)
                .then(() => {
                    getListLocation();
                    closeDialog();
                    resetListIdDelete();
                    generateNotify("Xóa thành công địa điểm", true)
                })
                .catch(() => {
                    generateNotify("Không xóa được địa điểm")
                })
                .finally(() => $q.loading.hide());
            } else {
                $q.loading.show();
                api
                    .deleteLocation(locationId.value)
                    .then(() => {
                        getListLocation();
                        closeDialog();
                        generateNotify("Xóa thành công địa điểm", true)
                    })
                    .catch(() => {
                        generateNotify("Không xóa được địa điểm")
                    })
                    .finally(() => $q.loading.hide());
            }
        };

        const generateNotify = (message, isSuccess=false) => {
            isSuccess ? $q.notify({icon: "check",
            message: message,
            color: "positive",
            position: "top-right",}) :
            $q.notify({ icon: "report_problem",
            message: message,
            color: "negative",
            position: "top-right"})
        }

        const resetFrom = (arrProperties) => {
        for (const element of arrProperties) {
            const type = typeof element.value
            switch (type) {
                case 'string':
                    element.value = ""
                    break;
                case 'boolean':
                    element.value = false
                    break;
                case 'number':
                    element.value = 0
                    break;
                case 'object':
                    element.value = null
                    break;
                }
            }
        }

        const onChangeDialog = () => {
            isShow.value = true;
        }

        return {
            handleDelete,
            onChangeDialog,
            isShow,
            closeDialog,
            setLocationId,
            setListIdLocation,
            listIdLocation
        }
    },
})
</script>

