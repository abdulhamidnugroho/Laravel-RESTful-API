Hello {{ $user->name }}


You changed your email. Please use link below:

{{ route('verify', $user->verification_token) }}
