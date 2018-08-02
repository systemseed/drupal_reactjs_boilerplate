#!/usr/bin/env bash

echo "== DB DUMP STARTED $(date +"%d.%m.%Y %T") =="
time drush sql-dump -r web --gzip --result-file -v

echo "Removing older dumps..."

# Move to the backups folder.
cd "$HOME/drush-backups/main"

# List all backups | find total number of backups | decrease by 1.
NUMBER_OF_FOLDERS_TO_DEL=$(ls -1q | wc -l | awk '{print $0-1}')

if [ "$NUMBER_OF_FOLDERS_TO_DEL" -gt 0 ]; then
  echo "Going to delete $NUMBER_OF_FOLDERS_TO_DEL old backups.."

  # List all backups | sort by name DESC | print last NUMBER_OF_FOLDERS_TO_DEL backups only | remove them.
  ls -1q | sort -nr | tail --lines=$NUMBER_OF_FOLDERS_TO_DEL | xargs rm -rfv
fi;

echo "== DB DUMP FINISHED $(date +"%d.%m.%Y %T") =="
