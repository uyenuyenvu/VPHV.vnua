<template>
    <div class="role-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el
                label="Bảng điều khiển"
                icon="home"
                :to="{ name: 'Home' }"
            />
            <q-breadcrumbs-el label="Quản lý người dùng" :to="{ name: 'User' }"/>
            <q-breadcrumbs-el :label="idUser ? 'Chỉnh sửa' :'Tạo mới'"/>
        </q-breadcrumbs>

        <div class="main">
            <div class="row">
                <div class="col-9 q-pr-lg">
                    <q-card class="main-form col-6">
                        <div class="form-group row gap-2 q-gutter-md">
                            <div class="col">
                                <label for="full_name" class="text-bold"
                                >Họ và tên <span class="required">*</span></label
                                >
                                <q-input
                                    outlined
                                    dense
                                    v-model="full_name"
                                    id="full_name"
                                    :ref="refInput.full_name"
                                    :rules="rule.full_name"
                                    :error-message="getValidationErrors('full_name')"
                                    :error="hasValidationErrors('full_name')"
                                />
                            </div>
                            <div class="col">
                                <label for="name" class="text-bold"
                                >Tên thường gọi <span class="required">*</span></label
                                >
                                <q-input
                                    :disable="idUser ? true : false"
                                    outlined
                                    dense
                                    v-model="user_name"
                                    id="user_name"
                                    :ref="refInput.user_name"
                                    :rules="rule.user_name"
                                    :error-message="getValidationErrors('user_name')"
                                    :error="hasValidationErrors('user_name')"
                                />
                            </div>
                        </div>
                        <div class="form-group row q-gutter-md">
                            <div class="col">
                                <label for="email" class="text-bold"
                                >Email <span class="required">*</span></label
                                >
                                <q-input
                                    outlined
                                    dense
                                    v-model="email"
                                    id="email"
                                    :ref="refInput.email"
                                    :rules="rule.email"
                                    :error-message="getValidationErrors('email')"
                                    :error="hasValidationErrors('email')"
                                />
                            </div>
                            <div class="col">
                                <label for="phone" class="text-bold"
                                >Số điện thoại <span class="required">*</span></label
                                >
                                <q-input
                                    outlined
                                    dense
                                    v-model="phone"
                                    id="phone"
                                    :ref="refInput.phone"
                                    :rules="rule.phone"
                                    :error-message="getValidationErrors('phone')"
                                    :error="hasValidationErrors('phone')"
                                />
                            </div>
                        </div>
                        <div class="form-group row gap-2 q-gutter-md">
                            <div class="col" style="max-height: 63px">
                                <label for="role_id" class="text-bold"
                                >Nhóm quyền <span class="required">*</span></label
                                >
                                <div>
                                    <q-select
                                        emit-value
                                        map-options
                                        option-label="name"
                                        option-value="id"
                                        :ref="refInput.role_id"
                                        borderless
                                        dense
                                        outlined
                                        v-model="role_id"
                                        :options="roles"
                                        :rules="rule.role_id"
                                    />
                                </div>
                            </div>
                          <div class="col q-px-sm">
                            <div>
                              <label for="name" class="text-bold">Trưởng Đơn vị </label>
                              <q-toggle v-model="is_leader"/>
                            </div>
                          </div>
                        </div>
<!--                        <div class="form-group">-->
<!--                            <label for="name" class="text-bold">Chức danh </label>-->
<!--                            <div class="row border-group">-->
<!--                                <div class="col q-px-sm">-->
<!--                                    <div>-->
<!--                                        <label for="name" class="text-bold">Quản trị viên </label>-->
<!--                                        <q-toggle v-model="is_super_admin"/>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="col q-px-sm">-->
<!--                                    <div>-->
<!--                                        <label for="name" class="text-bold">Trưởng Đơn vị </label>-->
<!--                                        <q-toggle v-model="is_leader"/>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                        <div v-if="is_leader" class="form-group row q-gutter-md q-mt-sm">
                            <div class="col" style="max-height: 63px">
                                <label for="name" class="text-bold"
                                >Đơn vị<span class="required">*</span></label
                                >
                                <div>
                                    <q-select
                                        emit-value
                                        map-options
                                        :ref="refInput.department_id"
                                        borderless
                                        dense
                                        outlined
                                        v-model="department_id"
                                        option-value="id"
                                        option-label="name"
                                        :options="departments"
                                        :rules="rule.department_id"
                                    />
                                </div>
                            </div>
                        </div>
                    </q-card>
                </div>
                <div class="col-3 right-sidebar">
                    <q-card class="widget meta-boxes action-horizontal">
                        <q-card>
                            <q-card-section>
                                <div class="widget-title text-bold">Tác vụ</div>
                            </q-card-section>
                            <q-separator/>
                            <q-card-section>
                                <q-btn
                                    @click="handleSave"
                                    no-caps
                                    color="secondary"
                                    class="q-mr-sm"
                                >
                                    <q-icon
                                        name="fa-solid fa-save"
                                        class="q-mr-sm"
                                        size="xs"
                                    ></q-icon>
                                    Lưu
                                </q-btn>
                                <q-btn
                                    @click="redirectRouter('User')"
                                    no-caps
                                    color="warning"
                                    class="q-mr-sm"
                                >
                                    <q-icon
                                        name="fa-solid fa-rotate-left"
                                        class="q-mr-sm"
                                        size="xs"
                                    ></q-icon>
                                    Quay lại
                                </q-btn>
                            </q-card-section>
                        </q-card>
                    </q-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent, onMounted, reactive, ref, watch} from "vue";
import api from "../../api";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import {useStore} from "vuex";
import {useQuasar} from "quasar";
import {useRouter} from "vue-router/dist/vue-router";
import eventBus from "../../utils/eventBus";
import {validationHelper} from "../../utils/validationHelper";
import _ from "lodash";
import IRoleResult from "../../models/IRoleResult";
import IPaginate from "resources/js/models/IPaginate";
import {IDepartmentResult} from "../../models/IDepartmentResult";
import {VALIDATE_EMAIL_REGEX, VALIDATE_PHONE_REGEX_RULE,} from "../../utils/constants";
import {useRoute} from 'vue-router'
import IUserResult from "resources/js/models/IUserResult";


export default defineComponent({
    name: "UserCreate",
    setup() {
        const store = useStore();
        const $q = useQuasar();
        const router = useRouter();
        const {
            setValidationErrors,
            getValidationErrors,
            hasValidationErrors,
            resetValidateErrors,
        } = validationHelper();
        const route = useRoute()

        const user: any = {
            user_name: ref<string>(""),
            full_name: ref<string>(""),
            phone: ref<string>(""),
            email: ref<string>(""),
            is_super_admin: ref(false),
            is_leader: ref(false),
            department_id: ref<number>(),
            role_id: ref<number>(),
        };

        const payload = reactive({...user})
        const roles = reactive(ref<IRoleResult[]>([]))
        const departments = ref<IDepartmentResult[]>([]);

        const refInput: any = {
            user_name: ref<any>(null),
            full_name: ref<any>(null),
            phone: ref<any>(null),
            email: ref<any>(null),
            department_id: ref<any>(null),
            role_id: ref<any>(null),
        };

        const rule = {
            full_name: [
                (val: any) =>
                    (val && val.length > 0) || "Trường họ và tên không được bỏ trống!",
            ],
            phone: [
                (val: any) =>
                    (val && val.length > 0) ||
                    "Trường số điện thoại không được bỏ trống!",
                (val: any) => val.length < 11 || "Trường số điện thoại không hợp lệ!",
                (val: string) =>
                    VALIDATE_PHONE_REGEX_RULE.test(val) ||
                    "Trường số điện thoại không hợp lệ!",
            ],
            user_name: [
                (val: any) =>
                    (val && val.length > 0) ||
                    "Trường tên tài khoản không được bỏ trống!",
            ],
            email: [
                (val: any) =>
                    (val && val.length > 0) || "Trường email không được bỏ trống!",
                (val: string) =>
                    (val.toString().includes('@5l8tj4.onmicrosoft.com')) || "Trường email không hợp lệ!",
            ],
            role_id: [(val: any) => val || "Trường nhóm quyền không được bỏ trống!"],
            department_id: [
                (val: any) => val || "Trường Đơn vị không được bỏ trống!",
            ],
        };

        const isPwd = ref(true);

        const ruleSelect = (val: any) => {
            if (val === null) {
                return "Trường Đơn vị không được bỏ trống!";
            }
        };

        const description = ref<string>("");
        const idUser = ref("")
        const ticked = ref<any>([]);
        const loadingPermission = ref<boolean>(false);
        const permissionArray = ref<any>([]);

        const handleSave = (): void => {
            if (isValidate()) {
                const data = JSON.parse(JSON.stringify(payload));
                if (idUser.value) {
                    api.updateUser(data, idUser.value).then(res => {
                        if (res) {
                            eventBus.$emit('notify-success', 'Cập nhật người dùng thành công')
                            redirectRouter('User')
                        }
                    }).catch(error => {
                        let errors = _.get(error.response, 'data.error', {})
                        if (Object.keys(errors).length === 0) {
                            let message = _.get(error.response, 'data.message', '')
                            $q.notify({
                                icon: 'report_problem',
                                message,
                                color: 'negative',
                                position: 'top-right'
                            })
                        }
                        if (Object.keys(errors).length > 0) {
                            setValidationErrors(errors)
                        }
                    }).finally(() => $q.loading.hide())
                } else {
                    api.createUser<IRoleResult>(data).then(res => {
                        if (res) {
                            eventBus.$emit('notify-success', 'Tạo mới người dùng thành công')
                            redirectRouter('User')
                        }
                    }).catch(error => {
                        let errors = _.get(error.response, 'data.error', {})
                        console.log('errors', errors)
                        if (Object.keys(errors).length === 0) {
                            let message = _.get(error.response, 'data.message', '')
                            $q.notify({
                                icon: 'report_problem',
                                message,
                                color: 'negative',
                                position: 'top-right'
                            })
                        }
                        if (Object.keys(errors).length > 0) {
                            setValidationErrors(errors)
                        }
                    }).finally(() => $q.loading.hide())
                }
            }
        };

        const isValidate = (): boolean => {
            let nameInput: string = "";
            let check = true;
            const listInput: any = user.is_leader.value
                ? {...refInput}
                : {...refInput, department_id: null}
            for (nameInput in listInput) {
                listInput[nameInput]?.value?.validate();
                if (listInput[nameInput]?.value?.hasError) {
                    check = false;
                }
            }
            return check;
        };

        const getRoleList = () => {
            const payload = {
                page: 1,
                limit: 100,
            };
            api
                .getRoles<IPaginate<IRoleResult[]>>(payload)
                .then((res) => {
                    roles.value = _.get(res, "data.data.roles.data");
                })
                .catch(() => {
                    $q.notify({
                        icon: "report_problem",
                        message: "Không tải được danh sách nhóm vai trò!",
                        color: "negative",
                        position: "top-right",
                    });
                })
                .finally(() => {
                });
        };

        const getListDepartment = (): void => {
            const payload = {
                page: 1,
                limit: 100,
            };
            api
                .getDepartments<IPaginate<IDepartmentResult[]>>(payload)
                .then((res) => {
                    departments.value = _.get(res, "data.data.department.data");
                })
                .catch(() => {
                    $q.notify({
                        icon: "report_problem",
                        message: "Không tải được danh sách nhóm vai trò!",
                        color: "negative",
                        position: "top-right",
                    });
                })
                .finally(() => {
                });
        };

        const handleGetUser = (id: string): void => {
            $q.loading.show()
            api.getUser<IUserResult>(id).then(res => {
                const data = _.get(res, 'data.data.user', '')
                for (const key in user) {
                    if (['is_leader', 'is_super_admin'].includes(key))
                        user[key].value = data[key] ? true : false
                    else user[key].value = data[key]
                }
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được dữ liệu user!',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => $q.loading.hide())
        }

        watch(user.user_name, (): void => {
            resetValidateErrors('user_name')
            refInput.user_name.value.resetValidation()
        })

        for (const key in payload) {
            if (refInput[key]) {
                watch(user[key], (): void => {
                    resetValidateErrors(key)
                    refInput[key].value?.resetValidation()
                })
            }
        }

        const redirectRouter = (nameRoute: string): void => {
            router.push({name: nameRoute});
        };

        onMounted(() => {
            store.commit(`home/${HomeMutationTypes.SET_TITLE}`, "Quản lý người dùng");
            getRoleList();
            getListDepartment();
            idUser.value = <string>route.params.id
            if (idUser.value) {
                handleGetUser(idUser.value)
            }
        });
        return {
            handleSave,
            description,
            ticked,
            permissionArray,
            loadingPermission,
            redirectRouter,
            getValidationErrors,
            hasValidationErrors,
            ruleSelect,
            isPwd,
            ...user,
            rule,
            refInput,
            roles,
            departments,
            idUser,
        };
    },
});
</script>

<style scoped lang="scss">
.role-wrapper {
    .main {
        margin-top: 20px;

        .main-form {
            padding: 10px;
            margin-bottom: 15px;

            .form-group {
                margin-bottom: 15px;

                .border-group {
                    border: 1px solid #c2c2c2;
                    border-radius: 4px;
                }
            }
        }
    }
}
</style>
