<section>
    <header>
        <h2 class="text-2xl font-bold text-gray-900">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Use a strong password to keep your account secure.
        </p>
    </header>

    <form method="post"
          action="{{ route('password.update') }}"
          class="mt-6 space-y-6">

        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>

            <x-input-label
                for="update_password_current_password"
                :value="__('Current Password')"
            />

            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full rounded-xl"
                autocomplete="current-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2"
            />

        </div>

        {{-- New Password --}}
        <div>

            <x-input-label
                for="update_password_password"
                :value="__('New Password')"
            />

            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full rounded-xl"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password')"
                class="mt-2"
            />

        </div>

        {{-- Confirm Password --}}
        <div>

            <x-input-label
                for="update_password_password_confirmation"
                :value="__('Confirm Password')"
            />

            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full rounded-xl"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password_confirmation')"
                class="mt-2"
            />

        </div>

        {{-- Password Tips --}}
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">

            <h3 class="font-semibold text-blue-900 mb-2">
                Password Tips
            </h3>

            <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">

                <li>Use at least 8 characters</li>

                <li>Include uppercase and lowercase letters</li>

                <li>Add numbers and symbols</li>

                <li>Avoid common passwords</li>

            </ul>

        </div>

        {{-- Button --}}
        <div class="flex items-center gap-4">

            <x-primary-button>
                Update Password
            </x-primary-button>

            @if (session('status') === 'password-updated')

                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-700 font-semibold"
                >

                    Password updated successfully.

                </p>

            @endif

        </div>

    </form>
</section>