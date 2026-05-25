#!/bin/bash
#
# upload-approve.sh — 本番アップロードを1回だけ許可する
#
# 使い方:
#   ./.claude/hooks/upload-approve.sh
#
# 許可は10分以内・1回限り。使用後は自動失効。

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APPROVAL_FILE="${SCRIPT_DIR}/.upload-approved"

date +%s > "${APPROVAL_FILE}"

echo "============================================"
echo " ✅ 本番アップロードを1回分、許可しました。"
echo "============================================"
echo ""
echo " 有効期限: 10分以内"
echo " 有効回数: 1回（自動失効）"
echo ""
echo " 許可を取り消す場合:"
echo "   rm '${APPROVAL_FILE}'"
echo "============================================"
