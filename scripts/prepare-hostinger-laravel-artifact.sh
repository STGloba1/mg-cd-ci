#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUTPUT_DIR="${1:-${ROOT_DIR}/deploy-artifacts/minutes-generator}"

if [[ ! -f "${ROOT_DIR}/artisan" ]]; then
  echo "Missing Laravel artisan file at ${ROOT_DIR}/artisan." >&2
  exit 1
fi

if [[ ! -f "${ROOT_DIR}/public/index.php" ]]; then
  echo "Missing Laravel front controller at ${ROOT_DIR}/public/index.php." >&2
  exit 1
fi

if [[ ! -d "${ROOT_DIR}/vendor" ]]; then
  echo "Missing vendor directory. Run composer install before preparing the artifact." >&2
  exit 1
fi

rm -rf "${OUTPUT_DIR}"
mkdir -p "${OUTPUT_DIR}"

rsync -a --delete \
  --exclude '.git/' \
  --exclude '.github/' \
  --exclude '.env' \
  --exclude '.env.*' \
  --exclude 'composer.json' \
  --exclude 'composer.lock' \
  --exclude 'deploy-artifacts/' \
  --exclude 'node_modules/' \
  --exclude 'tests/' \
  --exclude '.phpunit.result.cache' \
  --exclude 'storage/logs/*.log' \
  "${ROOT_DIR}/" "${OUTPUT_DIR}/"

mkdir -p \
  "${OUTPUT_DIR}/storage/app/private" \
  "${OUTPUT_DIR}/storage/app/public" \
  "${OUTPUT_DIR}/storage/framework/cache" \
  "${OUTPUT_DIR}/storage/framework/sessions" \
  "${OUTPUT_DIR}/storage/framework/views" \
  "${OUTPUT_DIR}/storage/logs" \
  "${OUTPUT_DIR}/bootstrap/cache"

touch \
  "${OUTPUT_DIR}/storage/app/private/.gitkeep" \
  "${OUTPUT_DIR}/storage/app/public/.gitkeep" \
  "${OUTPUT_DIR}/storage/framework/cache/.gitkeep" \
  "${OUTPUT_DIR}/storage/framework/sessions/.gitkeep" \
  "${OUTPUT_DIR}/storage/framework/views/.gitkeep" \
  "${OUTPUT_DIR}/storage/logs/.gitkeep" \
  "${OUTPUT_DIR}/bootstrap/cache/.gitkeep"

if find "${OUTPUT_DIR}" \
  -name '.env' \
  -o -name '.env.*' \
  -o -path '*/node_modules/*' \
  -o -path '*/tests/*' \
  -o -path '*/.git/*' \
  | grep -q .; then
  echo "Laravel artifact contains forbidden files." >&2
  exit 1
fi

printf 'Prepared Hostinger Laravel artifact at %s\n' "${OUTPUT_DIR}"
