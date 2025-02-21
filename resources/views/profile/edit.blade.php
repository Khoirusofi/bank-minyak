<x-landing-layout>
    <x-navbar />

    <x-profilnav />

    <x-header title="Pengaturan Akun" />


    <div class="wrapper flex flex-col gap-2.5 p-4 pb-40">
        {{-- <p class="text-base font-bold">Pengaturan Profil</p> --}}
        <div class="gap-4 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div> --}}
        </div>

        <form method="POST" action="{{ route('logout') }}" class="flex items-center justify-center p-4">
            @csrf
            <x-primary-button type="submit">{{ __('Keluar') }}</x-primary-button>
        </form>
    </div>
</x-landing-layout>
