Deploying with GitHub Actions (SSH)
=================================

This repository includes a simple GitHub Actions workflow at
`.github/workflows/deploy.yml` that syncs the repository to a remote server
and installs the nginx snippet for protected video delivery.

Secrets required (add in GitHub repo settings -> Secrets):
- SSH_HOST: server host or IP
- SSH_USER: ssh username
- SSH_PRIVATE_KEY: private key content (no passphrase recommended for CI)
- REMOTE_PATH: remote directory where the app lives (e.g. /var/www/app)
Optional:
- SSH_PORT (defaults to 22)
- PROD_ENV_FILE: base64-encoded content of your production `.env` (if you want
  the workflow to write .env automatically)

How it works
------------
1. When you push to `main`, the workflow rsyncs the repo to the remote `REMOTE_PATH`.
2. It copies `docs/nginx/protected_videos.conf` to the server and places it under
   `/etc/nginx/snippets/` (or `/etc/nginx/conf.d/`).
3. It runs `nginx -t` and reloads nginx.
4. Optionally writes the `.env` and restarts PHP-FPM.

Security note
-------------
Keep the private key secret. Using a deploy key with limited permissions is
recommended. If you prefer CI credentials with more control, use your CI/CD
secrets and a dedicated user on the server.
