@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => route('web') . '/?' . $conversation->from_url])
{{ $subject }}
@endcomponent
@endslot
Hi {{ $user->name }}! {{ $message }}

@component('mail::button', ['url' => route('web') . '?' . $conversation->admin_id . '?' . $conversation->from_url])
GoTo Inbox
@endcomponent

Regards,
Soft Host It

@slot('footer')
@component('mail::footer')
@endcomponent
@endslot
@endcomponent
