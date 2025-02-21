<x-landing-layout>

    <x-profilnav />

    <x-navbar />

    <x-header title="Simpanan Minyak Jelatah" />

    <section class="wrapper">
        <div
            class="flex justify-between items-center gap-5 bg-gradient-to-r
                    from-[#7B0000] via-[#D32F2F] to-[#FF0000]
                    p-6 shadow-lg rounded-3xl text-white relative transition-all duration-300 hover:shadow-xl">

            <!-- Info Saldo -->
            <div class="flex flex-col">
                <p class="text-sm font-medium">Saldo Anda</p>

                <p class="text-2xl font-extrabold">
                    @auth
                        @if ($latestOilData)
                            Rp {{ number_format($latestOilData->total_saldo_price, 0, ',', '.') }}
                        @else
                            <span class="text-lg font-semibold">Rp 0</span>
                        @endif
                    @else
                        <span class="text-lg font-semibold">
                            Silakan
                            <a href="{{ route('login') }}" class="underline text-white font-bold">
                                Masuk
                            </a>
                            untuk melihat saldo Anda.
                        </span>
                    @endauth
                </p>
            </div>

            <!-- Ikon Dompet -->
            <i class="ri-wallet-line text-6xl opacity-90"></i>
        </div>
    </section>


    <section class="wrapper flex flex-col gap-2.5">
        <p class="text-base font-bold flex items-center gap-2">
            <span class="relative">
                <i class="ri-notification-3-line text-xl"></i>
                <span class="block rounded-full size-2 bg-primary absolute top-0 right-0 -translate-x-1/2"></span>
            </span>
            Riwayat Simpanan
        </p>

        <div class="bg-white shadow-md rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gradient-to-r from-[#FF0000] via-[#D32F2F] to-[#7B0000] text-white sticky top-0">
                        <tr>
                            <th class="py-3 px-4 text-sm font-semibold">No</th>
                            <th class="py-3 px-4 text-sm font-semibold">Tanggal</th>
                            <th class="py-3 px-4 text-sm font-semibold">Jumlah</th>
                            <th class="py-3 px-4 text-sm font-semibold">Harga</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse ($latestDeposits as $index => $deposit)
                            <tr class="border-t">
                                <td class="py-3 px-4 text-sm text-nowrap">{{ $latestDeposits->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4 text-sm text-nowrap">
                                    {{ \Carbon\Carbon::parse($deposit->created_at)->translatedFormat('d F Y') }}</td>
                                <td class="py-3 px-4 text-sm text-nowrap">{{ $deposit->total_liter }} Liter</td>
                                <td class="py-3 px-4 text-sm text-nowrap">Rp
                                    {{ number_format($deposit->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td class="py-3 px-4 text-sm" colspan="4">Belum ada setoran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $latestDeposits->links() }}
        </div>
    </section>

    <section class="wrapper flex flex-col gap-2.5 pb-40">
        <p class="text-base font-bold flex items-center gap-2">
            <span class="relative">
                <i class="ri-notification-3-line text-xl"></i>
                <span class="block rounded-full size-2 bg-primary absolute top-0 right-0 -translate-x-1/2"></span>
            </span>
            Riwayat Pencairan
        </p>

        <div class="bg-white shadow-md rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class=" bg-gradient-to-r from-[#7B0000] via-[#D32F2F] to-[#FF0000] text-white sticky top-0">
                        <tr>
                            <th class="py-3 px-4 text-sm font-semibold">No</th>
                            <th class="py-3 px-4 text-sm font-semibold">ID Pencairan</th>
                            <th class="py-3 px-4 text-sm font-semibold">Tanggal</th>
                            <th class="py-3 px-4 text-sm font-semibold">Harga</th>
                            <th class="py-3 px-4 text-sm font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse ($latestRedeems as $index => $redeem)
                            <tr class="border-t">
                                <td class="py-3 px-4 text-sm text-nowrap">{{ $latestRedeems->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4 text-sm text-nowrap">
                                    {{ $redeem->booking_trx_id }}
                                </td>
                                <td class="py-3 px-4 text-sm text-nowrap">
                                    {{ \Carbon\Carbon::parse($redeem->created_at)->translatedFormat('d F Y') }}
                                </td>
                                <td class="py-3 px-4 text-sm text-nowrap">Rp
                                    {{ number_format($redeem->total_redeem_price, 0, ',', '.') }}
                                </td>
                                @php
                                    $statusClasses = [
                                        'pending' => 'text-yellow-500',
                                        'rejected' => 'text-red-500',
                                        'approved' => 'text-green-500',
                                    ];
                                @endphp
                                <td
                                    class="py-3 px-4 text-sm font-semibold text-nowrap {{ $statusClasses[$redeem->status] ?? 'bg-gray-100 text-gray-900' }}">
                                    {{ $redeem->status === 'pending' ? 'Proses' : ($redeem->status === 'approved' ? 'Berhasil' : 'Gagal') }}
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td class="py-3 px-4 text-sm" colspan="4">Belum ada pencairan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $latestRedeems->links() }}
        </div>
    </section>

</x-landing-layout>
