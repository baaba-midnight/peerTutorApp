const tutors = [
  {
    id: 1,
    name: "Dr. Sarah Johnson",
    profilePic: "/api/placeholder/150/150",
    averageRating: 4.8,
    specialty: "Mathematics",
    reviews: [
      {
        id: 1,
        student: "Michael P.",
        rating: 5,
        comment: "Excellent at explaining complex calculus concepts!",
        date: "2025-03-15",
      },
      {
        id: 2,
        student: "Emma L.",
        rating: 5,
        comment: "Dr. Johnson helped me improve my test scores significantly.",
        date: "2025-02-28",
      },
      {
        id: 3,
        student: "James K.",
        rating: 4,
        comment: "Very patient and knowledgeable.",
        date: "2025-02-10",
      },
      {
        id: 4,
        student: "Sophia W.",
        rating: 5,
        comment: "Best math tutor I've ever had!",
        date: "2025-01-22",
      },
    ],
  },
  {
    id: 2,
    name: "Prof. David Chen",
    profilePic: "/api/placeholder/150/150",
    averageRating: 4.6,
    specialty: "Physics",
    reviews: [
      {
        id: 1,
        student: "Alex T.",
        rating: 5,
        comment: "Made quantum physics actually understandable!",
        date: "2025-03-20",
      },
      {
        id: 2,
        student: "Olivia R.",
        rating: 4,
        comment: "Very thorough explanations and helpful practice problems.",
        date: "2025-03-05",
      },
      {
        id: 3,
        student: "Daniel M.",
        rating: 5,
        comment: "Helped me ace my AP Physics exam.",
        date: "2025-02-18",
      },
    ],
  },
  {
    id: 3,
    name: "Maria Garcia",
    profilePic: "/api/placeholder/150/150",
    averageRating: 4.9,
    specialty: "Chemistry",
    reviews: [
      {
        id: 1,
        student: "Ryan P.",
        rating: 5,
        comment: "Maria explains organic chemistry so clearly!",
        date: "2025-03-10",
      },
      {
        id: 2,
        student: "Isabella J.",
        rating: 5,
        comment: "Extremely knowledgeable and supportive.",
        date: "2025-02-25",
      },
      {
        id: 3,
        student: "Ethan L.",
        rating: 4,
        comment: "Great at providing real-world examples.",
        date: "2025-02-01",
      },
      {
        id: 4,
        student: "Ava M.",
        rating: 5,
        comment: "Helped me prepare perfectly for my college applications.",
        date: "2025-01-15",
      },
      {
        id: 5,
        student: "Noah C.",
        rating: 5,
        comment: "The best chemistry tutor I've ever had!",
        date: "2025-01-05",
      },
    ],
  },
  {
    id: 4,
    name: "Dr. Robert Williams",
    profilePic: "/api/placeholder/150/150",
    averageRating: 4.5,
    specialty: "Literature",
    reviews: [
      {
        id: 1,
        student: "Charlotte B.",
        rating: 4,
        comment: "Great insights on classical literature.",
        date: "2025-03-18",
      },
      {
        id: 2,
        student: "William T.",
        rating: 5,
        comment: "Helped me improve my essay writing tremendously.",
        date: "2025-03-01",
      },
      {
        id: 3,
        student: "Amelia S.",
        rating: 4,
        comment: "Very knowledgeable about literary history.",
        date: "2025-02-12",
      },
    ],
  },
];

// Function to populate tutor profile and reviews
function populateTutorReviews(tutorId) {
    const tutor = tutors.find(t => t.id === tutorId);
    if (!tutor) return;

    document.getElementById("tutorProfile").innerHTML = `
        <img src="${tutor.profilePic}" alt="${tutor.name}" class="tutor-photo-large">
        <h2>${tutor.name}</h2>
        <p>${tutor.specialty}</p>
        <p>⭐ ${tutor.averageRating}</p>
    `;

    const reviewsContainer = document.getElementById("reviewsContainer");
    reviewsContainer.innerHTML = "";

    tutor.reviews.forEach(review => {
        const reviewDiv = document.createElement("div");
        reviewDiv.classList.add("review");
        reviewDiv.innerHTML = `
            <div class="review-header">
                <strong>${review.student}</strong>
                <span class="date">${new Date(review.date).toLocaleDateString()}</span>
            </div>
            <div class="stars">${"⭐".repeat(review.rating)}</div>
            <p>${review.comment}</p>
        `;
        reviewsContainer.appendChild(reviewDiv);
    });
}



// when view button is clicked, show the tutor profile and reviews
document.querySelectorAll(".view-button").forEach(button => {
    button.addEventListener("click", function() {
        const tutorId = parseInt(this.getAttribute("data-tutor-id"));
        populateTutorReviews(tutorId);
    });
});