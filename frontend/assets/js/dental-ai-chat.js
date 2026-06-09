/* ── MediTrust Dental AI Chat Card ──
   Injects an interactive card into #aiChatBody
   when the "Phân tích răng" quick button is clicked.
   Shares localStorage['groq_key'] with dental-ai/ app.
*/
(function () {
  'use strict';

  const GROQ_API   = 'https://api.groq.com/openai/v1/chat/completions';
  const GROQ_MODEL = 'meta-llama/llama-4-scout-17b-16e-instruct';

  let currentB64 = null;
  let camStream  = null;

  const $ = id => document.getElementById(id);
  function esc(s) {
    return String(s || '')
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function scrollChat() {
    const body = $('aiChatBody');
    if (body) body.scrollTop = body.scrollHeight;
  }

  /* ── Init ── */
  function init() {
    // Listen for the dental quick button anywhere in the document
    document.addEventListener('click', e => {
      if (e.target.closest('[data-quick="dental-scan"]')) openCard();
    });
    // File input change (static element in footer)
    $('dntFile')?.addEventListener('change', () => {
      const f = $('dntFile').files[0];
      if (f) { loadFile(f); $('dntFile').value = ''; }
    });
  }
  document.readyState === 'loading'
    ? document.addEventListener('DOMContentLoaded', init)
    : init();

  /* ── Open: inject card into chat body ── */
  function openCard() {
    const body = $('aiChatBody');
    if (!body) return;

    // Remove any existing card first
    body.querySelector('.dnt-card')?.remove();
    stopCam();

    // User bubble
    const userBubble = document.createElement('div');
    userBubble.className = 'ai-chat-message ai-chat-message--user';
    userBubble.innerHTML = '<div class="ai-chat-message-content">🦷 Phân tích ảnh răng</div>';
    body.appendChild(userBubble);

    // Dental AI card (AI bubble)
    const card = buildCard();
    body.appendChild(card);
    scrollChat();

    bindCardEvents();
  }

  /* ── Build card HTML ── */
  function buildCard() {
    const el = document.createElement('div');
    el.className = 'ai-chat-message ai-chat-message--ai dnt-card';

    const needKey = !getKey();
    el.innerHTML = `
      <div class="dnt-card-hd">🦷 Phân tích ảnh răng AI <small>— chọn hoặc chụp ảnh để bắt đầu</small></div>

      ${needKey ? `
      <div class="dnt-key-inline" id="dntKeyInline">
        <input type="password" id="dntApiKey" placeholder="Groq API key: gsk_..." autocomplete="off"/>
        <button id="dntSaveKey">Lưu</button>
      </div>` : ''}

      <!-- S1: Upload -->
      <div id="dntS1">
        <div class="dnt-drop" id="dntDrop" tabindex="0" role="button">
          <i class="bi bi-cloud-arrow-up-fill"></i>
          <span>Kéo thả hoặc bấm chọn ảnh răng</span>
          <em>JPG, PNG · tối đa 10MB</em>
        </div>
        <button type="button" class="ai-quick-btn dnt-cam-btn" id="dntCamBtn">
          <i class="bi bi-camera-fill"></i> Dùng Camera
        </button>
      </div>

      <!-- S2: Camera -->
      <div id="dntS2" style="display:none">
        <div class="dnt-cam-box">
          <video id="dntCamFeed" autoplay playsinline muted></video>
          <div class="dnt-cam-err" id="dntCamHelp" style="display:none">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span id="dntCamHelpMsg">Không thể truy cập camera</span>
          </div>
        </div>
        <div class="dnt-cam-bar">
          <button type="button" class="dnt-capture-btn" id="dntCapture">
            <i class="bi bi-lightning-charge-fill"></i> Chụp ảnh
          </button>
          <button type="button" class="dnt-stop-btn" id="dntStopCam">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
      </div>

      <!-- S3: Preview + Analyze -->
      <div id="dntS3" style="display:none">
        <div class="dnt-img-wrap">
          <img id="dntImg" alt="Ảnh răng"/>
          <button type="button" class="dnt-img-x" id="dntClear">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <button type="button" class="dnt-analyze-btn" id="dntAnalyze">
          <i class="bi bi-search-heart"></i> Phân tích AI
        </button>
      </div>

      <!-- S4: Loading -->
      <div id="dntS4" style="display:none" class="dnt-loading-state">
        <div class="dnt-loading-row">
          <span class="dnt-spin">🦷</span>
          <div>
            <div class="dnt-loading-txt">AI đang phân tích ảnh của bạn...</div>
            <div class="dnt-steps" id="dntSteps">
              <span class="dst active">Đọc ảnh</span>
              <span class="dst">Phát hiện</span>
              <span class="dst">Phân tích</span>
              <span class="dst">Tổng hợp</span>
            </div>
          </div>
        </div>
        <div class="dnt-prog"><div class="dnt-prog-fill" id="dntProgBar"></div></div>
      </div>

      <!-- S5: Results -->
      <div id="dntS5" style="display:none">
        <div class="dnt-score-row">
          <div class="dnt-score-circle" id="dntScore">0</div>
          <div>
            <span class="dnt-status-pill" id="dntStatusPill"></span>
            <p id="dntStatusDesc"></p>
          </div>
        </div>
        <div class="dnt-res-lbl"><i class="bi bi-clipboard2-pulse"></i> Vấn đề phát hiện</div>
        <div class="dnt-issues" id="dntIssues"></div>
        <div class="dnt-res-lbl"><i class="bi bi-patch-check-fill"></i> Dịch vụ khuyến nghị</div>
        <div class="dnt-recs" id="dntRecs"></div>
        <div class="dnt-advice" id="dntAdvice" style="display:none"></div>
        <button type="button" class="dnt-book-btn" id="dntBook">
          <i class="bi bi-calendar2-check"></i> Đặt lịch khám ngay
        </button>
        <button type="button" class="dnt-again-btn" id="dntAgain">
          <i class="bi bi-arrow-counterclockwise"></i> Phân tích ảnh khác
        </button>
      </div>
    `;
    return el;
  }

  /* ── Bind events on the injected card ── */
  function bindCardEvents() {
    // API key save
    $('dntSaveKey')?.addEventListener('click', () => {
      const v = $('dntApiKey')?.value.trim();
      if (v?.startsWith('gsk_') && v.length > 20) {
        localStorage.setItem('groq_key', v);
        $('dntKeyInline') && ($('dntKeyInline').style.display = 'none');
      } else if ($('dntApiKey')) {
        $('dntApiKey').style.borderColor = '#ef4444';
      }
    });
    $('dntApiKey')?.addEventListener('keydown', e => { if (e.key === 'Enter') $('dntSaveKey')?.click(); });

    // Upload drop zone
    const drop = $('dntDrop');
    if (drop) {
      drop.addEventListener('click', () => $('dntFile')?.click());
      drop.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') $('dntFile')?.click(); });
      ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => {
        e.preventDefault(); drop.classList.add('dragover');
      }));
      ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => {
        e.preventDefault(); drop.classList.remove('dragover');
        if (ev === 'drop') {
          const f = e.dataTransfer?.files[0];
          if (f?.type.startsWith('image/')) loadFile(f);
        }
      }));
    }

    // Camera
    $('dntCamBtn')?.addEventListener('click', startCam);
    $('dntCapture')?.addEventListener('click', capturePhoto);
    $('dntStopCam')?.addEventListener('click', stopCam);

    // Preview controls
    $('dntClear')?.addEventListener('click', () => {
      currentB64 = null;
      showState(1);
    });
    $('dntAnalyze')?.addEventListener('click', runAnalysis);

    // Results
    $('dntBook')?.addEventListener('click', () => {
      // Trigger existing appointment flow
      setTimeout(() => document.querySelector('[data-quick="appointment"]')?.click(), 50);
    });
    $('dntAgain')?.addEventListener('click', () => {
      currentB64 = null;
      $('dntImg') && ($('dntImg').src = '');
      showState(1);
    });
  }

  /* ── State switcher ── */
  function showState(n) {
    [1,2,3,4,5].forEach(i => {
      const el = $(`dntS${i}`);
      if (el) el.style.display = i === n ? '' : 'none';
    });
    scrollChat();
  }

  /* ── File load + resize ── */
  function loadFile(file) {
    if (file.size > 10 * 1024 * 1024) return;
    const reader = new FileReader();
    reader.onload = e => {
      const img = new Image();
      img.onload = () => {
        const ratio = Math.min(1024 / img.width, 1024 / img.height, 1);
        const cv = document.createElement('canvas');
        cv.width  = Math.round(img.width  * ratio);
        cv.height = Math.round(img.height * ratio);
        cv.getContext('2d').drawImage(img, 0, 0, cv.width, cv.height);
        currentB64 = cv.toDataURL('image/jpeg', 0.88).split(',')[1];
        if ($('dntImg')) $('dntImg').src = `data:image/jpeg;base64,${currentB64}`;
        showState(3);
      };
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }

  /* ── Camera ── */
  async function startCam() {
    const secure = location.protocol === 'https:'
                || ['localhost','127.0.0.1'].includes(location.hostname)
                || location.hostname.endsWith('.localhost');
    showState(2);
    if ($('dntCamHelp')) $('dntCamHelp').style.display = 'none';
    if (!secure || !navigator.mediaDevices?.getUserMedia) {
      showCamErr('Camera cần HTTPS hoặc localhost. Hãy dùng tính năng upload ảnh.');
      return;
    }
    try {
      camStream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 } }, audio: false,
      }).catch(() => navigator.mediaDevices.getUserMedia({ video: true, audio: false }));
      if ($('dntCamFeed')) $('dntCamFeed').srcObject = camStream;
    } catch (err) {
      const msg = err.name === 'NotAllowedError'  ? 'Trình duyệt chặn camera — cấp quyền trong thanh địa chỉ'
                : err.name === 'NotFoundError'     ? 'Không tìm thấy camera'
                : err.name === 'NotReadableError'  ? 'Camera đang bận bởi app khác'
                : 'Không mở được camera';
      showCamErr(msg);
    }
  }
  function showCamErr(msg) {
    if ($('dntCamHelpMsg')) $('dntCamHelpMsg').textContent = msg;
    if ($('dntCamHelp')) $('dntCamHelp').style.display = 'flex';
  }
  function capturePhoto() {
    const feed = $('dntCamFeed'), cv = $('dntCamCanvas');
    if (!feed || !cv) return;
    cv.width = feed.videoWidth || 640; cv.height = feed.videoHeight || 480;
    cv.getContext('2d').drawImage(feed, 0, 0);
    currentB64 = cv.toDataURL('image/jpeg', 0.88).split(',')[1];
    stopCam();
    if ($('dntImg')) $('dntImg').src = `data:image/jpeg;base64,${currentB64}`;
    showState(3);
  }
  function stopCam() {
    camStream?.getTracks().forEach(t => t.stop()); camStream = null;
    if ($('dntCamFeed')) $('dntCamFeed').srcObject = null;
  }

  /* ── Analysis ── */
  async function runAnalysis() {
    const key = getKey();
    if (!key) {
      if ($('dntKeyInline')) $('dntKeyInline').style.display = 'flex';
      $('dntApiKey')?.focus();
      return;
    }
    if (!currentB64) return;
    const btn = $('dntAnalyze');
    if (btn) btn.disabled = true;
    showState(4);
    animateProgress();

    try {
      const result = await callGroq(key, currentB64);
      stopProgress();
      renderResults(result);
      showState(5);
    } catch (err) {
      stopProgress();
      showState(3);
      if (btn) btn.disabled = false;
      // Show error message in chat body
      const errEl = document.createElement('div');
      errEl.className = 'ai-chat-message ai-chat-message--ai';
      errEl.innerHTML = `<div class="ai-chat-message-content" style="color:#dc2626">⚠ ${esc(err.message)}</div>`;
      $('aiChatBody')?.appendChild(errEl);
      scrollChat();
    }
  }

  /* ── Groq API ── */
  async function callGroq(key, b64) {
    const prompt = `Phân tích ảnh răng. Trả về JSON thuần (không markdown):
{"health_score":<0-100>,"overall_status":"<green|amber|red>","status_title":"<tiêu đề>","status_description":"<1 câu>","issues":[{"name":"<tên>","severity":"<green|amber|red>","description":"<ngắn>"}],"recommendations":[{"service":"<tên>","icon":"<emoji>","priority":"<urgent|soon|routine>","reason":"<lý do>","cost_range":"<VNĐ>"}],"general_advice":"<1-2 câu>"}
Nếu không phải ảnh răng: {"error":"..."}. Tiếng Việt.`;

    const res = await fetch(GROQ_API, {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${key}`, 'Content-Type': 'application/json' },
      body: JSON.stringify({
        model: GROQ_MODEL,
        messages: [{ role: 'user', content: [
          { type: 'image_url', image_url: { url: `data:image/jpeg;base64,${b64}` } },
          { type: 'text', text: prompt },
        ]}],
        max_tokens: 900, temperature: 0.25,
      }),
    });
    if (!res.ok) {
      const e = await res.json().catch(() => ({}));
      if (res.status === 401) throw new Error('API key không hợp lệ');
      if (res.status === 429) throw new Error('Quá nhiều yêu cầu, thử lại sau vài giây');
      throw new Error(e?.error?.message || `Lỗi máy chủ (${res.status})`);
    }
    const data  = await res.json();
    const raw   = data.choices?.[0]?.message?.content?.trim() || '';
    const match = raw.match(/\{[\s\S]*\}/);
    if (!match) throw new Error('Phản hồi AI không hợp lệ');
    const p = JSON.parse(match[0]);
    if (p.error) throw new Error(p.error);
    return p;
  }

  /* ── Render results ── */
  const ST = {
    green: { cls: 'green', lbl: 'Tốt' },
    amber: { cls: 'amber', lbl: 'Cần chú ý' },
    red:   { cls: 'red',   lbl: 'Cần điều trị' },
  };
  const PL = { urgent: 'Khẩn cấp', soon: 'Sớm', routine: 'Định kỳ' };

  function renderResults(d) {
    const sc = Math.max(0, Math.min(100, d.health_score || 0));
    const st = ST[d.overall_status] || ST.amber;

    const scoreEl = $('dntScore');
    if (scoreEl) { scoreEl.textContent = sc; scoreEl.className = `dnt-score-circle ${st.cls}`; }

    const pill = $('dntStatusPill');
    if (pill) { pill.textContent = d.status_title || st.lbl; pill.className = `dnt-status-pill ${st.cls}`; }
    if ($('dntStatusDesc')) $('dntStatusDesc').textContent = d.status_description || '';

    const iss = $('dntIssues');
    if (iss) {
      const list = Array.isArray(d.issues) ? d.issues.slice(0, 4) : [];
      iss.innerHTML = list.length
        ? list.map(i => `<div class="dnt-iss ${i.severity||'amber'}">
            <span class="dnt-iss-nm">${esc(i.name)}</span>
            <span class="dnt-iss-tx">${esc(i.description||'')}</span>
          </div>`).join('')
        : '<p style="font-size:12px;color:#10b981;font-weight:600;padding:4px 0">✓ Không phát hiện vấn đề đáng kể</p>';
    }

    const recs = $('dntRecs');
    if (recs) {
      recs.innerHTML = (Array.isArray(d.recommendations) ? d.recommendations.slice(0,3) : [])
        .map(r => `<div class="dnt-rec pri-${r.priority||'routine'}">
          <span class="dnt-rec-icon">${esc(r.icon||'🦷')}</span>
          <div>
            <div class="dnt-rec-top">
              <span class="dnt-rec-nm">${esc(r.service)}</span>
              <span class="dnt-rec-badge ${r.priority||'routine'}">${PL[r.priority]||'Định kỳ'}</span>
            </div>
            <div class="dnt-rec-rs">${esc(r.reason||'')}</div>
            ${r.cost_range ? `<div class="dnt-rec-cost">💰 ${esc(r.cost_range)}</div>` : ''}
          </div>
        </div>`).join('');
    }

    const adv = $('dntAdvice');
    if (adv && d.general_advice) { adv.textContent = '💡 ' + d.general_advice; adv.style.display = ''; }
  }

  /* ── Progress animation ── */
  let progTimer = null;
  const PROG = [10, 32, 65, 88, 96];
  function animateProgress() {
    const bar   = $('dntProgBar');
    const steps = document.querySelectorAll('.dst');
    if (bar) bar.style.width = '0%';
    steps.forEach(s => s.className = 'dst');
    let i = 0;
    progTimer = setInterval(() => {
      if (i >= PROG.length) { clearInterval(progTimer); return; }
      if (bar) bar.style.width = PROG[i] + '%';
      steps.forEach((s, j) => {
        if (j < i)      s.className = 'dst done';
        else if (j === i) s.className = 'dst active';
        else            s.className = 'dst';
      });
      i++;
    }, 650);
  }
  function stopProgress() {
    clearInterval(progTimer);
    const bar = $('dntProgBar');
    if (bar) bar.style.width = '100%';
    document.querySelectorAll('.dst').forEach(s => s.className = 'dst done');
  }

  /* ── Helpers ── */
  function getKey() { return localStorage.getItem('groq_key') || ''; }

})();
