document.querySelector(".clear").addEventListener("click", () => {
    document.querySelector(".search-bar input").value = "";
    document.querySelector(".search-bar input").focus();
  });
  
  document.querySelector(".search-btn").addEventListener("click", () => {
    const query = document.querySelector(".search-bar input").value.trim();
    if (query) {
      alert(`Searching for: ${query}`);
    }
  });
  