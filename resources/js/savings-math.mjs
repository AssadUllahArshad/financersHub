export function projectSavings(principal, monthly, rate, years) {
    if (
        ![principal, monthly, rate, years].every(Number.isFinite) ||
        principal < 0 ||
        principal > 1e8 ||
        monthly < 0 ||
        monthly > 1e6 ||
        rate < 0 ||
        rate > 30 ||
        !Number.isInteger(years) ||
        years < 1 ||
        years > 50
    )
        throw new RangeError(
            "Enter valid amounts, a rate from 0 to 30%, and 1 to 50 whole years.",
        );
    let balance = principal;
    const rows = [];
    for (let month = 1; month <= years * 12; month++) {
        balance = balance * (1 + rate / 1200) + monthly;
        if (month % 12 === 0) {
            const contributed = principal + monthly * month;
            rows.push({
                year: month / 12,
                contributed,
                interest: Math.max(0, balance - contributed),
                balance,
            });
        }
    }
    return rows;
}
