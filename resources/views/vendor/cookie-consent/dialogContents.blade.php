<style>
    .cookie-consent-banner {
        position: fixed;
        bottom: 2rem;
        left: 2rem;
        z-index: 9999;
        width: calc(100% - 4rem);
        max-width: 420px;
        background: #1a1a1a;
        border: 1px solid #333;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    @media (max-width: 480px) {
        .cookie-consent-banner {
            bottom: 1rem;
            left: 1rem;
            width: calc(100% - 2rem);
            padding: 1.25rem;
        }
    }

    .cookie-consent-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: var(--font-display, 'Oswald', sans-serif);
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--white, #ffffff);
    }

    .cookie-consent-header svg {
        color: var(--gold, #c8a96e);
        width: 22px;
        height: 22px;
    }

    .cookie-consent-text {
        font-family: var(--font-body, 'Barlow', sans-serif);
        font-size: 0.95rem;
        line-height: 1.5;
        color: var(--silver, #d1d5db);
    }

    .cookie-consent-actions {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .cookie-btn {
        flex: 1;
        font-family: var(--font-display, 'Oswald', sans-serif);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        text-align: center;
    }

    .cookie-btn-accept {
        background: var(--gold, #c8a96e);
        color: var(--bg, #1a1a1a);
    }

    .cookie-btn-accept:hover {
        background: var(--white, #ffffff);
    }

    .cookie-btn-decline {
        background: transparent;
        color: var(--white, #ffffff);
        border: 1px solid #444;
    }

    .cookie-btn-decline:hover {
        background: #333;
    }
</style>

<div class="js-cookie-consent cookie-consent-banner">
    <div class="cookie-consent-header">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        Your Privacy
    </div>
    <div class="cookie-consent-text cookie-consent__message">
        {!! trans('cookie-consent::texts.message') !!}
    </div>
    <div class="cookie-consent-actions">
        <button class="cookie-btn cookie-btn-decline" onclick="window.laravelCookieConsent.hideCookieDialog()">
            Decline
        </button>
        <button class="js-cookie-consent-agree cookie-consent__agree cookie-btn cookie-btn-accept">
            {!! trans('cookie-consent::texts.agree') !!}
        </button>
    </div>
</div>
