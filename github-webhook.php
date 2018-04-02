<?php
require __DIR__ . '/vendor/autoload.php';

use GitHubWebhook\Handler;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

function write_log($message) {
  $log_directory = 'logs';
  $file_path = $log_directory . '/github-webhook.log';

  if (!is_dir($log_directory)) {
    mkdir($log_directory, 0755);
  }

  if (!is_file($file_path)) {
    $file_handle = fopen($file_path, 'w');
    fclose($file_handle);
  }

  $message = gmdate('[Y-m-d H:i:s]') . ' ' . $message . "\n";

  echo($message);
  error_log($message, 3, $file_path);
}

$gh_webhook_secret = getenv('GH_WEBHOOK_SECRET');

if (empty($gh_webhook_secret)) {
  write_log('GH_WEBHOOK_SECRET environment variable not set');
  exit(1);
}

$git_path = `which git`;

if (empty($git_path)) {
  write_log('git not found on path');
  exit(1);
}

$git_branch = trim(shell_exec('git rev-parse --abbrev-ref HEAD'));

if ($git_branch != 'master') {
  write_log('git branch is not master: ' . $git_branch);
  exit(1);
}

$request = new Handler($gh_webhook_secret, __DIR__);

if ($request->validate()) {
  $event = $request->getEvent();

  if ($event == 'push') {
    write_log(shell_exec('git pull --rebase'));
  }
} else {
  write_log('request is invalid');
  exit(1);
}
?>
