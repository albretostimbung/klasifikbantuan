    <!-- Sidebar -->
<div class="fixed inset-y-0 left-0 lg:max-w-[295px] w-full overflow-y-auto bg-white shadow-sm z-[999] transform -translate-x-full lg:translate-x-0 transition-transform duration-300" id="sidebarHRIS">
    <div class="px-6 py-[50px] gap-y-[50px] flex flex-col">
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex justify-center items-center">
                <svg class="w-16 h-16 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19a5 5 0 1 0 0-10 5 5 0 0 0 0 10z"></path>
                </svg>
                <div class="text-dark text-[14px] font-bold mt-2">Klasifikasi Penerima Bantuan</div>
            </a>
            <a href="#" id="toggleCloseSidebar" class="block lg:hidden">
                <svg class="w-6 h-6 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </a>
        </div>
        <div class="flex flex-col gap-4">
            <div class="text-sm text-grey">Daily Use</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <img src="{{ asset('assets/svgs/ic-grid.svg') }}" alt="" />
                Dashboard
            </a>
            <a href="{{ route('atribut.index') }}" class="nav-link {{ request()->routeIs('atribut.*') ? 'active' : '' }}">
                <img src="{{ asset('assets/svgs/ic-settings.svg') }}" alt="" />
                Atribut & Klasifikasi
            </a>
            <a href="{{ route('data-masyarakat.index') }}" class="nav-link {{ request()->routeIs('data-masyarakat.*') ? 'active' : '' }}">
                <img src="{{ asset('assets/svgs/ic-users.svg') }}" alt="" />
                Data Masyarakat
            </a>
            <a href="{{ route('hasil-klasifikasi') }}" class="nav-link {{ request()->routeIs('hasil-klasifikasi') ? 'active' : '' }}">
                <img src="{{ asset('assets/svgs/ic-briefcase.svg') }}" alt="" />
                Hasil Klasifikasi
            </a>
            <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
                <img src="{{ asset('assets/svgs/ic-box.svg') }}" alt="" />
                Laporan & Export
            </a>
        </div>
        <div class="flex flex-col gap-4">
            <div class="text-sm text-grey">Others</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link cursor-pointer w-full">
                    <img src="{{ asset('assets/svgs/ic-signout.svg') }}" alt="" />
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .nav-link {
        @apply flex items-center gap-4 text-dark hover:text-primary transition-colors duration-200;
    }
    .nav-link.active {
        @apply text-primary font-semibold;
    }
</style>
