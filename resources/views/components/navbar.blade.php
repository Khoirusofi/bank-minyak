<nav
    class=" wrapper fixed z-50 bottom-[30px] bg-gradient-to-br from-[#FF0000] via-[#D32F2F] to-[#7B0000] rounded-[50px] pt-[18px] px-32 left-1/2 -translate-x-1/2 w-80 shadow-lg">
    <div class="flex items-center justify-center gap-5 flex-nowrap">
        <a href="{{ route('front.index') }}"
            class="flex flex-col items-center justify-center group gap-1 px-1 {{ Route::is('front.index') ? 'is-active' : '' }}">
            <!-- Menampilkan ikon garis (tidak aktif) jika tidak aktif -->
            @if (!Route::is('front.index'))
                <i class="ri-home-8-line text-xl text-white transition"></i>
            @else
                <!-- Menampilkan ikon terisi (aktif) jika aktif -->
                <i class="ri-home-8-fill text-xl text-white transition"></i>
            @endif

            <p
                class="border-b-4 border-transparent pb-3 text-xs text-center font-semibold text-white group-[.is-active]:font-bold">
                Home
            </p>
        </a>

        <a href="{{ route('deposit') }}"
            class="flex flex-col items-center justify-center group gap-1 px-1 {{ Route::is('deposit') ? 'is-active' : '' }}">
            @if (!Route::is('deposit'))
                <i class="ri-wallet-line text-xl text-white transition"></i>
            @else
                <i class="ri-wallet-fill text-xl text-white transition"></i>
            @endif
            <p
                class="border-b-4 border-transparent pb-3 text-xs text-center font-semibold text-white group-[.is-active]:font-bold">
                Simpanan
            </p>
        </a>

        <a href="{{ route('redeem') }}"
            class="flex flex-col items-center justify-center group gap-1 px-1 {{ Route::is('redeem') ? 'is-active' : '' }}">
            @if (!Route::is('redeem'))
                <i class="ri-shopping-cart-2-line text-xl text-white transition"></i>
            @else
                <i class="ri-shopping-cart-2-fill text-xl text-white transition"></i>
            @endif
            <p
                class="border-b-4 border-transparent pb-3 text-xs text-center font-semibold text-white group-[.is-active]:font-bold">
                Pencairan
            </p>
        </a>

        <a href="{{ route('profile.edit') }}"
            class="flex flex-col items-center justify-center group gap-1 px-1 {{ Route::is('profile.edit') ? 'is-active' : '' }}">
            @if (!Route::is('profile.edit'))
                <i class="ri-user-line text-xl text-white transition"></i>
            @else
                <i class="ri-user-fill text-xl text-white transition"></i>
            @endif
            <p
                class="border-b-4 border-transparent pb-3 text-xs text-center font-semibold text-white group-[.is-active]:font-bold">
                Akun
            </p>
        </a>

    </div>
</nav>
