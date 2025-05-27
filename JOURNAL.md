### 27/05/2025

#### Issue

Initially, I used Laravel Herd, which automatically managed the local environment. Laravel Herd provided a local `.test` domain, but Google and YouTube APIs require a top-level domain for OAuth. Because `.test` domains are not considered top-level, I couldn't fully implement Google's OAuth process. Laravel Herd also handled PHP setup, environment variables, and paths automatically.

#### Solution

I transitioned from Laravel Herd to a standard Laravel installation with Vite. To maintain hot-reloading functionality, I modified the Vite configuration. Previously, Laravel Herd managed the php artisan serve command, and I only needed to run Vite to compile frontend changes. Now, I run two commands separately:
 - `php -S 0.0.0.0:80 -t public` to launch the PHP server on port `80`.
 - `npm run dev` to compile the frontend on port `5173`.
I adjusted `vite.config.ts` to handle requests seamlessly from port `5173` to port `80`. Additionally, I edited my Windows hosts file by adding `127.0.0.1 curatio.com`. My local project now runs successfully at `curatio.com`, meeting Google's API requirements for a top-level domain.