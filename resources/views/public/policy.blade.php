@extends('public.layouts.app')

@section('title', $policy->localized_title)

@section('content')
<style>
    .policy-shell {
        display: grid;
        gap: clamp(1.4rem, 3vw, 2.1rem);
    }
    .policy-hero {
        position: relative;
        overflow: hidden;
    }
    .policy-hero::after {
        content: "";
        position: absolute;
        width: min(36vw, 340px);
        aspect-ratio: 1;
        border-radius: 999px;
        right: -8%;
        bottom: -45%;
        background: radial-gradient(circle, rgba(185, 144, 104, .18), transparent 68%);
        pointer-events: none;
    }
    .policy-hero-copy {
        position: relative;
        z-index: 1;
        max-width: 64ch;
    }

    .policy-frame {
        max-width: 940px;
        margin-inline: auto;
        background: linear-gradient(180deg, #fffdfb 0%, #fffcf9 100%);
        border-radius: 24px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-soft);
        overflow: hidden;
    }
    .policy-frame-head {
        padding: 1.1rem clamp(1.1rem, 2.8vw, 1.7rem);
        border-bottom: 1px solid #ece0d4;
        background: linear-gradient(180deg, rgba(248, 239, 230, .7), rgba(255, 252, 248, .65));
    }
    .policy-frame-head p {
        margin: 0;
        color: #6f5f52;
        text-transform: uppercase;
        letter-spacing: .13em;
        font-size: .68rem;
        font-weight: 600;
    }

    .policy-content {
        margin: 0;
        padding: clamp(1.2rem, 3.5vw, 2.1rem) clamp(1.1rem, 3.5vw, 2.25rem) clamp(1.5rem, 3.9vw, 2.4rem);
        color: #4b3f35;
        font-size: 1.01rem;
        line-height: 1.92;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }
    .policy-content :is(h2, h3, h4) {
        margin: 1.45rem 0 .6rem;
        color: #2f2722;
        line-height: 1.35;
    }
    .policy-content h2 { font-size: clamp(1.24rem, 2.3vw, 1.46rem); }
    .policy-content h3 { font-size: clamp(1.08rem, 2vw, 1.2rem); }
    .policy-content p { margin: .9rem 0 0; }
    .policy-content ul,
    .policy-content ol {
        margin: .7rem 0 0;
        padding-left: 1.2rem;
    }
    .policy-content li + li { margin-top: .35rem; }

    @media (max-width: 768px) {
        .policy-content {
            font-size: .97rem;
            line-height: 1.82;
        }
    }
</style>

<section class="page-section policy-shell">
    <div class="page-hero policy-hero">
        <div class="policy-hero-copy">
            <p class="section-kicker">Policy</p>
            <h1 class="section-title">{{ $policy->localized_title }}</h1>
            <p class="muted" style="margin:0;">Please review this policy before your appointment to ensure a smooth and comfortable Skin by Noor experience.</p>
        </div>
    </div>

    <article class="policy-frame" aria-label="Policy details">
        <header class="policy-frame-head">
            <p>Skin by Noor Patient Policy</p>
        </header>
        <div class="policy-content">{!! nl2br(e($policy->localized_content)) !!}</div>
    </article>
</section>
@endsection
