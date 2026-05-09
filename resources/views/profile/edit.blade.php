<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Account Settings
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h1 class="text-3xl font-bold text-gray-900">
                    User Settings
                </h1>

                <p class="text-gray-600 mt-2">
                    Update your profile information, password, and account preferences.
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-8 border border-red-100">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>