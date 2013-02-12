<?php
/**
 *
 * @author f0t0n
 */
interface IGitHubIssueComment {
    public function createComment($fullRepoName, $issueNumber, $body);
}