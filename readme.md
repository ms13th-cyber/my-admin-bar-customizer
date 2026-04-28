# My Admin Bar Customizer

A minimalist WordPress plugin that allows you to easily hide specific items from the WordPress admin bar (toolbar) and automatically shortens long site names for a cleaner workspace.

[日本語の解説は英語の後にあります]

---

## Key Features

- **Invisible Item Detection**: Automatically detects all items in your admin bar, including those added by third-party plugins (e.g., WP Fastest Cache, UpdraftPlus).
- **Simple Checkbox Toggle**: No complex code required. Simply check the items you want to hide from the settings page.
- **Smart Site Name Truncation**: Automatically shortens long site titles in the admin bar (default: 15 characters) to prevent layout breaking.
- **Persistence Logic**: Items remain in the settings list even after being hidden, allowing you to easily restore them at any time.
- **Performance Optimized**: Lightweight implementation with minimal database impact, using standard WordPress hooks.
- **One-Click Reset**: Includes a "Reset Detected List" button to clear the stored menu items and rescan your current environment.

## Installation

1. Upload the `my-admin-bar-customizer` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Settings > 管理バー設定 (Admin Bar Settings)** to configure your preferences.

---

## 主な機能（日本語）

WordPress管理バー（ツールバー）の不要な項目を簡単に非表示にし、長すぎるサイト名を自動で省略してワークスペースをスッキリさせる軽量プラグインです。

- **項目の自動検知**: 標準メニューだけでなく、サードパーティ製プラグイン（WP Fastest CacheやUpdraftPlusなど）が追加した項目も自動で検知します。
- **直感的なチェックボックス操作**: 難しいコードは不要です。設定画面で非表示にしたい項目にチェックを入れるだけで簡単に整理できます。
- **サイト名の自動省略**: 管理バーに表示されるサイト名が長い場合、自動的に省略（デフォルト15文字）して「...」を表示し、表示崩れを防ぎます。
- **非表示後の復元も簡単**: 一度非表示にした項目もリストに残り続けるため、いつでもチェックを外して元に戻すことが可能です。
- **パフォーマンス重視**: WordPress標準のフックを利用した軽量な設計で、サイトの動作に影響を与えません。
- **リストのリセット機能**: 検知済みリストをワンクリックで初期化するボタンを搭載。プラグインの入れ替え時などに現在の環境を再スキャンできます。

## インストール・設定

1. `my-admin-bar-customizer` フォルダを `/wp-content/plugins/` にアップロードします。
2. 管理画面の「プラグイン」から有効化してください。
3. 「設定」 > 「管理バー設定」から、非表示にしたい項目を選択して「変更を保存」をクリックしてください。
    - ※特定のプラグイン項目が表示されない場合は、一度サイトのフロントページ（公開側）を表示してから設定画面をリロードしてください。

## 開発者情報
- **Author**: masato shibuya (Image-box Co., Ltd.)
- **Version**: 1.0.0
- **Update**: https://github.com/ms13th-cyber/my-admin-bar-customizer/