import { defineStore } from 'pinia';
import { ref } from 'vue';
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";

export const useGameCrudStore = defineStore('gamecrud', () => {
    // starter state
    const filters = ref({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        room_id: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        is_finished: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
        game_state: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
    });

    // store sorts
    const sortField = ref('id');
    const sortOrder = ref(1); // 1 for asc, -1 for desc

    return { filters, sortField, sortOrder };
}, {
    persist: true
});