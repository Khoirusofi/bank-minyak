<section class="wrapper">
    <div
        class="flex justify-between items-center gap-5 bg-gradient-to-r from-[#FF0000] via-[#D32F2F] to-[#7B0000]
                p-3 shadow-lg rounded-2xl text-white relative transition-all duration-300 hover:shadow-xl">
        <div x-data="{ open: false }" class="relative flex items-center gap-3">
            @auth
                <div @click="open = !open" class="flex justify-center items-center cursor-pointer hover:font-bold transition">
                    <i class="ri-user-line text-2xl text-white transition"></i>
                </div>

                <div>
                    <p class="text-base font-semibold uppercase text-white">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-sm text-white">Nasabah</p>
                </div>

                <div x-show="open" @click.away="open = false"
                    class="absolute top-full left-2 mt-2 w-48 bg-white rounded-lg shadow-md p-2 z-50 transition-all duration-300">
                    <x-responsive-nav-link :href="route('profile.edit')"
                        class="flex items-center gap-2 text-gray-900 hover:bg-red-200 rounded-md p-2">
                        <i class="ri-user-line text-lg"></i> {{ __('Akun') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="flex items-center gap-2 text-gray-900 hover:bg-red-200 rounded-md p-2">
                            <i class="ri-logout-box-r-line text-lg"></i> {{ __('Keluar') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @endauth
        </div>

        <div class="flex items-center gap-[10px] relative">
            @auth
                <a href="https://wa.me/6281380311253?text=Halo,%20saya%20ingin%20mengkonfirmasi%20pencairan.%20Silakan%20salin%20dan%20tempel%20ID%20pencairan%20Anda%20di%20sini:%20[ID_PENCAIRAN]"
                    target="_blank" class="relative px-2 py-1 transition">
                    <span class="relative">
                        <i class="ri-phone-line text-md text-white transition"></i>
                    </span>
                </a>

                <button type="button" class="px-2 py-1 transition relative">
                    <span class="relative">
                        <i class="ri-notification-3-line text-md fons text-white transition"></i>

                        @if (session('success') || session('error') || $errors->any())
                            <span class="block rounded-full size-2 bg-white absolute top-0 right-0 -translate-x-1/2"
                                id="notif-popup"></span>
                        @endif
                    </span>
                </button>

                @if (session('success') || session('error') || $errors->any())
                    <div id="notif-message"
                        class="absolute top-10 right-1 w-60 text-sm shadow-lg rounded-lg px-4 py-2 text-gray-900 border
                        {{ session('success') ? 'border-green-700 bg-green-50' : 'border-red-700 bg-red-50' }} opacity-0 transition-all duration-500 z-50">
                        <p class="font-medium {{ session('success') ? 'text-green-700' : 'text-red-700' }}">
                            {{ session('success') ?? (session('error') ?? $errors->first()) }}
                        </p>
                    </div>
                @endif
            @endauth

            @guest
                <a href="{{ route('login') }}"
                    class="rounded-full bg-white text-primary flex w-max gap-2.5 px-6 py-2 justify-center items-center text-base font-bold shadow-md hover:shadow-lg transition">
                    Masuk
                </a>

                <a href="{{ route('register') }}"
                    class="rounded-full bg-white text-primary flex w-max gap-2.5 px-6 py-2 justify-center items-center text-base font-bold shadow-md hover:shadow-lg transition">
                    Daftar
                </a>
            @endguest
        </div>
    </div>
</section>
