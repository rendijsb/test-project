<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="userShow({{ $userId }})">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <template x-if="loading">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Loading...') }}</p>
                    </div>
                </div>
            </template>

            <template x-if="!loading && user">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" x-text="user.name"></h3>
                        <a href="{{ route('users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                            &larr; {{ __('Back to list') }}
                        </a>
                    </div>

                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="user.name"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100" x-text="user.email"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Role') }}</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200': user.role === 'admin',
                                            'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200': user.role === 'user',
                                        }"
                                        x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)"
                                    ></span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </template>

            <template x-if="!loading && !user">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('User not found.') }}</p>
                        <a href="{{ route('users.index') }}" class="mt-4 inline-block text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                            &larr; {{ __('Back to list') }}
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        function userShow(userId) {
            return {
                user: null,
                loading: true,

                async init() {
                    try {
                        const response = await axios.get(`/api/users/${userId}`);
                        this.user = response.data.data;
                    } catch (error) {
                        toastr.error('Failed to load user.');
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
</x-app-layout>
