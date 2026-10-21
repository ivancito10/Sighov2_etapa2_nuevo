@props([
    'lat',
    'lng',
    'nombre',
    'zona',
    'direccion',
    'nro',
])

<div id="map" style="height: 400px; width: 100%;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let latitud = parseFloat(@json($lat));
        let longitud = parseFloat(@json($lng));
        let nombre_seguro = @json($nombre);
        let nombre_zona = @json($zona);
        let nombre_direccion = @json($direccion);
        let numero = @json($nro);

        async function initMap() {
            const { Map, Circle, InfoWindow } = await google.maps.importLibrary("maps");

            const location = { lat: latitud, lng: longitud };

            const map = new Map(document.getElementById("map"), {
                center: location,
                zoom: 19,
            });

            const circle = new Circle({
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
                fillOpacity: 0.35,
                map: map,
                center: location,
                radius: 25,
            });

            const marker = new google.maps.Marker({
                position: location,
                map: map,
                title: '¡Ubicacion Encontrada!',
            });

            const infoWindow = new InfoWindow({
                content: `<b style='color:green'> Aquí se encuentra la ubicación de ${nombre_seguro} </b> <br> Dirección: Zona ${nombre_zona} Dirección: ${nombre_direccion} ${numero}`,
                position: location,
            });

            setTimeout(() => {
                circle.setOptions({
                    strokeColor: "#0000FF",
                    fillColor: "#0000FF",
                    radius: 50,
                });
                infoWindow.open(map);

                document.getElementById('blinkingText').classList.add('blinking');
            }, 5000);
        }

        initMap();
    });
</script>
<style>
    .blinking {
        animation: blinkingText 1.5s infinite;
    }

    @keyframes blinkingText {
        0% { color: red; }
        50% { color: transparent; }
        100% { color: red; }
    }
</style>
