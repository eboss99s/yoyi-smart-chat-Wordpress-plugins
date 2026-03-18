# ✨ Yoyi AI 智慧客服 (Yoyi Smart Chatbot for WordPress)

專為 WordPress 網站打造的純淨版 24 小時線上智能客服外掛。
結合 **Google Gemini API** 提供強大的 AI 彈性回覆，並支援「自訂關鍵字優先觸發」與多管道客服整合 (LINE, FB Messenger)，為您的網站提供全方位的自動化客服體驗。

![Version](https://img.shields.io/badge/Version-3.8.4-blue)
![WordPress](https://img.shields.io/badge/WordPress-Tested_&_Ready-brightgreen)
![License](https://img.shields.io/badge/License-Open_Source-green)

---

## 🌟 核心功能特色

* 🤖 **強大 AI 驅動**：完美串接 Google Gemini 2.5 Flash / Pro API，讓機器人根據您設定的「店家基本資訊」自然回答客人的問題。
* 🎯 **自訂問答優先觸發**：可自訂「關鍵字」與「標準答案」。當客人提問命中關鍵字時，系統會優先給出您設定的標準答案，確保重要資訊準確傳達。
* 🔀 **多管道客服整合**：不僅有 AI 聊天，還能在介面中無縫引導客戶前往 **LINE 官方帳號** 或 **Facebook Messenger** 尋求真人協助。
* 📝 **離線留言系統**：完美整合 **Contact Form 7**。當機器人無法解答時，客人可以直接在客服視窗內填寫 CF7 留言表單。
* ⚡ **極致效能與純淨代碼**：全站採用標準 SVG 向量圖形，不依賴龐大的外部字體庫；內建防刷頻 (Rate Limit) 機制，保護您的伺服器與 API 額度。
* 🎨 **高自訂性外觀**：可從 WordPress 後台輕鬆自訂客服視窗的主色調、機器人名稱與大頭貼。
* 📊 **對話紀錄保存**：後台自動保留最近 100 筆的完整對話紀錄，方便您隨時掌握客人的常見問題與 AI 的回覆狀況。

---

## 📥 安裝指南

這是一款標準的 WordPress 外掛，安裝過程非常簡單：

1. 前往右側的 **[Releases](https://github.com/eboss99s/yoyi-smart-chat-Wordpress-plugins/releases/latest)** 頁面。
2. 找到最新的版本，點擊 **`yoyi-bot.zip`** 下載安裝檔。
3. 登入您的 WordPress 後台。
4. 前往 `外掛` -> `安裝外掛` -> `上傳外掛`。
5. 選擇剛剛下載的 `yoyi-bot.zip` 檔案，點擊「立即安裝」並「啟用」。
6. 啟用後，左側選單會出現 **「Yoyi 客服」**，點擊進入設定中心。

---

## ⚙️ 快速設定步驟

為了讓 AI 正常運作，請完成以下基本設定：

1. **獲取 Gemini API Key**：
   * 前往 [Google AI Studio](https://aistudio.google.com/)。
   * 登入您的 Google 帳號，點擊「Get API key」免費建立一把密鑰。
2. **填寫 API 密鑰**：
   * 回到 WordPress 後台的「Yoyi 客服」設定頁面。
   * 切換到 **「🤖 AI 設定」** 頁籤，貼上您的 API Key 並儲存。
3. **建立背景知識**：
   * 切換到 **「🏢 基本資訊」** 頁籤。
   * 填寫您的品牌名稱、服務介紹、營業時間與聯絡方式。**填寫得越詳細，AI 的回答就會越精準！**
4. **設定留言表單 (選用)**：
   * 如果您希望客人可以留言，請先在 WordPress 安裝並建立好 Contact Form 7 表單。
   * 複製該表單的 Shortcode (例如：`[contact-form-7 id="123" title="聯絡表單"]`)。
   * 貼到「Yoyi 客服」-> **「⚙️ 基本設定」** 最下方的欄位即可。

---

## 👨‍💻 關於作者

**Yoyi (Huang Jin De)**
Yoyi Digital 創辦人 / 擁有 14 年專業經驗的攝影師與跨界開發者。
專精於前端開發 (HTML, CSS, JS) 與 WordPress (Flatsome 佈景主題) 網站建置。致力於透過數位工具與自動化系統，幫助企業提升營運效率與品牌價值。

* 如果這個外掛對您有幫助，歡迎在右上角點個 ⭐ **Star** 支持一下！
* 若有任何 Bug 或功能建議，歡迎開啟 Issue 討論。
