Using the GitHub CLI to create repository secrets
===============================================

This guide shows how to create the secrets required by the deploy workflow using the GitHub CLI (`gh`).

Prerequisites
- Install GitHub CLI: https://cli.github.com/
- Authenticate: `gh auth login`

Create secrets example

Replace placeholders and run:

```bash
REPO="owner/repo" # e.g. zarinjas/cbacademy

# SSH host and user
gh secret set SSH_HOST --repo "$REPO" --body "203.0.113.45"
gh secret set SSH_USER --repo "$REPO" --body "deploy"

# SSH private key (contents of private key file)
cat ~/.ssh/deploy_key | gh secret set SSH_PRIVATE_KEY --repo "$REPO"

# Remote path
gh secret set REMOTE_PATH --repo "$REPO" --body "/var/www/cbacademy"

# Optional: port
gh secret set SSH_PORT --repo "$REPO" --body "22"

# Optional: base64-encoded .env
base64 -w0 .env | gh secret set PROD_ENV_FILE --repo "$REPO"
```

Security note: keep the private key secure. Consider using a deploy key with limited permissions and restricting the SSH user.
