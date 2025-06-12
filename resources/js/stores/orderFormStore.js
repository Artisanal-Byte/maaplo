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
                cloth_img1_url: null,                // added URL fields here to be consistent
                cloth_img2_url: null,
                Pattern_img1: null,
                Pattern_img2: null,
                Pattern_img1_url: null,
                Pattern_img2_url: null,
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
                const item = this.order_items[index];
                this.order_items_template = {
                    ...item,
                    cloth_img1: null,  // reset file input
                    cloth_img2: null,
                    cloth_img1_url: item.cloth_img1_url || null,
                    cloth_img2_url: item.cloth_img2_url || null,
                    Pattern_img1: null,
                    Pattern_img2: null,
                    Pattern_img1_url: item.Pattern_img1_url || null,
                    Pattern_img2_url: item.Pattern_img2_url || null,
                    trial_dates: item.trial_dates || '', // Ensure this exists
                    delivery_date: item.delivery_date || '',
                    mode: 'edit'
                };
                // this.editingItemIndex = index;
            }
            console.log('cust id :', this.customer_id);

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
            this.user_id = order.user_id;
            this.customer_id = order.customer_id;
            this.status = order.status && [
                "created", "in process", "processed", "delivered", "completed", "cancelled"
            ].includes(order.status.toLowerCase())
                ? order.status.toLowerCase()
                : 'created';
            this.total_amount = order.total_amount;
            this.advance_paid = Number(order.advance_paid) || 0;
            // Convert DD-MM-YYYY to YYYY-MM-DD if necessary
            if (order.delivery_date && order.delivery_date.includes('-')) {
                const parts = order.delivery_date.split('-');
                if (parts.length === 3 && parts[2].length === 4) {
                    // Assume DD-MM-YYYY
                    this.delivery_date = `${parts[2]}-${parts[1]}-${parts[0]}`;
                } else {
                    this.delivery_date = order.delivery_date;
                }
            } else {
                this.delivery_date = '';
            }

            this.close_date = order.close_date || '';
            this.notes = Array.isArray(order.notes)
                ? order.notes
                : (typeof order.notes === 'string' ? JSON.parse(order.notes || '[]') : []);

            this.order_items = order.order_items.map(item => ({
                ...item,
                id: item.id,
                item_cost: Number(item.item_cost) || 0,
                colors:item.colors,
                is_urgent:item.isUrgent == 'yes' ? true :false,
                trial_dates: item.trial_dates || '',
                cloth_img1_url: item.cloth_img1_url || null,
                cloth_img2_url: item.cloth_img2_url || null,
                Pattern_img1_url: item.Pattern_img1_url || null,
                Pattern_img2_url: item.Pattern_img2_url || null,
                delivery_date: item.delivery_date && item.delivery_date.includes('-') ?
                    (() => {
                        const p = item.delivery_date.split('-');
                        return p.length === 3 && p[2].length === 4 ? `${p[2]}-${p[1]}-${p[0]}` : item.delivery_date;
                    })() : '',
                measurements: typeof item.measurements === 'string' ? JSON.parse(item.measurements || '{}') : item.measurements,
                design_detail: typeof item.design_detail === 'string' ? JSON.parse(item.design_detail || '{}') : item.design_detail,
                notes: typeof item.notes === 'string' ? JSON.parse(item.notes || '[]') : item.notes,
            }));

            this.resetOrderItemTemplate();
        },
        async updateOrder(orderId, toast) {
            const { order_items_template, ...formData } = this.$state;
            const form = useForm({ ...formData, total_amount: this.total_amount });  // Ensure total_amount is passed

            try {
                await form.put(route('orders.update', orderId), {
                    onSuccess: () => {
                        if (toast && typeof toast.success === 'function') {
                            toast.success('Order updated successfully!');
                        } else {
                            console.warn('Toast function is not available');
                        }
                        this.resetOrderData();
                        window.location.href = route('orders.index');
                    },
                    onError: (errors) => {
                        console.error('Update failed:', errors);
                        if (toast && typeof toast.error === 'function') {
                            toast.error('Update failed. Please fix the errors.');
                        } else {
                            console.warn('Toast function is not available');
                        }
                    },
                });
            } catch (error) {
                console.error('Update request failed:', error);
                if (toast && typeof toast.error === 'function') {
                    toast.error('Update failed. Please fix the errors.');
                }
            }
        }

    }

})
