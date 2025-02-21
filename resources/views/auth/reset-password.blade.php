<x-landing-layout>

    <x-header title="Ganti Kata Sandi" />

    <section class="wrapper px-5 pb-40">
        <div
            class="relative bg-gradient-to-b from-[#FF0000] via-[#D32F2F] to-[#7B0000] rounded-2xl shadow-lg text-white flex flex-col items-center gap-5 p-8 max-w-lg mx-auto">
            <!-- Logo -->
            <div class="p-4 ">
                <img src="{{ asset('assets/image/logo123.png') }}" class="w-full h-full object-contain"
                    alt="Minyak Setoran">
            </div>

            <!-- Teks -->
            <h1 class="text-4xl font-extrabold text-center leading-tight">
                Bank Minyak Jelantah
            </h1>

            <h2 class="text-2xl font-semibold text-white/90 text-center">
                Kecamatan Limo
            </h2>

            <form method="POST" action="{{ route('password.store') }}"
                class="mx-auto max-w-[400px] w-full border border-white p-6 rounded-3xl mt-2">

                <div class="mb-4 text-sm text-white">
                    {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.') }}
                </div>

                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <x-input-label for="email" :value="__('Alamat Email')" />
                    <x-text-input id="email" class="block mt-1 w-full text-primary" type="email" name="email"
                        :value="old('email', $request->email)" required autofocus autocomplete="email" placeholder="Masukkan alamat email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Kata Sandi')" />
                    <x-text-input id="password" class="block mt-1 w-full text-primary" type="password" name="password"
                        required autocomplete="new-password" placeholder="Masukkan kata sandi" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full text-primary" type="password"
                        name="password_confirmation" required autocomplete="new-password"
                        placeholder="Ulangi kata sandi" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-center mt-4">
                    <button type="submit"
                        class="mt-4 inline-flex text-primary font-bold text-base bg-white rounded-full whitespace-nowrap px-4 py-2 justify-center items-center">
                        {{ __('Konfirmasi') }}
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-landing-layout>
