(() => {
  if (window.__AI_CHAT_V2__) return;
  window.__AI_CHAT_V2__ = true;

  function qs(id) {
    return document.getElementById(id);
  }

  function sanitize(text) {
    return String(text || '').replace(/[<>]/g, '');
  }

  function normalizeVi(text) {
    return String(text || '')
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .replace(/đ/g, 'd');
  }

  function formatTime() {
    const now = new Date();
    return now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
  }

  function formatDateYMD(date = new Date()) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
  }

  function isDateInPastYMD(dateValue) {
    const today = formatDateYMD();
    return String(dateValue || '') < today;
  }

  function isTodayYMD(dateValue) {
    return String(dateValue || '') === formatDateYMD();
  }

  const launcher = qs('aiChatLauncher');
  const panel = qs('aiChatPanel');
  const closeBtn = qs('aiChatClose');
  const body = qs('aiChatBody');
  const form = qs('aiChatForm');
  const input = qs('aiChatInput');
  const sendBtn = qs('aiChatSend');
  const voiceBtn = qs('aiChatVoice');
  const speechToggleBtn = qs('aiChatSpeechToggle');
  const quick = qs('aiChatQuick');

  const state = {
    messages: [],
    data: null,
    user: null,
    flow: {
      active: false,
      step: null,
      data: {}
    },
    pendingAppointment: null,
    voice: {
      supported: Boolean(window.SpeechRecognition || window.webkitSpeechRecognition),
      recognition: null,
      listening: false,
      transcript: '',
      speakEnabled: false
    }
  };

  if (!launcher || !panel) return;

  function setBusy(isBusy) {
    panel.classList.toggle('is-busy', isBusy);
    if (input) input.disabled = isBusy;
    if (sendBtn) sendBtn.disabled = isBusy;
    if (voiceBtn) voiceBtn.disabled = isBusy;
  }

  function openChat() {
    panel.classList.add('is-open');
    panel.setAttribute('aria-hidden', 'false');
    if (input) input.focus();
  }

  function closeChat() {
    panel.classList.remove('is-open');
    panel.setAttribute('aria-hidden', 'true');
    if (state.voice.listening && state.voice.recognition) {
      state.voice.recognition.stop();
    }
  }

  function toPlainText(html) {
    const box = document.createElement('div');
    box.innerHTML = String(html || '');
    return (box.textContent || box.innerText || '').replace(/\s+/g, ' ').trim();
  }

  function speakText(text) {
    if (!state.voice.speakEnabled || !('speechSynthesis' in window)) return;
    const content = toPlainText(text);
    if (!content) return;

    window.speechSynthesis.cancel();
    const utterance = new SpeechSynthesisUtterance(content);
    utterance.lang = 'vi-VN';
    utterance.rate = 1;
    utterance.pitch = 1;

    const voices = window.speechSynthesis.getVoices();
    const viVoice = voices.find(v => /^vi[-_]/i.test(v.lang));
    if (viVoice) utterance.voice = viVoice;

    window.speechSynthesis.speak(utterance);
  }

  function updateVoiceButtonState() {
    if (!voiceBtn) return;
    voiceBtn.classList.toggle('is-listening', state.voice.listening);
    voiceBtn.title = state.voice.listening ? 'Dừng ghi âm' : 'Nhập bằng giọng nói';
  }

  function updateSpeechToggleState() {
    if (!speechToggleBtn) return;
    speechToggleBtn.classList.toggle('is-active', state.voice.speakEnabled);
    speechToggleBtn.title = state.voice.speakEnabled ? 'Đang bật đọc phản hồi' : 'Đang tắt đọc phản hồi';
  }

  function initVoiceRecognition() {
    const Recognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!Recognition || state.voice.recognition) return;

    const recognition = new Recognition();
    recognition.lang = 'vi-VN';
    recognition.continuous = false;
    recognition.interimResults = true;
    recognition.maxAlternatives = 1;

    recognition.onstart = () => {
      state.voice.listening = true;
      state.voice.transcript = '';
      updateVoiceButtonState();
    };

    recognition.onresult = (event) => {
      let finalPart = '';
      let interimPart = '';

      for (let i = event.resultIndex; i < event.results.length; i++) {
        const chunk = event.results[i][0]?.transcript || '';
        if (event.results[i].isFinal) {
          finalPart += chunk + ' ';
        } else {
          interimPart += chunk;
        }
      }

      if (finalPart) {
        state.voice.transcript += finalPart;
      }

      const merged = `${state.voice.transcript} ${interimPart}`.trim();
      if (input) input.value = merged;
    };

    recognition.onerror = (event) => {
      if (event.error === 'not-allowed' || event.error === 'service-not-allowed') {
        appendMessage('Bạn chưa cấp quyền microphone cho trình duyệt.', 'ai');
      } else if (event.error !== 'no-speech') {
        appendMessage('Không thể nhận giọng nói. Bạn thử lại giúp mình nhé.', 'ai');
      }
    };

    recognition.onend = () => {
      state.voice.listening = false;
      updateVoiceButtonState();
      const text = (input?.value || state.voice.transcript || '').trim();
      state.voice.transcript = '';
      if (text) {
        if (input) input.value = '';
        handleSend(text);
      }
    };

    state.voice.recognition = recognition;
  }

  function toggleVoiceInput() {
    if (!state.voice.supported) {
      appendMessage('Trình duyệt chưa hỗ trợ nhập giọng nói. Bạn dùng Chrome/Edge bản mới để dùng tính năng này.', 'ai');
      return;
    }

    initVoiceRecognition();
    if (!state.voice.recognition) return;

    if (state.voice.listening) {
      state.voice.recognition.stop();
      return;
    }

    try {
      if (input) input.value = '';
      state.voice.recognition.start();
    } catch (e) {
      appendMessage('Microphone đang bận, bạn thử lại sau vài giây.', 'ai');
    }
  }

  function appendMessage(content, role, options = {}) {
    if (!body) return;
    const msg = document.createElement('div');
    msg.className = `ai-chat-message ai-chat-message--${role}`;
    if (options.typing) {
      msg.id = 'aiChatTypingMessage';
      msg.classList.add('ai-chat-message--typing');
      msg.innerHTML = '<div class="ai-chat-typing"><span></span><span></span><span></span></div>';
    } else {
      msg.innerHTML = `
        <div class="ai-chat-message-content">${content}</div>
        <div class="ai-chat-message-time">${formatTime()}</div>
      `;
    }
    body.appendChild(msg);
    body.scrollTop = body.scrollHeight;

    if (role === 'ai' && options.speak) {
      speakText(content);
    }
  }

  function showTyping() {
    hideTyping();
    appendMessage('', 'ai', { typing: true });
  }

  function hideTyping() {
    const typing = document.getElementById('aiChatTypingMessage');
    if (typing) typing.remove();
  }

  async function fetchData() {
    if (state.data) return state.data;
    try {
      const res = await fetch('index.php?page=ai-data');
      state.data = await res.json();
    } catch (err) {
      state.data = { doctors: [], services: [] };
      appendMessage('Không thể tải dữ liệu bác sĩ/dịch vụ lúc này.', 'ai');
    }
    return state.data;
  }

  async function fetchUser() {
    if (state.user) return state.user;
    try {
      const res = await fetch('index.php?page=ai-user-data');
      state.user = await res.json();
    } catch (err) {
      state.user = { logged_in: false };
    }
    return state.user;
  }

  async function sendToAi() {
    const res = await fetch('index.php?page=ai', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ messages: state.messages })
    });
    const data = await res.json();
    return data.reply || 'Hệ thống bận, vui lòng thử lại.';
  }

  function resetFlow() {
    state.flow = { active: false, step: null, data: {} };
  }

  function renderServicePills(services) {
    if (!services || !services.length) return;
    const pills = services.map(s =>
      `<button class="ai-chat-pill ai-chat-pill--service" data-action="pick-service" data-id="${s.id}" data-name="${sanitize(s.ten || '')}">
        ${sanitize(s.ten || '')}${s.gia ? ` - ${sanitize(s.gia)}` : ''}
      </button>`
    ).join('');
    appendMessage(`<div class="ai-chat-pill-group">${pills}</div>`, 'ai');
  }

  function filterDoctorsByService(doctors, serviceId, serviceName) {
    if (!doctors || !doctors.length) return [];
    if (!serviceId && !serviceName) return doctors;
    let filtered = doctors;
    if (serviceId) {
      filtered = doctors.filter(d => String(d.dich_vu_id || '') === String(serviceId));
    }
    if ((!filtered || !filtered.length) && serviceName) {
      filtered = doctors.filter(d => (d.chuyen_mon || '').toLowerCase().includes(String(serviceName).toLowerCase()));
    }
    return filtered && filtered.length ? filtered : doctors;
  }

  function findDoctorByInput(doctors, inputValue) {
    if (!Array.isArray(doctors) || doctors.length === 0) return null;
    const raw = String(inputValue || '').trim();
    if (!raw) return null;

    const byId = doctors.find(d => String(d.id || '') === raw);
    if (byId) return byId;

    const normalizedInput = normalizeVi(raw);
    return doctors.find(d => normalizeVi(d.ten || '').includes(normalizedInput)) || null;
  }

  function findServiceByInput(services, inputValue) {
    if (!Array.isArray(services) || services.length === 0) return null;
    const raw = String(inputValue || '').trim();
    if (!raw) return null;

    const byId = services.find(s => String(s.id || '') === raw);
    if (byId) return byId;

    const normalizedInput = normalizeVi(raw);
    return services.find(s => normalizeVi(s.ten || '').includes(normalizedInput)) || null;
  }

  function renderDoctorPills(doctors, serviceId, serviceName) {
    const list = filterDoctorsByService(doctors, serviceId, serviceName);
    if (!list || !list.length) return;
    const pills = list.slice(0, 8).map(d =>
      `<button class="ai-chat-pill" data-action="pick-doctor" data-id="${d.id}" data-name="${sanitize(d.ten || '')}">
        ${sanitize(d.ten || '')}
      </button>`
    ).join('');
    appendMessage(`<div class="ai-chat-pill-group">${pills}</div>`, 'ai');
  }

  function toHHmm(slot) {
    const raw = String(slot || '');
    return raw.length >= 5 ? raw.slice(0, 5) : raw;
  }

  function isPastTimeSlot(dateValue, slotValue) {
    if (!isTodayYMD(dateValue)) return false;
    const hhmm = toHHmm(slotValue);
    if (!/^\d{2}:\d{2}$/.test(hhmm)) return false;

    const [h, m] = hhmm.split(':').map(Number);
    const now = new Date();
    const nowMinutes = now.getHours() * 60 + now.getMinutes();
    const slotMinutes = h * 60 + m;
    return slotMinutes <= nowMinutes;
  }

  async function fetchAvailableTimeSlots(doctorId, dateValue) {
    if (!doctorId || !dateValue) return [];
    try {
      const res = await fetch('index.php?page=getAvailableTime', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: new URLSearchParams({
          bac_si_id: String(doctorId),
          ngay: String(dateValue)
        }).toString()
      });
      const data = await res.json();
      return Array.isArray(data) ? data : [];
    } catch (err) {
      return [];
    }
  }

  async function renderTimeSlotsForDate(container, doctorId, dateValue) {
    if (!container) return;
    if (!doctorId || !dateValue) {
      container.innerHTML = '<div class="ai-chat-meta">Vui lòng chọn ngày khám.</div>';
      return;
    }

    if (isDateInPastYMD(dateValue)) {
      container.innerHTML = '<div class="ai-chat-meta">Không thể chọn ngày trong quá khứ.</div>';
      return;
    }

    container.innerHTML = '<div class="ai-chat-meta">Đang tải giờ trống...</div>';
    const slots = await fetchAvailableTimeSlots(doctorId, dateValue);
    const filteredSlots = slots.filter(slot => !isPastTimeSlot(dateValue, slot));

    if (!filteredSlots.length) {
      container.innerHTML = '<div class="ai-chat-meta">Ngày này đã hết giờ phù hợp hoặc chưa có ca khám.</div>';
      return;
    }

    const html = filteredSlots.map(slot =>
      `<button class="ai-chat-pill ai-chat-pill--time" data-action="pick-time" data-id="${sanitize(slot)}" data-date="${sanitize(dateValue)}">
        ${sanitize(toHHmm(slot))}
      </button>`
    ).join('');
    container.innerHTML = `
      <div class="ai-chat-time-picker">
        <div class="ai-chat-time-title">Khung giờ khả dụng</div>
        <div class="ai-chat-time-grid">${html}</div>
      </div>
    `;
  }

  async function renderQuickDateTimePicker() {
    const minDate = formatDateYMD();
    const pickerHtml = `
      <div class="ai-chat-datetime">
        <div class="ai-chat-form-row">
          <input type="date" name="quick_date" min="${minDate}" value="${minDate}" data-action="pick-date">
        </div>
        <div class="ai-chat-meta">Chọn ngày để xem giờ trống và bấm chọn nhanh.</div>
        <div data-role="available-times"><div class="ai-chat-meta">Đang tải giờ trống...</div></div>
      </div>
    `;
    appendMessage(pickerHtml, 'ai');

    const blocks = body ? body.querySelectorAll('.ai-chat-datetime') : [];
    const latest = blocks.length ? blocks[blocks.length - 1] : null;
    const slotsContainer = latest ? latest.querySelector('[data-role="available-times"]') : null;
    await renderTimeSlotsForDate(slotsContainer, state.flow.data.doctor_id, minDate);
  }

  async function submitAppointment(data) {
    const user = await fetchUser();
    const payload = {
      name: data.name || (user.logged_in ? user.ho_ten : '') || '',
      email: data.email || (user.logged_in ? user.email : '') || '',
      phone: data.phone || (user.logged_in ? user.so_dien_thoai : '') || '',
      service_id: data.service_id || '',
      doctor_id: data.doctor_id || '',
      date: data.date || '',
      time: data.time || data.time_slot || '',
      message: data.note || ''
    };

    if (!payload.doctor_id || !payload.date) {
      appendMessage('Mình cần bác sĩ và ngày giờ khám để tạo lịch nhanh nhé.', 'ai');
      return;
    }

    if (!user.logged_in && (!payload.name || !payload.phone)) {
      appendMessage('Bạn vui lòng bổ sung họ tên và số điện thoại để gửi yêu cầu.', 'ai');
      return;
    }

    const res = await fetch('index.php?page=ai-appointment', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await res.json();
    appendMessage(result.message || 'Đã gửi yêu cầu đặt lịch.', 'ai');
    if (result.ok) {
      appendMessage('Trạng thái: Đang chờ phòng khám xác nhận.', 'ai');
    }
  }

  async function handleFlow(text) {
    if (!state.flow.active) return false;
    const value = String(text || '').trim();
    const data = await fetchData();

    if (state.flow.step === 'name') {
      if (!value) {
        appendMessage('Bạn vui lòng cho mình xin họ và tên nhé.', 'ai');
        return true;
      }
      state.flow.data.name = value;
      state.flow.step = 'phone';
      appendMessage('Cảm ơn bạn! Cho mình xin số điện thoại liên hệ?', 'ai');
      return true;
    }

    if (state.flow.step === 'phone') {
      if (!value) {
        appendMessage('Bạn vui lòng nhập số điện thoại nhé.', 'ai');
        return true;
      }
      state.flow.data.phone = value;
      state.flow.step = 'service';
      appendMessage('Bạn muốn đặt lịch cho dịch vụ nào? Chọn nhanh bên dưới hoặc nhập tên dịch vụ.', 'ai');
      renderServicePills(data.services || []);
      return true;
    }

    if (state.flow.step === 'service') {
      if (!value) {
        appendMessage('Bạn vui lòng chọn dịch vụ để mình gợi ý đúng bác sĩ.', 'ai');
        renderServicePills(data.services || []);
        return true;
      }

      const matchedService = findServiceByInput(data.services || [], value);
      if (!matchedService) {
        appendMessage('Mình chưa nhận ra dịch vụ này. Bạn chọn trong danh sách giúp mình nhé.', 'ai');
        renderServicePills(data.services || []);
        return true;
      }

      state.flow.data.service_id = matchedService.id;
      state.flow.data.service_name = matchedService.ten || '';
      state.flow.step = 'doctor';
      appendMessage(`Bạn đã chọn dịch vụ: ${sanitize(matchedService.ten || '')}. Tiếp theo chọn bác sĩ nhé.`, 'ai');
      renderDoctorPills(
        data.doctors || [],
        state.flow.data.service_id,
        state.flow.data.service_name
      );
      return true;
    }

    if (state.flow.step === 'doctor') {
      if (!value) {
        appendMessage('Bạn có thể chọn bác sĩ trong danh sách hoặc nhập tên.', 'ai');
        return true;
      }
      const doctorPool = filterDoctorsByService(
        data.doctors || [],
        state.flow.data.service_id,
        state.flow.data.service_name
      );
      const match = findDoctorByInput(doctorPool, value);
      if (match) {
        state.flow.data.doctor_id = match.id;
      } else {
        appendMessage('Mình chưa nhận ra bác sĩ. Bạn chọn trong danh sách bên dưới giúp mình nhé.', 'ai');
        renderDoctorPills(
          data.doctors || [],
          state.flow.data.service_id,
          state.flow.data.service_name
        );
        return true;
      }
      state.flow.step = 'datetime';
      appendMessage('Bạn chọn nhanh ngày và giờ khám bên dưới nhé.', 'ai');
      await renderQuickDateTimePicker();
      return true;
    }

    if (state.flow.step === 'datetime') {
      if (!value) {
        appendMessage('Bạn chọn ngày và bấm giờ trống bên dưới để tiếp tục.', 'ai');
        return true;
      }
      const match = value.match(/^\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}(:\d{2})?$/);
      if (!match) {
        appendMessage('Bạn có thể chọn nhanh bằng ô ngày/giờ trong khung phía trên, hoặc nhập đúng dạng YYYY-MM-DD HH:mm.', 'ai');
        return true;
      }
      state.flow.data.date = value;
      state.flow.step = 'reason';
      appendMessage('Bạn vui lòng bổ sung lý do khám hoặc triệu chứng.', 'ai');
      return true;
    }

    if (state.flow.step === 'reason') {
      state.flow.data.note = value;
      await submitAppointment(state.flow.data);
      resetFlow();
      return true;
    }

    return false;
  }

  function renderDoctors(doctors) {
    if (!doctors || !doctors.length) {
      appendMessage('Hiện chưa có thông tin bác sĩ.', 'ai');
      return;
    }
    const cards = doctors.map(d => {
      const name = d.ten || 'Bác sĩ';
      const spec = d.chuyen_mon || 'Chuyên môn cập nhật sau';
      const work = d.gio_lam ? `Giờ làm: ${d.gio_lam}` : 'Giờ làm: Liên hệ';
      const avatarUrl = (d.photo_url || 'frontend/assets/img/default-doctor.png').trim();
      const avatar = `<img src="${sanitize(avatarUrl)}" alt="${sanitize(name)}">`;
      return `
        <div class="ai-chat-card">
          <div class="ai-chat-avatar">${avatar}</div>
          <div>
            <h5>${sanitize(name)}</h5>
            <small>${sanitize(spec)}</small>
            <div class="ai-chat-meta">${sanitize(work)}</div>
          </div>
        </div>
      `;
    }).join('');
    appendMessage(`<strong>Đội ngũ bác sĩ:</strong><div class="ai-chat-card-list">${cards}</div>`, 'ai');
  }

  function renderServices(services) {
    if (!services || !services.length) {
      appendMessage('Hiện chưa có thông tin dịch vụ.', 'ai');
      return;
    }
    const list = services.map(s => {
      const name = s.ten || 'Dịch vụ';
      const price = s.gia ? ` - Giá: ${s.gia}` : '';
      return `<li>${sanitize(name)}${sanitize(price)}</li>`;
    }).join('');
    appendMessage(`<strong>Dịch vụ phổ biến:</strong><ul>${list}</ul>`, 'ai');
  }

  function renderDoctorOptionsHtml(doctors, serviceId = '') {
    const source = Array.isArray(doctors) ? doctors : [];
    const filtered = serviceId
      ? source.filter(d => String(d.dich_vu_id || '') === String(serviceId))
      : source;
    return filtered.map(d =>
      `<option value="${d.id}" data-service-id="${sanitize(d.dich_vu_id || '')}">${sanitize(d.ten || '')}</option>`
    ).join('');
  }

  function updateAppointmentFormDoctors(formEl, serviceId = '') {
    if (!formEl) return;
    const doctorSelect = formEl.querySelector('select[name="doctor_id"]');
    if (!doctorSelect) return;

    const currentDoctor = doctorSelect.value;
    const doctors = state.data?.doctors || [];
    const optionsHtml = renderDoctorOptionsHtml(doctors, serviceId);
    doctorSelect.innerHTML = `<option value="">Chọn bác sĩ *</option>${optionsHtml}`;

    const hasCurrentDoctor = Array.from(doctorSelect.options).some(opt => String(opt.value) === String(currentDoctor));
    if (currentDoctor && hasCurrentDoctor) {
      doctorSelect.value = currentDoctor;
    } else {
      doctorSelect.value = '';
    }
  }

  async function handleSend(text) {
    const clean = sanitize(text);
    if (!clean) return;
    appendMessage(clean, 'user');
    state.messages.push({ role: 'user', content: clean });

    if (await handleFlow(clean)) {
      return;
    }

    if (/đặt lịch|đặt hẹn|lịch khám/i.test(clean)) {
      const data = await fetchData();
      const user = await fetchUser();
      if (user.logged_in) {
        startQuickFlowLoggedIn();
      } else {
        renderAppointmentForm(data.doctors || [], data.services || [], { loggedIn: false });
      }
      return;
    }

    setBusy(true);
    showTyping();
    try {
      const reply = await sendToAi();
      hideTyping();
      appendMessage(reply, 'ai', { speak: true });
      state.messages.push({ role: 'assistant', content: reply });
    } catch (err) {
      hideTyping();
      appendMessage('Xin lỗi, hệ thống đang bận. Vui lòng thử lại.', 'ai', { speak: true });
    } finally {
      setBusy(false);
    }
  }

  launcher.addEventListener('click', openChat);
  if (closeBtn) closeBtn.addEventListener('click', closeChat);

  document.addEventListener('click', (e) => {
    if (!panel.classList.contains('is-open')) return;
    const target = e.target;
    if (!target) return;
    if (panel.contains(target) || launcher.contains(target)) return;
    closeChat();
  });

  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      if (!input) return;
      const text = input.value.trim();
      input.value = '';
      handleSend(text);
    });
  }

  if (sendBtn) {
    sendBtn.addEventListener('click', (e) => {
      e.preventDefault();
      if (!input) return;
      const text = input.value.trim();
      input.value = '';
      handleSend(text);
    });
  }

  if (input) {
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        if (sendBtn) sendBtn.click();
      }
    });
  }

  if (quick) {
    quick.addEventListener('click', async (e) => {
      const btn = e.target.closest('.ai-quick-btn');
      if (!btn) return;
      const type = btn.getAttribute('data-quick');
      const data = await fetchData();
      if (type === 'doctors') {
        renderDoctors(data.doctors);
      } else if (type === 'services') {
        renderServices(data.services);
      } else if (type === 'appointment') {
        const user = await fetchUser();
        if (user.logged_in) {
          startQuickFlowLoggedIn();
        } else {
          renderAppointmentForm(data.doctors || [], data.services || [], { loggedIn: false });
        }
      } else if (type === 'call-now') {
        const phone = panel.dataset.aiPhone || launcher.dataset.aiPhone || '';
        if (phone) {
          appendMessage(`Bạn có thể gọi ngay: <a href="tel:${sanitize(phone)}">${sanitize(phone)}</a>`, 'ai');
        } else {
          appendMessage('Số hotline hiện chưa được cập nhật.', 'ai');
        }
      }
    });
  }

  if (body) {
    body.addEventListener('click', async (e) => {
      const target = e.target.closest('[data-action]');
      if (!target) return;

      if (target.dataset.action === 'pick-service') {
        state.flow.active = true;
        state.flow.step = 'doctor';
        state.flow.data.service_id = target.dataset.id || '';
        state.flow.data.service_name = target.dataset.name || '';
        appendMessage(`Bạn đã chọn dịch vụ: ${sanitize(target.dataset.name || '')}. Tiếp theo, chọn bác sĩ nhé.`, 'ai');
        const data = await fetchData();
        renderDoctorPills(data.doctors || [], state.flow.data.service_id, state.flow.data.service_name);
      }

      if (target.dataset.action === 'pick-doctor') {
        state.flow.active = true;
        state.flow.step = 'datetime';
        state.flow.data.doctor_id = target.dataset.id || '';
        appendMessage(`Bạn đã chọn bác sĩ: ${sanitize(target.dataset.name || '')}. Chọn nhanh ngày và giờ khám bên dưới nhé.`, 'ai');
        await renderQuickDateTimePicker();
      }

      if (target.dataset.action === 'pick-time') {
        const wrapper = target.closest('.ai-chat-time-grid');
        if (wrapper) {
          wrapper.querySelectorAll('.ai-chat-pill--time.is-selected').forEach(el => el.classList.remove('is-selected'));
          target.classList.add('is-selected');
        }
        const pickedDate = (target.dataset.date || '').trim();
        const pickedTime = (target.dataset.id || '').trim();
        if (!pickedDate || !pickedTime) {
          appendMessage('Không lấy được thời gian đã chọn. Bạn thử chọn lại giúp mình nhé.', 'ai');
          return;
        }
        if (isDateInPastYMD(pickedDate) || isPastTimeSlot(pickedDate, pickedTime)) {
          appendMessage('Khung giờ này đã qua. Bạn vui lòng chọn giờ khác.', 'ai');
          return;
        }
        state.flow.active = true;
        state.flow.step = 'reason';
        state.flow.data.time_slot = pickedTime;
        state.flow.data.date = `${pickedDate} ${toHHmm(pickedTime)}`;
        appendMessage(`Đã chọn lịch khám: ${sanitize(pickedDate)} ${sanitize(toHHmm(pickedTime))}. Bạn mô tả lý do khám giúp mình nhé.`, 'ai');
      }

      if (target.dataset.action === 'submit-appointment-form') {
        const formEl = target.closest('.ai-chat-form');
        if (!formEl) return;
        const payload = {
          name: formEl.querySelector('input[name="name"]')?.value.trim() || '',
          email: formEl.querySelector('input[name="email"]')?.value.trim() || '',
          phone: formEl.querySelector('input[name="phone"]')?.value.trim() || '',
          service_id: formEl.querySelector('select[name="service_id"]')?.value || '',
          doctor_id: formEl.querySelector('select[name="doctor_id"]')?.value || '',
          date: formEl.querySelector('input[name="date"]')?.value || '',
          time: formEl.querySelector('input[name="time"]')?.value || '',
          message: formEl.querySelector('textarea[name="message"]')?.value.trim() || ''
        };
        if (state.user?.logged_in) {
          payload.name = payload.name || state.user.ho_ten || '';
          payload.phone = payload.phone || state.user.so_dien_thoai || '';
          payload.email = payload.email || state.user.email || '';
        }
        if (payload.date && payload.time) {
          payload.date = `${payload.date} ${payload.time}`;
        }

        const pickedTs = payload.date ? new Date(payload.date.replace(' ', 'T')).getTime() : NaN;
        if (Number.isFinite(pickedTs) && pickedTs <= Date.now()) {
          appendMessage('Ngày giờ khám phải lớn hơn thời điểm hiện tại.', 'ai');
          return;
        }

        if (!payload.service_id || !payload.doctor_id || !payload.date || !payload.message) {
          appendMessage('Bạn vui lòng chọn dịch vụ, bác sĩ, ngày giờ khám và nhập lý do khám.', 'ai');
          return;
        }

        state.pendingAppointment = payload;
        appendMessage(buildAppointmentConfirm(payload), 'ai');
      }

      if (target.dataset.action === 'confirm-appointment') {
        if (!state.pendingAppointment) {
          appendMessage('Không có yêu cầu đặt lịch nào đang chờ xác nhận.', 'ai');
          return;
        }
        const response = await fetch('index.php?page=ai-appointment', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(state.pendingAppointment)
        });
        const result = await response.json();
        appendMessage(result.message || 'Đã gửi yêu cầu đặt lịch.', 'ai');
        if (result.ok) {
          appendMessage('Trạng thái: Đang chờ phòng khám xác nhận.', 'ai');
        }
        state.pendingAppointment = null;
        
      }

      if (target.dataset.action === 'cancel-appointment') {
        state.pendingAppointment = null;
        appendMessage('Mình đã hủy yêu cầu. Bạn có thể nhập lại thông tin nếu cần.', 'ai');
      }
    });
  }

  if (body) {
    body.addEventListener('change', async (e) => {
      const dateInput = e.target && e.target.closest ? e.target.closest('input[data-action="pick-date"]') : null;
      if (dateInput) {
        let selectedDate = String(dateInput.value || '').trim();
        if (isDateInPastYMD(selectedDate)) {
          const today = formatDateYMD();
          dateInput.value = today;
          selectedDate = today;
          appendMessage('Không thể chọn ngày trong quá khứ. Mình đã chuyển về ngày hôm nay.', 'ai');
        }
        const wrapper = dateInput.closest('.ai-chat-datetime');
        const slotsContainer = wrapper ? wrapper.querySelector('[data-role="available-times"]') : null;
        await renderTimeSlotsForDate(slotsContainer, state.flow.data.doctor_id, selectedDate);
        return;
      }

      const serviceSelect = e.target && e.target.closest ? e.target.closest('select[data-action="form-service-change"]') : null;
      if (serviceSelect) {
        const formEl = serviceSelect.closest('.ai-chat-form');
        updateAppointmentFormDoctors(formEl, serviceSelect.value || '');
        return;
      }

      const formDateInput = e.target && e.target.closest ? e.target.closest('input[data-action="form-date-change"]') : null;
      if (formDateInput) {
        const selectedDate = String(formDateInput.value || '').trim();
        if (selectedDate && isDateInPastYMD(selectedDate)) {
          const today = formatDateYMD();
          formDateInput.value = today;
          appendMessage('Ngày khám không được ở quá khứ.', 'ai');
        }
      }
    });
  }

  function renderAppointmentForm(doctors, services, options = {}) {
    const isLoggedIn = Boolean(options.loggedIn);
    const profile = options.profile || {};
    const minDate = formatDateYMD();
    const serviceOptions = (services || []).map(s =>
      `<option value="${s.id}">${sanitize(s.ten || '')}</option>`
    ).join('');
    const doctorOptions = renderDoctorOptionsHtml(doctors || [], '');

    const guestFields = isLoggedIn ? '' : `
      <div class="ai-chat-form-row">
        <input type="text" name="name" placeholder="Tên đầy đủ của bạn *">
        <input type="tel" name="phone" placeholder="Số điện thoại *">
      </div>
      <div class="ai-chat-form-row">
        <input type="email" name="email" placeholder="Email (không bắt buộc)">
      </div>
    `;

    const profileHint = isLoggedIn
      ? `<div class="ai-chat-meta">Đang dùng hồ sơ: ${sanitize(profile.ho_ten || '')} - ${sanitize(profile.so_dien_thoai || '')}</div>`
      : '';

    const formHtml = `
      <div class="ai-chat-form" id="aiChatAppointmentForm">
        ${profileHint}
        ${guestFields}
        <div class="ai-chat-form-row">
          <select name="service_id" data-action="form-service-change">
            <option value="">Chọn dịch vụ *</option>
            ${serviceOptions}
          </select>
        </div>
        <div class="ai-chat-form-row">
          <select name="doctor_id" data-action="form-doctor">
            <option value="">Chọn bác sĩ *</option>
            ${doctorOptions}
          </select>
        </div>
        <div class="ai-chat-form-row">
          <input type="date" name="date" min="${minDate}" value="${minDate}" data-action="form-date-change" required>
          <input type="time" name="time" required>
        </div>
        <textarea name="message" rows="3" placeholder="Lý do khám / triệu chứng *"></textarea>
        <button type="button" class="ai-chat-form-submit" data-action="submit-appointment-form">Gửi yêu cầu đặt lịch</button>
      </div>
    `;

    appendMessage(formHtml, 'ai');
  }

  if (voiceBtn) {
    if (!state.voice.supported) {
      voiceBtn.disabled = true;
      voiceBtn.title = 'Trình duyệt chưa hỗ trợ nhập giọng nói';
    }
    voiceBtn.addEventListener('click', (e) => {
      e.preventDefault();
      toggleVoiceInput();
    });
    updateVoiceButtonState();
  }

  if (speechToggleBtn) {
    if (!('speechSynthesis' in window)) {
      speechToggleBtn.disabled = true;
      speechToggleBtn.title = 'Trình duyệt chưa hỗ trợ đọc phản hồi';
    }
    speechToggleBtn.addEventListener('click', () => {
      if (!('speechSynthesis' in window)) return;
      state.voice.speakEnabled = !state.voice.speakEnabled;
      updateSpeechToggleState();
      const notice = state.voice.speakEnabled
        ? 'Đã bật đọc phản hồi bằng giọng nói.'
        : 'Đã tắt đọc phản hồi bằng giọng nói.';
      appendMessage(notice, 'ai');
    });
    updateSpeechToggleState();
  }

  async function startQuickFlowLoggedIn() {
    const data = await fetchData();
    const user = await fetchUser();
    if (user.logged_in && user.tien_su_benh) {
      appendMessage(`Mình đã có tiền sử bệnh của bạn: ${sanitize(user.tien_su_benh)}.`, 'ai');
    }
    state.flow.active = true;
    state.flow.step = 'service';
    state.flow.data = {
      name: user.ho_ten || '',
      phone: user.so_dien_thoai || '',
      email: user.email || ''
    };
    appendMessage('Mình hỗ trợ đặt lịch nhanh cho bạn. Bắt đầu bằng dịch vụ bạn muốn khám nhé.', 'ai');
    renderServicePills(data.services || []);
  }

  function buildAppointmentConfirm(payload) {
    const doctors = state.data?.doctors || [];
    const services = state.data?.services || [];
    const doctor = doctors.find(d => String(d.id) === String(payload.doctor_id));
    const service = services.find(s => String(s.id) === String(payload.service_id));
    const doctorName = doctor ? (doctor.ten || payload.doctor_id) : (payload.doctor_id || '');
    const serviceName = service ? (service.ten || payload.service_id) : (payload.service_id || '');

    const rows = [
      ['Họ tên', sanitize(payload.name || '')],
      ['Email', sanitize(payload.email || '---')],
      ['SĐT', sanitize(payload.phone || '')],
      ['Dịch vụ', sanitize(serviceName || '')],
      ['Bác sĩ', sanitize(doctorName || '')],
      ['Ngày', sanitize(payload.date || '')],
      ['Mô tả', sanitize(payload.message || '---')]
    ];
    const htmlRows = rows.map(r => `
      <div class="ai-chat-confirm-item">
        <span class="label">${r[0]}</span>
        <span class="value">${r[1]}</span>
      </div>
    `).join('');

    return `
      <div class="ai-chat-card" style="grid-template-columns:1fr;">
        <div class="ai-chat-confirm">
          <h5>Xác nhận yêu cầu đặt lịch</h5>
          ${htmlRows}
          <div class="ai-chat-confirm-actions">
            <button type="button" class="ai-chat-form-submit" data-action="confirm-appointment">Xác nhận gửi</button>
            <button type="button" class="ai-chat-pill" data-action="cancel-appointment">Hủy</button>
          </div>
        </div>
      </div>
    `;
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && panel.classList.contains('is-open')) {
      closeChat();
    }
  });
})();
