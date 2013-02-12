<?php
/**
 * GitHubAuth
 *
 * @author f0t0n
 */
class GitHubAuth {

    const GITHUB_ACCESS_SCOPE = 'repo,gist';

    const GITHUB_AUTH_URL = 'https://github.com/login/oauth/authorize';
    const GITHUB_ACCESS_TOKEN_URL =
        'https://github.com/login/oauth/access_token';

    const PROJECT_ID_SESS_KEY = 'github_integration_project_id';
    const RETURN_URL_SESS_KEY = 'github_integration_return_url';

    const AUTH_MESSAGE_SUCCESS = 'GitHub authentication complete.';
    const AUTH_MESSAGE_FAIL = 'GitHub authentication failed.';
}