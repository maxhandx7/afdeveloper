// AF Developer — interacciones del sitio público (sin dependencias).
(() => {
    // Navegación: borde al hacer scroll + menú móvil
    const nav = document.querySelector('[data-nav]');
    const toggle = document.querySelector('[data-nav-toggle]');
    const links = document.querySelector('[data-nav-links]');

    const onScroll = () => nav?.classList.toggle('is-scrolled', window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    toggle?.addEventListener('click', () => {
        const open = links.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', String(open));
    });
    links?.addEventListener('click', (e) => {
        if (e.target.closest('a')) {
            links.classList.remove('is-open');
            toggle?.setAttribute('aria-expanded', 'false');
        }
    });

    // Diagrama: al pasar o enfocar un nodo, se ilumina su conexión
    document.querySelectorAll('[data-diagram] [data-node]').forEach((node) => {
        const edge = document.querySelector(`[data-edge="${node.dataset.node}"]`);
        const on = () => edge?.classList.add('is-hot');
        const off = () => edge?.classList.remove('is-hot');
        ['mouseenter', 'focus'].forEach((ev) => node.addEventListener(ev, on));
        ['mouseleave', 'blur'].forEach((ev) => node.addEventListener(ev, off));
    });

    // Formulario de contacto con errores por campo
    const form = document.querySelector('[data-contact-form]');
    if (!form) return;

    const status = form.querySelector('[data-status]');
    const button = form.querySelector('button[type="submit"]');
    const label = button.textContent;

    const setStatus = (msg, ok) => {
        status.textContent = msg;
        status.className = 'form-status ' + (ok ? 'ok' : 'err');
    };
    const clearErrors = () => form.querySelectorAll('.field').forEach((f) => {
        f.classList.remove('has-error');
        const err = f.querySelector('.field-error');
        if (err) err.textContent = '';
        f.querySelector('input, textarea')?.removeAttribute('aria-invalid');
    });
    const showErrors = (errors) => Object.entries(errors).forEach(([name, messages]) => {
        const input = form.querySelector(`[name="${name}"]`);
        const field = input?.closest('.field');
        if (!field) return;
        field.classList.add('has-error');
        input.setAttribute('aria-invalid', 'true');
        field.querySelector('.field-error').textContent = messages[0];
    });

    form.querySelectorAll('input, textarea').forEach((el) => el.addEventListener('input', () => {
        const field = el.closest('.field');
        field?.classList.remove('has-error');
        el.removeAttribute('aria-invalid');
        const err = field?.querySelector('.field-error');
        if (err) err.textContent = '';
    }));

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();
        status.textContent = '';
        button.disabled = true;
        button.textContent = 'Enviando…';

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: new FormData(form),
            });
            const data = await res.json().catch(() => ({}));

            if (res.ok && data.success) {
                form.reset();
                setStatus('Mensaje enviado. Te respondo en menos de 24 horas hábiles.', true);
            } else if (res.status === 422) {
                showErrors(data.errors ?? {});
                setStatus('Revisa los campos marcados.', false);
                form.querySelector('[aria-invalid="true"]')?.focus();
            } else if (res.status === 429) {
                setStatus('Enviaste varios mensajes seguidos. Espera un minuto e intenta de nuevo.', false);
            } else if (res.status === 419) {
                setStatus('La página llevaba mucho tiempo abierta. Recárgala e intenta de nuevo.', false);
            } else {
                setStatus('No se pudo enviar. Escríbeme por WhatsApp o al correo de la izquierda.', false);
            }
        } catch {
            setStatus('Sin conexión. Revisa tu internet e intenta de nuevo.', false);
        } finally {
            button.disabled = false;
            button.textContent = label;
        }
    });
})();
