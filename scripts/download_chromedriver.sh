#!/usr/bin/env bash
# download_chromedriver.sh
# Usage: bash scripts/download_chromedriver.sh <download_url> [output_path]
# Example: bash scripts/download_chromedriver.sh "https://edgedl.me.gvt1.com/edgedl/chrome/chrome-for-testing/114.0.5735.90/linux64/chrome-linux64.zip" tools/chromedriver

set -euo pipefail

URL="$1"
OUT_DIR="${2:-tools/chromedriver}"

mkdir -p "$OUT_DIR"
TMPZIP="$OUT_DIR/chrome-for-testing.zip"

echo "Downloading Chrome for Testing from: $URL"

if command -v curl >/dev/null 2>&1; then
  curl -L --fail -o "$TMPZIP" "$URL"
elif command -v wget >/dev/null 2>&1; then
  wget -O "$TMPZIP" "$URL"
else
  echo "Neither curl nor wget found. Install one to use this script." >&2
  exit 2
fi

echo "Extracting to $OUT_DIR"
if command -v unzip >/dev/null 2>&1; then
  unzip -o "$TMPZIP" -d "$OUT_DIR"
else
  echo "unzip not found. Trying native tar (works if zip is actually tar).")
  tar -xf "$TMPZIP" -C "$OUT_DIR" || true
fi

rm -f "$TMPZIP"

# Find chrome driver binary
if [ -f "$OUT_DIR/chrome-linux64/chrome-linux64" ]; then
  echo "Found chrome binary at chrome-linux64/chrome-linux64"
fi

# Attempt to find chromedriver
CHROME_DRIVER_PATH=""
if [ -f "$OUT_DIR/chromedriver" ]; then
  CHROME_DRIVER_PATH="$OUT_DIR/chromedriver"
else
  # search common locations inside extracted folder
  CHROME_DRIVER_PATH=$(find "$OUT_DIR" -type f -iname "chromedriver*" | head -n 1 || true)
fi

if [ -n "$CHROME_DRIVER_PATH" ]; then
  chmod +x "$CHROME_DRIVER_PATH" || true
  echo "Chromedriver installed at: $CHROME_DRIVER_PATH"
  echo "Set DUSK_CHROME_DRIVER_PATH to that path in your .env or environment variables."
else
  echo "Chromedriver binary not found automatically. Inspect $OUT_DIR to locate chromedriver." >&2
  exit 3
fi
