@component('mail::message')
# Watchdog Alert

Hello {{ $watchdog->user->name }},

The price of **{{ $watchdog->currency->name }}** is now **${{ $currentPrice }}**, which has reached your target of **${{ $watchdog->target_price }}**.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
