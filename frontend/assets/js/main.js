/**
* Template Name: MediTrust
* Template URL: https://bootstrapmade.com/meditrust-bootstrap-hospital-website-template/
* Updated: Jul 04 2025 with Bootstrap v5.3.7
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
  }

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  if (scrollTop) {
    scrollTop.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    if (typeof AOS === 'undefined') return;
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate Pure Counter
   */
  if (typeof PureCounter !== 'undefined') {
    new PureCounter();
  }

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    if (typeof Swiper === 'undefined') return;
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Init isotope layout and filters
   */
  document.querySelectorAll('.isotope-layout').forEach(function(isotopeItem) {
    let layout = isotopeItem.getAttribute('data-layout') ?? 'masonry';
    let filter = isotopeItem.getAttribute('data-default-filter') ?? '*';
    let sort = isotopeItem.getAttribute('data-sort') ?? 'original-order';

    let initIsotope;
    if (typeof imagesLoaded !== 'undefined' && typeof Isotope !== 'undefined') {
      imagesLoaded(isotopeItem.querySelector('.isotope-container'), function() {
        initIsotope = new Isotope(isotopeItem.querySelector('.isotope-container'), {
          itemSelector: '.isotope-item',
          layoutMode: layout,
          filter: filter,
          sortBy: sort
        });
      });
    }

    isotopeItem.querySelectorAll('.isotope-filters li').forEach(function(filters) {
      filters.addEventListener('click', function() {
        isotopeItem.querySelector('.isotope-filters .filter-active').classList.remove('filter-active');
        this.classList.add('filter-active');
        if (initIsotope) {
          initIsotope.arrange({
            filter: this.getAttribute('data-filter')
          });
        }
        if (typeof aosInit === 'function') {
          aosInit();
        }
      }, false);
    });

  });

  /**
   * Initiate glightbox
   */
  if (typeof GLightbox !== 'undefined') {
    GLightbox({
      selector: '.glightbox'
    });
  }

  /**
   * Frequently Asked Questions Toggle
   */
  document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle, .faq-item .faq-header').forEach((faqItem) => {
    faqItem.addEventListener('click', () => {
      faqItem.parentNode.classList.toggle('faq-active');
    });
  });

  /**
   * AI Chat widget
   */
  if (window.__AI_CHAT_V2__) {
    return;
  }
  let chatLauncher = document.getElementById('aiChatLauncher');
  const chatPanel = document.getElementById('aiChatPanel');
  const chatClose = document.getElementById('aiChatClose');
  const chatBody = document.getElementById('aiChatBody');
  const chatForm = document.getElementById('aiChatForm');
  const chatInput = document.getElementById('aiChatInput');
  const chatSend = document.getElementById('aiChatSend');
  const chatQuick = document.getElementById('aiChatQuick');

  const chatState = {
    messages: [],
    aiData: null,
    user: null,               // cached user info from server
    appointment: {
      active: false,
      step: null,
      data: {}
    }
  };

  function toggleChat(open) {
    if (!chatPanel || !chatLauncher) return;
    if (open) {
      chatPanel.classList.add('is-open');
      chatPanel.setAttribute('aria-hidden', 'false');
      chatInput && chatInput.focus();
    } else {
      chatPanel.classList.remove('is-open');
      chatPanel.setAttribute('aria-hidden', 'true');
    }
  }

  function appendMessage(content, role) {
    if (!chatBody) return;
    const msg = document.createElement('div');
    msg.className = `ai-chat-message ai-chat-message--${role}`;
    msg.innerHTML = content;
    chatBody.appendChild(msg);
    chatBody.scrollTop = chatBody.scrollHeight;
  }

  function sanitizeText(text) {
    return text.replace(/[<>]/g, '');
  }

  async function sendToAi(text) {
    const payload = {
      messages: chatState.messages
    };

    const response = await fetch('index.php?page=ai', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });

    const data = await response.json();
    return data.reply || 'Hệ thống bận, vui lòng thử lại.';
  }

  async function handleSend(text) {
    const clean = sanitizeText(text);
    if (!clean) return;
    appendMessage(clean, 'user');
    chatState.messages.push({ role: 'user', content: clean });

    if (!chatState.appointment.active && /đặt lịch|đặt hẹn|lịch khám/i.test(clean)) {
      startAppointmentFlow();
      return;
    }

    if (await handleAppointmentStep(clean)) {
      return;
    }

    appendMessage('Đang xử lý...', 'ai');
    const loader = chatBody.lastElementChild;

    try {
      const reply = await sendToAi(clean);
      loader.remove();
      appendMessage(reply, 'ai');
      chatState.messages.push({ role: 'assistant', content: reply });
    } catch (err) {
      loader.remove();
      appendMessage('Xin lỗi, hệ thống đang bận. Vui lòng thử lại.', 'ai');
    }
  }

  async function fetchAiData() {
    if (chatState.aiData) return chatState.aiData;
    const response = await fetch('index.php?page=ai-data');
    chatState.aiData = await response.json();
    return chatState.aiData;
  }

  // fetch information about logged-in patient to prefill form
  async function fetchAiUserData() {
    if (chatState.user) return chatState.user;
    try {
      const response = await fetch('index.php?page=ai-user-data');
      const data = await response.json();
      chatState.user = data;
      return data;
    } catch (err) {
      return null;
    }
  }

  function renderDoctors(doctors) {
    if (!Array.isArray(doctors) || doctors.length === 0) {
      appendMessage('Hiện chưa có thông tin bác sĩ.', 'ai');
      return;
    }
    const cards = doctors.map(d => {
      const name = d.ten || 'Bác sĩ';
      const spec = d.chuyen_mon || 'Chuyên môn cập nhật sau';
      const work = d.gio_lam ? `Giờ làm: ${d.gio_lam}` : 'Giờ làm: Liên hệ';
      const initials = name.split(' ').slice(0, 2).map(t => t[0]).join('').toUpperCase();
      const avatarUrl = (d.photo_url || d.avatar || '').trim();
      const avatarHtml = avatarUrl
        ? `<img src="${sanitizeText(avatarUrl)}" alt="${sanitizeText(name)}">`
        : `<span>${sanitizeText(initials)}</span>`;
      return `
        <div class="ai-chat-card">
          <div class="ai-chat-avatar">${avatarHtml}</div>
          <div>
            <h5>${sanitizeText(name)}</h5>
            <small>${sanitizeText(spec)}</small>
            <div class="ai-chat-meta">${sanitizeText(work)}</div>
          </div>
        </div>
      `;
    }).join('');
    appendMessage(`<strong>Đội ngũ bác sĩ:</strong><div class="ai-chat-card-list">${cards}</div>`, 'ai');
  }

  function renderServices(services) {
    if (!Array.isArray(services) || services.length === 0) {
      appendMessage('Hiện chưa có thông tin dịch vụ.', 'ai');
      return;
    }
    const list = services.map(s => {
      const name = s.ten || 'Dịch vụ';
      const price = s.gia ? ` - Giá: ${s.gia}` : '';
      return `<li>${sanitizeText(name)}${sanitizeText(price)}</li>`;
    }).join('');
    appendMessage(`<strong>Dịch vụ phổ biến:</strong><ul>${list}</ul>`, 'ai');
  }

  function resetAppointmentFlow() {
    chatState.appointment = {
      active: false,
      step: null,
      data: {}
    };
  }

  async function startAppointmentFlow() {
    resetAppointmentFlow();
    chatState.appointment.active = true;

    // preload user info if available
    const user = await fetchAiUserData();
    if (user && user.logged_in) {
      if (user.ho_ten) chatState.appointment.data.name = user.ho_ten;
      if (user.so_dien_thoai) chatState.appointment.data.phone = user.so_dien_thoai;
      if (user.email) chatState.appointment.data.email = user.email;
    }

    if (chatState.appointment.data.name && chatState.appointment.data.phone) {
      chatState.appointment.step = 'service';
      appendMessage('Chúng ta đã có thông tin cơ bản. Bạn muốn đặt dịch vụ nào?', 'ai');
      const data = await fetchAiData();
      if ((data.services || []).length) renderServicePills(data.services);
      return;
    }

    chatState.appointment.step = 'name';
    appendMessage('Để đặt lịch nhanh, cho mình xin họ và tên của bạn nhé.', 'ai');
  }

  function renderServicePills(services) {
    const pills = services.slice(0, 8).map(s => `
      <button class="ai-chat-pill" data-action="pick-service" data-id="${s.id}" data-name="${sanitizeText(s.ten || '')}">
        ${sanitizeText(s.ten || '')}
      </button>
    `).join('');
    appendMessage(`<div class="ai-chat-pill-group">${pills}</div>`, 'ai');
  }

  function renderDoctorPills(doctors) {
    const pills = doctors.slice(0, 8).map(d => `
      <button class="ai-chat-pill" data-action="pick-doctor" data-id="${d.id}" data-name="${sanitizeText(d.ten || '')}">
        ${sanitizeText(d.ten || '')}
      </button>
    `).join('');
    appendMessage(`<div class="ai-chat-pill-group">${pills}</div>`, 'ai');
  }

  async function renderTimePills() {
    const appt = chatState.appointment;
    if (!appt.data.doctor_id || !appt.data.date) return;
    try {
      const form = new URLSearchParams();
      form.append('bac_si_id', appt.data.doctor_id);
      form.append('ngay', appt.data.date);
      const res = await fetch('index.php?page=getAvailableTime', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: form.toString()
      });
      const times = await res.json();
      if (Array.isArray(times) && times.length) {
        const pills = times.map(t => `
          <button class="ai-chat-pill" data-action="pick-time" data-time="${sanitizeText(t)}">
            ${sanitizeText(t)}
          </button>
        `).join('');
        appendMessage(`<strong>Chọn giờ khám:</strong><div class="ai-chat-pill-group">${pills}</div>`, 'ai');
      } else {
        appendMessage('Không có khung giờ trống trong ngày này. Vui lòng chọn ngày khác.', 'ai');
      }
    } catch (err) {
      appendMessage('Không thể tải khung giờ, vui lòng thử lại.', 'ai');
    }
  }

  async function handleAppointmentStep(inputText) {
    const appt = chatState.appointment;
    if (!appt.active) return false;

    const data = await fetchAiData();
    const text = (inputText || '').trim();

    if (appt.step === 'name') {
      if (!text) {
        appendMessage('Bạn vui lòng cho mình xin họ và tên nhé.', 'ai');
        return true;
      }
      appt.data.name = text;
      // if phone was already filled from session, skip asking again
      if (appt.data.phone) {
        appt.step = 'service';
        appendMessage('Cảm ơn bạn! Mình đã có số điện thoại, bạn muốn đặt dịch vụ nào?', 'ai');
        if ((data.services || []).length) {
          renderServicePills(data.services || []);
        }
      } else {
        appt.step = 'phone';
        appendMessage('Cảm ơn bạn! Cho mình xin số điện thoại liên hệ?', 'ai');
      }
      return true;
    }

    if (appt.step === 'phone') {
      if (!text) {
        appendMessage('Bạn vui lòng nhập số điện thoại để phòng khám liên hệ.', 'ai');
        return true;
      }
      appt.data.phone = text;
      appt.step = 'service';
      appendMessage('Bạn muốn đặt dịch vụ nào? Chọn nhanh bên dưới hoặc nhập tên dịch vụ.', 'ai');
      if ((data.services || []).length) {
        renderServicePills(data.services || []);
      }
      return true;
    }

    if (appt.step === 'service') {
      if (!text) {
        appendMessage('Bạn có thể chọn dịch vụ trong danh sách hoặc nhập tên dịch vụ.', 'ai');
        return true;
      }
      const service = (data.services || []).find(s => (s.ten || '').toLowerCase() === text.toLowerCase());
      if (service) {
        appt.data.service_id = service.id;
        appt.data.service_name = service.ten;
      } else {
        appt.data.service_note = text;
      }
      appt.step = 'doctor';
      appendMessage('Bạn muốn khám với bác sĩ nào? Chọn nhanh hoặc nhập tên bác sĩ.', 'ai');
      if ((data.doctors || []).length) {
        renderDoctorPills(data.doctors || []);
      }
      return true;
    }

    if (appt.step === 'doctor') {
      if (!text) {
        appendMessage('Bạn có thể chọn bác sĩ trong danh sách hoặc nhập tên.', 'ai');
        return true;
      }
      const doctor = (data.doctors || []).find(d => (d.ten || '').toLowerCase() === text.toLowerCase());
      if (doctor) {
        appt.data.doctor_id = doctor.id;
        appt.data.doctor_name = doctor.ten;
      } else {
        appt.data.doctor_note = text;
      }
      appt.step = 'date';
      appendMessage('Bạn muốn đặt lịch vào ngày nào? (Ví dụ: 2026-02-10)', 'ai');
      return true;
    }

    if (appt.step === 'date') {
      if (!text) {
        appendMessage('Bạn vui lòng nhập ngày mong muốn (YYYY-MM-DD).', 'ai');
        return true;
      }
      appt.data.date = text;
      appt.step = 'time';
      appendMessage('Cảm ơn, mình sẽ tra khung giờ trống...', 'ai');
      await renderTimePills();
      return true;
    }

    return false;
  }

  async function submitAppointmentFromFlow(data) {
    const payload = {
      name: data.name || '',
      email: data.email || '',
      phone: data.phone || '',
      service_id: data.service_id || '',
      doctor_id: data.doctor_id || '',
      date: data.date || '',
      time: data.time || data.time_slot || '',
      message: data.service_note || data.doctor_note || ''
    };

    if (!payload.name || !payload.phone || !payload.date) {
      appendMessage('Mình chưa đủ thông tin. Vui lòng cung cấp họ tên, số điện thoại và ngày mong muốn.', 'ai');
      return;
    }

    if (!payload.service_id) {
      appendMessage('Bạn vui lòng chọn dịch vụ từ danh sách để mình tạo lịch nhé.', 'ai');
      const dataCache = await fetchAiData();
      renderServicePills(dataCache.services || []);
      return;
    }

    if (!payload.doctor_id) {
      appendMessage('Bạn vui lòng chọn bác sĩ từ danh sách để mình tạo lịch nhé.', 'ai');
      const dataCache = await fetchAiData();
      renderDoctorPills(dataCache.doctors || []);
      return;
    }

    const response = await fetch('index.php?page=ai-appointment', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });

    const result = await response.json();
    appendMessage(result.message || 'Đã gửi yêu cầu đặt lịch.', 'ai');
  }

  async function submitAppointment(formEl) {
    // collect any fields that exist; some chat forms include time but not service or name
    const data = {
      name: formEl.querySelector('input[name="name"]') ? formEl.querySelector('input[name="name"]').value.trim() : '',
      email: formEl.querySelector('input[name="email"]') ? formEl.querySelector('input[name="email"]').value.trim() : '',
      phone: formEl.querySelector('input[name="phone"]') ? formEl.querySelector('input[name="phone"]').value.trim() : '',
      service_id: formEl.querySelector('select[name="service_id"]') ? formEl.querySelector('select[name="service_id"]').value : '',
      doctor_id: formEl.querySelector('select[name="doctor_id"]') ? formEl.querySelector('select[name="doctor_id"]').value : '',
      date: formEl.querySelector('input[name="date"]') ? formEl.querySelector('input[name="date"]').value : '',
      time: formEl.querySelector('input[name="time"]') ? formEl.querySelector('input[name="time"]').value : '',
      message: formEl.querySelector('textarea[name="message"]') ? formEl.querySelector('textarea[name="message"]').value.trim() : ''
    };

    const response = await fetch('index.php?page=ai-appointment', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    const result = await response.json();
    appendMessage(result.message || 'Đã gửi yêu cầu.', 'ai');
  }

  if (!chatLauncher && chatPanel) {
    const launcherHtml = `
      <div class="ai-chat-launcher" id="aiChatLauncher" aria-label="Mở tư vấn nha khoa">
        <span class="ai-chat-dot"></span>
        <i class="bi bi-chat-dots"></i>
        <span class="ai-chat-text">Tư vấn nha khoa</span>
      </div>
    `;
    document.body.insertAdjacentHTML('beforeend', launcherHtml);
    chatLauncher = document.getElementById('aiChatLauncher');
  }

  if (chatLauncher && chatPanel) {
    chatLauncher.addEventListener('click', async () => {
      toggleChat(true);
      // show personalized greeting once
      const user = await fetchAiUserData();
      if (user && user.logged_in && chatState.messages.length === 0) {
        const greet = `Xin chào ${sanitizeText(user.ho_ten || 'bạn')}! Mình có thể giúp gì hôm nay?`;
        appendMessage(greet, 'ai');
        chatState.messages.push({ role: 'assistant', content: greet });
      }
    });
    chatClose && chatClose.addEventListener('click', () => toggleChat(false));
  }

  if (chatForm) {
    chatForm.addEventListener('submit', (e) => {
      e.preventDefault();
      if (!chatInput) return;
      const text = chatInput.value.trim();
      chatInput.value = '';
      handleSend(text);
    });
  }

  if (chatSend) {
    chatSend.addEventListener('click', () => {
      if (!chatInput) return;
      const text = chatInput.value.trim();
      chatInput.value = '';
      handleSend(text);
    });
  }
    
  if (chatQuick) {

      chatQuick.addEventListener('click', async (e) => {
      const target = e.target;
      if (!target.classList.contains('ai-quick-btn')) return;
      const type = target.getAttribute('data-quick');
      const data = await fetchAiData();
      if (type === 'doctors') {
        renderDoctors(data.doctors);
      } else if (type === 'services') {
        renderServices(data.services);
      } else if (type === 'appointment') {
        appendMessage('Bạn muốn đặt lịch theo từng bước. Mình sẽ hỏi lần lượt nhé.', 'ai');
        startAppointmentFlow();
      } else if (type === 'fast-appointment') {
        startAppointmentFlow();
      } else if (type === 'call-now') {
        const phone = (chatPanel && chatPanel.dataset.aiPhone) || (chatLauncher && chatLauncher.dataset.aiPhone) || '';
        if (phone) {
          appendMessage(`Bạn có thể gọi ngay: <a href="tel:${sanitizeText(phone)}">${sanitizeText(phone)}</a>`, 'ai');
        } else {
          appendMessage('Số hotline hiện chưa được cập nhật.', 'ai');
        }
      }
    });
  }

  if (chatBody) {
    chatBody.addEventListener('click', async (e) => {
      const target = e.target;
      if (!target) return;

      // handle either variant of submit action (legacy vs new form)
      if (target.dataset.action === 'submit-appointment' || target.dataset.action === 'submit-appointment-form') {
        const formEl = target.closest('.ai-chat-form');
        if (formEl) {
          await submitAppointment(formEl);
        }
      }

      if (target.dataset.action === 'pick-service') {
        if (!chatState.appointment.active) {
          chatState.appointment.active = true;
        }
        const text = target.dataset.name || '';
        chatState.appointment.data.service_id = target.dataset.id || '';
        chatState.appointment.data.service_name = text;
        chatState.appointment.step = 'doctor';
        appendMessage(`Bạn đã chọn dịch vụ: ${sanitizeText(text)}. Tiếp theo, chọn bác sĩ nhé.`, 'ai');
        const data = await fetchAiData();
        renderDoctorPills(data.doctors || []);
      }

      if (target.dataset.action === 'pick-doctor') {
        if (!chatState.appointment.active) {
          chatState.appointment.active = true;
        }
        const text = target.dataset.name || '';
        chatState.appointment.data.doctor_id = target.dataset.id || '';
        chatState.appointment.data.doctor_name = text;
        chatState.appointment.step = 'date';
        appendMessage(`Bạn đã chọn bác sĩ: ${sanitizeText(text)}. Vui lòng nhập ngày mong muốn (YYYY-MM-DD).`, 'ai');
      }

      if (target.dataset.action === 'pick-time') {
        if (!chatState.appointment.active) {
          chatState.appointment.active = true;
        }
        const time = target.dataset.time || '';
        chatState.appointment.data.time = time;
        chatState.appointment.step = 'done';
        appendMessage(`Bạn đã chọn khung giờ: ${sanitizeText(time)}.`, 'ai');
        await submitAppointmentFromFlow(chatState.appointment.data);
        resetAppointmentFlow();
      }
    });
  }

})();
