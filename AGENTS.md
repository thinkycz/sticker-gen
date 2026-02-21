# Agent Reference Sheet (Sticker Sheet App)

This file is a reference document generated for AI coding assistants to quickly understand the project's structure, goals, and technological choices.

## Project Overview
**Sticker Sheet App** is a minimalist web application for creating, designing, and printing sticker sheets. It features a layout builder (setup), a drag-and-drop builder for sticker elements (designer), and an optimized print preview.

## Tech Stack
-   **Backend**: Laravel 12 (PHP ^8.2)
-   **Frontend**: Vue 3 (Composition API / `<script setup>`) + Inertia.js v2
-   **Styling**: Tailwind CSS v4 (configured via Vite)
-   **Database**: SQLite (`database/database.sqlite`)
-   **Routing**: Ziggy for Vue routing, defined in `routes/web.php`
-   **Build Tool**: Vite
-   **Key Libraries**: `bwip-js` (Barcode Generation), `@inertiajs/vue3`, `axios`, `laravel/breeze` (Authentication)

## Key Entities & Models (`app/Models/`)
1.  **User**: Standard authentication model via Laravel Breeze.
2.  **StickerSheet**: Represents an active sticker sheet layout being designed (dimensions, elements).
3.  **SheetConfig**: Represents saved configurations for paper sizes, grid layouts, gaps, and margins.

## Key Controllers (`app/Http/Controllers/`)
-   **StickerSheetController**: Handles the core workflow (`setup`, `designer`, `preview`, duplicates, and deletions).
-   **SheetConfigController**: Handles storing and deleting layouts.
-   **ProfileController**: User profile management.

## Frontend Structure (`resources/js/`)
-   **Pages/**: Inertia page components:
    -   `Dashboard.vue`: User's saved sheets and dashboard.
    -   `Setup.vue`: Define paper size, sticker size, grid layout, and margins.
    -   `Designer.vue`: Drag-and-drop canvas to add elements (Text, Barcode) to a template.
    -   `Preview.vue`: Full sheet print preview with optimized CSS for the browser's native print dialog.
    -   `Welcome.vue`: Landing page.

## File System Notes
-   There is no `routes/api.php` as this is an Inertia monolith. All routes are located in `routes/web.php`.
-   API calls from the frontend should follow Inertia's `router` visits or use standard `axios` pointing to `web.php` routes.
-   When creating or modifying styling, rely on Tailwind CSS v4 classes directly.

## Development Workflow
If changes need to be made or verified:
1.  **Run Server**: `php artisan serve`
2.  **Run Assets**: `npm run dev`
3.  **Database**: `php artisan migrate`

## Design Directives
-   Always use **Composition API (`<script setup>`)** for new Vue Components.
-   Strive for **vibrant, modern aesthetics** as noted in core AI instructions (this app relies heavily on interactive Canvas/Layout and live previews).
-   Ensure **print styles** (`@media print`) are respected and maintained when editing the `Preview.vue` or related styles, as printing is a core feature.
