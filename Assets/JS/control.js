const videoElement = document.querySelector('.bg-video');

// Play the video for 6 seconds, then loop
const stopPlaybackAt = 15; // Seconds
let intervalId;

function stopVideoAtSixSeconds() {
  if (videoElement.currentTime >= stopPlaybackAt) {
    videoElement.currentTime = 0; // Reset video to beginning
  }
}

intervalId = setInterval(stopVideoAtSixSeconds, 100); // Check every 100ms