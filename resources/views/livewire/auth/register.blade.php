<x-ui.auth-card>

    {{-- LOGO --}}
    <div class="mb-8 flex justify-center">

        <img src="{{ asset('images/branding/logo-horizontal.png') }}" class="h-32 w-auto">

    </div>
    <div class="mb-8 text-center">

        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Crea tu cuenta para comenzar
        </p>

    </div>

    {{-- FORM --}}
    <form wire:submit="register" class="space-y-5">

        <x-ui.input wire:model="nombres" label="Nombres" placeholder="Ingresa tus nombres" />

        <x-ui.input wire:model="apellidos" label="Apellidos" placeholder="Ingresa tus apellidos" />

        <x-ui.input wire:model="email" label="Correo electrónico" type="email" placeholder="correo@empresa.com" />

        <x-ui.input wire:model="password" label="Contraseña" type="password" placeholder="••••••••" />

        <x-ui.input wire:model="password_confirmation" label="Confirmar contraseña" type="password"
            placeholder="••••••••" />

        <x-ui.select wire:model="sociedad_id" label="Sociedad">

            <option value="">
                Selecciona una sociedad
            </option>

            @foreach ($sociedades as $sociedad)
                <option value="{{ $sociedad->id }}">
                    {{ $sociedad->nombre }}
                </option>
            @endforeach

        </x-ui.select>

        <x-ui.select wire:model="area_id" label="Área">

            <option value="">
                Selecciona tu área
            </option>

            @foreach ($areas as $area)
                <option value="{{ $area->id }}">
                    {{ $area->nombre }}
                </option>
            @endforeach

        </x-ui.select>

        <button type="submit"
            class="w-full rounded-2xl bg-primary py-3 text-sm font-semibold text-white transition hover:bg-primary-dark">

            Crear cuenta

        </button>

    </form>

    {{-- LOGIN LINK --}}
    <div class="mt-6 text-center">

        <a href="{{ route('login') }}" class="text-sm font-medium text-primary hover:underline">

            Ya tengo una cuenta

        </a>

    </div>

</x-ui.auth-card>
