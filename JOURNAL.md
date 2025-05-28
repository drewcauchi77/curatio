### 27/05/2025

#### Issue

Initially, I used Laravel Herd, which automatically managed the local environment. Laravel Herd provided a local `.test` domain, but Google and YouTube APIs require a top-level domain for OAuth. Because `.test` domains are not considered top-level, I couldn't fully implement Google's OAuth process. Laravel Herd also handled PHP setup, environment variables, and paths automatically.

#### Solution

I transitioned from Laravel Herd to a standard Laravel installation with Vite. To maintain hot-reloading functionality, I modified the Vite configuration. Previously, Laravel Herd managed the php artisan serve command, and I only needed to run Vite to compile frontend changes. Now, I run two commands separately:
 - `php -S 0.0.0.0:80 -t public` to launch the PHP server on port `80`.
 - `npm run dev` to compile the frontend on port `5173`.
 
I adjusted `vite.config.ts` to handle requests seamlessly from port `5173` to port `80`. Additionally, I edited my Windows hosts file by adding `127.0.0.1 curatio.com`. My local project now runs successfully at `curatio.com`, meeting Google's API requirements for a top-level domain.

### 28/05/2025

#### Issue

I wanted to integrate Google's and YouTube's APIs via OAuth to fetch YouTube videos. However, I encountered an SSL/SSH issue: `cURL error 60: SSL peer certificate or SSH remote key was not OK`. This error appeared because Google's endpoint uses a certificate my local server did not trust. Additionally, I struggled with OAuth implementation, specifically around receiving and managing tokens. The process was more complex than expected. I'm also not satisfied with my code structure. My controllers handle too much logic, making the code difficult to manage.

#### Solution

For now, I temporarily bypassed SSL verification in `Guzzle` for Google's API requests. Later, I'll refactor this by adding environment-specific logic. In development and production environments, SSL verification will remain enabled for security reasons. I've previously implemented OAuth with Google's API using NodeJS, which was simpler. For Laravel, I used the `google/apiclient` package to create a basic working solution. In the next phase, I'll persist OAuth tokens outside Laravel's session. This approach will allow cron jobs to regularly fetch videos and update modules. To improve code readability and maintainability, I'll soon introduce repositories and DTOs to better separate responsibilities.