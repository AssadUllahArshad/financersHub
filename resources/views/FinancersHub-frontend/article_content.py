"""Original educational draft copy for the US reader-facing preview.

Each guide links to the primary source used for its factual background. This
is not a substitute for a formal editorial, legal, or similarity review.
"""
GUIDES = {
    'emergency-fund': {
        'intro': 'An emergency fund is money set aside for expenses that do not fit the usual monthly plan. The useful question is not whether your target matches someone else’s number. It is whether your reserve would help you handle a realistic interruption without immediately borrowing.',
        'sections': [
            ('Start with your own pressure points', 'List the expenses that would continue if income paused: housing, groceries, utilities, insurance, and required debt payments. Then consider the events most likely to create a shortfall, such as a repair or an unexpected bill. This gives the fund a purpose before it gives it a number.'),
            ('Make the first milestone reachable', 'Choose an amount you can build consistently, even if it is smaller than your eventual target. A recurring transfer can make progress less dependent on remembering to move money. Review the transfer when income or essential expenses change.'),
            ('Keep access and safety in view', 'The reserve should be available when an unplanned expense arrives. Compare account access, fees, and applicable deposit protection before choosing where to hold it. Review the balance after using the fund and decide how you will rebuild it.'),
        ],
        'checks': ['Which expenses must continue during an income gap?', 'How quickly can you reach this money?', 'What is your plan to replenish it?'],
        'source': ('Consumer Financial Protection Bureau: An essential guide to building an emergency fund','https://www.consumerfinance.gov/an-essential-guide-to-building-an-emergency-fund/'),
    },
    'index-funds': {
        'intro': 'An index fund is built to follow a selected market index rather than choose holdings one by one in pursuit of a different result. That description is a starting point, not a guarantee about cost, diversification, or return.',
        'sections': [
            ('Identify the index first', 'Two funds can both be called index funds while tracking very different markets. Read the fund objective and holdings to see what you would actually own. A fund concentrated in one sector does not play the same role as one spread across many sectors.'),
            ('Read the fund costs', 'Expenses and trading costs reduce the return investors receive. Compare the expense ratio, transaction charges that may apply, and how closely the fund has followed its stated benchmark. A low headline fee is useful, but it is not the entire decision.'),
            ('Fit the investment to the goal', 'Consider when you will need the money and whether you can tolerate a drop in value along the way. Index funds still carry market risk. Review how a proposed fund fits with your other holdings before deciding it is diversified enough for you.'),
        ],
        'checks': ['Which index does the fund follow?', 'What costs and tracking differences should you expect?', 'How does it fit your time horizon and other holdings?'],
        'source': ('Investor.gov: Investor bulletin on index funds','https://www.investor.gov/introduction-investing/general-resources/news-alerts/alerts-bulletins/investor-bulletins-26'),
    },
    'bank-account': {
        'intro': 'A bank account should work for the way you receive, spend, and move money. The best advertised feature matters less if the everyday terms make the account expensive or inconvenient for you.',
        'sections': [
            ('Compare the monthly reality', 'Look for maintenance fees and the conditions for waiving them. Check what happens when the balance is low, how overdraft choices work, and whether out-of-network ATM use brings extra charges. Compare the requirements with how you actually bank.'),
            ('Check access before opening', 'Think about direct deposit, cash deposits, transfers, bill payment, customer support, and the time it takes to reach your money. An attractive rate may be less useful if the account does not support your usual transactions.'),
            ('Verify protection and ownership', 'Confirm whether the institution and account type have the deposit protection you expect. Ask how coverage applies if you hold other accounts at the same institution. Keep the disclosures for the specific account, because terms can change.'),
        ],
        'checks': ['Which fees could apply to your normal activity?', 'Can you deposit and withdraw money when needed?', 'What protection applies to this account?'],
        'source': ('FDIC: Deposit insurance resources','https://www.fdic.gov/resources/deposit-insurance'),
        'additional_sources': [('Consumer Financial Protection Bureau: Checking account fees','https://www.consumerfinance.gov/ask-cfpb/should-i-get-a-checking-account-that-pays-interest-en-925/')],
    },
    'credit-interest': {
        'intro': 'A credit card’s annual percentage rate is only one part of its borrowing cost. The balance you carry, the way payments are applied, and any grace period all affect what you may pay.',
        'sections': [
            ('Find out when interest starts', 'A grace period is the time between the end of a billing cycle and the payment due date. Many cards offer one on purchases when the statement balance is paid in full by the due date, but issuers are not required to provide it. Check the agreement for your card and for different types of transactions.'),
            ('Look beyond the minimum payment', 'A minimum payment can keep an account current while leaving a balance that continues to accrue interest. Read the payment information on your statement and compare what changes if you pay more. Avoid treating a lower monthly payment as a lower total cost.'),
            ('Review the actual agreement', 'Check the purchase APR, other APRs, fees, and any promotional terms. If you have carried a balance, ask the issuer when interest will stop after you pay it off. Rules vary by account, and your statement is the place to start.'),
        ],
        'checks': ['Does a purchase grace period apply to you?', 'What happens if you pay only the minimum?', 'Which rate and fees apply to this transaction?'],
        'source': ('Consumer Financial Protection Bureau: Credit card grace periods','https://www.consumerfinance.gov/ask-cfpb/what-is-a-grace-period-for-a-credit-card-en-47/'),
        'additional_sources': [('Consumer Financial Protection Bureau: Credit card terms','https://www.consumerfinance.gov/consumer-tools/credit-cards/answers/key-terms/')],
    },
    'business-cash-flow': {
        'intro': 'A profitable month can still feel tight when customer payments arrive after payroll, rent, or supplier bills. A short cash flow review makes the timing visible before it becomes a surprise.',
        'sections': [
            ('Map money by expected date', 'Begin with the cash available today. List incoming payments by the date you realistically expect to receive them, then list outgoing obligations by due date. Separate confirmed receipts from amounts you still need to collect.'),
            ('Look for the narrow weeks', 'A monthly total can hide a shortfall between two large payments. Scan the coming weeks for moments when scheduled bills exceed money available. Identify which invoices need follow-up and which costs may be flexible, without assuming every customer will pay on time.'),
            ('Keep a simple record', 'Compare your forecast with what actually arrived and left. Update assumptions as payment patterns change. Bookkeeping records, income statements, and a cash flow projection each answer different questions; use them together rather than relying on the bank balance alone.'),
        ],
        'checks': ['Which receipts are confirmed and which are uncertain?', 'When are the next fixed payments due?', 'Where does the forecast differ from actual cash?'],
        'source': ('U.S. Small Business Administration: Manage your finances','https://www.sba.gov/business-guide/manage-your-business/manage-your-finances'),
    },
    'saving-investing': {
        'intro': 'Saving and investing both move money toward a goal, but they expose it to different kinds of uncertainty. The decision begins with when you need the money and how much change in value you can accept.',
        'sections': [
            ('Start with the deadline', 'Money needed for an imminent bill or emergency has a different job from money intended for a distant goal. Savings products can help keep short-term money accessible, while investments may fluctuate before you are ready to use them.'),
            ('Name the risk you can carry', 'Investment returns are not guaranteed, and selling after a decline can turn a temporary loss into a realized one. Holding only cash for a long-term goal has its own trade-offs. Consider both the timing of the goal and how a loss would affect your plan.'),
            ('Give each dollar a job', 'You do not need one answer for every goal. Separate essential reserves, upcoming purchases, and long-term objectives, then review your choices when the goal or timeline changes. Check the terms and protections of any account or product before using it.'),
        ],
        'checks': ['When will you need this money?', 'Can you handle a decline before that date?', 'What access do you need along the way?'],
        'source': ('Investor.gov: Introduction to investing','https://www.investor.gov/introduction-investing'),
    },
    'retirement-planning': {
        'intro': 'A distant retirement goal can be hard to turn into a monthly decision. Start with the pieces you can measure today, then revisit the plan rather than waiting for a perfect forecast.',
        'sections': [
            ('Make the goal more concrete', 'Estimate what you can regularly set aside after essential expenses and near-term obligations. If an employer plan or individual retirement account is available to you, compare eligibility, costs, and investment choices using current plan documents.'),
            ('Use time as a planning input', 'Your time horizon is the period before you expect to use the money. A longer horizon may allow a different mix of investments from a short one, but your ability and willingness to bear loss also matter. Diversification can manage some risks; it cannot remove all risk.'),
            ('Revisit the plan as life changes', 'A raise, job change, new household expense, or approaching retirement can change what is feasible. Check contributions, fees, investment mix, and beneficiary information on a schedule that you can actually keep.'),
        ],
        'checks': ['What contribution fits your current budget?', 'What are the costs and choices in your plan?', 'When will you review the goal again?'],
        'source': ('Investor.gov: Asset allocation and diversification','https://www.investor.gov/introduction-investing/getting-started/asset-allocation'),
    },
    'insurance-coverage': {
        'intro': 'The cheapest premium is not always the least expensive outcome. Insurance comparisons make more sense when you hold coverage, exclusions, limits, and deductibles side by side.',
        'sections': [
            ('Compare the same protection', 'Ask each insurer for quotes with similar coverage types and limits. Review the deductible you would have to pay after a covered event and any separate deductible for a particular kind of claim. A lower premium may come with more cost at claim time.'),
            ('Read the exclusions', 'The policy describes what is covered and what is not. Look for limits on valuable property, location-specific risks, and conditions that might affect a claim. If wording is unclear, ask the company or agent to explain it before purchase.'),
            ('Review after a change', 'A move, new vehicle, renovation, or change in household can make an old policy a poor match. Keep a copy of your declarations and policy documents, and check the current terms at renewal rather than assuming coverage stayed the same.'),
        ],
        'checks': ['Are the coverage limits comparable?', 'What deductibles and exclusions apply?', 'Have your circumstances changed since the last review?'],
        'source': ('National Association of Insurance Commissioners: Get smart about your insurance coverage','https://content.naic.org/article/consumer-insight-get-smart-about-your-insurance-coverage'),
    },
    'tax-planning': {
        'intro': 'Tax planning starts well before a return is filed. Keeping records and checking withholding during the year can make the next decision clearer without guessing at a result.',
        'sections': [
            ('Keep useful records as you go', 'Organize income documents, relevant receipts, and information about payments and deductions in one place. The details you need depend on your situation; check current IRS instructions rather than relying on a past return as a complete checklist.'),
            ('Check withholding when life changes', 'A change in employment or household circumstances may affect the amount withheld from pay. The IRS Tax Withholding Estimator can help eligible taxpayers review federal withholding, but it does not replace individualized tax advice.'),
            ('Separate planning from promises', 'Tax rules and eligibility change, and a strategy that fits one person may not fit another. Before acting on a deduction, credit, or payment decision, check current IRS guidance and ask a qualified tax professional when the facts are complicated.'),
        ],
        'checks': ['Are your records complete enough to support a claim?', 'Does your withholding still reflect your situation?', 'Which current IRS guidance applies?'],
        'source': ('Internal Revenue Service: Tax withholding','https://www.irs.gov/payments/tax-withholding'),
        'additional_sources': [('Internal Revenue Service: Recordkeeping','https://www.irs.gov/businesses/small-businesses-self-employed/recordkeeping')],
    },
    'financial-app': {
        'intro': 'A financial app can make a task more convenient while adding a new place where information or money may sit. Examine the service behind the screen before connecting an account.',
        'sections': [
            ('Understand what the app does', 'Determine whether it displays information, moves money, stores a balance, or offers another financial product. Read who provides each service and how to get support if a transaction fails. An appealing interface does not tell you what protections apply.'),
            ('Inspect permissions and costs', 'Review the information the app requests, what it shares, how to disconnect an account, and whether fees appear after a trial or transfer. Use the actual privacy and account terms to check these points rather than relying only on marketing copy.'),
            ('Check where funds are held', 'If an app holds a balance, find out which institution holds the money and whether deposit insurance applies to your arrangement. Do not assume funds stored with a nonbank app are protected the same way as deposits at an insured bank or credit union.'),
        ],
        'checks': ['Who provides the underlying service?', 'What data and account access does the app request?', 'Where does any stored balance sit?'],
        'source': ('Consumer Financial Protection Bureau: Funds stored through payment apps','https://www.consumerfinance.gov/data-research/research-reports/issue-spotlight-analysis-of-deposit-insurance-coverage-on-funds-stored-through-payment-apps/full-report/'),
        'additional_sources': [('Federal Trade Commission: How websites and apps collect information','https://consumer.ftc.gov/articles/how-websites-apps-collect-use-your-information')],
    },
    'loan-questions': {
        'intro': 'A manageable payment can hide an expensive loan if the term is long or fees are high. Compare offers using the same borrowing amount and repayment assumptions.',
        'sections': [
            ('Look at more than the payment', 'Ask for the annual percentage rate, total repayment amount, term, and any origination or other fees. APR incorporates certain borrowing costs beyond the interest rate, but the dollars paid over the full loan also matter.'),
            ('Test the schedule against your budget', 'Find out when payments begin, whether the rate can change, and what happens if you miss a payment. A longer term can reduce a monthly payment while increasing the overall cost. Check the final disclosure before accepting.'),
            ('Compare the same kind of offer', 'Use the same loan amount and term when possible, and ask each lender to explain unfamiliar charges. If an offer has a feature you value, weigh it against its total cost rather than assuming the lowest advertised rate is automatically best.'),
        ],
        'checks': ['What are the APR, fees, and total payments?', 'Can the rate or payment change?', 'What happens if you pay early or late?'],
        'source': ('Consumer Financial Protection Bureau: Loan interest rate and APR','https://www.consumerfinance.gov/ask-cfpb/what-is-the-difference-between-a-loan-interest-rate-and-the-apr-en-733/'),
        'additional_sources': [('Consumer Financial Protection Bureau: Comparing loan terms','https://www.consumerfinance.gov/ask-cfpb/how-do-i-compare-auto-loan-offers-what-should-i-look-at-besides-the-monthly-payment-en-753/')],
    },
}
