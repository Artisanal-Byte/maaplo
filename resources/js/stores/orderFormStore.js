import { defineStore } from 'pinia'

export const useOrderFormStore = defineStore('orderForm', {
    state: () => ({
        template_id: null,
        measurements: [],
        design_detail: [],
        colors: '',
        work_type: '',
        material_type: '',
        material_code: '',
        refrence_dress: '',
        is_urgent: '',
        material_cost: null,
        stiching_cost: null,
        item_cost: null,
        notes: [{}],
        trial_dates: '',
        delivery_date: '',
        status: '',
        cloth_img1: null,
        cloth_img2: null,
        Pattern_img1: null,
        Pattern_img2: null
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
