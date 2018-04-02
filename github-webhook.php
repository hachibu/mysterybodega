<?php
require __DIR__ . '/vendor/autoload.php';

use GitHubWebhook\Handler;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$gh_webhook_secret = getenv('GH_WEBHOOK_SECRET');

if (empty($gh_webhook_secret)) {
  echo('GH_WEBHOOK_SECRET environment variable not set' . "\n");
  exit(1);
}

$git_path = `which git`;

if (empty($git_path)) {
  echo('git not found on path' . "\n");
  exit(1);
}

$git_branch = trim(shell_exec('git rev-parse --abbrev-ref HEAD'));

if ($git_branch != 'master') {
  echo('git branch is not master: ' . $git_branch . "\n");
  exit(1);
}

$request = new Handler($gh_webhook_secret, __DIR__);

if ($request->validate()) {
  $event = $request->getEvent();

  if ($event == 'push') {
    echo(shell_exec('git pull --rebase'));
  }
} else {
  echo('request is invalid' . "\n");
  exit(1);
}
?>
