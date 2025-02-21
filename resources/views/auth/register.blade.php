<x-landing-layout>

    <x-header title="Silahkan Buat Akun Anda" />

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

            <form action="{{ route('register') }}" method="post"
                class="mx-auto max-w-[400px] w-full border border-white p-6 rounded-3xl mt-2">
                @csrf
                <div class="flex flex-col gap-5">
                    <p class="text-[22px] font-bold">
                        Buat Akun Baru
                    </p>

                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" />
                        <x-text-input id="name" class="block mt-1 w-full text-primary" type="text"
                            name="name" :value="old('name')" required autofocus autocomplete="name"
                            placeholder="Masukkan nama lengkap" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="nik" :value="__('NIK')" />
                        <x-text-input id="nik" class="block mt-1 w-full text-primary" type="text"
                            name="nik" :value="old('nik')" required autofocus autocomplete="nik"
                            placeholder="Masukkan NIK" />
                        <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="number" :value="__('No Telepon')" />
                        <x-text-input id="number" class="block mt-1 w-full text-primary" type="text"
                            name="number" :value="old('number')" required autofocus autocomplete="number"
                            placeholder="Masukkan No Telepon" />
                        <x-input-error :messages="$errors->get('number')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="address" :value="__('Alamat Lengkap')" />
                        <x-text-input id="address" class="block mt-1 w-full text-primary" type="text"
                            name="address" :value="old('address')" required autofocus autocomplete="address"
                            placeholder="Masukkan alamat lengkap" />
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Alamat Email')" />
                        <x-text-input id="email" class="block mt-1 w-full text-primary" type="email"
                            name="email" :value="old('email')" required autofocus autocomplete="email"
                            placeholder="Masukkan alamat email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Kata Sandi')" />
                        <x-text-input id="password" class="block mt-1 w-full text-primary" type="password"
                            name="password" required autocomplete="new-password" placeholder="Masukkan kata sandi" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full text-primary" type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Ulangi kata sandi" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit"
                        class="mt-4 inline-flex text-primary font-bold text-base bg-white rounded-full whitespace-nowrap px-4 py-2 justify-center items-center shadow-md hover:bg-gray-200 transition">
                        Buat Akun
                    </button>
                </div>
            </form>
            <a href="{{ route('login') }}" class="font-semibold text-base mt-[30px]">
                Masuk ke Akun Saya
            </a>
        </div>
    </section>

</x-landing-layout>
