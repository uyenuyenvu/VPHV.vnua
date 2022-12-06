import {ref} from "vue";
import {ILocationResult} from "../models/ILocationResult";
import api from "../api";
import _ from 'lodash'

interface ILocations {
    locations: ILocationResult[]
}

const locations = ref<ILocationResult[]>([])

const isError = ref<boolean>(false)
const isLoading = ref<boolean>(false)

const getAllLocation = (params = {}): void => {
    isError.value = false
    isLoading.value = true
    api.getAllLocation<ILocations>(params).then((res) => {
        locations.value = _.get(res, 'data.data.locations', [])
    }).catch(() => {
        isError.value = true
    }).finally(()=> isLoading.value = false)
}

export default function () {
    return {
        locations,
        isError,
        isLoading,
        getAllLocation,
    }
}
