#!/bin/bash
#
# production-guard.sh — PreToolUse (Bash) 安全ガード
# ─────────────────────────────────────────────────────
#  A) 常時ブロック : SSH / rsync / scp / 本番 wp-cli
#  B) 許可制       : FTP/FTPS アップロード（1回限り承認）
#  C) 常時OK       : curl/wget 読み取り / ローカル作業 / バックアップ
# ─────────────────────────────────────────────────────

INPUT=$(cat)
COMMAND=$(printf '%s' "$INPUT" | python3 -c "
import sys, json
try:
    print(json.load(sys.stdin).get('tool_input', {}).get('command', ''))
except:
    print('')
" 2>/dev/null)

# コマンドが取れなければ素通り
[ -z "$COMMAND" ] && exit 0

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APPROVAL_FILE="${SCRIPT_DIR}/.upload-approved"

# ======================================================================
# (A) 常時ブロック — 本番への直接操作
# ======================================================================
FORBIDDEN=(
  '(^|[;&|[:space:]])ssh[[:space:]]'        # SSH 接続
  '(^|[;&|[:space:]])rsync[[:space:]]'      # rsync 転送
  '(^|[;&|[:space:]])scp[[:space:]]'        # scp 転送
  'wp[[:space:]]+db[[:space:]]'             # wp db（本番DB操作）
  'wp[[:space:]].*--ssh='                   # wp-cli SSH経由
  'wp[[:space:]].*--url=.*sakura'           # wp-cli 本番URL指定
  'wp[[:space:]]+ai1wm[[:space:]]+restore'  # 本番上書き復元
)
FORBIDDEN_PATTERN=$(IFS='|'; echo "${FORBIDDEN[*]}")

if printf '%s' "$COMMAND" | grep -qiE "$FORBIDDEN_PATTERN"; then
  cat <<'EOF'
{
  "decision": "block",
  "reason": "🚫 本番環境への直接操作はブロックしました。\n\n禁止:\n  ・SSH / rsync / scp\n  ・本番DB への wp-cli\n  ・wp ai1wm restore\n\n必要な場合はゆうたさんご自身で実行してください。"
}
EOF
  exit 0
fi

# ======================================================================
# (B) 許可制 — FTP / FTPS アップロード
#     curl / wget は読み取りなので対象外
# ======================================================================
UPLOAD_PATTERN='(^|[;&|[:space:]])(ftp|lftp|sftp|ncftp)([[:space:]]|$)'

if printf '%s' "$COMMAND" | grep -qiE "$UPLOAD_PATTERN"; then
  if [ -f "${APPROVAL_FILE}" ]; then
    APPROVED_AT=$(cat "${APPROVAL_FILE}" 2>/dev/null)
    NOW=$(date +%s)
    if [ -n "${APPROVED_AT}" ] && [ $((NOW - APPROVED_AT)) -le 600 ]; then
      rm -f "${APPROVAL_FILE}"   # 1回使ったら失効
      exit 0
    fi
    rm -f "${APPROVAL_FILE}"     # 期限切れは掃除
  fi

  cat <<'EOF'
{
  "decision": "block",
  "reason": "🔐 本番へのアップロードには許可が必要です。\n\n許可の出し方:\n  ./.claude/hooks/upload-approve.sh\n\n有効期限: 10分 / 回数: 1回限り"
}
EOF
  exit 0
fi

# ======================================================================
# (C) 常時OK — ローカル作業 / 読み取り / バックアップ
# ======================================================================
exit 0
