<?php
/***
 * This job uploads ALL sql.gz backups available in Drush backups folder on
 * production into Amazon S3.
 * Adapted from an example by https://bitbucket.org/snippets/kaypro4/gnB4E.
 *
 * Usually it's triggered by cron automatically.
 * To trigger manually run the following command from home directory:
 * php scripts/upload_backups.php
 */
require '.global/vendor/autoload.php';
use Aws\S3\S3Client;
// Gets Amazon settings from Platform Variables (variables should be added to project/branch variables).
$aws_bucket = getenv('AWS_BACKUP_BUCKET') ? getenv('AWS_BACKUP_BUCKET') : '';
$aws_region = getenv('AWS_BACKUP_BUCKET_REGION') ? getenv('AWS_BACKUP_BUCKET_REGION') : '';
$aws_access_key_id = getenv('AWS_BACKUP_ACCESS_KEY_ID');
$aws_secret_access_key = getenv('AWS_BACKUP_SECRET_ACCESS_KEY');
// Skip script execution if any of AWS variables are empty.
if (empty($aws_bucket) || empty($aws_region) || empty($aws_access_key_id) || empty($aws_secret_access_key)) {
  echo "ERROR: some AWS variables are empty. Make sure AWS settings are added as Platform variables.\n";
  exit(1);
}
$backups_dir = getenv('HOME') . '/drush-backups';
$environment = $_ENV['PLATFORM_BRANCH'];
echo "== UPLOADING OF BACKUPS STARTED " . date('d.m.Y H:i:s') . " ==\n\n";
// Initialize Amazon connection.
$s3 = new S3Client([
  'version' => 'latest',
  'region' => $aws_region,
  // See api-platform-cw-backups user in Amazon IAM.
  'credentials' => [
    'key' => $aws_access_key_id,
    'secret' => $aws_secret_access_key,
  ]
]);
$psh = new Platformsh\ConfigReader\Config();
if (!$psh->isAvailable()) {
  echo "ERROR: Platform.sh configuration is not found.\n";
  exit(1);
}
foreach (glob("$backups_dir/main/*/*.sql.gz") as $filename) {
  // Path to file on Amazon.
  $aws_file_key = "$psh->project/$environment/" . basename($filename);
  try {
    if ($s3->doesObjectExist($aws_bucket, $aws_file_key)) {
      continue;
    }
    $aws_tags = 'type=daily';
    // If it's first day of month then mark this backup as monthly in S3.
    // It allows to apply different lifecycle rules to different types of backups.
    if (date('j') == '1') {
      $aws_tags = 'type=monthly';
    }
    // Push database backup to Amazon server.
    $result = $s3->putObject([
      'Bucket' => $aws_bucket,
      'Key' => $aws_file_key,
      'SourceFile' => $filename,
      'ServerSideEncryption' => 'AES256',
      'Tagging' => $aws_tags,
    ]);
    if ($result && $result['ObjectURL']) {
      echo "Success: " . $result['ObjectURL'] . "\n";
    }
    else {
      echo "ERROR: " . $filename . "\n";
    }
  }
  catch (Aws\S3\Exception\S3Exception $e) {
    echo "ERROR: " . $filename . "\n";
    echo $e->getMessage() . "\n";
  }
}
echo "== UPLOADING OF BACKUPS FINISHED " . date('d.m.Y H:i:s') . " ==\n\n";