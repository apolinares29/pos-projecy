@component('mail::message')
# Hello!

Your two-factor authentication code is:

<div style="text-align:center; margin: 20px 0;">
    <span style="font-size:2em; font-weight:bold; letter-spacing:2px;">{{ $code }}</span>
</div>

This code will expire in 10 minutes.

If you did not request this, please secure your account.

Regards,<br>
{{ config('app.name') }}
@endcomponent 