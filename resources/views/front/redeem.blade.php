<x-landing-layout>

    <x-profilnav />

    <x-header title="Pencairan Minyak Jelatah" />

    <form action="{{ route('redeem.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <section class="wrapper flex flex-col gap-2.5">
            <div class="flex items-center justify-between">
                <p class="text-base font-bold text-gray-900">
                    Jumlah Pencairan
                </p>
            </div>

            <div class="flex flex-col gap-4">
                <div
                    class="p-6 bg-gradient-to-r from-[#7B0000] via-[#D32F2F] to-[#FF0000]
                            rounded-3xl flex items-center gap-5 shadow-lg text-white
                            transition-all duration-300 hover:shadow-xl">

                    <!-- Ikon Pencairan -->
                    <i class="ri-wallet-line text-6xl opacity-90"></i>

                    <div class="flex flex-col w-full">
                        <h3 class="text-sm font-medium">Saldo Anda</h3>
                        <p class="text-2xl font-extrabold">
                            @if ($latestOilData)
                                Rp {{ number_format($latestOilData->total_saldo_price, 0, ',', '.') }}
                            @else
                                <span class="text-lg font-semibold">Rp 0</span>
                            @endif
                        </p>

                        <!-- Pilihan Nominal Pencairan -->
                        <div class="flex flex-col gap-1 mt-2">
                            <label for="redeemAmount" class="text-sm font-medium">Nominal Pencairan</label>
                            <x-select name="redeemAmount" id="redeemAmount">
                                @php
                                    $nominalOptions = [
                                        30000,
                                        40000,
                                        50000,
                                        100000,
                                        150000,
                                        200000,
                                        250000,
                                        300000,
                                        350000,
                                        400000,
                                        450000,
                                        500000,
                                        550000,
                                        600000,
                                        650000,
                                        700000,
                                        750000,
                                        800000,
                                        850000,
                                        900000,
                                        950000,
                                        1000000,
                                    ];
                                    $saldo = $latestOilData ? $latestOilData->total_saldo_price : 0;
                                @endphp
                                @foreach ($nominalOptions as $nominal)
                                    <option value="{{ $nominal }}" {{ $saldo < $nominal ? 'disabled' : '' }}>
                                        Rp {{ number_format($nominal, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="wrapper flex flex-col gap-2.5">
            <div class="flex items-center justify-between">
                <p class="text-base font-bold text-gray-900">Metode Pencairan</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Metode Tunai -->
                <label
                    class="relative rounded-2xl bg-white flex gap-2.5 px-3.5 py-3 items-center shadow-md cursor-pointer
                            hover:shadow-lg has-[:checked]:text-white has-[:checked]:bg-gradient-to-r from-[#7B0000] via-[#D32F2F] to-[#FF0000]">
                    <input type="radio" name="method" value="cash" id="cash" class="absolute opacity-0">
                    <i class="ri-cash-line font-semibold text-xl"></i>
                    <p class="text-base font-semibold has-[:checked]:text-white">Tunai</p>
                </label>

                <!-- Metode Transfer -->
                <label
                    class="relative rounded-2xl bg-white flex gap-2.5 px-3.5 py-3 items-center shadow-md cursor-pointer
                            hover:shadow-lg has-[:checked]:text-white has-[:checked]:bg-gradient-to-r from-[#FF0000] via-[#D32F2F] to-[#7B0000]">
                    <input type="radio" name="method" value="transfer" id="transfer" class="absolute opacity-0">
                    <i class="ri-bank-card-line font-semibold text-xl"></i>
                    <p class="text-base font-semibold has-[:checked]:text-white">Transfer</p>
                </label>
            </div>

            <!-- Informasi Transfer -->
            <div class="p-5 mt-0.5 bg-white text-gray-900 rounded-3xl shadow-lg hidden transition-all duration-300"
                id="transferDetails">
                <div class="flex flex-col gap-5">
                    <p class="text-base font-bold">Informasi Bank</p>
                    @isset($user)
                        @if (empty($user->bank_name) || empty($user->bank_number))
                            <div class="bg-white p-3 text-gray-900">
                                <p class="mb-2 italic text-sm text-red-500 border-l-4 border-red-500 pl-4">
                                    Sebelum melakukan pencairan menggunakan metode transfer.
                                </p>
                                <p class="text-sm font-semibold">
                                    Silahkan isi informasi bank di
                                    <a href="{{ route('profile.edit') }}" class="text-red-500 underline">profil.</a>
                                </p>
                            </div>
                        @endif

                        <div class="flex items-center text-gray-900 gap-1">
                            <i class="ri-bank-line text-lg"></i>
                            <p class="text-base font-semibold">
                                {{ $user->bank_name ?? 'Belum Input Nama Bank' }}
                            </p>
                        </div>

                        <div class="flex items-center text-gray-900 gap-1">
                            <i class="ri-secure-payment-line text-lg"></i>
                            <p class="text-base font-semibold">
                                {{ $user->bank_number ?? 'Belum Input No Rekening' }}
                            </p>
                        </div>
                    @endisset
                </div>
            </div>
        </section>


        <section class="wrapper flex flex-col gap-2.5 pb-40">
            <div class="flex items-center justify-between">
                <p class="text-base font-bold">
                    Details Pencairan
                </p>
                <button type="button" class="p-2 bg-white rounded-full" data-expand="detailsPayment">
                    <img src="{{ asset('assets/svgs/ic-chevron.svg') }}" class="transition-all duration-300 size-5"
                        alt="">
                </button>
            </div>
            <div class="p-6 bg-white rounded-3xl" id="detailsPayment" style="display: none;">
                <ul class="flex flex-col gap-5">
                    <li class="flex items-center justify-between">
                        <p class="text-base font-semibold first:font-normal">
                            Pencairan
                        </p>
                        <p class="text-base font-semibold first:font-normal text-primary" id="checkout-redeem-amount">
                            Rp 0
                        </p>
                    </li>
                    <li class="flex items-center justify-between">
                        <p class="text-base font-semibold first:font-normal">
                            Biaya Admin
                        </p>
                        <p class="text-base font-semibold first:font-normal" id="checkout-tax">
                            Rp 0
                        </p>
                    </li>
                </ul>
            </div>
        </section>

        <div
            class="fixed z-50 bottom-[30px] bg-gradient-to-r from-[#FF0000] via-[#D32F2F] to-[#7B0000] rounded-3xl p-3 left-1/2 -translate-x-1/2 w-[calc(100dvw-32px)] max-w-[395px]">
            <section class="flex items-center justify-between gap-5">
                <div class="ml-1">
                    <p class="text-sm text-white mb-0.5">
                        Total Pencairan
                    </p>
                    <p class="text-lg min-[350px]:text-2xl font-bold text-white" id="checkout-grand-total-price">
                        Rp 0
                    </p>
                </div>
                <button type="submit"
                    class="inline-flex items-center justify-center px-3 py-2 text-base font-semibold text-primary rounded-full w-max bg-white whitespace-nowrap">
                    Konfirmasi
                </button>
            </section>
        </div>
    </form>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const totalSaldo = {{ $latestOilData ? $latestOilData->total_saldo_price : 0 }};
            const taxCash = {{ $taxPrice && $taxPrice->taxCash ? $taxPrice->taxCash : 0 }};
            const taxTransfer = {{ $taxPrice && $taxPrice->taxTransfer ? $taxPrice->taxTransfer : 0 }};

            const redeemAmountSelect = document.getElementById('redeemAmount');
            const checkoutRedeemAmount = document.getElementById('checkout-redeem-amount');
            const checkoutTax = document.getElementById('checkout-tax');
            const checkoutGrandTotalPrice = document.getElementById('checkout-grand-total-price');
            const methodRadios = document.querySelectorAll('input[name="method"]');

            function updateDetails() {
                const selectedAmount = parseInt(redeemAmountSelect.value) || 0;

                // Cek metode yang dipilih
                const selectedMethod = document.querySelector('input[name="method"]:checked')?.value || 'cash';

                // Gunakan pajak sesuai metode yang dipilih
                const ppn = selectedMethod === 'cash' ? taxCash : taxTransfer;

                // Hitung total setelah pajak
                const grandTotal = selectedAmount - ppn;

                // Update tampilan
                checkoutRedeemAmount.textContent = `Rp ${selectedAmount.toLocaleString('id-ID')}`;
                checkoutTax.textContent = `Rp ${ppn.toLocaleString('id-ID')}`;
                checkoutGrandTotalPrice.textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;

                document.getElementById('detailsPayment').style.display = 'block';
            }

            // Sembunyikan opsi yang lebih besar dari saldo
            Array.from(redeemAmountSelect.options).forEach(option => {
                option.style.display = parseInt(option.value) > totalSaldo ? 'none' : 'block';
            });

            // Event listener untuk perubahan jumlah pencairan dan metode pembayaran
            redeemAmountSelect.addEventListener('change', updateDetails);
            methodRadios.forEach(radio => radio.addEventListener('change', updateDetails));

            // Jalankan pertama kali
            updateDetails();
        });
    </script>

</x-landing-layout>
