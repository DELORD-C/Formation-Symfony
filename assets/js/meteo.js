
document.addEventListener('DOMContentLoaded', function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(fetchMeteo);
    }
});

function fetchMeteo (position) {
    let latitude = position.coords.latitude;
    let longitude = position.coords.longitude;
    fetch("https://api.open-meteo.com/v1/forecast?latitude=" + latitude + "&longitude=" + longitude + "&current_weather=true")
        .then(function (response) {
            return response.json().then(function (meteo) {
                document.getElementById('meteo').innerHTML = meteo.current_weather.temperature + " °C <img src='https://openweathermap.org/img/wn/" + String(meteo.current_weather.weathercode).padStart(2, '0') + "d@2x.png'>";
            })
        });

    fetch("https://geocode.maps.co/reverse?lat=" + latitude + "&lon=" + longitude + "")
        .then(function (response) {
            return response.json().then(function (city) {
                document.getElementById('city').innerHTML = city.address.city;

            })
        })

}