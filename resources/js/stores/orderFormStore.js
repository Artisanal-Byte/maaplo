import { defineStore } from 'pinia'

export const useOrderFormStore = defineStore('orderForm', {
    state: () => ({
        order_items: [],
    }),
    // actions: {
    //     setImage(file) {
    //         this.img = file
    //     },
    //     setField(field, value) {
    //         this[field] = value
    //     }
    // },
})
