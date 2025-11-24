<script>
var is_loading = false;
const helpList=<?= $helpList ?>;

let postList = [];
function getLocalDateTimeString() {
    const now = new Date();
    const pad = n => n.toString().padStart(2, '0');
    return `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ` +
           `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
}
function load_iframe(form) {
    if (is_loading) {
        console.log('wait for loading');
        return false;
    }

    is_loading = true;

    const btn = form.querySelector('button[type="submit"]');

    // 檢查是否已經加過 loading span（避免重複）
    if (!btn.querySelector('.loading-text')) {
        const span = document.createElement('span');
        span.className = 'loading-text';
        span.style.marginLeft = '8px';
        span.innerText = '⏳ 執行中...';
        btn.appendChild(span);
    }

    btn.disabled = true;

    const url = form.getAttribute('action');
    const formData = new FormData(form);
    const sendTime = getLocalDateTimeString();
    const i_send_url = form.querySelector('input[name="i_send_url"]')?.value || url;

    const headerInputs = form.querySelectorAll('.i_input_header');
    const headers = {};
    headerInputs.forEach(input => {
        const key = input.getAttribute('data-key')?.trim();
        const value = input.value?.trim();
        if (key && value) {
            headers[key] = value;
        }
    });

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: headers
    })
    .then(response => response.text())
    .then(data => {
        is_loading = false;
        btn.disabled = false;
        const loadingSpan = btn.querySelector('.loading-text');
        if (loadingSpan) loadingSpan.remove();

        const receiveTime = getLocalDateTimeString();
        const newPost = {
            sendTime: sendTime,
            receiveTime: receiveTime,
            url: url,
            i_send_url: i_send_url,
            rawData: data
        };
        postList.push(newPost);

        const historyList = document.getElementById('history_list');
        const li = document.createElement('li');
        li.textContent = `[${postList.length - 1}] ${newPost.i_send_url}`;
        li.setAttribute('data-index', postList.length - 1);
        li.onclick = function () {
            showPost(this.getAttribute('data-index'));
        };
        historyList.insertBefore(li, historyList.firstChild);

        showPost(postList.length - 1);
    })
    .catch(error => {
        is_loading = false;
        btn.disabled = false;
        const loadingSpan = btn.querySelector('.loading-text');
        if (loadingSpan) loadingSpan.remove();

        const receiveTime = getLocalDateTimeString();
        const newPost = {
            sendTime: sendTime,
            receiveTime: receiveTime,
            url: url,
            i_send_url: i_send_url,
            rawData: `{"error":"${error}"}`
        };
        postList.push(newPost);

        const historyList = document.getElementById('history_list');
        const li = document.createElement('li');
        li.textContent = `[${postList.length - 1}] ${newPost.i_send_url}`;
        li.setAttribute('data-index', postList.length - 1);
        li.onclick = function () {
            showPost(this.getAttribute('data-index'));
        };
        historyList.insertBefore(li, historyList.firstChild);

        showPost(postList.length - 1);
    });

    return false;
}



function showPost(index) {
    if (index === "") return;
    const post = postList[index];
    const resultDiv = document.getElementById('result_div');

    let html = `
    <p><b>發送網址：</b>${post.i_send_url}</p>
    <p><b>發送時間：</b>${post.sendTime} <b>回傳時間：</b>${post.receiveTime}</p>
    <hr>
    `;

    try {
        const json = JSON.parse(post.rawData);
        if (json.error) {
            html += `<h3>error</h3><div class="pre-toggle"><textarea class="text_area_view">${json.error}</textarea></div>`;
        }
        html += `<h3>發送 Headers</h3><div class="pre-toggle"><button class="format-toggle-btn" onclick="toggleFormat(this)">Minify</button><textarea class="text_area_view">${formatJSON(json.requestHeaders)}</textarea></div>`;
        if (json.body) {
            if (isFormatJSON(json.body)) {
                html += `<h3>回應 Body</h3><div class="pre-toggle"><button class="format-toggle-btn" onclick="toggleFormat(this)">Minify</button><textarea class="text_area_view html_body_bg">${formatJSON(json.body)}</textarea></div>`;

                // 🔥 新增：回應 Body 說明表格
                html += `<h3>回應 Body 說明</h3>`;
                html += `<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">`;
                html += `<tr><th>Key</th><th>Type</th><th>Value</th><th>Help</th></tr>`;

                for (const key in json.body) {
                    if (helpList[key]) {
                        html += `<tr>
                            <td>${key}</td>
                            <td>${json.body[key]}</td>
                            <td>${helpList[key].type}</td>
                            <td>${helpList[key].help}</td>
                        </tr>`;
                        const targetElements = document.querySelectorAll(`.async_${key}`);

                        targetElements.forEach(targetElement => {
                            console.log("async:", `.async_${key}`);
                            targetElement.value=json.body[key];
                        });
                    }
                }
                html += `</table>`;
            } else {
                html += `<h3>回應 Body</h3><div class="pre-toggle"><div class="html_body">${formatJSON(json.body)}</div></div>`;
            }
        }

        if (json.sendBody) {
            html += `<h3>發送 Body</h3><div class="pre-toggle"><textarea class="text_area_view">${json.sendBody}</textarea></div>`;
        }
        if (json.send) {
            html += `<h3>發送組合</h3><div class="pre-toggle"><button class="format-toggle-btn" onclick="toggleFormat(this)">Minify</button><textarea class="text_area_view">${formatJSON(json.send)}</textarea></div>`;
        }
        if (json.responseHeaders) {
            html += `<h3>回應 Headers</h3><div class="pre-toggle"><button class="format-toggle-btn" onclick="toggleFormat(this)">Minify</button><textarea class="text_area_view">${formatJSON(json.responseHeaders)}</textarea></div>`;
        }


    } catch (e) {
        html += `<h3 style="color:red;">無法解析回傳資料</h3><div class="pre-toggle"><pre>${post.rawData}</pre></div>`;
    }

    resultDiv.innerHTML = html;
    const textareas = document.querySelectorAll('.text_area_view');

    textareas.forEach(textarea => {
        // 初始化
        autoResize(textarea);

        // 當輸入時調整高度
        textarea.addEventListener('input', () => autoResize(textarea));
    });

    // 🪄 自動高度函數
    function autoResize(textarea) {
        textarea.style.height = 'auto'; // 先清空高度
        textarea.style.height = (textarea.scrollHeight) + 'px'; // 設成內容高度
    }

}

function formatJSON(obj) {
    try {
        // 如果是字串，嘗試 parse 成 JSON
        if (typeof obj === 'string') {
            obj = JSON.parse(obj);
        }

        // 格式化並轉義 HTML 字元
        return JSON.stringify(obj, null, 2)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    } catch (e) {
        // 如果不是 JSON，就回傳原始資料（強制轉為字串）
        return String(obj);
    }
}

function isFormatJSON(obj) {
    try {
        // 如果是字串，嘗試 parse 成 JSON
        if (typeof obj === 'string') {
            obj = JSON.parse(obj);
        }

        // 格式化並轉義 HTML 字元
        return true;
    } catch (e) {
        // 如果不是 JSON，就回傳原始資料（強制轉為字串）
        return false;
    }
}

function toggleFormat(button) {
    const textarea = button.parentElement.querySelector('.text_area_view');
    let jsonText = textarea.value.trim();

    try {
        const json = JSON.parse(jsonText);
        if (button.textContent === "Format") {
            textarea.value = formatJSON(json);
            button.textContent = "Minify";
        } else {
            textarea.value = JSON.stringify(json);
            button.textContent = "Format";
        }
    } catch (e) {
        alert("內容不是有效的 JSON，無法格式化");
    }

    // 重新調整高度
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

let page = 1;
let hasMore = true;
function getLog() {
    if (!hasMore) return;

    const btn = document.getElementById('btn_has_more');
    if (btn) btn.disabled = true; // 點下後暫時 disable，避免重複點擊

    // 新增：取得最新 log（JSON 格式）
    fetch('post_get_log.php?page=' + page, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(logData => {
        console.log('Log fetch ok:');
        const historyList = document.getElementById('history_list');
        if (historyList) {
            // 清除原本的內容（或改用 += 追加）
            //logContainer.innerHTML = '';
            logs = logData.logs;
            logs.forEach(log => {
                console.log(log);
                const receiveTime = getLocalDateTimeString();
                is_loading = false;

                const newPost = {
                    sendTime: log.get_at,
                    receiveTime: log.created_at,
                    url: log.url,
                    i_send_url: log.url,
                    rawData: log.log
                };
                postList.push(newPost);


                const li = document.createElement('li');
                li.textContent = `[${postList.length - 1}] ${newPost.i_send_url}`;
                li.setAttribute('data-index', postList.length - 1);
                li.onclick = function () {
                    showPost(this.getAttribute('data-index'));
                };
                historyList.appendChild(li);

                showPost(postList.length - 1);
            });

            hasMore = logData.has_more;

            // 控制按鈕是否可繼續按
            if (btn) {
                btn.disabled = !hasMore;
            }

            if (hasMore) {
                page++;
                if (btn) btn.disabled = false; // 發生錯誤時讓按鈕可以再點
            }

        }
    })
    .catch(err => {
        console.error('Log fetch error:', err);
    });
}
getLog();

<?php
addAsyncInput($async);
?>

</script>
