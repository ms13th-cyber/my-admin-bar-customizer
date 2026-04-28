<?php
/*
Plugin Name: My Admin Bar Customizer
Description: 管理バーの項目を強制検知して非表示設定を行う
Version: 1.1.0
Tested up to: 6.9.4
Requires PHP: 8.3.23
Author: masato shibuya(Image-box Co., Ltd.)
*/

if (!defined('ABSPATH')) exit;

/**
 * 1. 保存処理（最優先実行）
 * 管理画面の初期化タイミングで保存を行うことで、管理バーの描画に間に合わせます。
 */
add_action('admin_init', function() {
    if (isset($_POST['manual_save_admin_bar']) && check_admin_referer('admin_bar_save_action', 'admin_bar_save_nonce')) {
        $hidden_nodes = isset($_POST['hidden_admin_bar_nodes']) ? array_map('sanitize_key', $_POST['hidden_admin_bar_nodes']) : array();
        update_option('hidden_admin_bar_nodes', $hidden_nodes);

        // 保存直後にリダイレクトをかけることで、ヘッダー等の表示崩れを防ぎ、完全に同期させます
        if (isset($_GET['page']) && $_GET['page'] === 'admin-bar-settings') {
            wp_redirect(admin_url('options-general.php?page=admin-bar-settings&settings-updated=true'));
            exit;
        }
    }

    // リセット処理
    if (isset($_POST['reset_nodes']) && check_admin_referer('reset_nodes_action', 'reset_nodes_nonce')) {
        delete_option('all_detected_nodes');
        delete_option('hidden_admin_bar_nodes');
        wp_redirect(admin_url('options-general.php?page=admin-bar-settings&reset=true'));
        exit;
    }
});

/**
 * 管理メニュー登録
 */
add_action('admin_menu', function() {
    add_options_page('管理バー設定', '管理バー設定', 'manage_options', 'admin-bar-settings', 'render_admin_bar_settings_page');
});

/**
 * 設定ページの表示
 */
function render_admin_bar_settings_page() {
    if (isset($_GET['settings-updated'])) {
        echo '<div class="updated"><p>設定を保存し、即座に反映しました。</p></div>';
    }
    if (isset($_GET['reset'])) {
        echo '<div class="updated"><p>リストをリセットしました。</p></div>';
    }

    $all_nodes = get_option('all_detected_nodes', array());
    $hidden_nodes = get_option('hidden_admin_bar_nodes', array());
    ?>
    <div class="wrap">
        <h1>管理バーの表示設定</h1>
        <p><strong>非表示にしたい項目</strong>にチェックを入れてください。</p>

        <form method="post" action="">
            <?php wp_nonce_field('admin_bar_save_action', 'admin_bar_save_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row">メニュー一覧</th>
                    <td>
                        <?php if (empty($all_nodes)) : ?>
                            <p>まだメニューが検知されていません。一度サイトの適当なページを表示してください。</p>
                        <?php else : ?>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                            <?php foreach ($all_nodes as $id => $data) :
                                $parent = isset($data['parent']) ? $data['parent'] : '';
                                if (!empty($parent) && !in_array($parent, array('top-secondary', 'root', 'wp-toolbar'))) continue;
                                if (in_array($id, array('top-secondary', 'root', 'wp-toolbar'))) continue;

                                $checked = in_array($id, $hidden_nodes) ? 'checked="checked"' : '';
                                $title = (!empty($data['title'])) ? $data['title'] : $id;
                            ?>
                                <label style="display: block; background: #fff; padding: 10px; border: 1px solid #ccd0d4; border-radius: 4px; max-width: 500px;">
                                    <input type="checkbox" name="hidden_admin_bar_nodes[]" value="<?php echo esc_attr($id); ?>" <?php echo $checked; ?>>
                                    <span style="margin-left: 8px; font-weight: bold;"><?php echo esc_html($title); ?></span>
                                    <code style="margin-left: 8px; color: #666;">(<?php echo esc_html($id); ?>)</code>
                                </label>
                            <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="manual_save_admin_bar" class="button button-primary" value="変更を保存">
            </p>
        </form>

        <hr style="margin: 40px 0 20px;">
        <form method="post">
            <?php wp_nonce_field('reset_nodes_action', 'reset_nodes_nonce'); ?>
            <input type="submit" name="reset_nodes" class="button" value="検知済みリストをリセット" onclick="return confirm('リストをリセットしますか？');">
        </form>
    </div>
    <?php
}

/**
 * 警告を出さないスキャン処理
 */
add_action('admin_bar_menu', function($wp_admin_bar) {
    if (!is_object($wp_admin_bar)) return;
    $nodes = $wp_admin_bar->get_nodes();
    if (!$nodes) return;
    $all_nodes = get_option('all_detected_nodes', array());
    $updated = false;
    foreach ($nodes as $node) {
        if (!isset($all_nodes[$node->id])) {
            $all_nodes[$node->id] = array(
                'title'  => $node->title ? strip_tags($node->title) : $node->id,
                'parent' => $node->parent
            );
            $updated = true;
        }
    }
    if ($updated) update_option('all_detected_nodes', $all_nodes);
}, 9999);

/**
 * 実際の非表示・サイト名省略処理
 */
add_action('wp_before_admin_bar_render', function() {
    global $wp_admin_bar;
    if (!is_object($wp_admin_bar)) return;
    $hidden_nodes = get_option('hidden_admin_bar_nodes', array());
    foreach ($wp_admin_bar->get_nodes() as $node) {
        if ($node->id === 'site-name') {
            $limit = 15;
            $current_title = strip_tags($node->title);
            if (mb_strlen($current_title) > $limit) {
                $args = (array) $node;
                $args['title'] = mb_substr($current_title, 0, $limit) . '...';
                $wp_admin_bar->add_node($args);
            }
        }
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