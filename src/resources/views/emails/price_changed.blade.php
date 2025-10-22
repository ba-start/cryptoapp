<p>Hi {{ $user->email }},</p>
<p>The price of {{ $currency->name }} changed from {{ $oldPrice }} to {{ $newPrice }} (USD).</p>
<p><a href="{{ $currency->image_url }}">coin image</a></p>
