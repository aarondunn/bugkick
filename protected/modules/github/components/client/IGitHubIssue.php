<?php
/**
 *
 * @author f0t0n
 */
interface IGitHubIssue {

    const STATE_OPEN = 'open';
    const STATE_CLOSED = 'closed';
    const ASSIGNEE_NONE = 'none';
    const ASSIGNEE_ANY = '*';

    public function createIssue($fullRepoName, $title, $body = null,
        $assignee = null, $milestone = null, $labels = array());
    public function getIssue($fullRepoName, $number);
    public function editIssue($fullRepoName, $number, $title = null,
        $body = null, $assignee = null, $state = null, $milestone = null,
        $labels = array());
}