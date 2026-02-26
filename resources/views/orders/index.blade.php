<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="orderManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</label>
                        <select
                            x-model="filterStatus"
                            @change="changeFilter()"
                            class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        >
                            <option value="">{{ __('All') }}</option>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="processing">{{ __('Processing') }}</option>
                            <option value="completed">{{ __('Completed') }}</option>
                            <option value="cancelled">{{ __('Cancelled') }}</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-500 dark:text-gray-400">{{ __('Customer') }}</label>
                        <select
                            x-model="filterCustomerId"
                            @change="changeFilter()"
                            class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        >
                            <option value="">{{ __('All') }}</option>
                            <template x-for="c in customersList" :key="c.id">
                                <option :value="c.id" x-text="c.name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <button
                    @click="openCreateModal()"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Add Order') }}
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <template x-if="fetchingOrders">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Loading...') }}</p>
                    </div>
                </template>

                <template x-if="!fetchingOrders && orders.length === 0">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No orders found.') }}</p>
                    </div>
                </template>

                <template x-if="!fetchingOrders && orders.length > 0">
                    <div>
                        <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    @foreach([
                                        'orderDate' => __('Order Date'),
                                        'productName' => __('Product'),
                                        'status' => __('Status'),
                                        'totalAmount' => __('Total'),
                                        'customerId' => __('Customer'),
                                    ] as $column => $label)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            <button
                                                @click="sort('{{ $column }}')"
                                                class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-200"
                                            >
                                                {{ $label }}
                                                <span x-show="sortBy === '{{ $column }}'" class="text-gray-900 dark:text-gray-100">
                                                    <svg x-show="sortDirection === 'asc'" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 9.707l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 7.828l-3.293 3.293a1 1 0 01-1.414-1.414z"/></svg>
                                                    <svg x-show="sortDirection === 'desc'" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M14.707 10.293l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 12.172l3.293-3.293a1 1 0 111.414 1.414z"/></svg>
                                                </span>
                                            </button>
                                        </th>
                                    @endforeach
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Qty') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Description') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-for="order in orders" :key="order.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100" x-text="order.orderDate"></td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100" x-text="order.productName"></td>
                                        <td class="px-6 py-4 text-sm">
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
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="'$' + parseFloat(order.totalAmount).toFixed(2)"></td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="getCustomerName(order.customerId)"></td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="order.quantity"></td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate" x-text="order.description || '—'"></td>
                                        <td class="px-6 py-4 text-right text-sm space-x-2">
                                            <a
                                                :href="`/orders/${order.id}`"
                                                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium"
                                            >{{ __('View') }}</a>
                                            <template x-if="canEdit(order)">
                                                <button
                                                    @click="openEditModal(order)"
                                                    class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium"
                                                >{{ __('Edit') }}</button>
                                            </template>
                                            @if(auth()->user()->isAdmin())
                                                <button
                                                    @click="openDeleteModal(order)"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium"
                                                >{{ __('Delete') }}</button>
                                            @endif
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        </div>

                        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Showing') }}
                                    <span x-text="(currentPage - 1) * perPage + 1"></span>–<span x-text="Math.min(currentPage * perPage, total)"></span>
                                    {{ __('of') }}
                                    <span x-text="total"></span>
                                </p>

                                <div class="flex items-center gap-2">
                                    <label class="text-sm text-gray-500 dark:text-gray-400">{{ __('Per page') }}</label>
                                    <select
                                        x-model="perPage"
                                        @change="changePerPage()"
                                        class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    >
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>

                            <template x-if="lastPage > 1">
                                <div class="flex items-center gap-1">
                                    <button
                                        @click="goToPage(currentPage - 1)"
                                        :disabled="currentPage === 1"
                                        class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >&laquo; {{ __('Previous') }}</button>

                                    <template x-for="page in lastPage" :key="page">
                                        <button
                                            @click="goToPage(page)"
                                            :class="page === currentPage
                                                ? 'bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 border-transparent'
                                                : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                            class="px-3 py-1 text-sm border rounded-md"
                                            x-text="page"
                                        ></button>
                                    </template>

                                    <button
                                        @click="goToPage(currentPage + 1)"
                                        :disabled="currentPage === lastPage"
                                        class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >{{ __('Next') }} &raquo;</button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div
            x-show="showFormModal"
            x-transition.opacity
            class="fixed inset-0 z-50 overflow-y-auto"
            x-cloak
        >
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75" @click="closeFormModal()"></div>

                <div
                    x-show="showFormModal"
                    x-transition
                    class="relative w-full max-w-2xl bg-white dark:bg-gray-800 rounded-md shadow-sm border border-gray-200 dark:border-gray-700"
                >
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="isEditing ? '{{ __('Edit Order') }}' : '{{ __('New Order') }}'"></h3>
                    </div>

                    <form @submit.prevent="save()">
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label value="{{ __('Customer') }}" />
                                    <select x-model="form.customerId" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                        <option value="">{{ __('Select customer...') }}</option>
                                        <template x-for="c in customersList" :key="c.id">
                                            <option :value="c.id" x-text="c.name"></option>
                                        </template>
                                    </select>
                                    <template x-if="errors.customerId">
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.customerId[0]"></p>
                                    </template>
                                </div>
                                <div>
                                    <x-input-label value="{{ __('Status') }}" />
                                    <select x-model="form.status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                        <option value="pending">{{ __('Pending') }}</option>
                                        <option value="processing">{{ __('Processing') }}</option>
                                        <option value="completed">{{ __('Completed') }}</option>
                                        <option value="cancelled">{{ __('Cancelled') }}</option>
                                    </select>
                                    <template x-if="errors.status">
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.status[0]"></p>
                                    </template>
                                </div>
                                <div>
                                    <x-input-label value="{{ __('Product Name') }}" />
                                    <input type="text" x-model="form.productName" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                    <template x-if="errors.productName">
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.productName[0]"></p>
                                    </template>
                                </div>
                                <div>
                                    <x-input-label value="{{ __('Quantity') }}" />
                                    <input type="number" min="1" x-model="form.quantity" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                    <template x-if="errors.quantity">
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.quantity[0]"></p>
                                    </template>
                                </div>
                                <div>
                                    <x-input-label value="{{ __('Total Amount') }}" />
                                    <input type="number" step="0.01" min="0" x-model="form.totalAmount" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                    <template x-if="errors.totalAmount">
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.totalAmount[0]"></p>
                                    </template>
                                </div>
                                <div>
                                    <x-input-label value="{{ __('Order Date') }}" />
                                    <input type="date" x-model="form.orderDate" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                    <template x-if="errors.orderDate">
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.orderDate[0]"></p>
                                    </template>
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label value="{{ __('Description') }}" />
                                    <textarea x-model="form.description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm"></textarea>
                                    <template x-if="errors.description">
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.description[0]"></p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                            <button
                                type="button"
                                @click="closeFormModal()"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            >{{ __('Cancel') }}</button>
                            <button
                                type="submit"
                                :disabled="loading"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                <svg x-show="loading" class="animate-spin -ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span x-text="isEditing ? '{{ __('Update') }}' : '{{ __('Create') }}'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
            <div
                x-show="showDeleteModal"
                x-transition.opacity
                class="fixed inset-0 z-50 overflow-y-auto"
                x-cloak
            >
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75" @click="showDeleteModal = false"></div>

                    <div
                        x-show="showDeleteModal"
                        x-transition
                        class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-md shadow-sm border border-gray-200 dark:border-gray-700 p-6"
                    >
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Delete Order') }}</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Are you sure you want to delete order') }} #<span class="font-medium text-gray-800 dark:text-gray-200" x-text="orderToDelete?.id"></span>{{ __('? This action cannot be undone.') }}
                        </p>

                        <div class="mt-6 flex justify-end gap-3">
                            <button
                                @click="showDeleteModal = false"
                                type="button"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            >{{ __('Cancel') }}</button>
                            <button
                                @click="confirmDelete()"
                                :disabled="loading"
                                type="button"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                <svg x-show="loading" class="animate-spin -ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        function orderManager() {
            return {
                orders: [],
                customersList: [],
                fetchingOrders: true,
                currentPage: 1,
                lastPage: 1,
                total: 0,
                perPage: 10,
                sortBy: 'orderDate',
                sortDirection: 'desc',
                filterStatus: '',
                filterCustomerId: '',
                showFormModal: false,
                showDeleteModal: false,
                isEditing: false,
                loading: false,
                orderToDelete: null,
                currentUserId: {{ auth()->id() }},
                isAdmin: {{ auth()->user()->isAdmin() ? 'true' : 'false' }},
                errors: {},
                form: {
                    id: null,
                    customerId: '',
                    status: 'pending',
                    productName: '',
                    quantity: 1,
                    totalAmount: '',
                    description: '',
                    orderDate: '',
                },

                async init() {
                    await this.fetchCustomersList();
                    await this.fetchOrders();
                },

                async fetchCustomersList() {
                    try {
                        const response = await axios.get('/api/customers', {
                            params: { perPage: 100, sortBy: 'name', sortDirection: 'asc' },
                        });
                        this.customersList = response.data.data;
                    } catch (error) {
                        toastr.error('Failed to load customers.');
                    }
                },

                async fetchOrders() {
                    this.fetchingOrders = true;

                    try {
                        const params = {
                            page: this.currentPage,
                            perPage: this.perPage,
                            sortBy: this.sortBy,
                            sortDirection: this.sortDirection,
                        };

                        if (this.filterStatus) {
                            params.status = this.filterStatus;
                        }
                        if (this.filterCustomerId) {
                            params.customerId = this.filterCustomerId;
                        }

                        const response = await axios.get('/api/orders', { params });
                        this.orders = response.data.data;
                        this.currentPage = response.data.meta.current_page;
                        this.lastPage = response.data.meta.last_page;
                        this.total = response.data.meta.total;
                    } catch (error) {
                        toastr.error('Failed to load orders.');
                    } finally {
                        this.fetchingOrders = false;
                    }
                },

                getCustomerName(customerId) {
                    const customer = this.customersList.find(c => c.id === customerId);
                    return customer ? customer.name : '—';
                },

                canEdit(order) {
                    return this.isAdmin || order.userId === this.currentUserId;
                },

                goToPage(page) {
                    if (page < 1 || page > this.lastPage) return;

                    this.currentPage = page;
                    this.fetchOrders();
                },

                changePerPage() {
                    this.perPage = parseInt(this.perPage);
                    this.currentPage = 1;
                    this.fetchOrders();
                },

                changeFilter() {
                    this.currentPage = 1;
                    this.fetchOrders();
                },

                sort(column) {
                    if (this.sortBy === column) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortBy = column;
                        this.sortDirection = 'asc';
                    }

                    this.currentPage = 1;
                    this.fetchOrders();
                },

                openCreateModal() {
                    this.isEditing = false;
                    this.resetForm();
                    this.showFormModal = true;
                },

                openEditModal(order) {
                    this.isEditing = true;
                    this.errors = {};
                    this.form = {
                        id: order.id,
                        customerId: order.customerId,
                        status: order.status,
                        productName: order.productName,
                        quantity: order.quantity,
                        totalAmount: order.totalAmount,
                        description: order.description || '',
                        orderDate: order.orderDate,
                    };
                    this.showFormModal = true;
                },

                openDeleteModal(order) {
                    this.orderToDelete = order;
                    this.showDeleteModal = true;
                },

                closeFormModal() {
                    this.showFormModal = false;
                    this.errors = {};
                },

                resetForm() {
                    this.form = {
                        id: null,
                        customerId: '',
                        status: 'pending',
                        productName: '',
                        quantity: 1,
                        totalAmount: '',
                        description: '',
                        orderDate: '',
                    };
                    this.errors = {};
                },

                async save() {
                    this.loading = true;
                    this.errors = {};

                    try {
                        const payload = {
                            customerId: parseInt(this.form.customerId),
                            status: this.form.status,
                            productName: this.form.productName,
                            quantity: parseInt(this.form.quantity),
                            totalAmount: this.form.totalAmount,
                            description: this.form.description || null,
                            orderDate: this.form.orderDate,
                        };

                        if (this.isEditing) {
                            await axios.put(`/api/orders/${this.form.id}`, payload);
                            toastr.success('Order updated successfully.');
                        } else {
                            await axios.post('/api/orders', payload);
                            toastr.success('Order created successfully.');
                        }

                        await this.fetchOrders();

                        this.closeFormModal();
                    } catch (error) {
                        if (error.response?.status === 422) {
                            this.errors = error.response.data.errors || {};
                        } else if (error.response?.status === 403) {
                            toastr.error('You are not authorized to perform this action.');
                        } else {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    } finally {
                        this.loading = false;
                    }
                },

                async confirmDelete() {
                    this.loading = true;

                    try {
                        await axios.delete(`/api/orders/${this.orderToDelete.id}`);
                        this.showDeleteModal = false;
                        this.orderToDelete = null;
                        toastr.success('Order deleted successfully.');
                        await this.fetchOrders();
                    } catch (error) {
                        if (error.response?.status === 403) {
                            toastr.error('You are not authorized to perform this action.');
                        } else {
                            toastr.error('Failed to delete order. Please try again.');
                        }
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
</x-app-layout>
