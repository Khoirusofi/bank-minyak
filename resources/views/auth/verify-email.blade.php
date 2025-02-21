<x-landing-layout>

    <x-header title="Verifikasi Email" />

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

            <form method="POST" action="{{ route('verification.send') }}"
                class="mx-auto max-w-[400px] w-full border border-white p-6 rounded-3xl mt-2">

                <div class="mb-4 text-sm text-white">
                    {{ __('Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda? Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan ulang.') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 font-medium text-md text-green-600">
                        {{ __('Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
                    </div>
                @endif

                @csrf

                <div class="flex items-center justify-center mt-4">
                    <button type="submit"
                        class="mt-4 inline-flex text-primary font-bold text-base bg-white rounded-full whitespace-nowrap px-4 py-2 justify-center items-center">
                        {{ __('Kirim Verifikasi Email') }}
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit"
                    class="inline-flex text-white font-bold text-base bg-primary rounded-full whitespace-nowrap px-4 py-2 justify-center items-center">
                    {{ __('Keluar') }}
                </button>
            </form>
        </div>
    </section>
</x-landing-layout>
