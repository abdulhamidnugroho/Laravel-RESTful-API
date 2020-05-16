{{-- Hello {{ $user->name }}


Thank you for creating account. Please verify your email using this link:

{{ route('verify', $user->verification_token) }}

Hello {{ $user->name }}


Thank you for creating account. Please verify your email using this button:

{{ route('verify', $user->verification_token) }} --}}

{{-- @component('mail::message')
# Hello {{ $user->name }}

You changed your email. Please use link below:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent --}}
