import _ from "lodash";
import { useQuasar } from "quasar";
import { defineComponent, getCurrentInstance, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import api from "../../api";
import { ILocationResult } from "../../models/ILocationResult";
import { IPage, IPayload } from "../../models/IPage";
import IPaginate from "../../models/IPaginate";
import { HomeMutationTypes } from "../../store/modules/home/mutation-types";
import eventBus from "../../utils/eventBus";
import { formatDate } from "../../utils/helpers";
import CreateOrUpdateLocation from "./CreateOrUpdate.vue";
import DeleteLocation from "./Delete.vue";

export default defineComponent({
  name: "LocationIndex",
  components: {
    DeleteLocation,
    CreateOrUpdateLocation
  },
  setup() {
    const {proxy} = getCurrentInstance()
    const $q = useQuasar();
    const store = useStore();
    const search = ref<string>("");
    const locations = ref<Array<ILocationResult>>([]);
    const locationIds = ref<Array<string>>([]);
    const checkboxArray = ref<Array<string>>([]);
    const checkboxAll = ref<boolean | string>(false);
    const locationCurrent = ref<any>({})
    const page = ref<IPage>({
      currentPage: 1,
      total: 0,
      perPage: 10,
    });

    const currentPage = ref<number>(1);
    const loadingLocations = ref<boolean>(false);
    const isFilter = ref<boolean>(false);
    const toggleFilter = (): void => {
      isFilter.value = !isFilter.value;
    };
    const closeFilter = (): void => {
      isFilter.value = false;
    };

    const onClickCreateBtn = async () => {
        proxy.$refs.popupRef?.onChangeDialog()
    }

    const handleFormatDate = (value: string): string => {
      return formatDate(value);
    };

    const getListLocation = (): void => {
      loadingLocations.value = true;
      const payload: IPayload = {
        page: 1,
      };

      if (search.value) {
        payload.q = search.value;
      }

      payload.page = page?.value?.currentPage;
      api
        .getLocations<IPaginate<ILocationResult[]>>(payload)
        .then((res) => {
            locations.value = _.get(res, "data.data.location.data");

            locationIds.value = locations.value.map(location => location.id);
                page.value.currentPage = _.get(
            res,
            "data.data.location.current_page",
            1
          );
          page.value.total = _.get(res, "data.data.location.last_page", 0);
          page.value.perPage = _.get(res, "data.data.location.per_page", 0);
        })
        .catch(() => {
            generateNotify("Không tải được danh sách địa điểm")
        })
        .finally(() => (loadingLocations.value = false));
    };

    const openDialogDelete = async (id: number) => {
        proxy.$refs.popupDeleteRef?.setLocationId(id)
        proxy.$refs.popupDeleteRef?.onChangeDialog()
    };

    const openDialogUpdate = async (location: any) => {
        if(!location) return;
        proxy.$refs.popupRef?.setLocationCurrent(location)
        proxy.$refs.popupRef?.onChangeDialog()
    };

    const openDialogDeleteSelect = async (checkboxArray) => {
        proxy.$refs.popupDeleteRef?.setListIdLocation(checkboxArray)
        proxy.$refs.popupDeleteRef?.onChangeDialog()

    };

    const getValueLodash = (res: object, data: string, d: any = null) => {
        return _.get(res, data, d);
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


    watch(
      () => page?.value?.currentPage,
      () => getListLocation()
    );
    watch(
      () => search.value,
      () => getListLocation()
    );
    watch(
      () => checkboxAll.value,
      (value) => {
        if (value === true) {
          checkboxArray.value = locationIds.value;
        }

        if (value === false) {
          checkboxArray.value = [];
        }
      }
    );

    watch(
      () => checkboxArray.value,
      (value) => {
        if (value.length < locationIds.value.length) {
          checkboxAll.value = "maybe";
        }

        if (value.length == 0) {
          checkboxAll.value = false;
        }
      }
    );

    onMounted(() => {
      store.commit(`home/${HomeMutationTypes.SET_TITLE}`, "Quản lý địa điểm");
      eventBus.$on("notify-success", (message: string) => {
        generateNotify(message, true)
      });
        getListLocation();

    });

    const resetListIdDelete = () => {
        checkboxArray.value = [];
    }

    return {
        openDialogUpdate,
        search,
        isFilter,
        toggleFilter,
        closeFilter,
        handleFormatDate,
        onClickCreateBtn,
        getValueLodash,
        currentPage,
        locations,
        loadingLocations,
        getListLocation,
        page,
        openDialogDelete,
        openDialogDeleteSelect,
        checkboxArray,
        checkboxAll,
        resetListIdDelete,
        locationCurrent,
    };
  },
});
