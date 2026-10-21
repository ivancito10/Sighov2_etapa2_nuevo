<!--este es el boton para cancelar -->

<button {{ $attributes->merge(['type' => 'button','class' => 'btn btn-siguienteDos'])}}" >

    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16">
    <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
    </svg>
    <span class="slot-content">{{ $slot }}</span>
 
</button>

<style>
    .btn-siguienteDos {
            content: '';  
            top: 0;
            cursor: pointer;
            overflow: hidden;
            transition: color 0.5s;
            bottom: 0;
            background-color:#10557c;

            padding: 3px 10px;
            
        }
        .btn-siguienteDos svg {
           
        
            fill: white; /* Color inicial del SVG */
          
        }
        .btn-siguienteDos .slot-content {
      
            color:white;
        }
            /* Efecto al hacer clic */
    .btn-siguienteDos:active {
        transform: scale(0.95); /* Simula el presionar */
        background-color: #10557c; /* Cambia ligeramente el color de fondo */
    }
</style>