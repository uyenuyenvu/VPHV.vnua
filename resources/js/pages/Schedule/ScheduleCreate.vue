<template>
  <div class="role-wrapper">
    <div class="main">
      <div class="row">
        <div class="col-9 q-pr-lg">
          <q-card class="main-form col-12">
            <div class="form-group row gap-2 q-gutter-md">
              <div class="col">
                <label for="title" class="text-bold"
                >Tiêu đề <span class="required">*</span></label
                >
                <q-input
                    outlined
                    dense
                    v-model="title"
                    id="title"
                    :ref="refInput.title"
                    :rules="rule.title"
                    :error-message="getValidationErrors('title')"
                    :error="hasValidationErrors('title')"
                />
              </div>
            </div>
            <div class="form-group row q-gutter-md">
              <div class="col">
                <label class="text-bold"
                >Địa điểm <span class="required">*</span></label
                >
                <q-select
                    emit-value
                    map-options
                    :ref="refInput.location_other_name"
                    borderless
                    dense
                    use-input
                    outlined
                    v-model="location_other_name"
                    @new-value="createValue"
                    @filter="filterFn"
                    :options="locationNameOptions"
                    :rules="rule.location_other_name"
                />
              </div>
              <div class="col">
                <label class="text-bold"
                >Thời gian bắt đầu <span class="required">*</span></label
                >
                <div class="input-time">
                  <input type="datetime-local" name="start_time" v-model="start_time" :rules="rule.start_time"
                         :error-message="getValidationErrors('start_time')"
                         :error="hasValidationErrors('start_time')">
                </div>

              </div>
              <div class="col">
                <label class="text-bold"
                >Thời gian kết thúc </label
                >
                <div class="input-time">
                  <input type="datetime-local" name="end_time"  v-model="end_time"
                         :rules="rule.end_time"
                         :error-message="getValidationErrors('end_time')"
                         :error="hasValidationErrors('end_time')">
                </div>
              </div>
            </div>
            <div class="form-group row gap-2 q-gutter-md">
              <div class="col">
                <label class="text-bold"
                >Mô tả chi tiết</label
                >
                <div>
                  <ckeditor :editor="editor" v-model="description" :config="editorConfig"/>
                </div>
              </div>
            </div>
            <div class="form-group row q-gutter-md">
              <div class="col">
                <label class="text-bold"
                >Chủ trì <span class="required">*</span></label
                >
                <q-select
                    emit-value
                    map-options
                    :ref="refInput.leader_orther_name"
                    borderless
                    dense
                    use-input
                    outlined
                    v-model="leader_orther_name"
                    @new-value="createValueLeader"
                    @filter="filterFnLeader"
                    :options="leaderNameOptions"
                    :rules="rule.leader_orther_name"
                />
              </div>
            </div>
            <div class="form-group row q-gutter-md">
              <div class="col">
                <label class="text-bold"
                >Thành phần tham dự</label
                >
                <Multiselect
                    v-model="lstElement"
                    mode="tags"
                    :close-on-select="false"
                    :searchable="true"
                    :create-option="true"
                    :options="elementNameOptions"
                    @select="selectElement"
                />
              </div>
            </div>
            <div class="form-group row q-gutter-md">
              <div class="col">
                <label class="text-bold"
                >Chế độ đăng</label
                >
                  <q-toggle v-model="is_public"/>
                <label :class="is_public? 'txt-green' : 'txt-red'">{{is_public?'Công khai' : 'Riêng tư'}}</label>
                <p v-if="is_public" style="font-style: italic; color: #6b7280">*Hiển thị lịch trình công khai</p>
                <p v-else style="font-style: italic; color: #6b7280">*Chỉ hiển thị lịch này với tài khoản quản trị viên và tài khoản các thành viên tham gia</p>
                <template v-if="auth.is_super_admin">
                  <label class="text-bold"
                  >Loại lịch trình:</label
                  >
                  <q-toggle v-model="type"/>
                  <label class="txt-green">{{type?'Lịch học viện' : 'Lịch Đơn vị'}}</label>
                  <template v-if="!type">
                    <br>
                    <label class="text-bold"
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
                  </template>
                </template>
                <template v-else>
                  <label class="text-bold"
                  >Loại lịch trình:</label
                  >
                  <q-toggle v-model="type"/>
                  <label class="txt-green">{{type?'Lịch học viện' : 'Lịch Đơn vị'}}</label>
                  <template v-if="!type">
                    <br>
                    <label class="text-bold"
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
                          :options="departments.filter(item => item.id === auth.department_id)"
                          :rules="rule.department_id"
                      />
                    </div>
                  </template>
                </template>
              </div>
              <div class="col">
                <q-uploader
                    url="http://localhost:4444/upload"
                    label="Tài liệu đính kèm"
                    multiple
                    style="width: 100%"
                    @added="addFile"
                    @removed="removeFile"
                />
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
      <q-dialog v-model="visibleDialogConfirmExist" persistent>
          <q-card>
              <q-card-section class="row items-center">
                  <span class="q-ml-sm">Cán bộ <b>{{nameExist}}</b> đã ở trong lịch trình khác <br> Bạn có chắc chắn muốn thêm?</span>
              </q-card-section>

              <q-card-actions align="right">
                  <q-btn flat label="Hủy" color="primary" @click="notAddElement" v-close-popup/>
                  <q-btn label="Đồng ý" color="red" @click="visibleDialogConfirmExist = false" v-close-popup/>
              </q-card-actions>
          </q-card>
      </q-dialog>
      <q-dialog v-model="visibleDialogConfirmExistLeader" persistent>
          <q-card>
              <q-card-section class="row items-center">
                  <span class="q-ml-sm">Cán bộ <b>{{nameExist}}</b> đã ở trong lịch trình khác <br> Bạn có chắc chắn muốn thêm?</span>
              </q-card-section>

              <q-card-actions align="right">
                  <q-btn flat label="Hủy" color="primary" @click="notAddAdmin" v-close-popup/>
                  <q-btn label="Đồng ý" color="red" @click="visibleDialogConfirmExistLeader = false" v-close-popup/>
              </q-card-actions>
          </q-card>
      </q-dialog>
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
import IScheduleResult from "../../models/IScheduleResult";
import IPaginate from "resources/js/models/IPaginate";
import {IDepartmentResult} from "../../models/IDepartmentResult";
import {ILocationResult} from "../../models/ILocationResult";
import {VALIDATE_EMAIL_REGEX, VALIDATE_PHONE_REGEX_RULE,} from "../../utils/constants";
import {useRoute} from 'vue-router'
import IUserResult from "resources/js/models/IUserResult";
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

// register globally
export default defineComponent({
  name: "ScheduleCreate",
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
      title: ref<string>(""),
      start_time: ref<string>(""),
      end_time: ref<string>(""),
      location_other_name: ref<string>(""),
      leader_orther_name: ref(""),
      description: ref<string>(""),
      type: ref<boolean>(true),
      is_public: ref<boolean>(true),
      department_id: ref<number>(),
    };
    const auth = store.getters["auth/getAuthUser"]

    const payload = reactive({...user})
    const roles = reactive(ref<IRoleResult[]>([]))
    const departments = ref<IDepartmentResult[]>([]);
    const locations = ref<ILocationResult[]>([]);
    const locationIds = ref<string[]>([]);
    const locationNameOptions = ref<string[]>([]);
    const users = ref<IUserResult[]>([]);
    const leaderIds = ref<string[]>([]);
    const leaderNameOptions = ref<string[]>([]);
    const files = ref<any[]>([])
    const userOld = ref<any>({})
      const visibleDialogConfirmExist = ref<boolean>(false)
      const visibleDialogConfirmExistLeader = ref<boolean>(false)
      const nameExist = ref<string>('')

      const elementNameOptions = ref<string[]>([])
    const lstElement = ref( ['1','2'])

    const refInput: any = {
      title: ref<any>(null),
      start_time: ref<any>(null),
      end_time: ref<any>(null),
      leader_orther_name: ref<any>(null),
      element: ref<any>(null),
      department_id: ref<any>(null),
    };

    const rule = {
      title: [
        (val: any) =>
            (val && val.length > 0) || "Trường tiêu đề không được bỏ trống!",
      ],
      location_other_name: [
        (val: any) =>
            (val && val.length > 0) || "Trường địa chỉ không được bỏ trống!",
      ],
      start_time: [
        (val: any) =>
            (val && val.length > 0) || "Trường thời gian bắt đầu không được bỏ trống!",
      ],
        end_time: [
        (val: any) =>
            (val && val > user.start_time.value) || "Trường thời gian bắt đầu không được bỏ trống!",
      ],
      leader_orther_name: [
        (val: any) =>
            (val && val.length > 0) || "Trường người chủ trì không được bỏ trống!",
      ],
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
    const editor = ClassicEditor;
    const idSchedule = ref("")
    const ticked = ref<any>([]);
    const loadingPermission = ref<boolean>(false);
    const permissionArray = ref<any>([]);
    const  editorConfig= {
      fontColor: {
        colors: [
          {
            color: 'hsl(0, 0%, 0%)',
            label: 'Black'
          },
          {
            color: 'hsl(0, 0%, 30%)',
            label: 'Dim grey'
          },
          {
            color: 'hsl(0, 0%, 60%)',
            label: 'Grey'
          },
          {
            color: 'hsl(0, 0%, 90%)',
            label: 'Light grey'
          },
          {
            color: 'hsl(0, 0%, 100%)',
            label: 'White',
            hasBorder: true
          },

          // ...
        ]
      },
      fontBackgroundColor: {
        colors: [
          {
            color: 'hsl(0, 75%, 60%)',
            label: 'Red'
          },
          {
            color: 'hsl(30, 75%, 60%)',
            label: 'Orange'
          },
          {
            color: 'hsl(60, 75%, 60%)',
            label: 'Yellow'
          },
          {
            color: 'hsl(90, 75%, 60%)',
            label: 'Light green'
          },
          {
            color: 'hsl(120, 75%, 60%)',
            label: 'Green'
          },

          // ...
          // ...
        ]
      },
      // toolbar: [
      //   'heading', 'bulletedList', 'numberedList', 'fontColor', 'fontBackgroundColor', 'undo', 'redo'
      // ]
      toolbar: {
        items: [
          'heading', '|',
          'fontfamily', 'fontsize', '|',
          'alignment', '|',
          'fontColor', 'fontBackgroundColor', '|',
          'bold', 'italic', 'strikethrough', 'underline', 'subscript', 'superscript', '|',
          'link', '|',
          'outdent', 'indent', '|',
          'bulletedList', 'numberedList', 'todoList', '|',
          'code', 'codeBlock', '|',
          'insertTable', '|',
          'uploadImage', 'blockQuote', '|',
          'undo', 'redo'
        ],
      }
    }

    const handleSave = (): void => {
      if (isValidate()) {
        const data = JSON.parse(JSON.stringify(payload));
        data.elements = lstElement.value;
        data.type = payload.type?1:0
        if (idSchedule.value) {
            if (userOld.value.title !== user.title.value){
                data.old_title = userOld.value.title
            }
            if (userOld.value.start_time !== user.start_time.value){
                data.old_start_time = userOld.value.start_time
            }
            if (userOld.value.end_time !== user.end_time.value){
                data.old_end_time = userOld.value.end_time
            }
            if (userOld.value.location_other_name !== user.location_other_name.value){
                data.old_location_other_name = userOld.value.location_other_name
            }
            if (userOld.value.leader_orther_name !== user.leader_orther_name.value){
                data.old_leader_orther_name = userOld.value.leader_orther_name
            }
            if (userOld.value.description !== user.description.value){
                data.old_description = userOld.value.description
            }
            if (userOld.value.department_id !== user.department_id.value){
                data.old_department_id = userOld.value.department_id
            }
            console.log(data)
          api.updateSchedule(data, idSchedule.value).then(res => {
            if (res) {
              eventBus.$emit('notify-success', 'Cập nhật lịch thành công')
                redirectRouter('AcademySchedule', {id: res.data?.data?.schedule?.id})
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
          api.createSchedule<IScheduleResult>(data).then(res => {
            if (res) {
              console.log(res)
              eventBus.$emit('notify-success', 'Tạo mới lịch thành công')
              redirectRouter('AcademySchedule', {id: res.data?.data?.schedule?.id})
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
      const listInput: any = !user.type.value
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
    const filterFn = (val, update): void => {
      update(() => {
        if (val === '') {
          locationNameOptions.value = locationIds.value
        } else {
          const needle = val.toLowerCase()
          locationNameOptions.value = locationIds.value.filter(
              v => v.toLowerCase().indexOf(needle) > -1
          )
        }
      })
    }
    const filterFnLeader = (val, update): void => {
      update(() => {
        if (val === '') {
          leaderNameOptions.value = leaderIds.value
        } else {
          const needle = val.toLowerCase()
          leaderNameOptions.value = leaderIds.value.filter(
              v => v.toLowerCase().indexOf(needle) > -1
          )
        }
      })
    }

    const createValue = (val, done): void => {
      done(val, 'toggle')
    }
    const createValueLeader = (val, done): void => {
      done(val, 'toggle')
    }

    const getListLocation = (): void => {
      const payload = {
        page: 1,
        limit: 100,
      };
      api
          .getLocations<IPaginate<ILocationResult[]>>(payload)
          .then((res) => {
            locations.value = _.get(res, "data.data.location.data");
            locationIds.value = locations.value.map((obj) => obj.name);
            locationNameOptions.value = locations.value.map((obj) => obj.name);
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
    const getListUser = (): void => {
      const payload = {
        page: 1,
        limit: 100,
      };
      api
          .getUsers<IPaginate<IUserResult[]>>(payload)
          .then((res) => {
            users.value = _.get(res, "data.data.users.data");
            leaderIds.value = users.value.map((obj) => obj.user_name);
            leaderNameOptions.value = users.value.map((obj) => obj.user_name);
            elementNameOptions.value = users.value.map((obj) => obj.user_name);
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

    const handleGetSchedule = (id: string): void => {
      $q.loading.show()
      api.getSchedule<IScheduleResult>(id).then(res => {
        const data = _.get(res, 'data.data.schedule', '')
        lstElement.value = _.get(res, 'data.data.elements', [''])
        for (const key in user) {
          if (['is_leader', 'is_super_admin'].includes(key))
            user[key].value = data[key] ? true : false
          else user[key].value = data[key]
        }
          userOld.value = _.cloneDeep(user)

      }).catch(() => {
        $q.notify({
          icon: 'report_problem',
          message: 'Không tải được dữ liệu lịch!',
          color: 'negative',
          position: 'top-right'
        })
      }).finally(() => $q.loading.hide())
    }

    for (const key in payload) {
      if (refInput[key]) {
        watch(user[key], (): void => {
            resetValidateErrors(key)
          refInput[key].value?.resetValidation()
        })
      }
    }

      watch(user.end_time, (): void => {
          if (user.end_time.value < user.start_time.value){
              $q.notify({
                  icon: 'report_problem',
                  message: 'Thời gian kết thúc phải sau thời gian bắt đâu',
                  color: 'negative',
                  position: 'top-right'
              })
          }
      })
      watch(user.start_time, (): void => {
          if (user.end_time.value < user.start_time.value){
              $q.notify({
                  icon: 'report_problem',
                  message: 'Thời gian bắt đầu phải trước thời gian kết thúc',
                  color: 'negative',
                  position: 'top-right'
              })
          }
      })
      watch(user.leader_orther_name, (value): void => {
          if (user.start_time.value && user.end_time.value && !_.isEmpty(value)){
              const params = {
                  name: value,
                  start_time: user.start_time.value,
                  end_time: user.end_time.value
              }
              api.checkExist<any>(params).then(res => {
                  const check = _.get(res, 'data.data.schedule', true)
                  if (!check){
                      console.log(lstElement.value)
                      nameExist.value = value
                      visibleDialogConfirmExistLeader.value = true
                  }
              }).catch(() => {
                  $q.notify({
                      icon: 'report_problem',
                      message: 'Không tải được dữ liệu lịch!',
                      color: 'negative',
                      position: 'top-right'
                  })
              }).finally(() => $q.loading.hide())
          }

      })

      const selectElement = (value, id):void => {
        if (user.start_time.value && user.end_time.value){
            const params = {
                name: value,
                start_time: user.start_time.value,
                end_time: user.end_time.value
            }
            console.log(value)
            api.checkExist<any>(params).then(res => {
                const check = _.get(res, 'data.data.schedule', true)
                if (!check){
                    console.log(lstElement.value)
                    nameExist.value = value
                    visibleDialogConfirmExist.value = true
                }
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được dữ liệu lịch!',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => $q.loading.hide())
        }
      }
    const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
      router.push({name: nameRoute, params: params})
    }

    const addFile = (file:any[]) : void =>{
      file.forEach((item)=>{
        files.value.push(item)
      })
    }
    const notAddElement = () : void =>{
        lstElement.value = lstElement.value.splice(lstElement.value.length - 1, 1)
    }
    const notAddAdmin = () : void =>{
        user.leader_orther_name.value = ''
    }
    const removeFile = (file:any[]) : void =>{
      files.value = files.value.filter(item => item !== file[0])
    }

    onMounted(() => {
      store.commit(`home/${HomeMutationTypes.SET_TITLE}`, "Quản lý người dùng");
      getRoleList();
      getListDepartment();
      getListLocation();
      getListUser();
      console.log(router)
      idSchedule.value = <string>route.params.id
      if (idSchedule.value) {
        handleGetSchedule(idSchedule.value)
      }
    });
    return {
      handleSave,
        selectElement,
        notAddElement,
        notAddAdmin,
        userOld,
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
      locations,
      users,
      locationIds,
      leaderIds,
      idSchedule,
      createValue,
      filterFn,
      createValueLeader,
      filterFnLeader,
      locationNameOptions,
      leaderNameOptions,
      getListUser,
      editor,
      editorConfig,
      elementNameOptions,
      lstElement,
      addFile,
      removeFile,
      files,
      auth,
        nameExist,
        visibleDialogConfirmExist,
        visibleDialogConfirmExistLeader
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

::v-deep {
  .q-field__native {
    span {
      padding: 5px 10px;
      background: #005bff1a;
      border-radius: 10px;
    }
  }

  .text-negative {

    .q-field__native {
      span {
        padding: 0 !important;
      }
    }
  }
}

.input-time {
  input {
    height: 40px;
    outline: none;
    border: 1px solid;
    border-color: #c2c2c2;
    border-radius: 3px;
    width: 100%;
    padding: 0 15px;
  }
}
::v-deep{
  .multiselect-tag{
    align-items: center;
    background: #e5eeff!important;
    border-radius: 5px;
    color: #1e1f68!important;
    display: flex;
    font-size: var(--ms-tag-font-size,.875rem);
    font-weight: normal!important;
    line-height: var(--ms-tag-line-height,1.25rem);
    margin-bottom: var(--ms-tag-my,.25rem);
    margin-right: var(--ms-tag-mx,.25rem);
    padding: 5px 10px!important;
    white-space: nowrap;
  }
}
.txt-green{
  color: green;
}
.txt-red{
  color: red;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
