import { reactive } from 'vue'

export function useOrder() {
    const orders = reactive({
        user_id: null,
        customer_id: null,
        status: 'create',
        total_amount: null,
        advance_paid: null,
        delivery_date: '',
        close_date: '',
        notes: [{ label: '', text: '' }],
        order_items: [],
    })

    return orders
}
