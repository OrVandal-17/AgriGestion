// ============================================================================
// api.js — Client API centralisé pour AGRI-TOGO
// Gère : l'URL de base, le token JWT, les appels authentifiés,
// la protection des pages par rôle, et le filtrage du menu.
// ============================================================================

const Api = (function () {
  // Adaptez cette URL selon votre installation WAMP / serveur.
  // Exemple WAMP : "http://localhost/AgriGestion/back/public"
  const DEFAULT_BASE_URL = "http://localhost/AgriGestion/back/public";

  // Racine du dossier "front" (déduite de l'URL de ce script lui-même),
  // pour que les redirections fonctionnent depuis la racine ET depuis pages/.
  const ROOT_URL = (function () {
    const scripts = document.getElementsByTagName("script");
    for (let i = 0; i < scripts.length; i++) {
      const src = scripts[i].src || "";
      const marker = "assets/js/api.js";
      const idx = src.indexOf(marker);
      if (idx !== -1) return src.slice(0, idx);
    }
    return ""; // repli : chemin relatif classique
  })();

  function getBaseUrl() {
    return localStorage.getItem("agritogo_api_base") || DEFAULT_BASE_URL;
  }
  function setBaseUrl(url) {
    localStorage.setItem("agritogo_api_base", url.replace(/\/$/, ""));
  }

  function getToken() {
    return localStorage.getItem("agritogo_token");
  }
  function setToken(token) {
    localStorage.setItem("agritogo_token", token);
  }
  function clearSession() {
    localStorage.removeItem("agritogo_token");
    localStorage.removeItem("agritogo_user");
  }

  function getUser() {
    try {
      return JSON.parse(localStorage.getItem("agritogo_user") || "null");
    } catch (e) {
      return null;
    }
  }
  function setUser(user) {
    localStorage.setItem("agritogo_user", JSON.stringify(user));
  }

  /**
   * Appel générique à l'API. Ajoute automatiquement le token JWT.
   * @param {string} path ex: "/agriculteurs" ou "/agriculteurs/3"
   * @param {object} options { method, body }
   */
  async function request(path, options = {}) {
    const headers = { "Content-Type": "application/json" };
    const token = getToken();
    if (token) headers["Authorization"] = "Bearer " + token;

    const config = { method: options.method || "GET", headers };
    if (options.body !== undefined) {
      config.body = JSON.stringify(options.body);
    }

    let res;
    try {
      res = await fetch(getBaseUrl() + path, config);
    } catch (networkErr) {
      throw new Error(
        "Impossible de contacter le serveur. Vérifiez que l'API tourne et que l'URL de base est correcte (" +
          getBaseUrl() +
          ")."
      );
    }

    let data = null;
    const text = await res.text();
    try {
      data = text ? JSON.parse(text) : null;
    } catch (e) {
      /* réponse non-JSON */
    }

    if (!res.ok) {
      // Session expirée ou invalide -> retour à la connexion
      if (res.status === 401) {
        clearSession();
        if (!location.pathname.endsWith("connexion.html")) {
          window.location.href = ROOT_URL + "connexion.html";
        }
      }
      const message = (data && (data.error || data.message)) || `Erreur ${res.status}`;
      throw new Error(message);
    }

    return data;
  }

  function get(path) {
    return request(path, { method: "GET" });
  }
  function post(path, body) {
    return request(path, { method: "POST", body });
  }
  function put(path, body) {
    return request(path, { method: "PUT", body });
  }
  function del(path) {
    return request(path, { method: "DELETE" });
  }

  async function login(email, motDePasse) {
    const data = await post("/auth/login", { email, mot_de_passe: motDePasse });
    setToken(data.token);
    setUser(data.utilisateur);
    return data;
  }

  function logout() {
    clearSession();
    window.location.href = ROOT_URL + "connexion.html";
  }

  /**
   * À appeler en haut de chaque page protégée.
   * @param {string[]} allowedRoles ex: ["Administrateur"], ou [] pour "authentifié peu importe le rôle"
   */
  function requireAuth(allowedRoles = []) {
    const token = getToken();
    const user = getUser();
    if (!token || !user) {
      window.location.href = ROOT_URL + "connexion.html";
      return null;
    }
    if (allowedRoles.length > 0 && !allowedRoles.includes(user.Role)) {
      alert("Accès refusé : cette page n'est pas disponible pour votre rôle (" + user.Role + ").");
      window.location.href = ROOT_URL + "index.html";
      return null;
    }
    return user;
  }

  /**
   * Filtre les liens du menu latéral selon l'attribut data-roles.
   * data-roles="Administrateur,Responsable" ; absent = visible pour tous.
   */
  function applyRoleMenu() {
    const user = getUser();
    const role = user ? user.Role : null;
    document.querySelectorAll(".accueil li[data-roles]").forEach(function (li) {
      const allowed = li.getAttribute("data-roles").split(",");
      if (!role || !allowed.includes(role)) {
        li.style.display = "none";
      }
    });
  }

  /** Petit utilitaire pour afficher une erreur dans un conteneur donné. */
  function showError(container, message) {
    if (!container) return;
    container.textContent = message;
    container.hidden = false;
  }

  return {
    getBaseUrl,
    setBaseUrl,
    getToken,
    getUser,
    clearSession,
    request,
    get,
    post,
    put,
    delete: del,
    login,
    logout,
    requireAuth,
    applyRoleMenu,
    showError,
  };
})();
