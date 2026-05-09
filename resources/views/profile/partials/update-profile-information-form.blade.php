<section>
    <header>
        <h2 class="text-2xl font-bold text-gray-900">
            Profile Information
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Update your account details, email, and profile photo.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex items-center gap-5 bg-gray-50 border border-gray-200 rounded-2xl p-5">
            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                     alt="Profile Photo"
                     class="w-24 h-24 rounded-full object-cover border-4 border-white shadow">
            @else
                <div class="w-24 h-24 rounded-full bg-green-700 text-white flex items-center justify-center text-3xl font-bold border-4 border-white shadow">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif

            <div class="flex-1">
                <p class="font-bold text-gray-900">
                    {{ $user->name }}
                </p>

                <p class="text-sm text-gray-500">
                    {{ $user->email }}
                </p>

                <span class="inline-block mt-2 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                    {{ ucfirst($user->role ?? 'user') }}
                </span>
            </div>
        </div>

        <div>
            <x-input-label for="profile_photo" :value="__('Change Profile Photo')" />

            <input id="profile_photo"
                   name="profile_photo"
                   type="file"
                   accept="image/*"
                   class="mt-2 block w-full text-sm text-gray-700">

            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />

            <x-text-input id="name"
                          name="name"
                          type="text"
                          class="mt-1 block w-full rounded-xl"
                          :value="old('name', $user->name)"
                          required
                          autofocus
                          autocomplete="name" />

            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />

            <x-text-input id="email"
                          name="email"
                          type="email"
                          class="mt-1 block w-full rounded-xl"
                          :value="old('email', $user->email)"
                          required
                          autocomplete="username" />

            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Your email address is unverified.

                        <button form="send-verification"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md">
                            Click here to re-send the verification email.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>
                Save Changes
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-700 font-semibold"
                >
                    Saved.
                </p>
            @endif
        </div>
    </form>
</section>