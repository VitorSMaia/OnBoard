<x-layouts.auth>
    <livewire:auth.reset-password :token="$request->token" :email="$request->email"/>
</x-layouts.auth>