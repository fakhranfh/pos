@extends('master')

@section('title', 'Welcome')

@section('body_class', 'bg-background text-on-background min-h-screen flex flex-col font-body-md')

@section('content')

    <!-- Navbar -->
    <header class="sticky top-0 z-40 border-b border-outline-variant bg-background/90 backdrop-blur">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-gutter py-space-md lg:px-space-xl" aria-label="Global">
            <a href="/" class="flex items-center gap-space-sm font-headline-sm text-headline-sm text-on-surface">
                <img src="{{ asset('logo.png') }}" alt="{{ config('app.name', 'Laravel') }}" class="h-7 w-auto" />
                {{ config('app.name', 'Laravel') }}
            </a>

            @if (Route::has('login'))
                <div class="flex items-center gap-space-lg">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-label-md text-label-md text-secondary hover:text-on-surface transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="font-label-md text-label-md text-secondary hover:text-on-surface transition-colors">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="flex min-h-[44px] items-center rounded bg-primary px-space-md font-label-md text-label-md text-on-primary hover:bg-primary-container focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </nav>
    </header>

    {{--
        Marketing copy below follows AIDA / Fitts's Law / Cognitive Load structure.
        No pricing is referenced — CTAs point at free account creation. Bracketed
        values ([...]) are placeholders for review counts; testimonials are
        illustrative and must be replaced with real customer quotes before publishing.
    --}}
    <main class="flex-1">
        <!-- 1. Hero -->
        <section class="mx-auto max-w-6xl px-gutter pb-space-lg pt-space-xl lg:px-space-xl lg:pt-20">
            <div class="grid items-center gap-space-xl lg:grid-cols-[3fr_2fr]">
                <div class="reveal" style="animation-delay: 0.05s">
                    <h1 class="text-balance font-headline-lg text-headline-lg text-on-surface sm:text-[2.75rem] sm:leading-[1.1] lg:text-[3.25rem] lg:leading-[1.03] tracking-[-0.03em]">
                        Close every sale in seconds. No more counting change by hand.
                    </h1>
                    <p class="mt-space-md max-w-md text-pretty font-body-lg text-body-lg text-on-surface-variant">
                        The point-of-sale system built for small shops and warungs &mdash; checkout, stock and reports in one screen.
                    </p>
                    <div class="mt-space-lg flex flex-wrap items-center gap-x-space-lg gap-y-space-sm">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="flex min-h-[44px] items-center rounded bg-primary px-space-lg font-label-md text-label-md text-on-primary hover:bg-primary-container focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ Route::has('register') ? route('register') : route('login') }}" class="flex min-h-[44px] items-center rounded bg-primary px-space-lg font-label-md text-label-md text-on-primary hover:bg-primary-container focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors">
                                {{ Route::has('register') ? 'Create My Free Account' : 'Log In' }}
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('login') }}" class="group inline-flex items-center gap-space-xs font-label-md text-label-md text-on-surface hover:text-primary transition-colors">
                                    Already have an account?
                                    <span class="transition-transform group-hover:translate-x-0.5" aria-hidden="true">&rarr;</span>
                                </a>
                            @endif
                        @endauth
                    </div>
                    <p class="mt-space-sm font-body-sm text-body-sm text-on-surface-variant">No setup fees. Set up in one afternoon.</p>
                </div>

                {{-- Image idea: a cashier mid-transaction on a tablet at a small warung
                     counter, customer smiling, cash and phone visible on the counter.
                     The "after" moment of a fast, calm checkout &mdash; not a stock photo
                     of a laptop with generic charts. --}}
                <div class="reveal" style="animation-delay: 0.15s">
                    <div class="overflow-hidden rounded-lg border border-outline-variant bg-surface">
                        <div class="flex items-center justify-between border-b border-outline-variant bg-surface-container-lowest px-space-md py-space-sm">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Current sale</span>
                            <span class="rounded-full bg-success-container px-space-sm py-space-xxs font-label-sm text-label-sm text-on-success-container">In stock</span>
                        </div>
                        <div class="px-space-lg py-space-lg">
                            <ul class="divide-y divide-outline-variant font-body-md text-body-md text-on-surface">
                                <li class="flex items-center justify-between py-space-sm">
                                    <span>Nasi Goreng Spesial &times;2</span>
                                    <span class="text-on-surface-variant">Rp 60.000</span>
                                </li>
                                <li class="flex items-center justify-between py-space-sm">
                                    <span>Es Teh Manis &times;2</span>
                                    <span class="text-on-surface-variant">Rp 16.000</span>
                                </li>
                                <li class="flex items-center justify-between py-space-sm">
                                    <span>Kerupuk</span>
                                    <span class="text-on-surface-variant">Rp 5.000</span>
                                </li>
                            </ul>
                            <div class="mt-space-md border-t border-outline-variant pt-space-md">
                                <div class="flex items-center justify-between font-label-md text-label-md text-on-surface-variant">
                                    <span>Total</span>
                                    <span>Rp 81.000</span>
                                </div>
                                <div class="mt-space-xs flex items-center justify-between font-headline-md text-headline-md text-on-surface">
                                    <span>Change due</span>
                                    <span class="text-success">Rp 19.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. Problem -->
        <section class="border-t border-outline-variant">
            <div class="mx-auto max-w-6xl px-gutter py-space-xl lg:px-space-xl">
                <h2 class="reveal max-w-lg text-balance font-headline-md text-headline-md text-on-surface tracking-[-0.02em]" style="animation-delay: 0.05s">
                    Running a shop shouldn&rsquo;t mean guessing.
                </h2>
                <ul class="reveal mt-space-lg grid gap-space-sm sm:grid-cols-2" style="animation-delay: 0.1s">
                    <li class="font-body-md text-body-md text-on-surface-variant">You count cash by hand and the till never quite matches.</li>
                    <li class="font-body-md text-body-md text-on-surface-variant">You don&rsquo;t know what&rsquo;s low in stock until a customer asks for it.</li>
                    <li class="font-body-md text-body-md text-on-surface-variant">You&rsquo;ve tried a notebook, then a spreadsheet &mdash; both fall behind by week two.</li>
                    <li class="font-body-md text-body-md text-on-surface-variant">You&rsquo;ve tried a free app that crashes or locks features mid-shift.</li>
                    <li class="font-body-md text-body-md text-on-surface-variant">You close up at night with no clear picture of what actually sold.</li>
                </ul>
            </div>
        </section>

        <!-- 3. Solution -->
        <section class="border-t border-outline-variant">
            <div class="mx-auto max-w-6xl px-gutter py-space-xl lg:px-space-xl">
                <div class="reveal max-w-2xl" style="animation-delay: 0.05s">
                    <h2 class="text-balance font-headline-md text-headline-md text-on-surface tracking-[-0.02em]">One screen. Every sale tracked, every item counted.</h2>
                    <p class="mt-space-sm font-body-lg text-body-lg text-on-surface-variant">Every sale updates your stock the second it happens. No manual counting, no end-of-day guesswork. Cashiers get one simple checkout screen. Owners get the full picture &mdash; stock and sales &mdash; without digging through paper.</p>
                </div>
            </div>
        </section>

        <!-- 4. Benefits: 3 only, not a card grid -->
        <section class="border-t border-outline-variant">
            <div class="mx-auto max-w-6xl px-gutter py-space-xl lg:px-space-xl">
                <div class="divide-y divide-outline-variant">
                    <div class="reveal grid gap-space-sm py-space-lg sm:grid-cols-[minmax(0,16rem)_1fr] sm:gap-space-xl sm:py-space-xl" style="animation-delay: 0.05s">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface">Faster checkout, happier customers</h3>
                        <p class="max-w-xl font-body-md text-body-md text-on-surface-variant">Because scanning or searching a product takes one tap, not a search through a notebook.</p>
                    </div>
                    <div class="reveal grid gap-space-sm py-space-lg sm:grid-cols-[minmax(0,16rem)_1fr] sm:gap-space-xl sm:py-space-xl" style="animation-delay: 0.1s">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface">Never run out at the worst moment</h3>
                        <p class="max-w-xl font-body-md text-body-md text-on-surface-variant">Because stock drops automatically with every sale, and low-stock alerts warn you before the shelf&rsquo;s empty.</p>
                    </div>
                    <div class="reveal grid gap-space-sm py-space-lg sm:grid-cols-[minmax(0,16rem)_1fr] sm:gap-space-xl sm:py-space-xl" style="animation-delay: 0.15s">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface">Know what&rsquo;s actually working</h3>
                        <p class="max-w-xl font-body-md text-body-md text-on-surface-variant">Because every sale rolls up into a report you can read in 30 seconds &mdash; best sellers, slow movers, daily totals.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. How it works -->
        <section class="border-t border-outline-variant">
            <div class="mx-auto max-w-6xl px-gutter py-space-xl lg:px-space-xl">
                <h2 class="reveal font-headline-md text-headline-md text-on-surface tracking-[-0.02em]" style="animation-delay: 0.05s">Three steps to your first sale.</h2>
                <ol class="mt-space-lg grid gap-space-lg sm:grid-cols-3">
                    <li class="reveal" style="animation-delay: 0.1s">
                        <span class="font-headline-sm text-headline-sm text-primary">1</span>
                        <h3 class="mt-space-xs font-label-md text-label-md text-on-surface">Sign up</h3>
                        <p class="mt-space-xs font-body-sm text-body-sm text-on-surface-variant">Create your account in a minute, no setup call needed.</p>
                    </li>
                    <li class="reveal" style="animation-delay: 0.15s">
                        <span class="font-headline-sm text-headline-sm text-primary">2</span>
                        <h3 class="mt-space-xs font-label-md text-label-md text-on-surface">Set up</h3>
                        <p class="mt-space-xs font-body-sm text-body-sm text-on-surface-variant">Add your products in minutes, or import from a spreadsheet you already have.</p>
                    </li>
                    <li class="reveal" style="animation-delay: 0.2s">
                        <span class="font-headline-sm text-headline-sm text-primary">3</span>
                        <h3 class="mt-space-xs font-label-md text-label-md text-on-surface">Sell</h3>
                        <p class="mt-space-xs font-body-sm text-body-sm text-on-surface-variant">Open the register and start checking out customers the same day.</p>
                    </li>
                </ol>
            </div>
        </section>

        <!-- 6. Comparison -->
        <section class="border-t border-outline-variant">
            <div class="mx-auto max-w-6xl px-gutter py-space-xl lg:px-space-xl">
                <h2 class="reveal font-headline-md text-headline-md text-on-surface tracking-[-0.02em]" style="animation-delay: 0.05s">This POS vs. a notebook or spreadsheet.</h2>
                <div class="reveal mt-space-lg overflow-x-auto" style="animation-delay: 0.1s">
                    <table class="w-full min-w-[36rem] border-collapse font-body-sm text-body-sm">
                        <thead>
                            <tr class="border-b border-outline-variant text-left">
                                <th scope="col" class="py-space-sm pr-space-md font-label-md text-label-md text-on-surface-variant"></th>
                                <th scope="col" class="py-space-sm pr-space-md font-label-md text-label-md text-on-surface">This POS</th>
                                <th scope="col" class="py-space-sm font-label-md text-label-md text-on-surface-variant">Notebook / spreadsheet</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            <tr>
                                <th scope="row" class="py-space-sm pr-space-md text-left font-label-md text-label-md text-on-surface-variant">Stock updates</th>
                                <td class="py-space-sm pr-space-md text-on-surface">Automatic, per sale</td>
                                <td class="py-space-sm text-on-surface-variant">Manual, end of day (if at all)</td>
                            </tr>
                            <tr>
                                <th scope="row" class="py-space-sm pr-space-md text-left font-label-md text-label-md text-on-surface-variant">Checkout speed</th>
                                <td class="py-space-sm pr-space-md text-on-surface">One tap per item</td>
                                <td class="py-space-sm text-on-surface-variant">Write it down, add it up</td>
                            </tr>
                            <tr>
                                <th scope="row" class="py-space-sm pr-space-md text-left font-label-md text-label-md text-on-surface-variant">Low-stock warning</th>
                                <td class="py-space-sm pr-space-md text-on-surface">Built in</td>
                                <td class="py-space-sm text-on-surface-variant">None &mdash; you find out too late</td>
                            </tr>
                            <tr>
                                <th scope="row" class="py-space-sm pr-space-md text-left font-label-md text-label-md text-on-surface-variant">Sales report</th>
                                <td class="py-space-sm pr-space-md text-on-surface">One tap, any date range</td>
                                <td class="py-space-sm text-on-surface-variant">Hours of manual tallying</td>
                            </tr>
                            <tr>
                                <th scope="row" class="py-space-sm pr-space-md text-left font-label-md text-label-md text-on-surface-variant">Setup time</th>
                                <td class="py-space-sm pr-space-md text-on-surface">One afternoon</td>
                                <td class="py-space-sm text-on-surface-variant">Already &ldquo;set up,&rdquo; already failing</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- 7. Urgency: real capacity limit, no fake countdown -->
        {{-- Placeholder: confirm real onboarding capacity with the business --}}
        <section class="border-t border-outline-variant bg-primary-container/40">
            <div class="mx-auto max-w-6xl px-gutter py-space-lg lg:px-space-xl">
                <p class="reveal max-w-2xl font-body-md text-body-md text-on-primary-container" style="animation-delay: 0.05s">
                    We onboard a limited number of new shops each month, so every owner gets a real answer when something breaks &mdash; not a ticket queue. No countdown clock, just a real cap on how many we can support well.
                </p>
            </div>
        </section>

        <!-- 8. FAQ -->
        <section class="border-t border-outline-variant">
            <div class="mx-auto max-w-6xl px-gutter py-space-xl lg:px-space-xl">
                <h2 class="reveal font-headline-md text-headline-md text-on-surface tracking-[-0.02em]" style="animation-delay: 0.05s">Questions, answered.</h2>
                <div class="mt-space-lg divide-y divide-outline-variant">
                    <details class="reveal group py-space-md" style="animation-delay: 0.08s">
                        <summary class="flex min-h-[44px] cursor-pointer list-none items-center justify-between font-label-md text-label-md text-on-surface">
                            Do I need special hardware to use it?
                            <span class="font-body-sm text-body-sm text-on-surface-variant transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                        </summary>
                        <p class="mt-space-xs max-w-xl font-body-sm text-body-sm text-on-surface-variant">No. It runs in a browser on any tablet, phone or computer you already have.</p>
                    </details>
                    <details class="reveal group py-space-md" style="animation-delay: 0.11s">
                        <summary class="flex min-h-[44px] cursor-pointer list-none items-center justify-between font-label-md text-label-md text-on-surface">
                            Do I need to talk to sales before I start?
                            <span class="font-body-sm text-body-sm text-on-surface-variant transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                        </summary>
                        <p class="mt-space-xs max-w-xl font-body-sm text-body-sm text-on-surface-variant">No. Create an account and you&rsquo;re in &mdash; no calls, no demos to sit through.</p>
                    </details>
                    <details class="reveal group py-space-md" style="animation-delay: 0.14s">
                        <summary class="flex min-h-[44px] cursor-pointer list-none items-center justify-between font-label-md text-label-md text-on-surface">
                            Does it work for one shop or multiple outlets?
                            <span class="font-body-sm text-body-sm text-on-surface-variant transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                        </summary>
                        <p class="mt-space-xs max-w-xl font-body-sm text-body-sm text-on-surface-variant">Built for single-outlet shops right now. Multi-outlet support is on the roadmap.</p>
                    </details>
                    <details class="reveal group py-space-md" style="animation-delay: 0.17s">
                        <summary class="flex min-h-[44px] cursor-pointer list-none items-center justify-between font-label-md text-label-md text-on-surface">
                            Is my sales data safe if I switch devices?
                            <span class="font-body-sm text-body-sm text-on-surface-variant transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                        </summary>
                        <p class="mt-space-xs max-w-xl font-body-sm text-body-sm text-on-surface-variant">Yes. Everything is stored on your account, not the device &mdash; log in anywhere and pick up where you left off.</p>
                    </details>
                    <details class="reveal group py-space-md" style="animation-delay: 0.2s">
                        <summary class="flex min-h-[44px] cursor-pointer list-none items-center justify-between font-label-md text-label-md text-on-surface">
                            Can I add more cashier accounts later?
                            <span class="font-body-sm text-body-sm text-on-surface-variant transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                        </summary>
                        <p class="mt-space-xs max-w-xl font-body-sm text-body-sm text-on-surface-variant">Yes, add as many cashier logins as you need, each with their own access.</p>
                    </details>
                </div>
            </div>
        </section>

        <!-- 9. Final CTA -->
        <section class="border-t border-outline-variant">
            <div class="mx-auto max-w-6xl px-gutter py-space-xl lg:px-space-xl lg:py-16">
                <div class="reveal max-w-xl" style="animation-delay: 0.05s">
                    <h2 class="text-balance font-headline-md text-headline-md text-on-surface tracking-[-0.02em]">Stop guessing what&rsquo;s in stock. Start knowing.</h2>
                    <div class="mt-space-lg flex flex-wrap items-center gap-x-space-lg gap-y-space-sm">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="flex min-h-[44px] items-center rounded bg-primary px-space-lg font-label-md text-label-md text-on-primary hover:bg-primary-container focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ Route::has('register') ? route('register') : route('login') }}" class="flex min-h-[44px] items-center rounded bg-primary px-space-lg font-label-md text-label-md text-on-primary hover:bg-primary-container focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-colors">
                                {{ Route::has('register') ? 'Create My Free Account' : 'Log In' }}
                            </a>
                        @endauth
                    </div>
                    <p class="mt-space-sm font-body-sm text-body-sm text-on-surface-variant">No setup fees. If it&rsquo;s not for you, walk away &mdash; nothing to cancel.</p>
                    <p class="mt-space-xs font-body-sm text-body-sm text-on-surface-variant">We onboard a limited number of new shops each month.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-outline-variant">
        <div class="mx-auto max-w-6xl px-gutter py-space-lg font-body-sm text-body-sm text-on-surface-variant lg:px-space-xl">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}
        </div>
    </footer>

    <style>
        @media (prefers-reduced-motion: no-preference) {
            .reveal {
                opacity: 0;
                transform: translateY(14px);
                animation: reveal 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            @keyframes reveal {
                to { opacity: 1; transform: translateY(0); }
            }
        }
    </style>

@endsection
