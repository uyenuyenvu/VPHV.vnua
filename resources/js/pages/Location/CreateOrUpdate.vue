<template>
    <q-dialog v-model="isShow" @hide="closeDialog">
        <q-card style="width: 300px">
            <q-card-section>
                <div class="text-h6">Thêm mới địa điểm</div>
            </q-card-section>
            <q-card-section class="row items-center" style="width: 100%">
                <label for="name" class="text-bold"
                >Tên địa điểm <span class="required">*</span></label
                >
                <q-input
                    class="full-width"
                    outlined
                    dense
                    v-model="name"
                    id="name"
                    ref="nameRef"
                    :rules="autoGenerateRule(`tên địa điểm`)"
                    :error-message="getValidationErrors('name')"
                    :error="hasValidationErrors('name')"
                />
                <label for="description" class="text-bold"
                >Mô tả địa điểm </label
                >
                <q-input
                    class="full-width"
                    type="textarea"
                    outlined
                    dense
                    v-model="description"
                    id="description"
                    ref="descriptionRef"
                ></q-input>

            </q-card-section>
            <q-card-actions align="right" class="row">
                <q-btn
                    flat
                    label="Đóng"
                    color="primary"
                    @click="closeDialog"
                    v-close-popup
                />
                <q-btn
                    label="Đồng ý"
                    color="blue"
                    @click="handleCreateOrUpdate"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>
<script>
import {validationHelper} from "../../utils/validationHelper";
import {getCurrentInstance, ref} from 'vue'
import api from "../../api";
import {useQuasar} from "quasar";

export default ({
    name: "CreateOrUpdateLocation",
    props: {
        getListLocation: {
            type: Function,
        }
    },
    setup({getListLocation}) {
        const nameRef = ref(null);
        const name = ref("")
        const descriptionRef = ref(null);
        const description = ref("")
        const isShow = ref(false);
        const locationCurrent = ref();

        const $q = useQuasar();

        const setLocationCurrent = (location) => {
            locationCurrent.value = location
            name.value = locationCurrent.value.name;
            description.value = locationCurrent.value.description;
        }

        const closeDialog = () => {
            resetFrom([description, name, isShow, locationCurrent]);
        };

        function capitalize(s)
        {
            return s.length > 0 ? s.charAt(0).toUpperCase() + s.slice(1) : '';
        }
        const {
            setValidationErrors,
            getValidationErrors,
            hasValidationErrors,
            resetValidateErrors,
        } = validationHelper();

        const isValidate = () => {
            let isCheck = true
            if (nameRef.value.hasError ) {
                isCheck = false
            }
            return isCheck
        }

        const handleCreateOrUpdate = () => {
            nameRef.value.validate()

            if (isValidate()) {
                const payload = {
                    description: description.value,
                    name: name.value,
                }
                if (locationCurrent.value) {
                    api.updateLocation(payload, locationCurrent.value.id)
                        .then(() => {
                            getListLocation();
                            closeDialog();
                            generateNotify("Sửa thành công địa điểm", true)
                        })
                        .catch(() => {
                            generateNotify("Sửa mới thất bại")
                        })
                        .finally(() => {
                            $q.loading.hide()
                        });
                } else
                    api.createLocation(payload)
                        .then(() => {
                            getListLocation();
                            closeDialog();
                            generateNotify("Thêm mới thành công địa điểm", true)
                        })
                        .catch(() => {
                            generateNotify("Thêm mới thất bại địa điểm")
                        })
                        .finally(() => {
                            $q.loading.hide()
                        });
            }
        }

        const autoGenerateRule = (name) => {
            return [
                (val) =>
                    (val && val.trim().length > 0) || `Trường ${name} không được bỏ trống!`,
            ];
        }

        const generateNotify = (message, isSuccess = false) => {
            isSuccess ? $q.notify({
                    icon: "check",
                    message: message,
                    color: "positive",
                    position: "top-right",
                }) :
                $q.notify({
                    icon: "report_problem",
                    message: message,
                    color: "negative",
                    position: "top-right"
                })
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
            name, nameRef, description, descriptionRef, handleCreateOrUpdate, setValidationErrors,
            getValidationErrors,
            hasValidationErrors,
            resetValidateErrors,
            autoGenerateRule,
            onChangeDialog,
            isShow,
            closeDialog,
            locationCurrent,
            setLocationCurrent,
            capitalize
        }
    },
})
</script>

