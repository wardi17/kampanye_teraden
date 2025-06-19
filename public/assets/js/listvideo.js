document.addEventListener("DOMContentLoaded", () => {
    const mainVideo = document.getElementById("main-video");
    const videoItems = document.querySelectorAll(".video-item");

    videoItems.forEach(item => {
        item.addEventListener("click", () => {
            mainVideo.src = item.getAttribute("data-src");
            mainVideo.play();
        });
    });
});
