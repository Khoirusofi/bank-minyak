<x-landing-layout>

    <x-header title="Silahkan Masuk Ke Akun Anda" />

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

            <p class="text-base font-medium text-white/80 text-center">
                Bergabunglah untuk mewujudkan
                lingkungan bersih dan hijau, serta mendukung ekonomi Anda!
            </p>

            <form action="{{ route('login') }}" method="POST"
                class="mx-auto max-w-[400px] w-full border border-white p-6 rounded-3xl mt-2">

                @csrf
                <div class="flex flex-col gap-5">
                    <p class="text-[22px] font-bold">
                        Masuk
                    </p>

                    <div>
                        <x-input-label for="login" :value="__('NIK atau Email')" />
                        <x-text-input id="login" class="block mt-1 w-full text-primary" type="text"
                            name="login" :value="old('login')" required autofocus autocomplete="username"
                            placeholder="Masukkan NIK atau Email" />
                        <x-input-error :messages="$errors->get('login')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Kata Sandi')" />
                        <x-text-input id="password" class="block mt-1 w-full text-primary" type="password"
                            name="password" required autocomplete="current-password"
                            placeholder="Masukkan kata sandi" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="text-sm font-normal mr-4 underline" href="{{ route('password.request') }}">
                                {{ __('Lupa Kata Sandi?') }}
                            </a>
                        @endif

                        <button type="submit"
                            class="bg-white text-[#B71C1C] font-bold px-4 py-2 rounded-full shadow-md hover:bg-gray-200 transition">
                            Masuk
                        </button>

                    </div>
                </div>
            </form>
            <a href="{{ route('register') }}" class="font-semibold items-center text-center text-base mt-[30px]">
                Buat Akun Baru
            </a>
        </div>
    </section>

</x-landing-layout>
