@component('mail::message')
# One Last Step

You're almost done, we just need to confirm you're a human.

@component('mail::button', ['url' => '#'])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
