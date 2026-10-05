import { projectSavings } from "./savings-math.mjs";
import { t, locale } from "./public-i18n.js";
const form = document.querySelector("#savings-calculator"),
    result = document.querySelector("#calculator-result"),
    download = document.querySelector("#download-savings");
let rows = [],
    currency = "USD";
function calculate(event) {
    event?.preventDefault();
    if (!form.reportValidity()) return;
    try {
        const fields = new FormData(form);
        currency = fields.get("currency");
        rows = projectSavings(
            ...["principal", "monthly", "rate", "years"].map((key) =>
                Number(fields.get(key)),
            ),
        );
        const money = (value) =>
            new Intl.NumberFormat(locale, {
                style: "currency",
                currency,
                maximumFractionDigits: 2,
            }).format(value);
        const last = rows.at(-1);
        result.replaceChildren();
        [
            ["Estimated balance", last.balance],
            ["Total contributed", last.contributed],
            ["Estimated interest", last.interest],
        ].forEach(([label, value]) => {
            const p = document.createElement("p"),
                strong = document.createElement("strong");
            p.append(t(label) + ": ");
            strong.textContent = money(value);
            p.append(strong);
            result.append(p);
        });
        document.querySelector("#contribution-bar").style.width =
            (last.balance ? (100 * last.contributed) / last.balance : 100) +
            "%";
        const body = document.querySelector("#savings-rows");
        body.replaceChildren();
        rows.forEach((row) => {
            const tr = document.createElement("tr");
            [
                row.year,
                money(row.contributed),
                money(row.interest),
                money(row.balance),
            ].forEach((value) => {
                const td = document.createElement("td");
                td.textContent = value;
                tr.append(td);
            });
            body.append(tr);
        });
        download.hidden = false;
    } catch (error) {
        result.textContent =
            locale === "es-US"
                ? "Revisa los valores: deben estar dentro de los límites indicados."
                : error.message;
        download.hidden = true;
    }
}
form.addEventListener("submit", calculate);
form.querySelector("[type=submit]").disabled = false;
calculate();
download.addEventListener("click", () => {
    const csv =
        (locale === "es-US"
            ? "Año,Moneda,Aportaciones,Interés,Saldo\r\n"
            : "Year,Currency,Contributed,Interest,Balance\r\n") +
        rows
            .map((row) =>
                [
                    row.year,
                    currency,
                    row.contributed.toFixed(2),
                    row.interest.toFixed(2),
                    row.balance.toFixed(2),
                ].join(","),
            )
            .join("\r\n");
    const url = URL.createObjectURL(
        new Blob([csv], { type: "text/csv;charset=utf-8" }),
    );
    const link = document.createElement("a");
    link.href = url;
    link.download = "savings-projection.csv";
    link.click();
    setTimeout(() => URL.revokeObjectURL(url), 1000);
});

form.addEventListener("input", () => {
    download.hidden = true;
    result.textContent = t(
        "Inputs changed. Select Calculate savings to refresh your results.",
    );
    document.querySelector("#savings-rows").replaceChildren();
    document.querySelector("#contribution-bar").style.width = "0%";
});
