import { defineStore } from 'pinia';
import { ref } from 'vue';
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";

export const useRoomCrudStore = defineStore('roomcrud', () => {
    // starter state
    const filters = ref({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        room_code: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        state: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        private: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
        host_id: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
    });

    // store sorts
    const sortField = ref('id');
    const sortOrder = ref(1); // 1 for asc, -1 for desc

    return { filters, sortField, sortOrder };
}, {
    persist: true
});