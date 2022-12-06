<template>
    <div class="department-wrap">
        <div class="department-header">
            <div class="item-header">Thông tin linh tinh</div>
            <div class="item-header">
                <q-btn no-caps @click="redirectRouter('UserCreate')" color="secondary" class="q-mr-sm">
                    <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
                    Thêm lịch
                </q-btn>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {useRouter} from "vue-router/dist/vue-router";

export default defineComponent({
    name: "Department",
    setup() {
        const router = useRouter()

        const redirectRouter = (nameRoute: string): void => {
            router.push({name: nameRoute})
        }
        return {
            redirectRouter
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
