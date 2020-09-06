<div class="userback-container">
    <h2>Userback</h2>
    <div class="setting-title">Settings</div>
    <form class="setting">
        <div class="setting-row" data-type="all">
            <div class="setting-label">Enable Userback</div>
            <div class="setting-value"><input type="checkbox" name="rp-is-active"></div>
        </div>
        <div class="setting-row" data-type="all">
            <div class="setting-label">Logged in users only</div>
            <div class="setting-value"><input type="checkbox" name="rp-logged-in-only"></div>
        </div>
        <div class="setting-row" data-type="all">
            <div class="setting-label">Page:</div>
            <div class="setting-value">
                <select name="rp-page" size="10" multiple>
                    <option value="0">All Pages and Blog Posts</option>
                    <option value="-1">All Pages and Blog Posts (Draft and Pending Review only)</option>
                    <option value="-2">All Pages</option>
                    <option value="-3">All Pages (Draft and Pending Review only)</option>
                    <option value="-4">All Blog Posts</option>
                    <option value="-5">All Blog Posts (Draft and Pending Review only)</option>
                </select>
            </div>
        </div>
        <div class="setting-row" data-type="all">
            <div class="setting-label">Widget Code:<br><a href="https://app.userback.io/dashboard/?get_code=2" target="_blank">Get your widget code here</a></div>
            <div class="setting-value"><textarea name="rp-widget-code" rows="15" spellcheck="false" required></textarea></div>
        </div>
        <br>
        <button class="button button-primary" id="save">Save</button>
    </form>
</div>