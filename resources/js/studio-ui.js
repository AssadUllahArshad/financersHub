(() => {
    document
        .querySelector("#command-search")
        ?.addEventListener("input", (event) => {
            const query = event.target.value.toLowerCase().trim();
            document
                .querySelectorAll("[data-catalog-command]")
                .forEach((row) => {
                    row.hidden = !row.textContent.toLowerCase().includes(query);
                });
        });
    const command = document.querySelector("#maintenance-command");
    const tasks = document.querySelectorAll(".console-task");
    const syncTask = () => {
        if (!command) return;
        document.querySelector("#task-description").textContent =
            command.selectedOptions[0].dataset.description;
        tasks.forEach((task) => {
            const active = task.dataset.command === command.value;
            task.classList.toggle("selected", active);
            task.setAttribute("aria-pressed", String(active));
        });
    };
    tasks.forEach((task) =>
        task.addEventListener("click", () => {
            command.value = task.dataset.command;
            syncTask();
        }),
    );
    command?.addEventListener("change", syncTask);
    document.querySelectorAll("[data-copy-output]").forEach((button) =>
        button.addEventListener("click", async () => {
            try {
                await navigator.clipboard.writeText(
                    document.getElementById(button.dataset.copyOutput)
                        .textContent,
                );
                button.textContent = "Copied";
            } catch {
                button.textContent = "Select output to copy";
            }
            setTimeout(() => {
                button.textContent = "Copy output";
            }, 2500);
        }),
    );
    const dialog = document.querySelector("#admin-confirm");
    let pending = null;
    const approved = new WeakSet();
    document.querySelectorAll("form[data-confirm]").forEach((form) =>
        form.addEventListener("submit", (event) => {
            if (approved.has(form)) return;
            event.preventDefault();
            pending = { form, submitter: event.submitter };
            document.querySelector("#confirm-description").textContent =
                form.dataset.confirm;
            dialog.showModal();
            dialog.querySelector("[data-confirm-cancel]").focus();
        }),
    );
    dialog
        ?.querySelector("[data-confirm-cancel]")
        .addEventListener("click", () => dialog.close());
    dialog
        ?.querySelector("[data-confirm-accept]")
        .addEventListener("click", () => {
            if (!pending) return;
            const { form, submitter } = pending;
            approved.add(form);
            dialog.close();
            form.requestSubmit(submitter);
        });
    const busyButtons = new Map();
    document.querySelectorAll("form[data-busy-label]").forEach((form) =>
        form.addEventListener("submit", (event) => {
            if (event.defaultPrevented) return;
            const button =
                event.submitter || form.querySelector("[type=submit]");
            if (!button || busyButtons.has(button)) return;
            busyButtons.set(button, [...button.childNodes]);
            const icon = button.querySelector("svg");
            button.disabled = true;
            button.replaceChildren(
                ...(icon ? [icon] : []),
                document.createTextNode(form.dataset.busyLabel),
            );
        }),
    );
    window.addEventListener("pageshow", () =>
        busyButtons.forEach((children, button) => {
            button.disabled = false;
            button.replaceChildren(...children);
            busyButtons.delete(button);
        }),
    );
})();
