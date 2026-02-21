# Sticker Sheet App

A minimalist web application for creating and printing sticker sheets.

## Features
- **Setup Screen**: Define paper size (A4, Letter, Custom), sticker size, grid layout, gaps, and margins. Live validation and preview.
- **Designer Screen**: Drag-and-drop text and barcode elements onto the sticker canvas.
- **Preview & Print Screen**: View the full sheet layout and print directly using browser print dialog (with optimized CSS print styles).

## Tech Stack
- **Framework**: Laravel 11
- **Frontend**: Vue 3 + Inertia.js
- **Styling**: Tailwind CSS v4
- **Barcode Generation**: bwip-js

## Installation & Setup

1.  **Install Dependencies**:
    ```bash
    composer install
    npm install
    ```

2.  **Setup Database**:
    The app uses SQLite by default. Run migrations:
    ```bash
    php artisan migrate
    ```

3.  **Build Assets**:
    ```bash
    npm run build
    ```
    Or for development:
    ```bash
    npm run dev
    ```

4.  **Serve Application**:
    ```bash
    php artisan serve
    ```

5.  **Access App**:
    Open `http://127.0.0.1:8000` in your browser.

## Workflow
1.  **Setup**: Configure your sheet layout. Ensure grid fits within paper dimensions.
2.  **Design**: Add elements (Text, Barcode) to the sticker template. Position them on the canvas.
3.  **Print**: Preview the full sheet and print. Make sure to set "Scale" to "100%" in print settings.
