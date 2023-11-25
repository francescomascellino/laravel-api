<x-mail::message>

    # New Message from: {{ $lead->email }}

    Name: {{ $lead->name }}

    Phone number: {{ $lead->phone }}

    Message:

    {{ $lead->message }}

    Thanks,

    {{ config('app.name') }}

</x-mail::message>
