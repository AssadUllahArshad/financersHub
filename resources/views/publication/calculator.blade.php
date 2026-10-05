@extends('layouts.site')
@section('title', __('Compound Interest Calculator with Monthly Contributions'))
@section('description', __('Estimate savings growth with our free compound interest calculator. Compare monthly contributions, view a yearly breakdown and download your results.'))
@section('content')
    <div class="wrap calculator-page"><x-breadcrumbs :items="[['label' => 'Savings calculator']]" />
        <header class="info-hero"><span class="eyebrow">{{ __('FREE FINANCIAL TOOL') }} </span>
            <h1>{{ __('See what steady saving could build.') }} </h1>
            <p>{{ __('Explore compound interest with a starting amount and regular monthly contributions. Your numbers stay in your browser.') }} </p>
        </header>
        <div class="calculator-grid">
            <form id="savings-calculator" class="calculator-panel">
                <h2>{{ __('Your savings scenario') }} </h2><label>{{ __('Currency') }} <select class="form-select" name="currency">
                        <option>{{ __('USD') }} </option>
                        <option>{{ __('PKR') }} </option>
                        <option>{{ __('GBP') }} </option>
                        <option>{{ __('EUR') }} </option>
                        <option>{{ __('INR') }} </option>
                        <option>{{ __('AED') }} </option>
                    </select></label><label>{{ __('Starting balance') }} <input class="form-control" name="principal" type="number"
                        min="0" max="100000000" step="0.01" value="1000" required></label><label>{{ __('Monthly contribution') }} <input class="form-control" name="monthly" type="number" min="0" max="1000000"
                        step="0.01" value="100" required></label><label>{{ __('Annual interest rate (%)') }} <input
                        class="form-control" name="rate" type="number" min="0" max="30" step="0.01"
                        value="5" required></label><label>{{ __('Years to save') }} <input class="form-control" name="years"
                        type="number" min="1" max="50" step="1" value="10" required></label><button
                    class="btn primary" type="submit" disabled><x-icon name="calculator" />{{ __('Calculate savings') }} </button>
                <p class="meta">{{ __('Example inputs are illustrative. Currency changes the display, not exchange rates.') }} </p>
            </form>
            <section class="calculator-panel" aria-labelledby="result-title">
                <h2 id="result-title">{{ __('Your projected balance') }} </h2>
                <div id="calculator-result" aria-live="polite"></div>
                <div class="savings-bar" aria-hidden="true"><span id="contribution-bar"></span></div>
                <p class="meta">{{ __('Teal: money contributed · Gold: estimated interest') }} </p><button class="btn ghost"
                    id="download-savings" type="button" hidden><x-icon name="download" />{{ __('Download yearly breakdown (CSV)') }} </button>
                <div class="calculator-table">
                    <table>
                        <caption>{{ __('Year-by-year projection') }} </caption>
                        <thead>
                            <tr>
                                <th>{{ __('Year') }} </th>
                                <th>{{ __('Contributed') }} </th>
                                <th>{{ __('Interest') }} </th>
                                <th>{{ __('Balance') }} </th>
                            </tr>
                        </thead>
                        <tbody id="savings-rows"></tbody>
                    </table>
                </div><noscript>
                    <p>{{ __('Enable JavaScript to calculate.') }} </p>
                </noscript>
            </section>
        </div>
        <section class="prose calculator-explanation">
            <h2>{{ __('How this calculator works') }} </h2>
            <p>{{ __('calculator_method') }} </p>
            <h2>{{ __('What is compound interest?') }} </h2>
            <p>{{ __('Compound interest is growth earned on both your original savings and interest already accumulated.') }} </p>
            <h2>{{ __('What happens at a zero interest rate?') }} </h2>
            <p>{{ __('Your ending balance is the starting amount plus all monthly contributions.') }} </p>
            <h2>{{ __('Does this predict investment returns?') }} </h2>
            <p>{{ __('No. This is a constant-rate illustration, not a forecast or financial advice. Actual rates and returns can vary or be negative. Taxes, fees, inflation and withdrawals are excluded.') }} </p>
            <p>{{ __('Learn more with the') }} <a
                    href="https://www.investor.gov/financial-tools-calculators/calculators/compound-interest-calculator"
                    rel="noopener noreferrer">{{ __('SEC\'s compound interest calculator') }} </a> {{ __(', or') }} <a
                    href="{{ \App\Support\Localization::route('search', ['q' => 'saving']) }}">{{ __('explore our savings guides') }} </a>.</p>
        </section>
    </div>
    @vite('resources/js/calculator.js')
@endsection
