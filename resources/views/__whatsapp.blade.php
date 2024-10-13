<style>
    .whatsapp {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 40px;
        right: 40px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        z-index: 100;
        animation: pulse 1.5s infinite;
    }

    .whatsapp-icon {
        margin-top: 13px;
    }

    .notification {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: red;
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        z-index: 101;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
</style>

<a href="https://wa.me/+573145561727" class="whatsapp" target="_blank">
    <i class="fab fa-whatsapp whatsapp-icon" style="color:#FFF" aria-hidden="true"></i>
    <span class="notification">1</span>
</a> 