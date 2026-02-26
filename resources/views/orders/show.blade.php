<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="orderShow({{ $orderId }})">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <template x-if="loading">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Loading...') }}</p>
                    </div>
                </div>
            </template>

            <template x-if="!loading && order">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Order') }} #<span x-text="order.id"></span>
                        </h3>
                        <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                            &larr; {{ __('Back to list') }}
                        </a>
                    </div>

                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Order Date') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="order.orderDate"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': order.status === 'pending',
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': order.status === 'processing',
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': order.status === 'completed',
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': order.status === 'cancelled',
                                        }"
                                        x-text="order.status.charAt(0).toUpperCase() + order.status.slice(1)"
                                    ></span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Product Name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="order.productName"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Quantity') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="order.quantity"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Amount') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="'$' + parseFloat(order.totalAmount).toFixed(2)"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Customer') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="customerName"></dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Description') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="order.description || '—'"></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </template>

            <template x-if="!loading && !order">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Order not found.') }}</p>
                        <a href="{{ route('orders.index') }}" class="mt-4 inline-block text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                            &larr; {{ __('Back to list') }}
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        function orderShow(orderId) {
            return {
                order: null,
                customerName: '—',
                loading: true,

                async init() {
                    try {
                        const response = await axios.get(`/api/orders/${orderId}`);
                        this.order = response.data.data;

                        try {
                            const customerResponse = await axios.get(`/api/customers/${this.order.customerId}`);
                            this.customerName = customerResponse.data.data.name;
                        } catch (e) {
                            this.customerName = '—';
                        }
                    } catch (error) {
                        toastr.error('Failed to load order.');
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
</x-app-layout>
