<section class="wrapper">
    <div
        class="relative bg-gradient-to-b from-[#FF0000] via-[#D32F2F] to-[#7B0000] rounded-2xl shadow-lg text-white flex flex-col items-center gap-5 p-8 max-w-lg mx-auto">
        <!-- Logo -->
        <div class="p-4 ">
            <img src="{{ asset('assets/image/logo123.png') }}" class="w-full h-full object-contain" alt="Minyak Setoran">
        </div>

        <!-- Teks -->
        <h1 class="text-4xl font-extrabold text-center leading-tight">
            Bank Mijel
        </h1>

        <h2 class="text-2xl font-semibold text-white/90 text-center">
            PKH Kota Depok
        </h2>

        <p class="text-base font-medium text-white/80 text-center max-w-sm">
            Nabung ga mesti pake uang,<br>Minyak jelantah bisa menambah saldo tabungan anda.
        </p>

        <!-- Tombol -->
        <a href="{{ route('register') }}"
            class="bg-white text-primary font-bold px-6 py-3 rounded-full shadow-md hover:shadow-xl transition">
            Buka Simpanan
        </a>
    </div>
</section>
