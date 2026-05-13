<style>
.wa-float {
    position: fixed;
    bottom: 2.2rem; right: 2.2rem;
    z-index: 400;
    width: 50px; height: 50px;
    background: #25d366;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.3rem;
    text-decoration: none;
    box-shadow: 0 4px 24px rgba(37,211,102,.3);
    transition: transform .25s, box-shadow .25s;
    animation: wa-appear .6s 1.5s ease backwards;
}
.wa-float:hover {
    transform: scale(1.08);
    box-shadow: 0 6px 32px rgba(37,211,102,.5);
    color: #fff;
}
@keyframes wa-appear {
    from { opacity: 0; transform: scale(.6); }
    to   { opacity: 1; transform: scale(1); }
}
</style>
<a href="https://wa.me/+573145561727" class="wa-float" target="_blank" rel="noopener" aria-label="WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>