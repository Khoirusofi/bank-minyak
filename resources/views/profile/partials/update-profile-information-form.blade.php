<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Akun') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui informasi akun Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full " :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Nomor Telepon -->
        <div>
            <x-input-label for="number" :value="__('Nomor Telepon')" />
            <x-text-input id="number" name="number" type="number" class="mt-1 block w-full " :value="old('number', $user->number)"
                required autofocus autocomplete="number" />
            <x-input-error class="mt-2" :messages="$errors->get('number')" />
        </div>

        <!-- NIK -->
        <div>
            <x-input-label for="nik" :value="__('NIK')" />
            <x-text-input id="nik" name="nik" type="number" class="mt-1 block w-full" :value="old('nik', $user->nik ?? '')"
                required autofocus autocomplete="nik" />
            <x-input-error class="mt-2" :messages="$errors->get('nik')" />
        </div>

        <!-- Alamat -->
        <div>
            <x-input-label for="address" :value="__('Alamat')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full " :value="old('address', $user->address)"
                required autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>



        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <header id="bankInfo">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Informasi Rekening') }}
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                Untuk proses pencairan minyak jelantah melalui metode <strong class="text-primary">transfer</strong>,
                harap isi <strong class="text-primary">Nama Bank </strong>
                <strong class="text-primary"> Nomor Rekening</strong> dengan benar.<br>
            <div class="mt-2 border-l-4 border-gray-400 pl-4">
                <p class=" italic text-gray-400 text-xs">
                    Jika Anda tidak ingin menggunakan metode pencairan transfer, kolom dapat dikosongkan.
                </p>
            </div>
            </p>
        </header>

        <!-- Nama Bank -->
        <div>
            <x-input-label for="bank_name" :value="__('Nama Bank')" />
            <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full " :value="old('bank_name', $user->bank_name)"
                autofocus autocomplete="bank_name" placeholder="Contoh BCA a/n Khoirusofi" />
            <x-input-error class="mt-2" :messages="$errors->get('bank_name')" />
        </div>

        <!-- No Rekening -->
        <div>
            <x-input-label for="bank_number" :value="__('No Rekening')" />
            <x-text-input id="bank_number" name="bank_number" type="number" class="mt-1 block w-full "
                :value="old('bank_number', $user->bank_number)" autofocus autocomplete="bank_number" placeholder="Masukan No Rekening" />
            <x-input-error class="mt-2" :messages="$errors->get('bank_number')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
