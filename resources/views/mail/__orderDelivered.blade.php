@component('mail::message')
Dear {{$mailData['name']}},

{{$mailData['message']}}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent