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
            editingItemIndex: null,
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
                Pattern_img2: null,

            },
        }
    ),
    actions: {
        pushOrderItem() {
            this.order_items.push({ ...this.order_items_template });
        },

        updateOrderItem() {
            if (this.editingItemIndex !== null) {
                this.order_items.splice(this.editingItemIndex, 1, { ...this.order_items_template });
                this.editingItemIndex = null;
            }
        },
        resetOrderItemTemplate() {
            this.order_items_template = {
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
            };
            this.editingItemIndex = null;
        },

        resetOrderData() {
            this.user_id = null
            this.customer_id = null
            this.status = 'create'
            this.total_amount = null
            this.advance_paid = null
            this.delivery_date = ''
            this.close_date = ''
            this.notes = [{ label: '', text: '' }]
            this.order_items = []
            this.resetOrderItemTemplate()
        },
        setOrderItemData(index) {
            if (index >= 0 && index < this.order_items.length) {
                this.order_items_template = { ...this.order_items[index] }
                this.order_items_template.mode = 'edit';
                this.editingItemIndex = index;
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
        setEditOrder(order) {
            console.log('set Edit Order', order);
            this.user_id = order.user_id;
            this.customer_id = order.customer_id;
            this.status = order.status || 'created';
            this.total_amount = order.total_amount;
            this.advance_paid = order.advance_paid;
            this.delivery_date = order.delivery_date;
            this.close_date = order.close_date || '';
            this.notes = Array.isArray(order.notes)
                ? order.notes
                : (typeof order.notes === 'string' ? JSON.parse(order.notes || '[]') : []);

            this.order_items = order.order_items.map(item => ({
                ...item,
                item_cost: Number(item.item_cost) || 0,
                measurements: typeof item.measurements === 'string' ? JSON.parse(item.measurements || '{}') : item.measurements,
                design_detail: typeof item.design_detail === 'string' ? JSON.parse(item.design_detail || '{}') : item.design_detail,
                notes: typeof item.notes === 'string' ? JSON.parse(item.notes || '[]') : item.notes,
            }));


            this.resetOrderItemTemplate();
        },
        async updateOrder(orderId) {
            const { order_items_template, ...formData } = this.$state;

            const form = useForm({ ...formData });

            form.put(route('orders.update', orderId), {
                onSuccess: () => {
                    ToastMagic.success('Order updated successfully!');
                    this.resetOrderData();
                    window.location.href = route('orders.index');
                },
                onError: (errors) => {
                    console.error('Update failed:', errors);
                    ToastMagic.error('Update failed. Please fix the errors.');
                },
            });
        },
    }

})
