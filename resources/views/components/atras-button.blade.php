<!--este es el boton para cancelar -->

<button {{ $attributes->merge(['type' => 'button','class' => 'btn btn-atras'])}}" >
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
  <path d="M5.921 11.9 1.353 8.62a.72.72 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z"/>
</svg>
<span class="slot-content">{{ $slot }}</span>
      
</button>

<style>
    .btn-atras {
            content: '';  
            top: 0;
            cursor: pointer;
            overflow: hidden;
            transition: color 0.5s;
            bottom: 0;
            background-color:#e12217;

            padding: 3px 10px;
            
        }
        .btn-atras svg {
           
        
            fill: white; /* Color inicial del SVG */
          
        }
        .btn-atras .slot-content {
      
            color:white;
        }
            /* Efecto al hacer clic */
    .btn-atras:active {
        transform: scale(0.95); /* Simula el presionar */
        background-color: #b51a13; /* Cambia ligeramente el color de fondo */
    }
</style>