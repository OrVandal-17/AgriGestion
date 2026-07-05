// ===== Menu mobile (ouverture / fermeture de la sidebar) =====
document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector(".sidebar");
  const backdrop = document.querySelector(".sidebar-backdrop");
  const toggleBtn = document.getElementById("menuToggle");

  function openMenu() {
    sidebar.classList.add("open");
    backdrop.classList.add("open");
  }
  function closeMenu() {
    sidebar.classList.remove("open");
    backdrop.classList.remove("open");
  }

  if (toggleBtn) {
    toggleBtn.addEventListener("click", function () {
      sidebar.classList.contains("open") ? closeMenu() : openMenu();
    });
  }
  if (backdrop) {
    backdrop.addEventListener("click", closeMenu);
  }
  // Ferme le menu quand on choisit un lien (mobile)
  document.querySelectorAll(".accueil a").forEach(function (link) {
    link.addEventListener("click", closeMenu);
  });

  // ===== Mise en surbrillance du lien actif dans le menu =====
  const currentPage = window.location.pathname.split("/").pop() || "index.html";
  document.querySelectorAll(".accueil a[href]").forEach(function (link) {
    const href = link.getAttribute("href");
    if (href === currentPage) {
      link.classList.add("active");
    }
  });
});
