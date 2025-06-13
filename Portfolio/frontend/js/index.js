  // Cursor follow logic
  const cursor = document.getElementById("cursor");

  window.addEventListener("mousemove", (e) => {
    cursor.style.left = `${e.clientX}px`;
    cursor.style.top = `${e.clientY}px`;
    console.log(e.clientX);
  });

  document.querySelectorAll(".btn, .card").forEach(item => {
    item.addEventListener("mouseenter", () => {
      cursor.style.transform = "translate(-50%, -50%) scale(1.3)";
    });
    item.addEventListener("mouseleave", () => {
      cursor.style.transform = "translate(-50%, -50%) scale(1.5)";
    });
  });

// Dark/Light mode toggle
const toggleBtn = document.getElementById("modeToggle");

const applyTheme = (theme) => {
  if (theme === "dark") {
    document.body.classList.add("dark-mode");
    toggleBtn.textContent = "☀️";
  } else {
    document.body.classList.remove("dark-mode");
    toggleBtn.textContent = "🌙";
  }
};

toggleBtn.addEventListener("click", () => {
  const newTheme = document.body.classList.contains("dark-mode") ? "light" : "dark";
  applyTheme(newTheme);
  localStorage.setItem("theme", newTheme);
});

// Load saved theme
const savedTheme = localStorage.getItem("theme") || "light";
applyTheme(savedTheme);



const loginBtn = document.getElementById("logbtn");
const heroText = document.getElementById("heroText");
const herosubText = document.getElementById("herosubText");
const loginForm = document.getElementById("loginForm");

function animateTextChange(from, to, element, duration = 500) {
  const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890,!? ";
  const maxLen = Math.max(from.length, to.length);
  const fromArr = from.padEnd(maxLen).split("");
  const toArr = to.padEnd(maxLen).split("");

  let frame = 0;
  const totalFrames = Math.floor(duration / 30);
  const interval = setInterval(() => {
    const output = fromArr.map((char, i) => {
      const progress = frame / totalFrames;
      if (progress > i / maxLen) return toArr[i];
      return chars[Math.floor(Math.random() * chars.length)];
    }).join("");

    element.textContent = output;
    frame++;
    if (frame > totalFrames) clearInterval(interval);
  }, 30);
}

loginBtn.addEventListener("click", () => {
  animateTextChange(heroText.textContent, "Welcome Back!", heroText);
  animateTextChange(herosubText.textContent, "Already a user? signup", herosubText);

  // Show form after short delay
  setTimeout(() => {
    loginForm.classList.add("show");
    loginForm.classList.remove("hidden");
  }, 600);
});
