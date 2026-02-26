<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customers') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="customerManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->isAdmin())
                <div class="mb-4 flex justify-end">
                    <button
                        @click="openCreateModal()"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('Add Customer') }}
                    </button>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <template x-if="fetchingCustomers">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Loading...') }}</p>
                    </div>
                </template>

                <template x-if="!fetchingCustomers && customers.length === 0">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No customers found.') }}</p>
                    </div>
                </template>

                <template x-if="!fetchingCustomers && customers.length > 0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    @foreach([
                                        'name' => __('Name'),
                                        'email' => __('Email'),
                                        'phone' => __('Phone'),
                                        'companyName' => __('Company'),
                                        'city' => __('City'),
                                        'country' => __('Country'),
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
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-for="customer in customers" :key="customer.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100" x-text="customer.name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="customer.email"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="customer.phone || '—'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="customer.companyName || '—'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="customer.city"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="customer.country"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                            <a
                                                :href="`/customers/${customer.id}`"
                                                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium"
                                            >{{ __('View') }}</a>
                                            @if(auth()->user()->isAdmin())
                                                <button
                                                    @click="openEditModal(customer)"
                                                    class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium"
                                                >{{ __('Edit') }}</button>
                                                <button
                                                    @click="openDeleteModal(customer)"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium"
                                                >{{ __('Delete') }}</button>
                                            @endif
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
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
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="isEditing ? '{{ __('Edit Customer') }}' : '{{ __('New Customer') }}'"></h3>
                        </div>

                        <form @submit.prevent="save()">
                            <div class="px-6 py-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label value="{{ __('Name') }}" />
                                        <input type="text" x-model="form.name" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                        <template x-if="errors.name">
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.name[0]"></p>
                                        </template>
                                    </div>
                                    <div>
                                        <x-input-label value="{{ __('Email') }}" />
                                        <input type="email" x-model="form.email" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                        <template x-if="errors.email">
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.email[0]"></p>
                                        </template>
                                    </div>
                                    <div>
                                        <x-input-label value="{{ __('Phone') }}" />
                                        <input type="text" x-model="form.phone" maxlength="10" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                        <template x-if="errors.phone">
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.phone[0]"></p>
                                        </template>
                                    </div>
                                    <div>
                                        <x-input-label value="{{ __('Company Name') }}" />
                                        <input type="text" x-model="form.companyName" maxlength="50" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                        <template x-if="errors.companyName">
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.companyName[0]"></p>
                                        </template>
                                    </div>
                                    <div>
                                        <x-input-label value="{{ __('Address Line 1') }}" />
                                        <input type="text" x-model="form.addressLine1" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                        <template x-if="errors.addressLine1">
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.addressLine1[0]"></p>
                                        </template>
                                    </div>
                                    <div>
                                        <x-input-label value="{{ __('Address Line 2') }}" />
                                        <input type="text" x-model="form.addressLine2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                        <template x-if="errors.addressLine2">
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.addressLine2[0]"></p>
                                        </template>
                                    </div>
                                    <div>
                                        <x-input-label value="{{ __('City') }}" />
                                        <input type="text" x-model="form.city" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                        <template x-if="errors.city">
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.city[0]"></p>
                                        </template>
                                    </div>
                                    <div>
                                        <x-input-label value="{{ __('Postal Code') }}" />
                                        <input type="text" x-model="form.postalCode" maxlength="10" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                        <template x-if="errors.postalCode">
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.postalCode[0]"></p>
                                        </template>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <x-input-label value="{{ __('Country') }}" />
                                        <input type="text" x-model="form.country" maxlength="100" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                        <template x-if="errors.country">
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.country[0]"></p>
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

            {{-- Delete Confirmation Modal --}}
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
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Delete Customer') }}</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Are you sure you want to delete') }} <span class="font-medium text-gray-800 dark:text-gray-200" x-text="customerToDelete?.name"></span>{{ __('? This action cannot be undone.') }}
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
        function customerManager() {
            return {
                customers: [],
                fetchingCustomers: true,
                sortBy: 'name',
                sortDirection: 'asc',
                showFormModal: false,
                showDeleteModal: false,
                isEditing: false,
                loading: false,
                customerToDelete: null,
                errors: {},
                form: {
                    id: null,
                    name: '',
                    email: '',
                    phone: '',
                    companyName: '',
                    addressLine1: '',
                    addressLine2: '',
                    city: '',
                    postalCode: '',
                    country: '',
                },

                async init() {
                    await this.fetchCustomers();
                },

                async fetchCustomers() {
                    this.fetchingCustomers = true;

                    try {
                        const response = await axios.get('/api/customers', {
                            params: {
                                sortBy: this.sortBy,
                                sortDirection: this.sortDirection,
                            },
                        });
                        this.customers = response.data.data;
                    } catch (error) {
                        toastr.error('Failed to load customers.');
                    } finally {
                        this.fetchingCustomers = false;
                    }
                },

                sort(column) {
                    if (this.sortBy === column) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortBy = column;
                        this.sortDirection = 'asc';
                    }

                    this.fetchCustomers();
                },

                openCreateModal() {
                    this.isEditing = false;
                    this.resetForm();
                    this.showFormModal = true;
                },

                openEditModal(customer) {
                    this.isEditing = true;
                    this.errors = {};
                    this.form = {
                        id: customer.id,
                        name: customer.name,
                        email: customer.email,
                        phone: customer.phone || '',
                        companyName: customer.companyName || '',
                        addressLine1: customer.addressLine1,
                        addressLine2: customer.addressLine2 || '',
                        city: customer.city,
                        postalCode: customer.postalCode,
                        country: customer.country,
                    };
                    this.showFormModal = true;
                },

                openDeleteModal(customer) {
                    this.customerToDelete = customer;
                    this.showDeleteModal = true;
                },

                closeFormModal() {
                    this.showFormModal = false;
                    this.errors = {};
                },

                resetForm() {
                    this.form = {
                        id: null,
                        name: '',
                        email: '',
                        phone: '',
                        companyName: '',
                        addressLine1: '',
                        addressLine2: '',
                        city: '',
                        postalCode: '',
                        country: '',
                    };
                    this.errors = {};
                },

                async save() {
                    this.loading = true;
                    this.errors = {};

                    try {
                        const payload = {
                            name: this.form.name,
                            email: this.form.email,
                            phone: this.form.phone || null,
                            companyName: this.form.companyName || null,
                            addressLine1: this.form.addressLine1,
                            addressLine2: this.form.addressLine2 || null,
                            city: this.form.city,
                            postalCode: this.form.postalCode,
                            country: this.form.country,
                        };

                        if (this.isEditing) {
                            await axios.put(`/api/customers/${this.form.id}`, payload);
                            toastr.success('Customer updated successfully.');
                        } else {
                            await axios.post('/api/customers', payload);
                            toastr.success('Customer created successfully.');
                        }

                        await this.fetchCustomers();

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
                        await axios.delete(`/api/customers/${this.customerToDelete.id}`);
                        this.showDeleteModal = false;
                        this.customerToDelete = null;
                        toastr.success('Customer deleted successfully.');
                        await this.fetchCustomers();
                    } catch (error) {
                        if (error.response?.status === 403) {
                            toastr.error('You are not authorized to perform this action.');
                        } else {
                            toastr.error('Failed to delete customer. Please try again.');
                        }
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
</x-app-layout>
