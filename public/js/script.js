function redirectLanePage(lane) {
    if (lane) {
        window.location.href = `/champions/${lane}`;
    } else {
        window.location.href = `/champions`;
    }
}