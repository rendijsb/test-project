<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Details') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="customerShow({{ $customerId }})">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <template x-if="loading">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Loading...') }}</p>
                    </div>
                </div>
            </template>

            <template x-if="!loading && customer">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="customer.name"></h3>
                        <a href="{{ route('customers.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                            &larr; {{ __('Back to list') }}
                        </a>
                    </div>

                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="customer.name"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="customer.email"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="customer.phone || '—'"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Company') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="customer.companyName || '—'"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Address Line 1') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="customer.addressLine1"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Address Line 2') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="customer.addressLine2 || '—'"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('City') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="customer.city"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Postal Code') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="customer.postalCode"></dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Country') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="customer.country"></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </template>

            <template x-if="!loading && !customer">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Customer not found.') }}</p>
                        <a href="{{ route('customers.index') }}" class="mt-4 inline-block text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                            &larr; {{ __('Back to list') }}
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        function customerShow(customerId) {
            return {
                customer: null,
                loading: true,

                async init() {
                    try {
                        const response = await axios.get(`/api/customers/${customerId}`);
                        this.customer = response.data.data;
                    } catch (error) {
                        toastr.error('Failed to load customer.');
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
</x-app-layout>
