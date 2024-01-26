document.addEventListener('DOMContentLoaded', initWeather);

async function initWeather() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition((position) => {
            updateWeather(position.coords.latitude, position.coords.longitude)
        }, () => {
            updateWeather();
        })
    }
    else {
        await updateWeather();
    }
}

async function updateWeather (lat = 48.85, lon = 2.29) {
    let data = new FormData;
    data.set('lat', lat);
    data.set('lon', lon);
    let json = await fetch('/api/weatherPost', {
        method: "POST",
        body: data
    });

    let response = await json.json();
    let element = document.querySelector("#weather");
    element.innerHTML = response.current.temperature_2m + '°C';
}