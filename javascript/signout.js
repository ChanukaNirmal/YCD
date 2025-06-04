
    const userBtn = document.getElementById("userBtn");
  const signOutMenu = document.getElementById("signOutMenu");

  userBtn.addEventListener("click", (e) => {
    e.stopPropagation(); // Prevent the document click from hiding it immediately
    signOutMenu.style.display = (signOutMenu.style.display === "block") ? "none" : "block";
  });

  function signOut() {
    window.location.href = "home.php";
  }

  // Hide the menu when clicking outside
  document.addEventListener("click", function (e) {
    if (!userBtn.contains(e.target) && !signOutMenu.contains(e.target)) {
      signOutMenu.style.display = "none";
    }
  });
