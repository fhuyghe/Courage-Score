@php $email = get_field('email', 'option') @endphp

<div class="contact">
    <a href="mailto:{{ $email }}" target="_blank">{{ $email }}</a><br>
    {{ get_field('phone', 'option') }}
</div>