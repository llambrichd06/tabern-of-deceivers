import { defineStore } from 'pinia';
import { ref } from 'vue';
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";

export const useLeaderboardCrudStore = defineStore('leaderboardcrud', () => {
    // starter state
    const filters = ref({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        user_id: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        points: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
        wins: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
        losses: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
        matches: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
        created_at: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }] },
    });

    // store sorts
    const sortField = ref('id');
    const sortOrder = ref(1); // 1 for asc, -1 for desc

    return { filters, sortField, sortOrder };
}, {
    persist: true //thats it??? one line to make it stay through refresh??
});