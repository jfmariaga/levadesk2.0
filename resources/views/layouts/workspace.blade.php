@extends('layouts.app')

@section('body')

<div class="flex h-screen overflow-hidden">

    <x-ui.sidebar />

    <div class="flex flex-1 flex-col overflow-hidden">

        <x-ui.topbar />

        <main class="flex-1 overflow-y-auto p-6">

            @yield('content')

        </main>

    </div>

</div>

@endsection