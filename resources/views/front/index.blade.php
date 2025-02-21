<x-landing-layout>

    <x-profilnav />

    <x-navbar />

    <x-limo />

    <x-oilprice />

    <section class="wrapper flex flex-col gap-4">
        <p
            class="text-base font-bold flex items-center gap-2 text-white bg-gradient-to-r from-[#FF0000] via-[#D32F2F] to-[#7B0000] p-3 rounded-lg shadow-md">
            <span class="relative">
                <i class="ri-notification-3-line text-xl"></i>
                <span class="block rounded-full size-2 bg-white absolute top-0 right-0 -translate-x-1/2"></span>
            </span>
            Riwayat Simpanan Minyak Jelantah
        </p>

        <div class="flex flex-col gap-4">
            @forelse ($latestDeposits as $deposit)
                <div
                    class="py-4 px-5 bg-white
                            rounded-lg shadow-md flex items-center gap-3 hover:shadow-lg">
                    <!-- Ikon -->
                    <div class="flex-shrink-0">
                        <i class="ri-contrast-drop-2-line text-3xl text-primary"></i>
                    </div>

                    <!-- Detail Setoran -->
                    <div class="flex-1 text-nowrap">
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $deposit->total_liter }} Liter
                        </p>
                        <p class="text-sm text-gray-900">
                            Rp {{ number_format($deposit->total_price, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Tanggal -->
                    <div class="flex-1 text-center text-nowrap">
                        <p class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($deposit->created_at)->format('d M Y') }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div
                        class="flex-shrink-0 px-3 py-1 rounded-full text-sm font-semibold text-nowrap shadow-sm bg-green-100 text-green-700">
                        Berhasil
                    </div>
                </div>
            @empty
                <div class="py-4 px-5 bg-white rounded-lg shadow-md flex justify-center items-center">
                    <p class="text-gray-500 text-sm font-semibold">
                        Belum ada simpanan
                    </p>
                </div>
            @endforelse
        </div>

    </section>

    <section class="wrapper">
        <div
            class="relative bg-gradient-to-b from-[#FF0000] via-[#D32F2F] to-[#7B0000] rounded-2xl shadow-lg text-white flex flex-col items-center gap-5 p-8 max-w-lg mx-auto">

            <h2 class="text-2xl font-semibold text-white/90 text-center">
                Bantu Lingkungan, Dapatkan Keuntungan!
            </h2>

            <p class=" text-sm font-light text-white/80 text-center">
                Kesempatan emas untuk berperan aktif menjaga lingkungan sekaligus meraih keuntungan finansial. Daur
                ulang minyak jelantah yang terbuang menjadi sumber pendapatan nyata. Proses pencairan yang mudah dan
                cepat membantu Anda menghasilkan keuntungan.
            </p>

            <a href="{{ route('redeem') }}"
                class="bg-white text-primary font-bold px-6 py-3 rounded-full shadow-md hover:bg-gray-200 transition">
                Proses Pencairan
            </a>
        </div>
    </section>

    <section class="wrapper flex flex-col gap-4 pb-40">
        <!-- Judul -->
        <p
            class="text-base font-bold flex items-center gap-2 text-white bg-gradient-to-r
                  from-[#7B0000] via-[#D32F2F] to-[#FF0000] p-3 rounded-lg shadow-md">
            <span class="relative">
                <i class="ri-notification-3-line text-xl"></i>
                <span class="block rounded-full size-2 bg-white absolute top-0 right-0 -translate-x-1/2"></span>
            </span>
            Riwayat Pencairan Minyak Jelantah
        </p>

        <!-- List Riwayat -->
        <div class="flex flex-col gap-4">
            @forelse ($latestRedeems as $redeem)
                <div
                    class="py-4 px-5 bg-white
                            rounded-lg shadow-md flex items-center gap-3 hover:shadow-lg">

                    <!-- Ikon -->
                    <div class="flex-shrink-0">
                        <i class="ri-hand-coin-line text-3xl text-primary"></i>
                    </div>

                    <div class="flex-1 text-nowrap">
                        <p class="text-lg font-semibold text-gray-900">
                            Rp {{ number_format($redeem->total_redeem_price, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-900">
                            {{ $redeem->booking_trx_id }}
                        </p>
                    </div>

                    <!-- Tanggal -->
                    <div class="flex-1 text-center text-nowrap">
                        <p class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($redeem->created_at)->format('d M Y') }}
                        </p>
                    </div>

                    <!-- Status -->
                    @php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            'approved' => 'bg-green-100 text-green-700',
                        ];
                        $statusText = [
                            'pending' => 'Proses',
                            'rejected' => 'Gagal',
                            'approved' => 'Berhasil',
                        ];
                    @endphp

                    <div
                        class="flex-shrink-0 px-3 py-1 rounded-full text-sm font-semibold text-nowrap {{ $statusClasses[$redeem->status] ?? 'bg-gray-100 text-gray-900' }}">
                        {{ $statusText[$redeem->status] ?? 'Tidak Diketahui' }}
                    </div>

                </div>
            @empty
                <div class="py-4 px-5 bg-white rounded-lg shadow-md flex justify-center items-center">
                    <p class="text-gray-500 text-sm font-semibold">Belum ada pencairan</p>
                </div>
            @endforelse
        </div>
    </section>



</x-landing-layout>
