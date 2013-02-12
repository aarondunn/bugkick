<?php

/**
 *
 * @author f0t0n
 */
interface IGitHubRepo {

    public function getCollaborator($fullRepoName, $login);
    public function getCollaborators($fullRepoName);
}