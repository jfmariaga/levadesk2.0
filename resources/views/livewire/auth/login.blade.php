<x-ui.auth-card>

    {{-- LOGO --}}
    <div class="mb-8 text-center">

        {{-- LOGO HORIZONTAL --}}
        <div class="mb-8 flex justify-center">

            <img src="{{ asset('images/branding/logo-horizontal.png') }}" class="h-32 w-auto">

        </div>

        {{-- SUBTITLE --}}
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Inicia sesión para continuar
        </p>

    </div>

    {{-- FORM --}}
    <form wire:submit="login" class="space-y-5">

        {{-- EMAIL --}}
        <x-ui.input wire:model="email" label="Correo corporativo" type="email" placeholder="correo@empresa.com" />

        {{-- PASSWORD --}}
        <x-ui.input wire:model="password" label="Contraseña" type="password" placeholder="••••••••" />

        {{-- REMEMBER --}}
        <label class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-300">

            <input type="checkbox" wire:model="remember"
                class="rounded border-slate-300 text-primary focus:ring-primary">

            Recordarme

        </label>

        {{-- ERROR --}}
        @error('email')
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">

                {{ $message }}

            </div>
        @enderror

        {{-- BUTTON --}}
        <button type="submit"
            class="w-full rounded-2xl bg-primary py-3 text-sm font-semibold text-white transition hover:bg-primary-dark">

            Ingresar

        </button>

    </form>

    {{-- LINKS --}}
    <div class="mt-6 flex justify-between text-sm">

        {{-- REGISTER --}}
        <a href="{{ route('register') }}" class="font-medium text-primary hover:underline">

            Crear cuenta

        </a>

        {{-- RECOVER --}}
        <a href="#" class="text-slate-500 hover:underline dark:text-slate-400">

            Recuperar contraseña

        </a>

    </div>

</x-ui.auth-card>
