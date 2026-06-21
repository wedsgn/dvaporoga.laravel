<style>
    .admin-related-section + .admin-related-section {
        margin-top: 1.5rem;
    }

    .admin-related-card {
        background: #21262d;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 1rem;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.16);
        height: 100%;
        overflow: hidden;
    }

    .admin-related-card--dirty {
        border-color: rgba(255, 193, 7, 0.45);
        box-shadow: 0 0 0 1px rgba(255, 193, 7, 0.12), 0 10px 24px rgba(0, 0, 0, 0.16);
    }

    .admin-related-card__image {
        align-items: center;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.01));
        display: flex;
        height: 220px;
        justify-content: center;
        overflow: hidden;
        padding: 1rem;
    }

    .admin-related-card__image img {
        height: 100%;
        max-width: 100%;
        object-fit: contain;
        width: 100%;
    }

    .admin-related-card__placeholder {
        align-items: center;
        color: #9aa4af;
        display: flex;
        font-size: 0.95rem;
        font-weight: 500;
        height: 100%;
        justify-content: center;
        text-align: center;
        width: 100%;
    }

    .admin-related-card__body {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        padding: 1.1rem 1.1rem 1.25rem;
    }

    .admin-related-card__title {
        color: #f6f7fb;
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.35;
        margin: 0;
    }

    .admin-related-card__meta {
        color: #98a6b5;
        font-size: 0.875rem;
        line-height: 1.5;
        margin: 0;
    }

    .admin-related-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }

    .admin-related-form-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }

    .admin-related-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .admin-related-topbar {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .admin-related-topbar__note {
        color: #98a6b5;
        font-size: 0.9rem;
        margin: 0;
    }

    .admin-related-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .admin-related-toolbar {
        align-items: end;
        background: rgba(255, 255, 255, 0.02);
        border: 1px dashed rgba(255, 255, 255, 0.08);
        border-radius: 1rem;
        display: grid;
        gap: 1rem;
        grid-template-columns: minmax(280px, 1.2fr) minmax(0, 1fr);
        margin-bottom: 1rem;
        padding: 1rem;
    }

    .admin-related-toolbar__label {
        color: #f6f7fb;
        display: block;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .admin-related-empty {
        background: rgba(255, 255, 255, 0.03);
        border: 1px dashed rgba(255, 255, 255, 0.08);
        border-radius: 1rem;
        color: #9aa4af;
        padding: 1.25rem;
        text-align: center;
    }

    .admin-generation-group + .admin-generation-group {
        margin-top: 1.5rem;
    }

    .admin-generation-group__head {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .admin-generation-group__title {
        color: #f6f7fb;
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0;
    }

    .admin-related-info-list {
        display: grid;
        gap: 0.5rem;
    }

    .admin-related-info-item {
        align-items: center;
        color: #b4c0cc;
        display: flex;
        font-size: 0.88rem;
        gap: 0.5rem;
    }

    .admin-related-info-item i {
        color: #7f8b97;
    }

    .admin-related-help {
        color: #98a6b5;
        font-size: 0.85rem;
        line-height: 1.45;
        margin: 0;
    }

    .admin-related-pending-note {
        color: #f7c948;
        font-size: 0.85rem;
        line-height: 1.4;
        margin-top: 0.5rem;
    }

    @media (max-width: 767.98px) {
        .admin-related-toolbar {
            grid-template-columns: 1fr;
        }

        .admin-related-card__image {
            height: 190px;
        }
    }
</style>
