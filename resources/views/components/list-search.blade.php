@props([
    'action',
    'value' => '',
    'placeholder' => 'Cari data...',
    'note' => '',
    'resetRoute' => null,
    'panel' => true,
])

<style>
    .list-search-panel {
        background: #FFFFFF;
        border: 1px solid rgba(17, 24, 39, 0.06);
        border-radius: 16px;
        padding: 1rem;
        margin: 0 0 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .list-search-panel > .page-toolbar,
    .list-search-panel > .page-intro,
    .list-search-panel > .page-top {
        margin-bottom: 0.85rem !important;
    }

    .list-search-panel .add-button {
        margin-bottom: 0 !important;
    }

    .list-search-form {
        display: grid;
        grid-template-columns: minmax(260px, 1fr) auto auto;
        align-items: center;
        gap: 0.65rem;
        width: 100%;
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        border-radius: 14px;
        padding: 0.75rem;
    }

    .list-search-input-wrap {
        position: relative;
        min-width: 0;
    }

    .list-search-icon {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: #6B7280;
        pointer-events: none;
    }

    .list-search-input {
        width: 100%;
        height: 46px;
        border: 1px solid #D1D5DB;
        border-radius: 10px;
        padding: 0 0.9rem 0 2.65rem;
        font: inherit;
        color: #111827;
        background: #FFFFFF;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .list-search-input:focus {
        border-color: #F8B803;
        box-shadow: 0 0 0 3px rgba(248, 184, 3, 0.18);
    }

    .list-search-button,
    .list-search-reset {
        height: 46px;
        border-radius: 10px;
        padding: 0 1.15rem;
        border: 1px solid transparent;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .list-search-button {
        background: #1F2937;
        color: #FFFFFF;
        box-shadow: 0 4px 10px rgba(17, 24, 39, 0.12);
    }

    .list-search-button:hover {
        background: #111827;
        transform: translateY(-1px);
    }

    .list-search-reset {
        background: #FFFFFF;
        border-color: #E5E7EB;
        color: #111827;
    }

    .list-search-reset:hover {
        background: #F9FAFB;
    }

    .list-search-note {
        grid-column: 1 / -1;
        color: #6B7280;
        font-size: 0.82rem;
        line-height: 1.45;
        padding-top: 0.2rem;
    }

    @media (max-width: 768px) {
        .list-search-form {
            grid-template-columns: 1fr;
        }

        .list-search-input-wrap,
        .list-search-button,
        .list-search-reset {
            width: 100%;
        }
    }
</style>

@if($panel)
    <div class="list-search-panel">
@endif
    <form action="{{ $action }}" method="GET" class="list-search-form" role="search">
        <div class="list-search-input-wrap">
            <i data-lucide="search" class="list-search-icon"></i>
            <input
                type="search"
                name="search"
                value="{{ $value }}"
                class="list-search-input"
                placeholder="{{ $placeholder }}"
                aria-label="Cari"
            >
        </div>
        <button type="submit" class="list-search-button">
            <i data-lucide="search"></i>
            <span>Cari</span>
        </button>
        @if($value !== '')
            <a href="{{ $resetRoute ?? $action }}" class="list-search-reset">
                <i data-lucide="x"></i>
                <span>Reset</span>
            </a>
        @endif
        @if($note !== '')
            <div class="list-search-note">{{ $note }}</div>
        @endif
    </form>
@if($panel)
    </div>
@endif
