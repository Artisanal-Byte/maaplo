import { router, useForm } from '@inertiajs/vue3'
import { defineStore } from 'pinia'
const toast = new ToastMagic();

export const useOrderFormStore = defineStore('orderForm', {
    state: () => (
        {
            isLoading: false,
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
                design_detail: {},
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
                const updatedItem = {
                    ...this.order_items_template,
                    id: this.order_items[this.editingItemIndex].id, // Preserve ID
                    cloth_img1: this.order_items_template.cloth_img1,
                    cloth_img2: this.order_items_template.cloth_img2,
                    Pattern_img1: this.order_items_template.Pattern_img1,
                    Pattern_img2: this.order_items_template.Pattern_img2,
                    refrence_dress: this.order_items_template.refrence_dress,
                };

                this.order_items.splice(this.editingItemIndex, 1, updatedItem);
                this.editingItemIndex = null;

                 this.total_amount = this.order_items.reduce((acc, item) => acc + (parseFloat(item.item_cost) || 0), 0);
            }
        },

        resetOrderItemTemplate() {
            this.order_items_template = {
                mode: 'create',
                template_id: null,
                measurements: [],
                design_detail: {},
                colors: '',
                work_type: 'New from Material',
                material_type: '',
                material_code: '',
                refrence_dress: '',
                is_urgent: false,
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
                    cloth_img1: null,
                    cloth_img2: null,
                    Pattern_img1: null,
                    Pattern_img2: null,
                    refrence_dress: null,
                    cloth_img1_url: item.cloth_img1_url || null,
                    cloth_img2_url: item.cloth_img2_url || null,
                    Pattern_img1_url: item.Pattern_img1_url || null,
                    Pattern_img2_url: item.Pattern_img2_url || null,
                    trial_dates: item.trial_dates || '',
                    delivery_date: item.delivery_date || '',
                    mode: 'edit'
                };

                // ✅ This is ESSENTIAL
                this.editingItemIndex = index;
            }

        },

        createOrder() {
            this.isLoading = true;
            const { order_items_template, ...formData } = this.$state;
            const form = useForm({ ...formData });
            form.post(route('orders.store'), {
                onSuccess: () => {
                    toast.success('Order created successfully!');
                    this.resetOrderData();

                    this.isLoading = false;
                },
                onError: (errors) => {
                    this.isLoading = false;
                    console.error('Order creation failed:', errors);
                }
            });
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
                "created", "in_process", "processed", "delivered", "completed", "cancelled", "trial_done", "in_alteration", "ready_for_delivery"
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

            this.order_items = order.order_items.map(item => {
                const parsedItem = {
                    ...item,
                    id: item.id,
                    template_id: item.template_id ?? item.item_template_id ?? null,
                    item_cost: Number(item.item_cost) || 0,
                    colors: item.colors,
                    is_urgent: item.is_urgent === true || item.is_urgent === 'true',

                    trial_dates: item.trial_dates || '',
                    cloth_img1_url: item.cloth_img1_url || null,
                    cloth_img2_url: item.cloth_img2_url || null,
                    Pattern_img1_url: item.Pattern_img1_url || null,
                    Pattern_img2_url: item.Pattern_img2_url || null,
                    delivery_date: item.delivery_date && item.delivery_date.includes('-')
                        ? (() => {
                            const p = item.delivery_date.split('-');
                            return p.length === 3 && p[2].length === 4 ? `${p[2]}-${p[1]}-${p[0]}` : item.delivery_date;
                        })()
                        : '',
                    measurements: typeof item.measurements === 'string' ? JSON.parse(item.measurements || '{}') : item.measurements,
                    design_detail: typeof item.design_detail === 'string' ? JSON.parse(item.design_detail || '{}') : item.design_detail,
                    notes: typeof item.notes === 'string' ? JSON.parse(item.notes || '[]') : item.notes,
                };

                return parsedItem;
            });

            this.resetOrderItemTemplate();
        },

        async updateOrder(orderId) {
            const { order_items_template, ...formData } = this.$state;
            const form = new FormData();

            form.append('_method', 'put');
            form.append('customer_id', formData.customer_id ?? '');
            form.append('total_amount', formData.total_amount ?? '');
            form.append('advance_paid', formData.advance_paid ?? '');
            form.append('status', formData.status ?? '');
            form.append('delivery_date', formData.delivery_date || '');
            form.append('close_date', formData.close_date || '');


            // Main order notes
            (formData.notes || []).forEach((note, i) => {
                form.append(`notes[${i}][label]`, note.label || '');
                form.append(`notes[${i}][text]`, note.text || '');
            });

            // Order items
            formData.order_items.forEach((item, index) => {
                form.append(`order_items[${index}][template_id]`, Number(item.template_id) || '');
                form.append(`order_items[${index}][is_urgent]`, item.is_urgent ? 'true' : 'false');
                // Measurements
                Object.entries(item.measurements || {}).forEach(([key, val]) => {
                    form.append(`order_items[${index}][measurements][${key}]`, val ?? '');
                });

                // Design detail
                Object.entries(item.design_detail || {}).forEach(([key, val]) => {
                    form.append(`order_items[${index}][design_detail][${key}]`, val ?? '');
                });

                // Item notes
                (item.notes || []).forEach((note, noteIndex) => {
                    form.append(`order_items[${index}][notes][${noteIndex}][label]`, note.label || '');
                    form.append(`order_items[${index}][notes][${noteIndex}][text]`, note.text || '');
                });

                // Basic fields
                form.append(`order_items[${index}][colors]`, item.colors || '');
                form.append(`order_items[${index}][trial_dates]`, item.trial_dates || '');
                form.append(`order_items[${index}][delivery_date]`, item.delivery_date || '');
                form.append(`order_items[${index}][work_type]`, item.work_type || '');
                form.append(`order_items[${index}][material_type]`, item.material_type || '');
                form.append(`order_items[${index}][material_code]`, item.material_code || '');
                form.append(`order_items[${index}][material_cost]`, item.material_cost ?? '');
                form.append(`order_items[${index}][stiching_cost]`, item.stiching_cost ?? '');
                form.append(`order_items[${index}][altering_cost]`, item.altering_cost ?? '');
                form.append(`order_items[${index}][item_cost]`, item.item_cost ?? '');

                if (item.id) {
                    form.append(`order_items[${index}][id]`, item.id);
                }

                // File fields
                ['refrence_dress', 'cloth_img1', 'cloth_img2', 'Pattern_img1', 'Pattern_img2'].forEach(field => {
                    if (item[field] instanceof File) {
                        form.append(`order_items[${index}][${field}]`, item[field]);
                    }
                });
            });

            // Send form
            try {
                router.post(route('orders.update', orderId), form, {
                    forceFormData: true,
                    preserveScroll: true,
                    onSuccess: () => {
                        toast.success('Order updated successfully!');
                        this.isLoading = false
                        this.resetOrderData();
                    },
                    onError: (errors) => {
                        if (toast && typeof toast.error === 'function') {
                            this.isLoading = false

                            toast.error('Update failed. Please fix the errors.');
                        }
                    }
                });
            } catch (error) {
                console.error('Update request failed:', error);
                if (toast && typeof toast.error === 'function') {
                    loading.value = false;
                    toast.error('Unexpected error occurred.');

                }
            }
        }


    }

})
