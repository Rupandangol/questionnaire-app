# Questionnaire App

Simple questionnaire app

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Testing](#testing)
- [Admin Credentials](#admin-credentials)

## Installation

Follow these steps to set up the project using Laravel Sail:

1. Clone the repository:

   ```bash
   git clone https://github.com/Rupandangol/questionnaire-app.git
   cd questionnaire-app
2. Copy the example environment file and modify the necessary       environment variables:
    ```bash
    cp .env.example .env
3. Install the dependencies:

    ```bash
    composer install
4. Start the Laravel Sail environment:
    ```bash
    ./vendor/bin/sail up
5. Run the database migrations:

    ```bash
    ./vendor/bin/sail artisan migrate
6. Seed the database (if applicable):

    ```bash
    ./vendor/bin/sail artisan db:seed
7. Build frontend assets:
    ```bash
    npm run dev
## Usage
- Ensure the Laravel Sail environment is running:
    ```bash
    ./vendor/bin/sail up

- You can then access the application at http://localhost.
## Testing
- To run the tests, use the following command:

    ```bash
    ./vendor/bin/sail artisan test  
## Admin Credentials
- You can use the following credentials to log in as an admin:

    ```bash
        Email: admin@test.com
        Password: password
