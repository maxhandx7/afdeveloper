@if ($whatsapp = preg_replace('/\D/', '', (string) $business->setting('whatsapp', '573145561727')))
    <a class="wa" href="https://wa.me/{{ $whatsapp }}?text={{ rawurlencode('Hola Alan, vengo de afdeveloper.com') }}"
       target="_blank" rel="noopener" aria-label="Escribir por WhatsApp">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
    </a>
@endif
