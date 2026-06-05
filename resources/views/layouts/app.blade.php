<!DOCTYPE html>
<html lang="es" class="h-full scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LevaDesk 2.0</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="h-full bg-slate-100 font-sans antialiased dark:bg-slate-950">

    @yield('body')

    @livewireScripts

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (event) => {
                const Toast = Swal.mixin({

                    toast: true,

                    position: 'top-end',

                    showConfirmButton: false,

                    timer: 3000,

                    timerProgressBar: true,

                    didOpen: (toast) => {

                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;

                    }

                });

                Toast.fire({

                    icon: event.type,

                    title: event.message

                });
            });
        });
    </script>
</body>

</html>
