document.addEventListener("DOMContentLoaded", () => {
  const houseList = document.getElementById("houseList");
  const filterForm = document.getElementById("filterForm");
  const showHistoryBtn = document.getElementById("showHistoryBtn");
  const bookingHistory = document.getElementById("bookingHistory");

  function renderHouses(data) {
    houseList.innerHTML = "";
    if (!Array.isArray(data) || data.length === 0) {
      houseList.textContent = "No houses found.";
      return;
    }
    data.forEach(house => {
      const div = document.createElement("div");
      div.className = "house-item";
      const img = house.image_path ? `<img src="../${house.image_path}" alt="" style="max-width:100%;border-radius:8px;margin:6px 0;"/>` : "";
      div.innerHTML = `
        <h4>${house.title}</h4>
        ${img}
        <p>Location: ${house.location}</p>
        <p>Category: ${house.category}</p>
        <p>Price: ${house.price} BDT</p>
        ${house.distance ? `<p>Distance: ${Number(house.distance).toFixed(2)} km</p>` : ""}
        <button data-id="${house.id}" class="rent-request-btn">Request Rent</button>
      `;
      houseList.appendChild(div);
    });

    document.querySelectorAll(".rent-request-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const houseId = btn.getAttribute("data-id");
        requestRent(houseId);
      });
    });
  }

  function fetchHouses(filters = {}) {
    const params = new URLSearchParams(filters);
    fetch(`../php/get_houses.php?${params.toString()}`)
      .then(res => res.json())
      .then(renderHouses)
      .catch(() => { houseList.textContent = "Failed to load houses."; });
  }

  function requestRent(houseId) {
    fetch("../php/request-house.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ house_id: houseId })
    })
    .then(res => res.text())
    .then(msg => alert(msg))
    .catch(() => alert("Failed to send request."));
  }

  filterForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const filters = {
      location: filterForm.searchLocation.value.trim(),
      category: filterForm.searchCategory.value,
      max_price: filterForm.searchPrice.value
    };
    fetchHouses(filters);
  });

  if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(position => {
      const { latitude, longitude } = position.coords;
      fetchHouses({ lat: latitude, lon: longitude });
    }, () => fetchHouses());
  } else { fetchHouses(); }

  showHistoryBtn.addEventListener("click", () => {
    if (bookingHistory.style.display === "none") {
      fetch("../php/get-request-history.php")
        .then(res => res.json())
        .then(data => {
          if (!Array.isArray(data) || data.length === 0) {
            bookingHistory.innerHTML = "<p>No requests yet.</p>";
          } else {
            bookingHistory.innerHTML = data.map(b => `
              <div>
                <p>House: ${b.house_title}</p>
                <p>Date: ${b.request_date}</p>
                <p>Status: ${b.status}</p>
              </div>
              <hr />
            `).join("");
          }
          bookingHistory.style.display = "block";
        });
    } else { bookingHistory.style.display = "none"; }
  });

  // Profile update
  document.getElementById("updateProfileForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch("../php/update-profile.php", { method: "POST", body: formData })
      .then(res => res.text())
      .then(data => { document.getElementById("profileMsg").textContent = data; });
  });
});
