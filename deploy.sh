#!/bin/bash
# =============================================================
# deploy.sh — Auto-deploy script for libros.javired.com
# Called via webhook or manually to pull latest from GitHub
# =============================================================

REPO_DIR="/home/yzibhssy/libros-repo"
DEPLOY_DIR="/home/yzibhssy/libros.javired.com"
GITHUB_REPO="https://github.com/Evolucion2022/libros.javired.com.git"
LOG_FILE="$DEPLOY_DIR/deploy.log"

echo "========================================" >> "$LOG_FILE"
echo "Deploy started at $(date)" >> "$LOG_FILE"

# 1. Pull latest from GitHub
if [ -d "$REPO_DIR/.git" ]; then
    cd "$REPO_DIR" && git pull origin main >> "$LOG_FILE" 2>&1
else
    git clone "$GITHUB_REPO" "$REPO_DIR" >> "$LOG_FILE" 2>&1
    cd "$REPO_DIR"
fi

# 2. Sync wp-content/themes
echo "Syncing themes..." >> "$LOG_FILE"
cp -rf "$REPO_DIR/wp-content/themes/"* "$DEPLOY_DIR/wp-content/themes/" 2>> "$LOG_FILE"

# 3. Sync wp-content/plugins
echo "Syncing plugins..." >> "$LOG_FILE"
cp -rf "$REPO_DIR/wp-content/plugins/"* "$DEPLOY_DIR/wp-content/plugins/" 2>> "$LOG_FILE"

# 4. Sync .htaccess
echo "Syncing .htaccess..." >> "$LOG_FILE"
cp -f "$REPO_DIR/.htaccess" "$DEPLOY_DIR/.htaccess" 2>> "$LOG_FILE"

# 5. Check if SQL changed and import
if [ -d "$REPO_DIR/database" ]; then
    SQL_FILE=$(find "$REPO_DIR/database/" -name "*.sql" -type f | sort -r | head -1)
    if [ -n "$SQL_FILE" ]; then
        CURRENT_HASH=$(md5sum "$SQL_FILE" 2>/dev/null | awk '{print $1}')
        STORED_HASH=""
        if [ -f "$DEPLOY_DIR/.last_sql_hash" ]; then
            STORED_HASH=$(cat "$DEPLOY_DIR/.last_sql_hash")
        fi
        if [ "$CURRENT_HASH" != "$STORED_HASH" ]; then
            echo "Database changed, importing: $SQL_FILE" >> "$LOG_FILE"
            mysql -u yzibhssy_libros1 -p'SPep0[81@9' yzibhssy_libros1 < "$SQL_FILE" 2>> "$LOG_FILE"
            echo "$CURRENT_HASH" > "$DEPLOY_DIR/.last_sql_hash"
            echo "Database import complete" >> "$LOG_FILE"
        else
            echo "Database unchanged, skipping import" >> "$LOG_FILE"
        fi
    fi
fi

echo "Deploy completed at $(date)" >> "$LOG_FILE"
echo "========================================" >> "$LOG_FILE"
