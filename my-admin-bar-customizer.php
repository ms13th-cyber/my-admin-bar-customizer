<?php
/*
Plugin Name: My Admin Bar Customizer
Description: 管理バーの項目を強制検知して非表示設定を行う
Version: 1.0.0
Tested up to: 6.9.4
Requires PHP: 8.3.23
Author: masato shibuya(Image-box Co., Ltd.)
*/

if (!defined('ABSPATH')) exit;

add_action('admin_menu', function() {
    add_options_page('管理バー設定', '管理バー設定', 'manage_options', 'admin-bar-settings', 'render_admin_bar_settings_page');
});

function render_admin_bar_settings_page() {
    ?>
    <div class="wrap">
        <h1>管理バーの表示設定</h1>
        <p><strong>非表示にしたい項目</strong>にチェックを入れてください。サイト名は自動で省略されます。</p>
        <form method="post" action="options.php">
            <?php
            settings_fields('admin_bar_group');
            do_settings_sections('admin-bar-settings');
            submit_button();
            ?>
        </form>
        <hr>
        <form method="post">
            <?php wp_nonce_field('reset_nodes_action', 'reset_nodes_nonce'); ?>
            <input type="submit" name="reset_nodes" class="button" value="検知済みリストをリセット" onclick="return confirm('リストをリセットしますか？');">
        </form>
    </div>
    <?php
    if (isset($_POST['reset_nodes']) && check_admin_referer('reset_nodes_action', 'reset_nodes_nonce')) {
        delete_option('all_detected_nodes');
        echo "<div class='updated'><p>リストをリセットしました。一度ページをリロードしてください。</p></div>";
    }
}

add_action('admin_init', function() {
    // 以前の「非表示リスト」の名前に戻します
    register_setting('admin_bar_group', 'hidden_admin_bar_nodes');
    register_setting('admin_bar_group', 'all_detected_nodes');

    add_settings_section('main_section', '非表示にするメニューの選択', null, 'admin-bar-settings');

    global $wp_admin_bar;
    if (!is_object($wp_admin_bar)) {
        require_once(ABSPATH . WPINC . '/class-wp-admin-bar.php');
        $wp_admin_bar = new WP_Admin_Bar;
    }
    do_action_ref_array('admin_bar_menu', array(&$wp_admin_bar));
    sync_admin_bar_nodes_to_db();

    $all_nodes = (array) get_option('all_detected_nodes', []);
    $hidden_nodes = (array) get_option('hidden_admin_bar_nodes', []);

    if (!empty($all_nodes)) {
        foreach ($all_nodes as $id => $data) {
            if (!is_array($data)) continue;

            $parent = isset($data['parent']) ? $data['parent'] : '';
            // 親が空、または主要なルート直下のみをリストに出す
            if (empty($parent) || in_array($parent, ['top-secondary', 'root', 'wp-toolbar'])) {
                if (in_array($id, ['top-secondary', 'root', 'wp-toolbar'])) continue;

                add_settings_field(
                    'field_' . $id,
                    (isset($data['title']) && $data['title']) ? $data['title'] : $id,
                    function($args) use ($hidden_nodes) {
                        $id = $args['id'];
                        $checked = in_array($id, $hidden_nodes) ? 'checked' : '';
                        echo "<label><input type='checkbox' name='hidden_admin_bar_nodes[]' value='{$id}' {$checked}> <code>{$id}</code> を非表示にする</label>";
                    },
                    'admin-bar-settings',
                    'main_section',
                    ['id' => $id]
                );
            }
        }
    }
});

// スキャン処理
function sync_admin_bar_nodes_to_db() {
    global $wp_admin_bar;
    if ( ! is_object( $wp_admin_bar ) ) return;
    $nodes = $wp_admin_bar->get_nodes();
    $all_nodes = (array) get_option('all_detected_nodes', []);
    $updated = false;
    if ($nodes) {
        foreach ($nodes as $node) {
            if (!isset($all_nodes[$node->id]) || !is_array($all_nodes[$node->id])) {
                $all_nodes[$node->id] = [
                    'title'  => $node->title ? strip_tags($node->title) : $node->id,
                    'parent' => $node->parent
                ];
                $updated = true;
            }
        }
        if ($updated) {
            update_option('all_detected_nodes', $all_nodes);
        }
    }
}
add_action('admin_bar_menu', 'sync_admin_bar_nodes_to_db', 1);

/**
 * 反映処理：サイト名省略 ＆ 非表示設定（引き算）
 */
add_action('wp_before_admin_bar_render', function() {
    global $wp_admin_bar;
    if (!is_object($wp_admin_bar)) return;

    $hidden_nodes = (array) get_option('hidden_admin_bar_nodes', []);
    $nodes = $wp_admin_bar->get_nodes();
    if (!$nodes) return;

    foreach ($nodes as $node) {
        // --- サイト名の省略処理 ---
        if ($node->id === 'site-name') {
            $limit = 15; // 制限文字数
            $current_title = strip_tags($node->title);
            if (mb_strlen($current_title) > $limit) {
                $new_title = mb_substr($current_title, 0, $limit) . '...';
                $args = (array) $node;
                $args['title'] = $new_title;
                $wp_admin_bar->add_node($args);
            }
        }

        // --- 非表示処理（引き算方式） ---
        // チェックが入っているIDだけを削除
        if (in_array($node->id, $hidden_nodes)) {
            $wp_admin_bar->remove_node($node->id);
        }
    }
}, 9999);


require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';

$updateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'https://github.com/ms13th-cyber/my-admin-bar-customizer/',
    __FILE__,
    'my-admin-bar-customizer'
);

$updateChecker->setBranch('main');