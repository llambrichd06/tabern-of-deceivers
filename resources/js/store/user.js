import { defineStore } from 'pinia';
import { ref } from 'vue';
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";

export const useUserCrudStore = defineStore('usercrud', () => {
    // starter state
    const filters = ref({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        name: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        alias: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        email: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        roles: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        created_at: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
    });

    // store sorts
    const sortField = ref('id');
    const sortOrder = ref(1); // 1 for asc, -1 for desc

    return { filters, sortField, sortOrder };
}, {
    persist: true
});