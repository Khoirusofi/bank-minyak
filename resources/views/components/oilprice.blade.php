<section class="wrapper">
    <div
        class="flex justify-between items-center gap-5 bg-gradient-to-r
                from-[#7B0000] via-[#D32F2F] to-[#FF0000]
                p-6 shadow-lg rounded-3xl text-white relative transition-all duration-300 hover:shadow-xl">

        <!-- Info Harga Minyak Jelantah -->
        <div class="flex flex-col">
            <p class="text-sm font-medium">Harga Minyak Jelantah</p>
            <p class="text-2xl font-extrabold">
                Rp {{ number_format($OilPrice->price ?? 0, 0, ',', '.') }}
                <span class="text-lg font-semibold">/ Liter</span>
            </p>
        </div>

        <!-- Ikon Minyak -->
        <i class="ri-oil-line text-6xl opacity-90"></i>
    </div>
</section>
