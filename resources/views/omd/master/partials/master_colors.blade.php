<style>
    /* Shared, scoped accents for the two OMD master pages. */
    .omd-master-accent {
        --master-surface: #ffffff;
        --master-soft: #f3f8f5;
        --master-card: #f8fbf9;
        --master-blue: #f0f5f9;
        --master-blue-text: #446681;
        --master-border: #dce8e0;
        --master-active: #e7f2eb;
        --master-green: #4d8065;
        --master-heading: #315840;
        --master-text: #283d31;
        --master-muted: #5c7062;
        --master-shadow: rgba(56, 89, 68, .06);
    }

    .card.omd-master-accent,
    .product-list-box.omd-master-accent {
        background: linear-gradient(150deg, var(--master-soft), var(--master-surface) 55%) !important;
        border-color: var(--master-border) !important;
    }

    .omd-master-accent :is(.plant-dashboard-header, .table-title, .table-header, .product-list-header) h4 {
        color: var(--master-heading) !important;
    }

    .omd-master-accent :is(.plant-card, .line-card) {
        background: linear-gradient(140deg, var(--master-card), var(--master-surface)) !important;
        border-color: var(--master-border) !important;
        box-shadow: 0 5px 16px var(--master-shadow) !important;
    }

    .omd-master-accent :is(.plant-card, .line-card):nth-child(even) {
        background: linear-gradient(140deg, var(--master-blue), var(--master-surface)) !important;
    }

    .omd-master-accent :is(.plant-card, .line-card):hover {
        border-color: #a7c7b0 !important;
    }

    .omd-master-accent :is(.plant-card, .line-card).active {
        background: linear-gradient(140deg, var(--master-active), var(--master-card)) !important;
        border-color: var(--master-green) !important;
        box-shadow: inset 3px 0 0 var(--master-green), 0 5px 16px var(--master-shadow) !important;
    }

    .omd-master-accent :is(.plant-icon, .line-icon, .empty-dashboard-icon) {
        background: var(--master-active) !important;
        color: var(--master-green) !important;
    }

    .omd-master-accent :is(.plant-card, .line-card):nth-child(even):not(.active) :is(.plant-icon, .line-icon) {
        background: var(--master-blue) !important;
        color: var(--master-blue-text) !important;
    }

    .omd-master-accent :is(.plant-card h4, .line-info h4, .model-name, .popup-product-name) {
        color: var(--master-text) !important;
    }

    .omd-master-accent :is(.plant-card p, .line-info span, .stat-box span, .product-list-header p) {
        color: var(--master-muted) !important;
    }

    .omd-master-accent .plant-manage,
    .omd-master-accent .product-list-header {
        border-color: var(--master-border) !important;
    }

    .omd-master-accent .line-stats .stat-box {
        background: var(--master-active) !important;
    }

    .omd-master-accent .line-stats .stat-box:nth-child(2) {
        background: var(--master-blue) !important;
    }

    .omd-master-accent .line-stats .stat-box strong {
        color: var(--master-heading) !important;
    }

    .omd-master-accent .line-stats .stat-box:nth-child(2) strong {
        color: var(--master-blue-text) !important;
    }

    .omd-master-accent :is(.product-count-badge, .product-list-count) {
        background: var(--master-blue) !important;
        color: var(--master-blue-text) !important;
    }

    .omd-master-accent table :is(thead, th) {
        background: var(--master-active) !important;
        color: var(--master-heading) !important;
        border-color: var(--master-border) !important;
    }

    .omd-master-accent table tbody tr:hover,
    .omd-master-accent table tbody tr:hover td {
        background: var(--master-soft) !important;
    }

    .omd-master-accent .popup-product-item {
        background: linear-gradient(120deg, var(--master-card), var(--master-surface)) !important;
        border-color: var(--master-border) !important;
    }

    .omd-master-accent .popup-product-item:hover {
        background: var(--master-soft) !important;
    }

    .omd-master-accent .inline-name input:focus {
        border-color: var(--master-green) !important;
        box-shadow: 0 0 0 3px var(--master-active) !important;
    }

    html[data-theme="dark"] .omd-master-accent {
        --master-surface: #18231d;
        --master-soft: #1c2c22;
        --master-card: #1e2e24;
        --master-blue: #202e36;
        --master-blue-text: #afc9da;
        --master-border: #34493c;
        --master-active: #294032;
        --master-green: #8eb19a;
        --master-heading: #c7dfce;
        --master-text: #e4eee7;
        --master-muted: #a4b6a9;
        --master-shadow: rgba(0, 0, 0, .12);
    }

    /* Keep the accents above the layout's global dark-theme overrides. */
    html[data-theme="dark"] :is(.card.omd-master-accent, .product-list-box.omd-master-accent) {
        background: linear-gradient(150deg, var(--master-soft), var(--master-surface) 55%) !important;
        border-color: var(--master-border) !important;
    }

    html[data-theme="dark"] .omd-master-accent :is(.plant-card, .line-card, .popup-product-item) {
        background: linear-gradient(140deg, var(--master-card), var(--master-surface)) !important;
        border-color: var(--master-border) !important;
    }

    html[data-theme="dark"] .omd-master-accent :is(.plant-card, .line-card):nth-child(even) {
        background: linear-gradient(140deg, var(--master-blue), var(--master-surface)) !important;
    }

    html[data-theme="dark"] .omd-master-accent :is(.plant-card, .line-card).active {
        background: linear-gradient(140deg, var(--master-active), var(--master-card)) !important;
        border-color: var(--master-green) !important;
    }

    html[data-theme="dark"] .omd-master-accent :is(.plant-card, .line-card):hover {
        border-color: var(--master-green) !important;
    }

    html[data-theme="dark"] .omd-master-accent .line-stats .stat-box {
        background: var(--master-active) !important;
    }

    html[data-theme="dark"] .omd-master-accent .line-stats .stat-box:nth-child(2) {
        background: var(--master-blue) !important;
    }

    html[data-theme="dark"] .omd-master-accent table :is(thead, th) {
        background: var(--master-active) !important;
        color: var(--master-heading) !important;
    }

    html[data-theme="dark"] .omd-master-accent table tbody tr:hover td,
    html[data-theme="dark"] .omd-master-accent .popup-product-item:hover {
        background: var(--master-soft) !important;
    }

    html[data-theme="dark"] .omd-master-accent :is(.master-delete, .popup-product-delete) {
        color: #a4b6a9;
    }

    html[data-theme="dark"] .omd-master-accent :is(.master-delete, .popup-product-delete):hover {
        color: #e4eee7;
    }
</style>
