### 27/05/2025

I initially used Laravel Herd for local development, which simplified setup by managing PHP, environment variables, and providing a `.test` domain. However, Google and YouTube OAuth APIs require a top-level domain, and `.test` doesn’t qualify, blocking full OAuth integration. To solve this, today I moved to a standard Laravel installation with Vite. I now run `php -S 0.0.0.0:80 -t public` for the backend and `npm run dev` for the frontend. I updated `vite.config.ts` to proxy requests from port `5173` to `80`, and added `127.0.0.1 curatio.com` to my Windows hosts file. With the project now accessible at `curatio.com`, I will be testing tomorrow if OAuth with Google works as expected.

### 28/05/2025

I ran into an `cURL error 60: SSL peer certificate or SSH remote key was not OK` error while integrating Google's and YouTube's APIs via OAuth, caused by my local server not trusting Google's SSL certificate. OAuth itself was also more complicated than expected - especially around token handling and my controller logic becoming messy and hard to manage. As a temporary fix, I disabled SSL verification in Guzzle for local development, but I plan to conditionally disable it in production and staging for security. I used the `google/apiclient` package in Laravel to get a basic OAuth flow working, although I’ve had simpler experiences doing this in NodeJS. Next, I’ll persist tokens outside of Laravel sessions so I can run scheduled jobs to fetch and update videos. I also plan to refactor the code using repositories and DTOs to clean up the controller logic and improve maintainability.

### 29/05/2025

The codebase was getting messy, with too much logic crammed into the controllers - especially the Module controller - which made things hard to manage. Since controllers should stay lean, I refactored the structure by introducing a `ModuleRepository`, a `DTO`, a `ModuleQueryService` for handling GET requests, and my new favourite a `ModuleScopes` file. This cleaned up the controller significantly and made the logic easier to follow. The frontend still works well with these changes, though I plan to improve it further in the coming days. There are still a few outstanding tasks marked with TODOs, including fixing an `N+1` query issue related to the Module model’s status and adding missing function annotations. My priority is to get the codebase into a clean, understandable state before moving on to new features.

### 01/06/2025

Installed PHPStan with level 7 and ran it across the codebase - some errors showed up, which I’ll address later. Continued the refactoring, and the module is almost complete now. I added actions, finalized the repositories, and will review the full implementation next to ensure everything is working as expected. Also added annotations throughout the module code to align with Laravel best practices.

### 02/06/2025

Didn't have much time today but still made some small progress. I started fixing some of the PHPStan errors and integrated the YouTube OAuth functionality into one component and one function. Over the next few days, I plan to extract the YouTube and Courses logic into dedicated services to better integrate them with the rest of the project. I also ran into a strange issue with route splitting - when using `require __DIR__` with a path, Inertia routes stopped working. For now, I reverted everything back to a single routes file. Interestingly, after checking some well-known Laravel + Inertia repositories on GitHub, most of them also keep their routes in a single file.

### 03/06/2025

Split the `YoutubeController` into two services: `YoutubeAuthService` and `YoutubeConnectionHandler`, which brings proper separation of concerns which was a solid improvement. I still need to add function annotations. On the frontend, I fixed some toast and flash message logic and resolved an issue where users navigating to a non-existent page would now be redirected to the first page with a flash message. Next, I want to improve error handling by passing error messages via flash, handling them properly in the frontend and adding validation using Vuelidate. I also installed Prettier and integrated the PHPStan command into the GitHub Workflows for continuous static analysis.

### 04/06/2025

Didn't have much time today but managed to install Laravel Telescope to monitor incoming requests when users navigate the dashboard. It's a great tool for inspecting requests, jobs, queues, and more, and I have limited access to the local environment only. I'm also experimenting with showing flash messages for validation errors and making sure they’re handled correctly by Inertia on the frontend. At the moment, it looks like Inertia's `onError` function only has access to the `errors` property, but not the `flash` property in the response, which might require a different approach for passing flash messages alongside validation feedback.

### 05/06/2025

Added exception and error handling using Inertia’s `errorBag`. I ran into an issue where error messages passed through the `errorBag` could only be of type `string`, not arrays which was a problem since I wanted to pass more structured data based on specific validation issues. I eventually found a clean way to work around it using formatted strings, which still keeps things readable. I'm happy with the current error handling setup on the Laravel side; the next step is to catch global exceptions and either redirect to a 500 page with an error message or show a toast on the current page. I also explored Inertia’s meta tag handling and added a global `MetaTags` component so we can dynamically set the browser tab title per route.

### 06/06/2025

Short day today: made some minor changes and did a general cleanup including moved some logic into separate traits for better structure. Also ran a few Pest tests to experiment with the setup, which I'll continue working on later. Did a `php artisan migrate:refresh` to ensure everything runs smoothly, and I'm considering creating a factory soon to generate some dummy data for easier testing.
