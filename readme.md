# My Admin Bar Customizer

A minimalist yet robust WordPress plugin that allows you to easily hide specific items from the admin bar and automatically shortens long site names for a cleaner workspace.

---

## Key Features

- **Dynamic Item Detection**: Automatically detects all admin bar items, including those from third-party plugins (e.g., WP Fastest Cache, SEO tools).
- **Rock-Solid Persistence**: Custom logic prevents settings from disappearing during save—a common issue with dynamic admin bar menus.
- **Instant Synchronization**: Changes reflect immediately after saving, thanks to advanced hook priority and redirection logic.
- **Smart Site Name Truncation**: Automatically shortens long site titles (default: 15 characters) to prevent toolbar layout breaking.
- **Automatic Updates**: Integrated with GitHub, allowing you to receive update notifications directly in your WordPress dashboard.
- **Performance Focused**: Minimal database impact using lightweight options instead of heavy meta-data.

## Installation

1. Upload the `my-admin-bar-customizer` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Settings > 管理バー設定 (Admin Bar Settings)** to configure your preferences.
4. *Tip: If a specific item isn't listed, visit your site's front-end once to trigger the auto-detection.*

---

## 主な機能（日本語）

WordPress管理バーの不要な項目を安全に非表示にし、長すぎるサイト名を自動で省略する、安定性と軽量さにこだわったプラグインです。

- **高度な項目検知**: 標準メニューに加え、外部プラグインが追加した項目も自動でリスト化します。
- **データ消失防止ロジック**: 動的なメニュー保存時に発生しがちな「一覧が消える」問題を、独自の先行保存プロセスで完全に解決しています。
- **即時反映**: 保存ボタンをクリックした瞬間に管理バーの表示が同期されます（リロード不要）。
- **サイト名の自動省略**: サイト名が長い場合に自動で15文字に省略し、管理バーの表示崩れを防ぎます。
- **GitHub自動更新対応**: GitHubのリポジトリと連動し、管理画面から標準プラグイン同様にアップデート通知を受け取れます。
- **パフォーマンス最適化**: 余計なスクリプトを読み込まず、サーバーのメモリ制限が厳しい環境でも軽快に動作します。

## インストール・設定

1. `my-admin-bar-customizer` フォルダを `/wp-content/plugins/` にアップロードします。
2. 管理画面の「プラグイン」から有効化してください。
3. **「設定」 > 「管理バー設定」**から、非表示にしたい項目を選択して「変更を保存」をクリックしてください。
    - ※項目が表示されない場合は、一度サイトのフロントページ（公開側）を表示してから設定画面をリロードしてください。

## 開発者情報
- **Author**: masato shibuya (Image-box Co., Ltd.)
- **Version**: 1.1.0
- **Update**: [https://github.com/ms13th-cyber/my-admin-bar-customizer/](https://github.com/ms13th-cyber/my-admin-bar-customizer/)