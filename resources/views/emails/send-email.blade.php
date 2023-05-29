@component('mail::layout')
@slot('header')
@component('mail::header', ['url' => route('web') . '/?'. $conversation->from_url])
{{ $user->name }} - Your live assistant is active now.
@endcomponent
@endslot
Hi {{ $user->name }}! We are active now, How can we help you?

@component('mail::button', ['url' => route('web') . '/?'. $conversation->from_url])
GoTo Inbox
@endcomponent

Regards,
Soft Host It

@slot('footer')
@component('mail::footer')
@endcomponent
@endslot
@endcomponent
