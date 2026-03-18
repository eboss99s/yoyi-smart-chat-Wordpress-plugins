<?php
/**
 * Plugin Name: Yoyi AI 智慧客服
 * Description: 專為網站打造的純淨版 24 小時線上智能客服。支援「自訂關鍵字問答系統」優先觸發，並完美結合 Google Gemini API 提供 AI 彈性回覆。內建自動隱藏離線留言表單、常見問題快捷按鈕與防刷頻機制，全站採用標準 SVG 向量圖形確保極致效能。
 * Version: 3.8.4
 * Author: Yoyi
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'yoyi_bot_add_admin_menu');
function yoyi_bot_add_admin_menu() {
    add_options_page(
        'Yoyi 客服設定',
        'Yoyi 客服',
        'manage_options',
        'yoyi-bot-settings',
        'yoyi_bot_render_settings_page'
    );
}

add_action('admin_init', 'yoyi_bot_register_settings');
function yoyi_bot_register_settings() {
    $fields = [
        'yoyi_bot_enabled', 'yoyi_bot_title', 'yoyi_bot_name', 'yoyi_bot_color',
        'yoyi_bot_icon_ai', 'yoyi_bot_icon_fb', 'yoyi_bot_icon_line', 
        'yoyi_bot_form_shortcode',
        'yoyi_bot_gemini_key', 'yoyi_bot_gemini_model',
        'yoyi_bot_info_name', 'yoyi_bot_info_desc', 'yoyi_bot_info_contact', 'yoyi_bot_info_mobile', 'yoyi_bot_info_line', 'yoyi_bot_info_line_qr', 'yoyi_bot_info_human_hours', 'yoyi_bot_info_fb', 'yoyi_bot_info_address', 'yoyi_bot_info_hours',
        'yoyi_bot_custom_qa' 
    ];
    foreach ($fields as $field) {
        register_setting('yoyi_bot_options_group', $field);
    }
}

function yoyi_bot_render_settings_page() {
    $primary_color = get_option('yoyi_bot_color', '#ff6b6b');
    $nonce = wp_create_nonce('yoyi_bot_admin_action');
    
    $current_icon = get_option('yoyi_bot_icon_ai', '');
    if (strpos($current_icon, 'icons8.com') !== false) {
        update_option('yoyi_bot_icon_ai', '');
        $current_icon = '';
    }
    
    $current_line_icon = get_option('yoyi_bot_icon_line', '');
    if (strpos($current_line_icon, 'pngtree.com') !== false) {
        update_option('yoyi_bot_icon_line', '');
        $current_line_icon = '';
    }
    ?>
    <div class="wrap">
        <h2>✨ Yoyi AI 智慧客服 - 設定中心</h2>
        
        <h2 class="nav-tab-wrapper" id="yoyi-tabs">
            <a href="#tab-basic" class="nav-tab nav-tab-active">⚙️ 基本設定</a>
            <a href="#tab-qa" class="nav-tab">💬 自訂問答</a>
            <a href="#tab-info" class="nav-tab">🏢 基本資訊</a>
            <a href="#tab-ai" class="nav-tab">🤖 AI 設定</a>
            <a href="#tab-logs" class="nav-tab" style="color:#d63638;">📝 AI 對話紀錄</a>
        </h2>

        <form method="post" action="options.php" id="yoyi-bot-form" style="background:#fff; padding:20px; border:1px solid #ccc; margin-top:15px; border-radius: 5px;">
            <?php settings_fields('yoyi_bot_options_group'); ?>
            
            <div id="tab-basic" class="yoyi-tab-content">
                <table class="form-table">
                    <tr>
                        <th scope="row">啟用前台客服</th>
                        <td><label><input type="checkbox" name="yoyi_bot_enabled" value="1" <?php checked(get_option('yoyi_bot_enabled', 1), 1); ?>> ✅ 顯示浮動聊天視窗</label></td>
                    </tr>
                    <tr>
                        <th scope="row">視窗標題</th>
                        <td><input type="text" name="yoyi_bot_title" value="<?php echo esc_attr(get_option('yoyi_bot_title', 'Yoyi 客服小幫手')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">機器人名稱</th>
                        <td><input type="text" name="yoyi_bot_name" value="<?php echo esc_attr(get_option('yoyi_bot_name', 'Yoyi')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">主色調</th>
                        <td><input type="color" name="yoyi_bot_color" value="<?php echo esc_attr($primary_color); ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <th scope="row">🤖 智能客服自訂圖示</th>
                        <td>
                            <input type="text" name="yoyi_bot_icon_ai" value="<?php echo esc_attr($current_icon); ?>" class="large-text" placeholder="https://...">
                            <p class="description">可自訂圖示，請上傳至「媒體庫」，再貼回圖片網址。（若留空將顯示系統預設圖示）</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">💬 FB Messenger 圖示網址</th>
                        <td>
                            <input type="text" name="yoyi_bot_icon_fb" value="<?php echo esc_attr(get_option('yoyi_bot_icon_fb', '')); ?>" class="large-text" placeholder="https://...">
                            <p class="description">可自訂圖示，請上傳至「媒體庫」，再貼回圖片網址。（若留空將顯示系統預設圖示）</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">🟢 LINE 客服圖示網址</th>
                        <td>
                            <input type="text" name="yoyi_bot_icon_line" value="<?php echo esc_attr($current_line_icon); ?>" class="large-text" placeholder="https://...">
                            <p class="description">可自訂圖示，請上傳至「媒體庫」，再貼回圖片網址。（若留空將顯示系統預設圖示）</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <th scope="row" style="color:#008a20;">📝 留言表單短代碼 (CF7)</th>
                        <td>
                            <input type="text" name="yoyi_bot_form_shortcode" value="<?php echo esc_attr(get_option('yoyi_bot_form_shortcode', '')); ?>" class="large-text" placeholder='例如：[contact-form-7 id="a3bb65a" title="聯絡表單 1"]'>
                            <p class="description">請貼入 Contact Form 7 的短代碼。設定後，前台的「填寫表單」功能將完美整合您的 CF7 系統。<br><strong>(若留空，前台將自動隱藏所有「留言」相關按鈕與功能)</strong></p>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="tab-qa" class="yoyi-tab-content" style="display:none;">
                <p class="description" style="font-size:14px; margin-bottom: 15px;">
                    🎯 <strong>最高優先權：</strong> 當客人的提問包含下方的關鍵字時，機器人將優先回覆此處的標準答案。<br>
                    💡 <strong>前台按鈕：</strong> 設定的「問題標題」會自動在聊天室中轉化為快捷按鈕。
                </p>
                <input type="hidden" name="yoyi_bot_custom_qa" id="yoyi_bot_custom_qa" value="<?php echo esc_attr(get_option('yoyi_bot_custom_qa', '[]')); ?>">
                <div id="qa-container"></div>
                <button type="button" id="add-qa-btn" class="button button-primary" style="margin-top: 10px;">➕ 新增問答組合</button>
            </div>

            <div id="tab-info" class="yoyi-tab-content" style="display:none;">
                <p class="description">此區資訊為 AI 的背景知識依據。</p>
                <table class="form-table">
                    <tr>
                        <th scope="row">品牌/店家名稱</th>
                        <td><input type="text" name="yoyi_bot_info_name" value="<?php echo esc_attr(get_option('yoyi_bot_info_name', 'Yoyi Store')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">介紹與主要服務</th>
                        <td><textarea name="yoyi_bot_info_desc" rows="5" class="large-text"><?php echo esc_textarea(get_option('yoyi_bot_info_desc', '')); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row">實體店營業時間</th>
                        <td><input type="text" name="yoyi_bot_info_hours" value="<?php echo esc_attr(get_option('yoyi_bot_info_hours', '週一至週五 09:00-18:00')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">人工客服上班時間</th>
                        <td>
                            <input type="text" name="yoyi_bot_info_human_hours" value="<?php echo esc_attr(get_option('yoyi_bot_info_human_hours', '週一至週五 09:00~17:00 (其它時間留言皆會回覆)')); ?>" class="large-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">聯絡方式 (市話/Email)</th>
                        <td><input type="text" name="yoyi_bot_info_contact" value="<?php echo esc_attr(get_option('yoyi_bot_info_contact', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">手機號碼</th>
                        <td><input type="text" name="yoyi_bot_info_mobile" value="<?php echo esc_attr(get_option('yoyi_bot_info_mobile', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">LINE ID / 連結</th>
                        <td><input type="text" name="yoyi_bot_info_line" value="<?php echo esc_attr(get_option('yoyi_bot_info_line', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">LINE QR Code 圖片網址</th>
                        <td><input type="text" name="yoyi_bot_info_line_qr" value="<?php echo esc_attr(get_option('yoyi_bot_info_line_qr', '')); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">Facebook 粉絲專頁網址</th>
                        <td><input type="url" name="yoyi_bot_info_fb" value="<?php echo esc_attr(get_option('yoyi_bot_info_fb', '')); ?>" class="large-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">實體地址</th>
                        <td><input type="text" name="yoyi_bot_info_address" value="<?php echo esc_attr(get_option('yoyi_bot_info_address', '')); ?>" class="large-text"></td>
                    </tr>
                </table>
            </div>

            <div id="tab-ai" class="yoyi-tab-content" style="display:none;">
                <table class="form-table">
                    <tr>
                        <th scope="row">Google Gemini API Key</th>
                        <td><input type="password" name="yoyi_bot_gemini_key" value="<?php echo esc_attr(get_option('yoyi_bot_gemini_key', '')); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">AI 模型</th>
                        <td>
                            <select name="yoyi_bot_gemini_model">
                                <option value="gemini-2.5-flash" <?php selected(get_option('yoyi_bot_gemini_model', 'gemini-2.5-flash'), 'gemini-2.5-flash'); ?>>Gemini 2.5 Flash (推薦，速度快)</option>
                                <option value="gemini-2.5-pro" <?php selected(get_option('yoyi_bot_gemini_model'), 'gemini-2.5-pro'); ?>>Gemini 2.5 Pro</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="tab-logs" class="yoyi-tab-content" style="display:none;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 15px;">
                    <p class="description" style="margin:0; font-size:14px;">自動保留最近 100 筆的對話紀錄。</p>
                    <button type="button" id="clear-logs-btn" class="button button-secondary" style="color:#d63638; border-color:#d63638;" data-nonce="<?php echo $nonce; ?>">🗑️ 清除紀錄</button>
                </div>
                
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 150px;">時間</th>
                            <th style="width: 35%;">客人問</th>
                            <th>機器人回答</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $logs = get_option('yoyi_bot_chat_logs', []);
                        if (empty($logs) || !is_array($logs)) {
                            echo '<tr><td colspan="3" style="padding:15px; color:#777;">目前還沒有任何對話紀錄。</td></tr>';
                        } else {
                            foreach ($logs as $log) {
                                echo '<tr>';
                                echo '<td style="color:#777;">' . esc_html($log['time']) . '</td>';
                                echo '<td><strong style="color:#2271b1; font-size:14px;">' . esc_html($log['user']) . '</strong></td>';
                                echo '<td style="font-size:13px; color:#333;">' . wp_kses_post($log['bot']) . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="save-btn-container">
                <?php submit_button('💾 儲存所有設定', 'primary', 'submit', true, ['style' => 'font-size: 16px; padding: 5px 20px;']); ?>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.nav-tab');
        const contents = document.querySelectorAll('.yoyi-tab-content');
        const saveBtnContainer = document.getElementById('save-btn-container');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                tabs.forEach(t => t.classList.remove('nav-tab-active'));
                contents.forEach(c => c.style.display = 'none');
                
                this.classList.add('nav-tab-active');
                const targetId = this.getAttribute('href');
                document.querySelector(targetId).style.display = 'block';

                if(targetId === '#tab-logs') {
                    saveBtnContainer.style.display = 'none';
                } else {
                    saveBtnContainer.style.display = 'block';
                }
            });
        });

        const qaInput = document.getElementById('yoyi_bot_custom_qa');
        const qaContainer = document.getElementById('qa-container');
        const addBtn = document.getElementById('add-qa-btn');

        let qaData = [];
        try { qaData = JSON.parse(qaInput.value) || []; } catch(e) { qaData = []; }

        function renderQA() {
            qaContainer.innerHTML = '';
            if (qaData.length === 0) {
                qaContainer.innerHTML = '<p style="color:#777;">目前無資料，請點擊下方按鈕新增。</p>';
                return;
            }

            qaData.forEach((item, index) => {
                const row = document.createElement('div');
                row.className = 'qa-row';
                row.style.cssText = 'background: #f9f9f9; border: 1px solid #ccd0d4; padding: 15px; margin-bottom: 15px; border-radius: 4px;';
                row.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                        <strong style="font-size:16px; color:#2271b1;">💡 問答組合 #${index + 1}</strong>
                        <button type="button" class="button remove-qa-btn" data-index="${index}" style="color:#d63638; border-color:#d63638;">🗑️ 刪除</button>
                    </div>
                    <table class="form-table" style="margin-top:0;">
                        <tr>
                            <th scope="row" style="padding: 10px 10px 10px 0; width: 150px;">問題標題</th>
                            <td style="padding: 10px 0;"><input type="text" class="qa-q regular-text" value="${item.question || ''}"></td>
                        </tr>
                        <tr>
                            <th scope="row" style="padding: 10px 10px 10px 0; color: #d63638;">⚡ 觸發關鍵字</th>
                            <td style="padding: 10px 0;"><input type="text" class="qa-k large-text" value="${item.keywords || ''}"></td>
                        </tr>
                        <tr>
                            <th scope="row" style="padding: 10px 10px 10px 0; color: #008a20;">💬 機器人回覆</th>
                            <td style="padding: 10px 0;"><textarea class="qa-a large-text" rows="3">${item.answer || ''}</textarea></td>
                        </tr>
                    </table>
                `;
                qaContainer.appendChild(row);
            });
        }

        document.getElementById('yoyi-bot-form').addEventListener('submit', function() {
            const rows = qaContainer.querySelectorAll('.qa-row');
            const newData = [];
            rows.forEach(row => {
                newData.push({
                    question: row.querySelector('.qa-q').value,
                    keywords: row.querySelector('.qa-k').value,
                    answer: row.querySelector('.qa-a').value
                });
            });
            qaInput.value = JSON.stringify(newData);
        });

        addBtn.addEventListener('click', () => { qaData.push({question: '', keywords: '', answer: ''}); renderQA(); });
        qaContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-qa-btn') && confirm('確定要刪除嗎？')) {
                qaData.splice(e.target.getAttribute('data-index'), 1); renderQA();
            }
        });

        renderQA();

        const clearLogsBtn = document.getElementById('clear-logs-btn');
        if(clearLogsBtn) {
            clearLogsBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if(confirm('確定要清除所有的紀錄嗎？')) {
                    const formData = new URLSearchParams(); 
                    formData.append('action', 'yoyi_bot_clear_logs');
                    formData.append('nonce', this.getAttribute('data-nonce'));
                    fetch('<?php echo admin_url('admin-ajax.php'); ?>', { method: 'POST', body: formData })
                    .then(res => res.json()).then(data => { if(data.success) window.location.reload(); });
                }
            });
        }
    });
    </script>
    <?php
}

add_action('wp_ajax_yoyi_bot_clear_logs', 'yoyi_bot_clear_logs');
function yoyi_bot_clear_logs() { 
    check_ajax_referer('yoyi_bot_admin_action', 'nonce');
    delete_option('yoyi_bot_chat_logs'); 
    wp_send_json_success(); 
}

function yoyi_bot_check_rate_limit($action_type = 'chat') {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $transient_name = 'yoyi_bot_limit_' . $action_type . '_' . md5($ip);
    if (get_transient($transient_name)) return false; 
    set_transient($transient_name, true, 3);
    return true;
}

function yoyi_bot_save_log($user_msg, $bot_reply) {
    $logs = get_option('yoyi_bot_chat_logs', []);
    if (!is_array($logs)) $logs = [];
    array_unshift($logs, [
        'time' => current_time('Y-m-d H:i:s'),
        'user' => sanitize_text_field($user_msg),
        'bot'  => wp_kses_post($bot_reply) 
    ]);
    if (count($logs) > 100) $logs = array_slice($logs, 0, 100);
    update_option('yoyi_bot_chat_logs', $logs, false);
}

function yoyi_bot_send_reply($message, $reply, $is_custom_qa = false) {
    if ($is_custom_qa) { $reply = esc_html($reply); } else { $reply = trim($reply); }

    $info_line_qr = get_option('yoyi_bot_info_line_qr', '');
    $info_line = get_option('yoyi_bot_info_line', ''); 
    $human_hours = get_option('yoyi_bot_info_human_hours', '週一至週五 09:00~17:00');
    
    if (!empty($info_line_qr)) {
        $trigger_words = ['line', '賴', '加好友', '人工', '真人', '專人', '客服'];
        $should_attach_qr = false;
        foreach ($trigger_words as $word) {
            if (mb_stripos($reply, $word) !== false) { $should_attach_qr = true; break; }
        }
        
        if ($should_attach_qr && mb_stripos($reply, '<img') === false) {
            $line_url = $info_line;
            if (!empty($info_line) && strpos($info_line, 'http') === false) {
                if (strpos($info_line, '@') === 0) {
                    $line_url = 'https://line.me/R/ti/p/' . urlencode($info_line);
                } else {
                    $line_url = 'https://line.me/ti/p/~' . urlencode($info_line);
                }
            }
            
            $reply .= "\n\n⏰ <strong>客服時間：{$human_hours}</strong>\n📱 電腦版請掃描 QR Code，<strong>手機版請「直接點擊圖片」</strong>加好友：\n";
            
            if (!empty($line_url)) {
                $reply .= "<a href='" . esc_url($line_url) . "' target='_blank' rel='noopener noreferrer' style='display:inline-block;'><img src='" . esc_url($info_line_qr) . "' style='width:150px; height:auto; border-radius:8px; margin-top:10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: transform 0.2s;'></a>";
            } else {
                $reply .= "<img src='" . esc_url($info_line_qr) . "' style='width:150px; height:auto; border-radius:8px; margin-top:10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>";
            }
        }
    }
    
    yoyi_bot_save_log($message, $reply);
    wp_send_json_success(['reply' => nl2br($reply)]);
    exit;
}

add_action('wp_ajax_nopriv_yoyi_bot_chat', 'yoyi_bot_handle_chat');
add_action('wp_ajax_yoyi_bot_chat', 'yoyi_bot_handle_chat');

function yoyi_bot_handle_chat() {
    $hp_check = isset($_POST['yoyi_hp']) ? sanitize_text_field($_POST['yoyi_hp']) : '';
    if (!empty($hp_check)) {
        wp_send_json_success(['reply' => '很抱歉，系統偵測到異常活動。']);
        exit;
    }

    if (!yoyi_bot_check_rate_limit('chat')) {
        wp_send_json_success(['reply' => '⚠️ 發送太快囉！請稍微等幾秒鐘再傳送下一個問題。']);
        exit;
    }

    $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';
    if (empty($message)) wp_send_json_error('沒有收到訊息');

    $custom_qa_json = get_option('yoyi_bot_custom_qa', '[]');
    $custom_qa = json_decode($custom_qa_json, true);
    if (is_array($custom_qa)) {
        
        foreach ($custom_qa as $qa) {
            if (!empty($qa['question']) && trim($message) === trim($qa['question'])) {
                yoyi_bot_send_reply($message, $qa['answer'], true);
            }
        }
        
        $best_match_qa = null;
        $max_match_length = 0;

        foreach ($custom_qa as $qa) {
            $keywords = explode(',', $qa['keywords']);
            foreach ($keywords as $kw) {
                $kw = trim($kw);
                if (!empty($kw) && mb_stripos($message, $kw) !== false) {
                    $kw_length = mb_strlen($kw);
                    if ($kw_length > $max_match_length) {
                        $max_match_length = $kw_length;
                        $best_match_qa = $qa;
                    }
                }
            }
        }

        if (!$best_match_qa) {
            $max_fuzzy_length = 0;
            foreach ($custom_qa as $qa) {
                $keywords = explode(',', $qa['keywords']);
                foreach ($keywords as $kw) {
                    $kw = trim($kw);
                    if (!empty($kw) && mb_strlen($kw) >= 2) {
                        $chars = preg_split('//u', $kw, -1, PREG_SPLIT_NO_EMPTY);
                        $regex = '/' . implode('.*?', array_map('preg_quote', $chars)) . '/iu';
                        
                        if (preg_match($regex, $message)) {
                            $kw_length = mb_strlen($kw);
                            if ($kw_length > $max_fuzzy_length) {
                                $max_fuzzy_length = $kw_length;
                                $best_match_qa = $qa;
                            }
                        }
                    }
                }
            }
        }

        if ($best_match_qa) {
            yoyi_bot_send_reply($message, $best_match_qa['answer'], true);
        }
    }

    $human_triggers = ['人工', '真人', '專人', '客服人員', '轉接'];
    foreach ($human_triggers as $trigger) {
        if (mb_stripos($message, $trigger) !== false) {
            $info_line = get_option('yoyi_bot_info_line', '');
            $reply = "好的！請透過以下方式與我們的人工客服聯繫：\nLINE ID / 連結：{$info_line}";
            yoyi_bot_send_reply($message, $reply, true);
        }
    }

    $api_key = get_option('yoyi_bot_gemini_key', '');
    if (empty($api_key)) {
        $reply = yoyi_bot_fallback_mode($message);
        yoyi_bot_send_reply($message, $reply);
    }

    $model = get_option('yoyi_bot_gemini_model', 'gemini-2.5-flash');
    $url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$api_key}";
    
    $bot_name = get_option('yoyi_bot_name', 'Yoyi');
    $info_name = get_option('yoyi_bot_info_name', 'Yoyi Store');
    $info_desc = get_option('yoyi_bot_info_desc', '');
    $info_hours = get_option('yoyi_bot_info_hours', '');
    $info_human_hours = get_option('yoyi_bot_info_human_hours', '');
    $info_contact = get_option('yoyi_bot_info_contact', '');
    $info_mobile = get_option('yoyi_bot_info_mobile', '');
    $info_line = get_option('yoyi_bot_info_line', '');
    $info_fb = get_option('yoyi_bot_info_fb', '');
    if (!empty($info_fb) && strpos($info_fb, 'facebook.com/') !== false) {
         $info_fb = preg_replace('/https?:\/\/(www\.)?facebook\.com\//i', 'https://m.me/', $info_fb);
    }
    $info_address = get_option('yoyi_bot_info_address', '');

    $prompt = "你是「{$bot_name}」，是「{$info_name}」的線上客服。\n請根據以下資訊，用親切自然的口吻回答客人的問題。\n";
    if ($info_desc) $prompt .= "介紹與服務：{$info_desc}\n";
    if ($info_hours) $prompt .= "實體店營業時間：{$info_hours}\n";
    if ($info_human_hours) $prompt .= "人工客服時間：{$info_human_hours}\n";
    if ($info_contact) $prompt .= "聯絡方式(市話/Email)：{$info_contact}\n";
    if ($info_mobile) $prompt .= "手機號碼：{$info_mobile}\n";
    if ($info_line) $prompt .= "LINE 官方/聯絡資訊：{$info_line}\n";
    if ($info_fb) $prompt .= "Facebook 粉專：{$info_fb}\n";
    if ($info_address) $prompt .= "地址：{$info_address}\n";
    $prompt .= "-----------------\n客人說：{$message}\n請回覆：";

    $postData = [
        'contents' => [['parts' => [['text' => $prompt]]]],
        'generationConfig' => ['temperature' => 0.7, 'maxOutputTokens' => 800]
    ];

    $response = wp_remote_post($url, [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode($postData),
        'timeout' => 15
    ]);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        $reply = yoyi_bot_fallback_mode($message);
        yoyi_bot_send_reply($message, $reply);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        $reply = $data['candidates'][0]['content']['parts'][0]['text'];
        $reply = preg_replace('/[\*\#\_]/', '', $reply);
        yoyi_bot_send_reply($message, $reply);
    } else {
        $reply = yoyi_bot_fallback_mode($message);
        yoyi_bot_send_reply($message, $reply);
    }
}

function yoyi_bot_fallback_mode($message) {
    $info_name = get_option('yoyi_bot_info_name', 'Yoyi Store');
    $info_desc = get_option('yoyi_bot_info_desc', '詳情請參考官網');
    $info_hours = get_option('yoyi_bot_info_hours', '請參考官網公告');
    $info_contact = get_option('yoyi_bot_info_contact', '');
    $info_mobile = get_option('yoyi_bot_info_mobile', '');
    $info_line = get_option('yoyi_bot_info_line', '');
    $info_fb = get_option('yoyi_bot_info_fb', '');
    if (!empty($info_fb) && strpos($info_fb, 'facebook.com/') !== false) {
         $info_fb = preg_replace('/https?:\/\/(www\.)?facebook\.com\//i', 'https://m.me/', $info_fb);
    }
    $info_address = get_option('yoyi_bot_info_address', '尚未提供');

    $combined_contact = "";
    if (!empty($info_contact)) $combined_contact .= "市話/Email：{$info_contact}\n";
    if (!empty($info_mobile)) $combined_contact .= "手機號碼：{$info_mobile}\n";
    if (!empty($info_line)) $combined_contact .= "LINE：{$info_line}\n";
    if (!empty($info_fb)) $combined_contact .= "Facebook：{$info_fb}\n";
    if (empty($combined_contact)) $combined_contact = "尚未提供聯絡資訊";

    $fallback_rules = [
        '你好' => '您好！請問有什麼我可以幫忙的嗎？',
        '早安' => '早安！今天也是充滿活力的一天。',
        '謝謝' => '不會！有問題隨時找我喔。',
        '店名' => "我們是「{$info_name}」，很高興為您服務！",
        '營業' => "我們的營業時間是：{$info_hours}",
        '時間' => "我們的營業時間是：{$info_hours}",
        'line' => "我們的 LINE 是：\n{$info_line}",
        '賴' => "我們的 LINE 是：\n{$info_line}",
        'fb' => "我們的 Facebook 粉專是：\n{$info_fb}",
        '臉書' => "我們的 Facebook 粉專是：\n{$info_fb}",
        '手機' => "我們的手機號碼是：\n{$info_mobile}",
        '電話' => "您可以透過以下方式聯絡我們：\n{$combined_contact}",
        '聯絡' => "您可以透過以下方式聯絡我們：\n{$combined_contact}",
        '地址' => "我們的實體地址在：\n{$info_address}",
        '服務' => "我們的主要服務包含：\n{$info_desc}",
    ];

    foreach ($fallback_rules as $key => $reply) {
        if (mb_stripos($message, $key) !== false) return $reply;
    }

    return "不好意思，目前客服小幫手正在維護中，無法完全明白您的意思。您可以嘗試點擊常見問題，我們會盡快由專人回覆您！";
}

add_action('wp_ajax_nopriv_yoyi_bot_line_contact', 'yoyi_bot_line_contact');
add_action('wp_ajax_yoyi_bot_line_contact', 'yoyi_bot_line_contact');

function yoyi_bot_line_contact() {
    $info_line = get_option('yoyi_bot_info_line', '');
    $reply = "歡迎與我們的人工客服聯繫！\nLINE ID / 連結：{$info_line}";
    yoyi_bot_send_reply("【系統：使用者點擊了人工 LINE 客服】", $reply, true);
}


add_action('wp_footer', 'yoyi_bot_render_widget');
function yoyi_bot_render_widget() {
    if (!get_option('yoyi_bot_enabled', 1)) return;

    $color = get_option('yoyi_bot_color', '#ff6b6b');
    $title = get_option('yoyi_bot_title', 'Yoyi 客服小幫手');
    $bot_name = get_option('yoyi_bot_name', 'Yoyi');
    
    $icon_ai = get_option('yoyi_bot_icon_ai', '');
    $icon_fb = get_option('yoyi_bot_icon_fb', '');
    $icon_line = get_option('yoyi_bot_icon_line', '');
    $form_shortcode = get_option('yoyi_bot_form_shortcode', '');

    $info_fb = get_option('yoyi_bot_info_fb', '');
    if (!empty($info_fb) && strpos($info_fb, 'facebook.com/') !== false) {
         $info_fb = preg_replace('/https?:\/\/(www\.)?facebook\.com\//i', 'https://m.me/', $info_fb);
    }
    
    $info_line = get_option('yoyi_bot_info_line', '');
    $ajax_url = admin_url('admin-ajax.php');
    ?>
    <style>
        #yoyi-bot-container { position: fixed; bottom: 20px; right: 20px; z-index: 99999; font-family: sans-serif; }
        
        #yoyi-bot-btn { 
            width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
            opacity: 0; pointer-events: none; transition: transform 0.2s, opacity 0.3s ease; 
        }
        #yoyi-bot-btn:hover { transform: scale(1.05); }
        #yoyi-bot-btn.is-visible { opacity: 1; pointer-events: auto; }
        
        .yoyi-window-layer { display: none; width: 350px; height: 500px; background: #fff; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.2); overflow: hidden; flex-direction: column; margin-bottom: 15px; position: relative; }
        
        .yoyi-menu-header { background: <?php echo $color; ?>; color: #fff; padding: 20px; text-align: center; position: relative; }
        .yoyi-menu-title { font-size: 18px; font-weight: bold; margin-bottom: 5px; display: block; }
        .yoyi-menu-subtitle { font-size: 13px; opacity: 0.9; }
        .yoyi-menu-close { position: absolute; top: 10px; right: 15px; cursor: pointer; font-size: 18px; }
        .yoyi-menu-body { padding: 20px; background: #f8f9fa; display: flex; flex-direction: column; gap: 10px; flex:1; overflow-y:auto; }
        .yoyi-option-btn { display: flex; align-items: center; gap: 12px; background: #fff; border: 1px solid #e0e0e0; padding: 15px; border-radius: 8px; cursor: pointer; text-decoration: none; color: #333; font-weight: bold; transition: all 0.2s; }
        .yoyi-option-btn:hover { background: #f0f0f0; border-color: #ccc; }
        
        #yoyi-bot-header { background: <?php echo $color; ?>; color: #fff; padding: 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center; z-index: 10;}
        #yoyi-bot-close { cursor: pointer; font-size: 18px; line-height: 1; }
        #yoyi-bot-messages { flex: 1; padding: 15px; overflow-y: auto; background: #f8f9fa; }
        .yoyi-msg { margin-bottom: 15px; max-width: 85%; line-height: 1.5; font-size: 14px; word-break: break-word; }
        .yoyi-msg-bot { background: #e9ecef; color: #333; padding: 10px 15px; border-radius: 15px 15px 15px 4px; align-self: flex-start; }
        .yoyi-msg-user { background: <?php echo $color; ?>; color: #fff; padding: 10px 15px; border-radius: 15px 15px 4px 15px; align-self: flex-end; margin-left: auto; text-align: left; }
        
        .yoyi-msg a { color: <?php echo $color; ?>; text-decoration: underline; word-break: break-all; }
        .yoyi-msg a:hover { opacity: 0.8; }
        .yoyi-msg-user a { color: #fff; } 

        .yoyi-typing-text::after {
            content: ''; animation: yoyi-typing-dots 1.5s infinite steps(4, end); display: inline-block; width: 12px; text-align: left;
        }
        @keyframes yoyi-typing-dots { 0%, 20% { content: ''; } 40% { content: '.'; } 60% { content: '..'; } 80%, 100% { content: '...'; } }

        #yoyi-qa-drawer {
            position: absolute; bottom: 60px; left: 0; width: 100%; background: #fff; border-top: 1px solid #ddd;
            box-shadow: 0 -4px 10px rgba(0,0,0,0.08); border-radius: 12px 12px 0 0; transform: translateY(100%);
            transition: transform 0.3s ease-in-out; z-index: 5; display: flex; flex-direction: column; max-height: 60%;
        }
        #yoyi-qa-drawer.open { transform: translateY(0); }
        #yoyi-qa-drawer-header {
            padding: 10px 15px; background: #f8f9fa; border-bottom: 1px solid #eee; font-size: 13px; font-weight: bold; color: #555;
            display: flex; justify-content: space-between; align-items: center; border-radius: 12px 12px 0 0;
        }
        #yoyi-qa-drawer-close { cursor: pointer; font-size: 16px; color: #888; }
        #yoyi-qa-drawer-body { padding: 15px; overflow-y: auto; flex: 1; }
        
        .yoyi-quick-reply-btn {
            background: #fff; border: 1px solid <?php echo $color; ?>; color: <?php echo $color; ?>;
            padding: 8px 12px; border-radius: 15px; font-size: 13px; cursor: pointer;
            transition: all 0.2s; margin-bottom: 8px; margin-right: 8px; display: inline-block; line-height: normal;
        }
        .yoyi-quick-reply-btn:hover { background: <?php echo $color; ?>; color: #fff; }

        #yoyi-bot-input-area { display: flex; padding: 10px; background: #fff; position: relative; z-index: 10; border-top: 1px solid #eee; }
        #yoyi-bot-input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 20px; outline: none; }
        #yoyi-bot-send { background: <?php echo $color; ?>; color: #fff; border: none; padding: 0 15px; margin-left: 10px; border-radius: 20px; cursor: pointer; font-weight: bold; }
        
        .yoyi-bottom-actions {
            display: flex; justify-content: space-between; padding: 8px 15px; background: #f0f0f0; border-top: 1px solid #ddd; z-index: 10; position: relative;
        }
        .yoyi-action-btn {
            font-size: 13px; color: #444; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;
            background: white; padding: 6px 12px; border-radius: 20px; border: 1px solid #ccc;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: background 0.2s;
        }
        .yoyi-action-btn:hover { background: #f9f9f9; }

        #yoyi-msg-body .wpcf7-form-control-wrap { display: block; width: 100%; margin-bottom: 10px; }
        #yoyi-msg-body input[type="text"], #yoyi-msg-body input[type="email"], #yoyi-msg-body input[type="tel"], #yoyi-msg-body textarea {
            width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; outline: none; box-sizing: border-box; font-family: inherit; font-size: 14px;
        }
        #yoyi-msg-body input[type="text"]:focus, #yoyi-msg-body input[type="email"]:focus, #yoyi-msg-body input[type="tel"]:focus, #yoyi-msg-body textarea:focus { border-color: <?php echo $color; ?>; }
        
        #yoyi-msg-body input[type="submit"] {
            background: <?php echo $color; ?> !important; color: white !important; border: none !important; 
            padding: 14px !important; border-radius: 8px !important; cursor: pointer !important; 
            font-weight: bold !important; font-size: 15px !important; width: 100% !important; 
            display: block !important; text-align: center !important; line-height: normal !important; 
            box-sizing: border-box !important; margin-top: 5px !important;
        }
        #yoyi-msg-body input[type="submit"]:hover { opacity: 0.9; }
        #yoyi-msg-body form p { margin-bottom: 10px; padding: 0; }
        
        @media (max-width: 768px) {
            #yoyi-bot-container { bottom: 72px; right: 15px; }
            .yoyi-window-layer { position: fixed; bottom: 72px; right: 15px; left: 15px; width: auto; height: 75vh; border-radius: 12px; margin: 0; }
        }
    </style>

    <div id="yoyi-bot-container">
        
        <!-- 第一層：選擇選單 -->
        <div id="yoyi-options-menu" class="yoyi-window-layer">
            <div class="yoyi-menu-header">
                <span class="yoyi-menu-close" id="yoyi-menu-close">✖</span>
                <span class="yoyi-menu-title">與我們聯絡</span>
                <span class="yoyi-menu-subtitle">您好，請選擇您偏好的聯絡方式！</span>
            </div>
            <div class="yoyi-menu-body">
                <div class="yoyi-option-btn" id="start-ai-chat">
                    <?php if (empty($icon_ai)): ?>
                        <div style="width:26px; height:26px; border-radius:50%; background-color: <?php echo esc_attr($color); ?>; display:flex; align-items:center; justify-content:center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <!-- 標準無變形單對話泡泡 SVG (尾巴向右偏移) -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffffff" style="width:14px; height:14px;">
                                <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h2v4l4-4h10c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                            </svg>
                        </div>
                    <?php else: ?>
                        <img src="<?php echo esc_url($icon_ai); ?>" style="width:26px; height:26px; border-radius:50%; object-fit:contain; background-color: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" alt="智能客服">
                    <?php endif; ?>
                    <span>與 <?php echo esc_html($bot_name); ?> 智能客服對話</span>
                </div>
                
                <?php if (!empty($info_fb)): ?>
                <a href="<?php echo esc_url($info_fb); ?>" target="_blank" class="yoyi-option-btn" id="start-fb-chat">
                    <?php if (!empty($icon_fb)): ?>
                        <img src="<?php echo esc_url($icon_fb); ?>" style="width:24px; height:24px; border-radius:50%; object-fit:contain;" alt="Facebook">
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 36 36" fill="none">
                            <defs>
                                <linearGradient id="messenger-grad" x1="0%" y1="100%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#00AFFF"/><stop offset="30%" stop-color="#0077FF"/><stop offset="60%" stop-color="#8938FF"/><stop offset="90%" stop-color="#FF3A7C"/><stop offset="100%" stop-color="#FF7061"/>
                                </linearGradient>
                            </defs>
                            <path d="M18 2C9.163 2 2 8.814 2 17.22c0 4.79 2.4 9.055 6.14 11.834v4.935a.856.856 0 0 0 1.257.75l4.577-2.522c1.312.36 2.698.544 4.126.544 8.837 0 16-6.814 16-15.22S26.837 2 18 2Zm1.693 20.355-3.615-3.856-7.054 3.856 7.747-8.212 3.738 3.856 6.932-3.856-7.748 8.212Z" fill="url(#messenger-grad)"/>
                        </svg>
                    <?php endif; ?>
                    <span>開啟 Facebook 訊息對話</span>
                </a>
                <?php endif; ?>

                <?php if (!empty($info_line)): ?>
                <div class="yoyi-option-btn" id="start-line-chat">
                    <?php if (empty($icon_line)): ?>
                        <!-- 使用者自訂 Base64 LINE SVG -->
                        <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2NCIgaGVpZ2h0PSI2NCI+PHBhdGggZD0iTTY0IDI3LjQ4N2MwLTE0LjMyLTE0LjM1NS0yNS45Ny0zMi0yNS45N1MwIDEzLjE2OCAwIDI3LjQ4N2MwIDEyLjgzNyAxMS4zODQgMjMuNTg4IDI2Ljc2MiAyNS42MiAxLjA0Mi4yMjUgMi40Ni42ODggMi44MiAxLjU3OC4zMjIuODEuMjEgMi4wNzYuMTAzIDIuODk0bC0uNDU3IDIuNzRjLS4xNC44MS0uNjQzIDMuMTY0IDIuNzcyIDEuNzI1czE4LjQyOC0xMC44NTIgMjUuMTQzLTE4LjU4aC0uMDAxQzYxLjc4IDM4LjM4IDY0IDMzLjIxOCA2NCAyNy40ODciIGZpbGw9IiMwMGI5MDAiLz48ZyBmaWxsPSIjZmZmIj48cGF0aCBkPSJNMjUuNDk4IDIwLjU2OGgtMi4yNDVjLS4zNDQgMC0uNjIzLjI4LS42MjMuNjIzdjEzLjk0M2EuNjIuNjIgMCAwIDAgLjYyMy42MmgyLjI0NWEuNjIuNjIgMCAwIDAgLjYyMy0uNjJWMjEuMmMwLS4zNDMtLjI4LS42MjMtLjYyMy0uNjIzbTE1LjQ1LS4wMWgtMi4yNDRjLS4zNDUgMC0uNjI0LjI4LS42MjQuNjIzdjguMjg0bC02LjQtOC42M2MtLjAxNC0uMDIyLS4wMy0uMDQzLS4wNDgtLjA2M2wtLjAwNC0uMDA0YS40LjQgMCAwIDAtLjAzOC0uMDM4bC0uMDQ0LS4wNGMtLjAwNi0uMDA0LS4wMS0uMDA4LS4wMTYtLjAxMmwtLjAzMi0uMDIyLS4wMi0uMDEyLS4wMzMtLjAyYy0uMDA2LS4wMDItLjAxNC0uMDA2LS4wMi0uMDEtLjAxMi0uMDA2LS4wMjMtLjAxMi0uMDM2LS4wMTZzLS4wMTQtLjAwNi0uMDItLjAwNmMtLjAxMi0uMDA2LS4wMjUtLjAwOC0uMDM3LS4wMTJsLS4wMjItLjAwNmMtLjAxMi0uMDAyLS4wMjMtLjAwNi0uMDM1LS4wMDhsLS4wMjYtLjAwNGMtLjAwOC0uMDAyLS4wMjItLjAwNC0uMDMzLS4wMDRsLS4wMzItLjAwMmMtLjAwOCAwLS4wMTQtLjAwMS0uMDIyLS4wMDFoLTIuMjQ0Yy0uMzQ0IDAtLjYyMy4yOC0uNjIzLjYyM1YzNS4xM2EuNjIuNjIgMCAwIDAgLjYyMy42MmgyLjI0NGMuMzQ0IDAgLjYyNC0uMjc4LjYyNC0uNjJ2LTguMjhsNi4zOTcgOC42NGEuNjMuNjMgMCAwIDAgLjE1OC4xNTRjLjAxOC4wMTQuMDMyLjAyMi4wNDUuMDMuMDA2LjAwNC4wMTIuMDA4LjAxOC4wMXMuMDIuMDEuMDMuMDE0LjAyLjAwOC4wMy4wMTRsLjA2LjAyMmEuNjIuNjIgMCAwIDAgLjE2OC4wMjJoMi4yNDRhLjYyLjYyIDAgMCAwIC42MjMtLjYyVjIxLjJjMC0uMzQzLS4yOC0uNjIzLS42MjMtLjYyMyIvPjxwYXRoIGQ9Ik0yMC4wODcgMzIuMjY0aC02LjFWMjEuMmMwLS4zNDQtLjI4LS42MjMtLjYyMy0uNjIzSDExLjEyYy0uMzQ0IDAtLjYyMy4yOC0uNjIzLjYyM3YxMy45NDJhLjYyLjYyIDAgMCAwIC4xNzQuNDMxYy4wMTIuMDEyLjAxNC4wMTYuMDE2LjAxOC4xMTMuMTA3LjI2NC4xNzQuNDMuMTc0aDguOTY4Yy4zNDQgMCAuNjIzLS4yOC42MjMtLjYyM3YtMi4yNDVjMC0uMzQ0LS4yNzgtLjYyMy0uNjIzLS42MjNtMzMuMjU4LTguMjE0Yy4zNDQgMCAuNjIzLS4yOC42MjMtLjYyM1YyMS4yYzAtLjM0NC0uMjc4LS42MjMtLjYyMy0uNjIzaC04Ljk2OGMtLjE2OCAwLS4zMi4wNjctLjQzMi4xNzYtLjAxMi4wMS0uMDE2LjAxNC0uMDE4LjAxOC0uMTA3LjEtLjE3My4yNjItLjE3My40M3YxMy45NDNhLjYyLjYyIDAgMCAwIC4xNzQuNDMxbC4wMTYuMDE2YS42Mi42MiAwIDAgMCAuNDMxLjE3NGg4Ljk2OGMuMzQ0IDAgLjYyMy0uMjguNjIzLS42MjN2LTIuMjQ2YzAtLjM0NC0uMjc4LS42MjMtLjYyMy0uNjIzaC02LjA5OHYtMi4zNTdoNi4wOThhLjYyLjYyIDAgMCAwIC42MjMtLjYyM1YyNy4wNGMwLS4zNDQtLjI3OC0uNjI0LS42MjMtLjYyNGgtNi4wOThWMjQuMDZoNi4wOTh6Ii8+PC9nPjwvc3ZnPg==" style="width:24px; height:24px; border-radius:50%; object-fit:contain;" alt="LINE">
                    <?php else: ?>
                        <img src="<?php echo esc_url($icon_line); ?>" style="width:24px; height:24px; border-radius:50%; object-fit:contain;" alt="LINE">
                    <?php endif; ?>
                    <span>聯繫人工 LINE 客服</span>
                </div>
                <?php endif; ?>

                <?php if (!empty($form_shortcode)): ?>
                <!-- CF7 留言表單入口 (僅在有填寫短代碼時顯示) -->
                <div class="yoyi-option-btn" id="start-msg-form" style="background:#fdfdfd; border-color:#dcdcdc;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="<?php echo $color; ?>"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    <span>填寫表單留言給我們</span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 第二層：AI 聊天視窗 -->
        <div id="yoyi-bot-window" class="yoyi-window-layer">
            <div id="yoyi-bot-header">
                <span style="display: flex; align-items: center; gap: 8px;">
                    <span id="yoyi-bot-back" style="cursor:pointer; font-size:14px; margin-right:5px;">◀ 返回</span>
                    <?php echo esc_html($title); ?>
                </span>
                <span id="yoyi-bot-close">✖</span>
            </div>
            
            <div id="yoyi-bot-messages" style="display:flex; flex-direction:column;">
                <div class="yoyi-msg yoyi-msg-bot">嗨！我是 <?php echo esc_html($bot_name); ?>，有什麼我可以協助您的嗎？</div>
            </div>

            <div id="yoyi-qa-drawer">
                <div id="yoyi-qa-drawer-header">
                    <span>💡 點擊問題，快速解答</span>
                    <span id="yoyi-qa-drawer-close">✖</span>
                </div>
                <div id="yoyi-qa-drawer-body">
                </div>
            </div>

            <div class="yoyi-bottom-actions" id="yoyi-bottom-actions">
                <span id="yoyi-open-qa-btn" class="yoyi-action-btn" style="color: <?php echo $color; ?>; border-color: <?php echo $color; ?>;">
                    💡 常見問題
                </span>
                <?php if (!empty($form_shortcode)): ?>
                <span id="yoyi-go-to-msg-btn" class="yoyi-action-btn">
                    📝 找不到解答？點此留言
                </span>
                <?php endif; ?>
            </div>

            <div id="yoyi-bot-input-area">
                <input type="text" id="yoyi-chat-hp-check" style="display:none !important;" tabindex="-1" autocomplete="off" placeholder="leave this empty">
                <input type="text" id="yoyi-bot-input" placeholder="請輸入訊息...">
                <button id="yoyi-bot-send">發送</button>
            </div>
        </div>

        <?php if (!empty($form_shortcode)): ?>
        <!-- 第三層：CF7 訪客留言表單視窗 (僅在有填寫短代碼時輸出) -->
        <div id="yoyi-msg-window" class="yoyi-window-layer">
            <div id="yoyi-msg-header" style="background: <?php echo $color; ?>; color: #fff; padding: 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
                <span id="yoyi-msg-back" style="cursor:pointer; font-size:14px;">◀ 返回</span>
                <span>📝 留言給我們</span>
                <span id="yoyi-msg-close" style="cursor:pointer; font-size:18px;">✖</span>
            </div>
            <div id="yoyi-msg-body" style="padding: 20px; display: flex; flex-direction: column; background: #f8f9fa; flex: 1; overflow-y: auto;">
                <p style="font-size:13.5px; color:#555; margin: 0 0 15px 0; line-height:1.5;">如果機器人無法解答，請留下您的聯絡方式與問題，專人會盡快回覆您！</p>
                <?php echo do_shortcode($form_shortcode); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 懸浮按鈕 -->
        <?php if (empty($icon_ai)): ?>
            <div id="yoyi-bot-btn" style="background: <?php echo esc_attr($color); ?>;">
                <!-- 標準無變形單對話泡泡 SVG (尾巴向右偏移) -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffffff" style="width:28px; height:28px;">
                    <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h2v4l4-4h10c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                </svg>
            </div>
        <?php else: ?>
            <div id="yoyi-bot-btn" style="background: #ffffff;">
                <img src="<?php echo esc_url($icon_ai); ?>" style="width:100%; height:100%; border-radius:50%; object-fit:contain;" alt="客服選單">
            </div>
        <?php endif; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('yoyi-bot-btn');
        const menu = document.getElementById('yoyi-options-menu');
        const chatWin = document.getElementById('yoyi-bot-window');
        const msgWin = document.getElementById('yoyi-msg-window');
        
        const menuClose = document.getElementById('yoyi-menu-close');
        const chatClose = document.getElementById('yoyi-bot-close');
        const msgClose = document.getElementById('yoyi-msg-close');
        
        const chatBack = document.getElementById('yoyi-bot-back');
        const msgBack = document.getElementById('yoyi-msg-back');
        
        const startAiChat = document.getElementById('start-ai-chat');
        const startFbChat = document.getElementById('start-fb-chat');
        const startLineChat = document.getElementById('start-line-chat');
        const startMsgForm = document.getElementById('start-msg-form');
        
        const bottomActions = document.getElementById('yoyi-bottom-actions');
        const goToMsgBtn = document.getElementById('yoyi-go-to-msg-btn');
        const openQaBtn = document.getElementById('yoyi-open-qa-btn');
        const qaDrawer = document.getElementById('yoyi-qa-drawer');
        const qaDrawerClose = document.getElementById('yoyi-qa-drawer-close');
        const qaDrawerBody = document.getElementById('yoyi-qa-drawer-body');
        
        const send = document.getElementById('yoyi-bot-send');
        const input = document.getElementById('yoyi-bot-input');
        const msgs = document.getElementById('yoyi-bot-messages');
        const chatHpInput = document.getElementById('yoyi-chat-hp-check');
        
        let isTyping = false;

        let scrollTimeout;

        function showBotBtnTemp() {
            if (btn.style.display === 'none') return;
            btn.classList.add('is-visible');
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                if (btn.style.display !== 'none') {
                    btn.classList.remove('is-visible');
                }
            }, 2500); 
        }

        btn.classList.add('is-visible');
        scrollTimeout = setTimeout(() => {
            if (btn.style.display !== 'none') {
                btn.classList.remove('is-visible');
            }
        }, 3000);

        window.addEventListener('scroll', showBotBtnTemp, { passive: true });
        window.addEventListener('touchstart', showBotBtnTemp, { passive: true });
        window.addEventListener('mousemove', showBotBtnTemp, { passive: true });

        const qaDataString = <?php echo json_encode(get_option('yoyi_bot_custom_qa', '[]')); ?>;
        let qaData = [];
        try { 
            qaData = JSON.parse(qaDataString); 
            if(typeof qaData === 'string') qaData = JSON.parse(qaData); 
        } catch(e) {}

        if (qaData && qaData.length > 0) {
            let hasValidQa = false;
            qaData.forEach(qa => {
                if (qa.question && qa.question.trim() !== '') {
                    hasValidQa = true;
                    const qBtn = document.createElement('button');
                    qBtn.className = 'yoyi-quick-reply-btn';
                    qBtn.innerText = qa.question;
                    qBtn.onclick = () => {
                        qaDrawer.classList.remove('open');
                        sendMessage(qa.question);
                    };
                    qaDrawerBody.appendChild(qBtn);
                }
            });
            if (!hasValidQa && openQaBtn) openQaBtn.style.display = 'none';
        } else {
            if (openQaBtn) openQaBtn.style.display = 'none';
        }

        // 動態隱藏底部按鈕區
        if (bottomActions) {
            const hasQaBtn = openQaBtn && openQaBtn.style.display !== 'none';
            const hasMsgBtn = !!goToMsgBtn;
            if (!hasQaBtn && !hasMsgBtn) {
                bottomActions.style.display = 'none';
            }
        }

        if (openQaBtn) {
            openQaBtn.onclick = () => qaDrawer.classList.add('open');
        }
        if (qaDrawerClose) {
            qaDrawerClose.onclick = () => qaDrawer.classList.remove('open');
        }

        function hideAll() {
            if (menu) menu.style.display = 'none';
            if (chatWin) chatWin.style.display = 'none';
            if (msgWin) msgWin.style.display = 'none';
            if (qaDrawer) qaDrawer.classList.remove('open');
        }

        function showMenu() { hideAll(); if (menu) menu.style.display = 'flex'; }
        function showChat() { hideAll(); if (chatWin) chatWin.style.display = 'flex'; scrollToBottom(); }
        function showMsgForm() { hideAll(); if (msgWin) msgWin.style.display = 'flex'; }

        btn.onclick = () => { 
            btn.style.display = 'none'; 
            btn.classList.remove('is-visible');
            showMenu(); 
        }
        
        const closeAll = () => {
            hideAll();
            btn.style.display = 'flex';
            showBotBtnTemp();
        };

        if (menuClose) menuClose.onclick = closeAll;
        if (chatClose) chatClose.onclick = closeAll;
        if (msgClose) msgClose.onclick = closeAll;
        
        if (chatBack) chatBack.onclick = showMenu;
        if (msgBack) msgBack.onclick = showMenu;

        if (startAiChat) startAiChat.onclick = showChat;
        if (startFbChat) startFbChat.onclick = () => { hideAll(); btn.style.display = 'flex'; showBotBtnTemp(); }
        if (startMsgForm) startMsgForm.onclick = showMsgForm;
        if (goToMsgBtn) goToMsgBtn.onclick = showMsgForm;

        if (startLineChat) startLineChat.onclick = () => {
            showChat();
            const typingDiv = appendMessage('bot', '<span style="opacity:0.6" class="yoyi-typing-text">系統連線中</span>');
            const formData = new URLSearchParams();
            formData.append('action', 'yoyi_bot_line_contact');

            fetch('<?php echo $ajax_url; ?>', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData 
            })
            .then(res => res.json())
            .then(data => {
                msgs.removeChild(typingDiv);
                if (data.success) {
                    appendMessage('bot', data.data.reply);
                } else {
                    appendMessage('bot', '系統連線發生異常，請稍後再試。');
                }
            })
            .catch(() => {
                msgs.removeChild(typingDiv);
                appendMessage('bot', '網路連線失敗，請檢查網路狀態。');
            });
        }

        document.addEventListener('wpcf7mailsent', function() {
            setTimeout(() => { showMenu(); }, 2500);
        }, false);

        function scrollToBottom() { setTimeout(() => { msgs.scrollTop = msgs.scrollHeight; }, 50); }
        
        function linkify(inputText) {
            var pattern = /(<[^>]+>)|(\b(https?):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
            return inputText.replace(pattern, function(match, htmlTag, url) {
                if (htmlTag) { return htmlTag; }
                if (url) { return '<a href="' + url + '" target="_blank" rel="noopener noreferrer">' + url + '</a>'; }
                return match;
            });
        }

        function appendMessage(sender, text) {
            const div = document.createElement('div');
            div.className = 'yoyi-msg yoyi-msg-' + sender;
            if (sender === 'bot') {
                div.innerHTML = linkify(text);
            } else {
                div.innerHTML = text;
            }
            msgs.appendChild(div);
            scrollToBottom();
            return div;
        }

        function sendMessage(forcedText = null) {
            const text = forcedText || input.value.trim();
            const hpValue = chatHpInput.value; 
            if (!text || isTyping) return;

            appendMessage('user', text);
            if (!forcedText) { input.value = ''; }
            isTyping = true;

            const typingDiv = appendMessage('bot', '<span style="opacity:0.6" class="yoyi-typing-text">正在為您解答</span>');

            const formData = new URLSearchParams();
            formData.append('action', 'yoyi_bot_chat');
            formData.append('yoyi_hp', hpValue); 
            formData.append('message', text);

            fetch('<?php echo $ajax_url; ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                msgs.removeChild(typingDiv);
                if (data.success) {
                    appendMessage('bot', data.data.reply);
                } else {
                    appendMessage('bot', '系統提示：' + (data.data || '系統發生錯誤，請稍後再試。'));
                }
            })
            .catch(() => {
                msgs.removeChild(typingDiv);
                appendMessage('bot', '網路連線失敗，請檢查網路狀態。');
            })
            .finally(() => {
                isTyping = false;
            });
        }

        if (send) send.onclick = () => sendMessage();
        if (input) input.onkeypress = (e) => { if (e.key === 'Enter') sendMessage(); }
    });
    </script>
    <?php
}