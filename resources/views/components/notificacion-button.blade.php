@props([
    'nro' => 0,
    'nueva' => false, // Cambiado a booleano para manejar el estado
])

<button {{ $attributes->merge(['type' => 'button', 'class' => 'icon-button']) }}" data-toggle="tooltip" data-placement="top" title="NOTIFICACIÓN">
    <span class="material-icons">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
        </svg>
    </span>
    <span id="notification-badge" class="icon-button__badge {{ $nro > 0 ? 'vibrate' : '' }}">{{ $nro }}</span>
</button>

<style>
    .icon-button {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 40px;
        color: #333333;
        background: #dddddd;
        border: none;
        outline: none;
        border-radius: 50%;
    }

    .icon-button:hover {
        cursor: pointer;
    }

    .icon-button:active {
        background: #cccccc;
    }

    .icon-button__badge {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 25px;
        height: 25px;
        background: red;
        color: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        transition: transform 0.3s ease;
    }

    .icon-button__badge.vibrate {
        animation: shake 0.5s;
        animation-iteration-count: infinite; /* Vibrando Continuamente */
    }

    @keyframes shake {
        0% { transform: translate(0, 0); }
        25% { transform: translate(2px, 0); }
        50% { transform: translate(-2px, 0); }
        75% { transform: translate(2px, 0); }
        100% { transform: translate(0, 0); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const badge = document.getElementById('notification-badge');

        function startVibration() {
            badge.classList.add('vibrate');
        }

        function stopVibration() {
            badge.classList.remove('vibrate');
        }

        // Check if there are notifications
        if (badge.textContent.trim() > 0) {
            startVibration();
        } else {
            stopVibration();
        }

        // Optionally, if you have an event or condition to check for updates
        // setInterval(() => {
        //     if (badge.textContent.trim() > 0) {
        //         startVibration();
        //     } else {
        //         stopVibration();
        //     }
        // }, 3000);
    });
</script>
