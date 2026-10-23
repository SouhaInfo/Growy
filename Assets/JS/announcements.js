document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const typeFilter = document.getElementById("typeFilter");
    const locationFilter = document.getElementById("locationFilter");

    function fetchAnnouncements() {
        const search = searchInput.value.trim();
        const type = typeFilter.value;
        const location = locationFilter.value;

        const formData = new FormData();
        formData.append("ajax", "true");
        formData.append("search", search);
        formData.append("type", type);
        formData.append("location", location);

        fetch("announcements.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                updateAnnouncements(data);
            })
            .catch((error) => console.error("Error fetching announcements:", error));
    }

    function updateAnnouncements(data) {
        const jobAnnouncementsGrid = document.querySelector("#jobAnnouncements .announcements-grid");
        const neededServicesGrid = document.querySelector("#neededServices .announcements-grid");

        jobAnnouncementsGrid.innerHTML = "";
        neededServicesGrid.innerHTML = "";

        data.jobAnnouncements.forEach((announcement) => {
            jobAnnouncementsGrid.innerHTML += generateCard(announcement);
        });

        data.neededServices.forEach((announcement) => {
            neededServicesGrid.innerHTML += generateCard(announcement);
        });
    }

    function generateCard(announcement) {
        return `
            <div class="announcement-card">
                <div class="announcement-img">
                    <img src="${announcement.annImage}" alt="Announcement Image">
                </div>
                <div class="announcement-details">
                    <h3>${announcement.annTitle}</h3>
                    <p>Type: ${announcement.annType}</p>
                    <p>Posted by: ${announcement.userName}</p>
                    <p>Posted on: ${announcement.annCreatedAt}</p>
                    <div class="explore">
                        <a href="../Components/announce1.php?annID=${announcement.annID}">
                            <button class="explore-btn">Explore</button>
                        </a>
                    </div>
                </div>
            </div>
        `;
    }

    searchInput.addEventListener("input", fetchAnnouncements);
    typeFilter.addEventListener("change", fetchAnnouncements);
    locationFilter.addEventListener("change", fetchAnnouncements);
});



/*
// Fade-In Animation for Announcement Cards
document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll('.announcement-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = 1;
            card.classList.add("fadeIn");
        }, index * 200); // Stagger animation for each card
    });
});
window.onscroll = function() {
    const backToTopButton = document.getElementById("backToTop");
    if (backToTopButton) {
        if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
            backToTopButton.style.display = "block";
        } else {
            backToTopButton.style.display = "none";
        }
    }
};

// Voice Search
const voiceSearchButton = document.getElementById("voiceSearchButton");
if (voiceSearchButton) {
    voiceSearchButton.addEventListener("click", () => {
        if ("SpeechRecognition" in window || "webkitSpeechRecognition" in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const recognition = new SpeechRecognition();
            recognition.onresult = (event) => {
                searchInput.value = event.results[0][0].transcript;
                filterAnnouncements();
            };
            recognition.start();
        } else {
            alert("Voice search is not supported on this browser.");
        }
    });
}

document.getElementById("backToTop").onclick = function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Loading Spinner
window.addEventListener("load", () => {
    const loadingSpinner = document.getElementById("loading-spinner");
    setTimeout(() => {
        loadingSpinner.style.display = "none";
    }, 2000); */