<script src="https://js.stripe.com/v3/"></script>
@php
    dump($checkout);
@endphp
{{ $checkout->button('Buy') }}
