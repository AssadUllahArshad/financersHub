(() => {
    const password = document.getElementById("password");
    const toggle = document.getElementById("password-toggle");
    toggle.hidden = false;
    toggle.addEventListener("click", () => {
        const visible = password.type === "password";
        password.type = visible ? "text" : "password";
        toggle.textContent = visible ? "Hide" : "Show";
        toggle.setAttribute("aria-pressed", String(visible));
        toggle.setAttribute(
            "aria-label",
            visible ? "Hide password" : "Show password",
        );
    });
    password.addEventListener("keyup", (event) => {
        document.getElementById("caps-lock").hidden =
            !event.getModifierState("CapsLock");
    });
    document.getElementById("login-errors")?.focus();
})();
