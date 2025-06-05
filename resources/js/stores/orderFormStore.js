import { useForm } from '@inertiajs/vue3'
import { defineStore } from 'pinia'

export const useOrderFormStore = defineStore('orderForm', {
    state: () => (
        {
            user_id: null,
            customer_id: null,
            status: 'create',
            total_amount: null,
            advance_paid: null,
            delivery_date: '',
            close_date: '',
            notes: [{ label: '', text: '' }],
            order_items: [],
            order_items_template:
            {
                mode: 'create',
                template_id: null,
                measurements: [],
                design_detail: [],
                colors: '',
                work_type: 'New from Material',
                material_type: '',
                material_code: '',
                refrence_dress: '',
                is_urgent: '',
                material_cost: null,
                stiching_cost: null,
                altering_cost: null,
                item_cost: null,
                notes: [{}],
                trial_dates: '',
                delivery_date: '',
                status: '',
                cloth_img1: null,
                cloth_img2: null,
                Pattern_img1: null,
                Pattern_img2: null
            },
        }
    ),
    actions: {
        pushOrderItem() {
            this.order_items.push(this.order_items_template)
        },
        resetOrderItemTemplate() {
            this.order_items_template = {
                template_id: null,
                measurements: [],
                design_detail: [],
                colors: '',
                work_type: 'New from Material',
                material_type: '',
                material_code: '',
                refrence_dress: '',
                is_urgent: '',
                material_cost: null,
                stiching_cost: null,
                altering_cost: null,
                item_cost: null,
                notes: [{}],
                trial_dates: '',
                delivery_date: '',
                status: '',
                cloth_img1: null,
                cloth_img2: null,
                Pattern_img1: null,
                Pattern_img2: null
            }
        },
        resetOrderData(){
            this.user_id= null
            this.customer_id=null
            this.status='create'
            this.total_amount= null
            this.advance_paid=null
            this.delivery_date= ''
            this.close_date= ''
            this.notes= [{ label: '', text: '' }]
            this.order_items=[]
            this.resetOrderItemTemplate()
        },
        setOrderItemData(index) {
            if (index >= 0 && index < this.order_items.length) {
                this.order_items_template = this.order_items[index]
            }
        },
        createOrder() {
            const { order_items_template, ...formData } = this.$state;
            const form = useForm({ ...formData });
            form.post(route('orders.store'));
            this.resetOrderData()
        },
        deleteOrderItem(index) {
            if (index >= 0 && index < this.order_items.length) {
                this.order_items.splice(index, 1)
            }
        },
    },
})
