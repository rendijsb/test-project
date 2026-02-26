<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="userManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <input
                            type="text"
                            x-model="search"
                            @input.debounce.400ms="changeSearch()"
                            placeholder="{{ __('Search by name or email...') }}"
                            class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-64"
                        >
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
                    {{ __('Add User') }}
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <template x-if="fetchingUsers">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Loading...') }}</p>
                    </div>
                </template>

                <template x-if="!fetchingUsers && users.length === 0">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No users found.') }}</p>
                    </div>
                </template>

                <template x-if="!fetchingUsers && users.length > 0">
                    <div>
                        <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    @foreach([
                                        'name' => __('Name'),
                                        'email' => __('Email'),
                                        'role' => __('Role'),
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
                                <template x-for="user in users" :key="user.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100" x-text="user.name"></td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400" x-text="user.email"></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                :class="{
                                                    'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200': user.role === 'admin',
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200': user.role === 'user',
                                                }"
                                                x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)"
                                            ></span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm space-x-2">
                                            <a
                                                :href="`/users/${user.id}`"
                                                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium"
                                            >{{ __('View') }}</a>
                                            <button
                                                @click="openEditModal(user)"
                                                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium"
                                            >{{ __('Edit') }}</button>
                                            <button
                                                @click="openDeleteModal(user)"
                                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium"
                                            >{{ __('Delete') }}</button>
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
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="isEditing ? '{{ __('Edit User') }}' : '{{ __('New User') }}'"></h3>
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
                                    <x-input-label value="{{ __('Password') }}" />
                                    <input type="password" x-model="form.password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" :required="!isEditing">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="isEditing">{{ __('Leave blank to keep current password') }}</p>
                                    <template x-if="errors.password">
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.password[0]"></p>
                                    </template>
                                </div>
                                <div>
                                    <x-input-label value="{{ __('Role') }}" />
                                    <select x-model="form.role" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm" required>
                                        <option value="user">{{ __('User') }}</option>
                                        <option value="admin">{{ __('Admin') }}</option>
                                    </select>
                                    <template x-if="errors.role">
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-text="errors.role[0]"></p>
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
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Delete User') }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Are you sure you want to delete') }} <span class="font-medium text-gray-800 dark:text-gray-200" x-text="userToDelete?.name"></span>{{ __('? This action cannot be undone.') }}
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
    </div>

    <script>
        function userManager() {
            return {
                users: [],
                fetchingUsers: true,
                currentPage: 1,
                lastPage: 1,
                total: 0,
                perPage: 10,
                sortBy: 'name',
                sortDirection: 'asc',
                search: '',
                showFormModal: false,
                showDeleteModal: false,
                isEditing: false,
                loading: false,
                userToDelete: null,
                errors: {},
                form: {
                    id: null,
                    name: '',
                    email: '',
                    password: '',
                    role: 'user',
                },

                async init() {
                    await this.fetchUsers();
                },

                async fetchUsers() {
                    this.fetchingUsers = true;

                    try {
                        const params = {
                            page: this.currentPage,
                            perPage: this.perPage,
                            sortBy: this.sortBy,
                            sortDirection: this.sortDirection,
                        };

                        if (this.search) {
                            params.search = this.search;
                        }

                        const response = await axios.get('/api/users', { params });
                        this.users = response.data.data;
                        this.currentPage = response.data.meta.current_page;
                        this.lastPage = response.data.meta.last_page;
                        this.total = response.data.meta.total;
                    } catch (error) {
                        toastr.error('Failed to load users.');
                    } finally {
                        this.fetchingUsers = false;
                    }
                },

                goToPage(page) {
                    if (page < 1 || page > this.lastPage) return;

                    this.currentPage = page;
                    this.fetchUsers();
                },

                changePerPage() {
                    this.perPage = parseInt(this.perPage);
                    this.currentPage = 1;
                    this.fetchUsers();
                },

                changeSearch() {
                    this.currentPage = 1;
                    this.fetchUsers();
                },

                sort(column) {
                    if (this.sortBy === column) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortBy = column;
                        this.sortDirection = 'asc';
                    }

                    this.currentPage = 1;
                    this.fetchUsers();
                },

                openCreateModal() {
                    this.isEditing = false;
                    this.resetForm();
                    this.showFormModal = true;
                },

                openEditModal(user) {
                    this.isEditing = true;
                    this.errors = {};
                    this.form = {
                        id: user.id,
                        name: user.name,
                        email: user.email,
                        password: '',
                        role: user.role,
                    };
                    this.showFormModal = true;
                },

                openDeleteModal(user) {
                    this.userToDelete = user;
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
                        password: '',
                        role: 'user',
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
                            role: this.form.role,
                        };

                        if (this.form.password) {
                            payload.password = this.form.password;
                        }

                        if (this.isEditing) {
                            await axios.put(`/api/users/${this.form.id}`, payload);
                            toastr.success('User updated successfully.');
                        } else {
                            payload.password = this.form.password;
                            await axios.post('/api/users', payload);
                            toastr.success('User created successfully.');
                        }

                        await this.fetchUsers();

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
                        await axios.delete(`/api/users/${this.userToDelete.id}`);
                        this.showDeleteModal = false;
                        this.userToDelete = null;
                        toastr.success('User deleted successfully.');
                        await this.fetchUsers();
                    } catch (error) {
                        if (error.response?.status === 403) {
                            toastr.error('You are not authorized to perform this action.');
                        } else {
                            toastr.error('Failed to delete user. Please try again.');
                        }
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
</x-app-layout>
