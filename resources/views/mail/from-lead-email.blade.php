<x-mail::message>

    # New Message from: {{ $lead->email }}

    Name: {{ $lead->name }}

    Phone number: {{ $lead->phone }}

    Message:

    {{ $lead->message }}

    <x-mail::button :url="''">
        Button Text
    </x-mail::button>

    Thanks,<br>

    {{ config('app.name') }}

</x-mail::message>
