function updateTime(){
    const timeElement = document.getElementById('system-time');

    const currentTime = new Date();

    let hours = currentTime.getHours();
    let minutes = currentTime.getMinutes();
    let seconds = currentTime.getSeconds();

    timeElement.textContent = `Time: ${hours}:${minutes}:${seconds}`;

}

updateTime();

setInterval(updateTime, 1000);


