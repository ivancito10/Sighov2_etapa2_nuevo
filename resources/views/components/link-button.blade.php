 <style>
        .btn-custom-dark {
            position: relative;
            display: inline-block;
            color: #000000;
            border: 1px solid #000000;
            cursor: pointer;
            overflow: hidden;
            transition: color 0.5s, background-color 0.5s;
            background-color: white;
            padding: 10px 20px;
            text-decoration: none; /* Ensure links have no underline */
        }

        .btn-custom-dark::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 100%;
            width: 100%;
            background-color: #000000;
            z-index: 0;
            transition: left 0.5s;
        }

        .btn-custom-dark:hover {
            color: white;
            background-color: #000000;
        }

        .btn-custom-dark:hover::before {
            left: 0;
        }

        .btn-custom-dark svg {
            position: relative;
            z-index: 1;
            transition: fill 0.5s;
            fill: currentColor; /* Make sure SVG fills with current text color */
        }

        .btn-custom-dark:hover svg {
            fill: white;
        }

        .btn-custom-dark .slot-content {
            position: relative;
            z-index: 1;
            transition: color 0.5s;
        }

        .btn-custom-dark:hover .slot-content {
            color: white;
        }
    </style>
    
@props(['url'])
    @if (isset($url))
        <a href="{{ $url }}" target="_blank" {{ $attributes->merge(['class' => 'btn btn-custom-dark', 'data-toggle' => 'tooltip', 'title' => 'Enlace directo']) }}>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bank" viewBox="0 0 16 16">
                <path d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1zM2 6v7h1V6zm2 0v7h2.5V6zm3.5 0v7h1V6zm2 0v7H12V6zM13 6v7h1V6zm2-1V4H1v1zm-.39 9H1.39l-.25 1h13.72z" />
            </svg>
            <span class="slot-content">{{ $slot }}</span>
        </a>
    @else
        <button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-dark', 'data-toggle' => 'tooltip', 'title' => 'Enlace directo']) }}>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bank" viewBox="0 0 16 16">
                <path d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1zM2 6v7h1V6zm2 0v7h2.5V6zm3.5 0v7h1V6zm2 0v7H12V6zM13 6v7h1V6zm2-1V4H1v1zm-.39 9H1.39l-.25 1h13.72z" />
            </svg>
            <span class="slot-content">{{ $slot }}</span>
        </button>
    @endif
