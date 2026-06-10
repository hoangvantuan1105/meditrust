<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Phân Tích Răng AI - MediTrust</title>
  <meta name="description" content="Phân tích sức khỏe răng miệng bằng trí tuệ nhân tạo. Upload ảnh để nhận đánh giá và tư vấn dịch vụ từ AI.">

  <link href="frontend/assets/img/favicon.png" rel="icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Raleway:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link href="frontend/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="frontend/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="frontend/assets/css/main.css" rel="stylesheet">

  <style>
    /* ── Page-specific styles ── */
    .dental-hero {
      padding: 80px 0 60px;
      background: linear-gradient(135deg, #021418 0%, #0f2e35 50%, #0f766e20 100%);
      position: relative;
      overflow: hidden;
      margin-top: 100px;
    }

    .dental-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse at 70% 50%, rgba(4, 158, 187, .15) 0%, transparent 60%);
    }

    .dental-hero .badge-pill {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: rgba(4, 158, 187, .15);
      border: 1px solid rgba(4, 158, 187, .35);
      color: #67e8f9;
      padding: 6px 16px;
      border-radius: 999px;
      font-size: 12.5px;
      font-weight: 600;
      letter-spacing: .3px;
      margin-bottom: 20px;
    }

    .dental-hero h1 {
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      font-weight: 800;
      line-height: 1.2;
    }

    .dental-hero .accent {
      color: #049ebb;
    }

    .dental-hero p {
      font-size: 1rem;
      color: rgba(255, 255, 255, .7);
      max-width: 540px;
    }

    /* ── Analyzer section ── */
    .dental-analyzer {
      padding: 60px 0 80px;
      background: #f2f8f9;
    }

    /* ── Cards ── */
    .dac {
      background: #fff;
      border-radius: 16px;
      padding: 28px;
      box-shadow: 0 4px 24px rgba(2, 20, 24, .07);
      height: 100%;
    }

    .dac-title {
      font-size: 1rem;
      font-weight: 700;
      color: #18444c;
      display: flex;
      align-items: center;
      gap: 9px;
      margin-bottom: 20px;
      padding-bottom: 14px;
      border-bottom: 1px solid #e8f4f6;
    }

    .dac-title .icon-wrap {
      width: 34px;
      height: 34px;
      border-radius: 9px;
      background: linear-gradient(135deg, #0f766e, #049ebb);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 15px;
      flex-shrink: 0;
    }

    /* ── Tabs ── */
    .da-tabs {
      display: flex;
      background: #f2f8f9;
      border-radius: 10px;
      padding: 4px;
      gap: 3px;
      margin-bottom: 20px;
    }

    .da-tab {
      flex: 1;
      border: none;
      background: transparent;
      border-radius: 7px;
      padding: 8px 10px;
      font-size: 12.5px;
      font-weight: 600;
      color: #64748b;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      cursor: pointer;
      transition: all .18s ease;
    }

    .da-tab.active {
      background: #fff;
      color: #049ebb;
      box-shadow: 0 1px 4px rgba(2, 20, 24, .1);
    }

    .da-pane {
      display: none;
    }

    .da-pane.active {
      display: block;
    }

    /* ── Drop zone ── */
    .da-drop {
      border: 2px dashed #b2d8e0;
      border-radius: 12px;
      padding: 30px 16px;
      text-align: center;
      cursor: pointer;
      background: #f7fcfd;
      transition: all .18s ease;
      margin-bottom: 14px;
    }

    .da-drop:hover,
    .da-drop.dragover {
      border-color: #049ebb;
      background: #e8f7fa;
    }

    .da-drop .drop-icon {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      background: linear-gradient(135deg, #e0f7fa, #b2ebf2);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 12px;
      font-size: 22px;
      color: #049ebb;
    }

    .da-drop h6 {
      font-size: 14px;
      font-weight: 700;
      color: #18444c;
      margin-bottom: 4px;
    }

    .da-drop small {
      color: #94a3b8;
      font-size: 12px;
    }

    /* ── Camera ── */
    .da-cam-wrap {
      position: relative;
      background: #021418;
      border-radius: 12px 12px 0 0;
      overflow: hidden;
    }

    #daVideo {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
    }

    .da-cam-err {
      position: absolute;
      inset: 0;
      background: rgba(2, 20, 24, .88);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 16px;
      text-align: center;
    }

    .da-cam-err i {
      font-size: 24px;
      color: #fbbf24;
    }

    .da-cam-err p {
      color: #fef3c7;
      font-size: 13px;
      margin: 0;
    }

    .da-cam-bar {
      background: #0f2229;
      border-radius: 0 0 12px 12px;
      padding: 10px 12px;
      display: flex;
      gap: 8px;
      margin-bottom: 14px;
    }

    .da-cam-bar .btn-capture {
      flex: 1;
      background: linear-gradient(135deg, #0f766e, #049ebb);
      border: none;
      border-radius: 8px;
      color: #fff;
      font-size: 13px;
      font-weight: 700;
      padding: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      cursor: pointer;
    }

    .da-cam-bar .btn-stop {
      width: 38px;
      background: rgba(255, 255, 255, .1);
      border: none;
      border-radius: 8px;
      color: #fff;
      font-size: 14px;
      cursor: pointer;
    }

    /* ── Preview ── */
    .da-preview-wrap {
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      margin-bottom: 14px;
    }

    #daPreviewImg {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
    }

    .da-preview-clear {
      position: absolute;
      top: 8px;
      right: 8px;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: rgba(2, 20, 24, .65);
      color: #fff;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      cursor: pointer;
    }

    .da-preview-badge {
      position: absolute;
      bottom: 8px;
      right: 10px;
      background: rgba(4, 158, 187, .9);
      color: #fff;
      font-size: 11px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 999px;
    }

    /* ── Analyze button ── */
    .btn-analyze {
      width: 100%;
      border: none;
      border-radius: 10px;
      padding: 13px;
      background: linear-gradient(135deg, #0f766e, #049ebb);
      color: #fff;
      font-size: 14px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(4, 158, 187, .3);
      transition: transform .18s, box-shadow .18s;
    }

    .btn-analyze:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(4, 158, 187, .4);
    }

    .btn-analyze:disabled {
      opacity: .55;
      cursor: not-allowed;
      transform: none;
    }

    /* ── Results card ── */
    #daResults {
      display: none;
    }

    .da-score-row {
      display: flex;
      align-items: center;
      gap: 16px;
      background: #f7fcfd;
      border-radius: 12px;
      padding: 14px 16px;
      margin-bottom: 20px;
      border: 1px solid #e0f0f4;
    }

    .da-score-circle {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      font-weight: 900;
      color: #fff;
    }

    .da-score-circle.green {
      background: linear-gradient(135deg, #059669, #10b981);
      box-shadow: 0 4px 14px rgba(16, 185, 129, .3);
    }

    .da-score-circle.amber {
      background: linear-gradient(135deg, #d97706, #f59e0b);
      box-shadow: 0 4px 14px rgba(245, 158, 11, .3);
    }

    .da-score-circle.red {
      background: linear-gradient(135deg, #dc2626, #ef4444);
      box-shadow: 0 4px 14px rgba(239, 68, 68, .3);
    }

    .da-status-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 11px;
      font-weight: 800;
      padding: 3px 12px;
      border-radius: 999px;
      text-transform: uppercase;
      letter-spacing: .05em;
      margin-bottom: 5px;
    }

    .da-status-pill.green {
      background: #dcfce7;
      color: #166534;
    }

    .da-status-pill.amber {
      background: #fef9c3;
      color: #713f12;
    }

    .da-status-pill.red {
      background: #fee2e2;
      color: #991b1b;
    }

    .da-score-meta h5 {
      font-size: 15px;
      font-weight: 700;
      color: #18444c;
      margin-bottom: 3px;
    }

    .da-score-meta p {
      font-size: 13px;
      color: #64748b;
      margin: 0;
    }

    .da-section-title {
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: #049ebb;
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 10px;
      margin-top: 18px;
    }

    /* Issues */
    .da-issue {
      display: flex;
      align-items: center;
      gap: 9px;
      padding: 9px 12px;
      border-radius: 10px;
      border: 1px solid #e2e8f0;
      border-left: 3px solid transparent;
      margin-bottom: 6px;
      background: #fff;
      transition: box-shadow .15s;
    }

    .da-issue:hover {
      box-shadow: 0 2px 8px rgba(2, 20, 24, .06);
    }

    .da-issue.red {
      border-left-color: #ef4444;
      background: #fff8f8;
    }

    .da-issue.amber {
      border-left-color: #f59e0b;
      background: #fffcf0;
    }

    .da-issue.green {
      border-left-color: #10b981;
      background: #f6fffe;
    }

    .da-issue-name {
      font-size: 13px;
      font-weight: 600;
      color: #18444c;
      flex: 1;
    }

    .da-issue-desc {
      font-size: 12px;
      color: #94a3b8;
      max-width: 55%;
      text-align: right;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* Recommendations */
    .da-rec {
      display: flex;
      gap: 12px;
      padding: 12px 14px;
      background: #fff;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      border-left: 4px solid transparent;
      margin-bottom: 8px;
      box-shadow: 0 2px 8px rgba(2, 20, 24, .04);
      transition: box-shadow .15s;
    }

    .da-rec:hover {
      box-shadow: 0 4px 14px rgba(2, 20, 24, .08);
    }

    .da-rec.pri-urgent {
      border-left-color: #ef4444;
    }

    .da-rec.pri-soon {
      border-left-color: #f59e0b;
    }

    .da-rec.pri-routine {
      border-left-color: #10b981;
    }

    .da-rec-icon {
      font-size: 22px;
      flex-shrink: 0;
      margin-top: 2px;
    }

    .da-rec-name {
      font-size: 13.5px;
      font-weight: 700;
      color: #18444c;
    }

    .da-rec-top {
      display: flex;
      align-items: center;
      gap: 7px;
      margin-bottom: 4px;
    }

    .da-rec-badge {
      font-size: 10px;
      font-weight: 800;
      padding: 2px 9px;
      border-radius: 999px;
      text-transform: uppercase;
    }

    .da-rec-badge.urgent {
      background: #fee2e2;
      color: #991b1b;
    }

    .da-rec-badge.soon {
      background: #fef9c3;
      color: #713f12;
    }

    .da-rec-badge.routine {
      background: #dcfce7;
      color: #166534;
    }

    .da-rec-reason {
      font-size: 12.5px;
      color: #64748b;
      line-height: 1.5;
    }

    .da-rec-cost {
      font-size: 12px;
      color: #94a3b8;
      margin-top: 4px;
    }

    /* Advice */
    .da-advice {
      background: linear-gradient(135deg, #e0f7fa, #e8f4e8);
      border: 1px solid #b2e0e8;
      border-radius: 12px;
      padding: 14px 16px;
      font-size: 13px;
      color: #18444c;
      line-height: 1.65;
      margin-top: 18px;
    }

    .da-advice strong {
      color: #049ebb;
    }

    /* Loading */
    #daLoading {
      display: none;
    }

    .da-loading-card {
      background: #f7fcfd;
      border-radius: 16px;
      padding: 36px 24px;
      text-align: center;
      border: 1px solid #e0f0f4;
      animation: daFadeIn .3s ease;
    }

    @keyframes daFadeIn {
      from {
        opacity: 0;
        transform: translateY(6px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Tooth orbit animation */
    .da-orbit-wrap {
      position: relative;
      width: 72px;
      height: 72px;
      margin: 0 auto 20px;
    }

    .da-orbit-ring {
      position: absolute;
      inset: 0;
      border-radius: 50%;
      border: 2px solid #e0f0f4;
      border-top-color: #049ebb;
      animation: daSpin .9s linear infinite;
    }

    .da-orbit-ring:nth-child(2) {
      inset: 8px;
      border-top-color: #0f766e;
      animation-duration: 1.4s;
      animation-direction: reverse;
    }

    .da-tooth-center {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
    }

    @keyframes daSpin {
      to {
        transform: rotate(360deg);
      }
    }

    .da-loading-title {
      font-size: 15px;
      font-weight: 700;
      color: #18444c;
      margin-bottom: 4px;
    }

    .da-loading-sub {
      font-size: 13px;
      color: #64748b;
      margin-bottom: 20px;
    }

    /* Step list */
    .da-step-list {
      display: flex;
      flex-direction: column;
      gap: 8px;
      text-align: left;
      max-width: 260px;
      margin: 0 auto 20px;
    }

    .da-step-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13px;
      color: #94a3b8;
      transition: color .3s;
    }

    .da-step-item .step-dot {
      width: 22px;
      height: 22px;
      border-radius: 50%;
      border: 2px solid #e0f0f4;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      flex-shrink: 0;
      transition: all .3s;
    }

    .da-step-item.active {
      color: #049ebb;
      font-weight: 600;
    }

    .da-step-item.active .step-dot {
      border-color: #049ebb;
      background: #e0f7fa;
      color: #049ebb;
    }

    .da-step-item.done {
      color: #10b981;
    }

    .da-step-item.done .step-dot {
      border-color: #10b981;
      background: #d1fae5;
      color: #10b981;
    }

    .da-prog-wrap {
      height: 6px;
      background: #e0f0f4;
      border-radius: 3px;
      overflow: hidden;
      max-width: 260px;
      margin: 0 auto;
    }

    .da-prog-fill {
      height: 100%;
      width: 0;
      border-radius: 3px;
      transition: width .6s cubic-bezier(.4, 0, .2, 1);
      background: linear-gradient(90deg, #0f766e, #049ebb);
    }

    /* Scan overlay on preview while analyzing */
    .da-preview-wrap.scanning::after {
      content: '';
      position: absolute;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, transparent, #049ebb 40%, #0f766e 60%, transparent);
      box-shadow: 0 0 10px rgba(4, 158, 187, .8);
      animation: daScanLine 1.6s ease-in-out infinite;
      pointer-events: none;
      z-index: 4;
    }

    @keyframes daScanLine {
      0% {
        top: 0%;
        opacity: 0;
      }

      5% {
        opacity: 1;
      }

      95% {
        opacity: 1;
      }

      100% {
        top: 100%;
        opacity: 0;
      }
    }

    .da-preview-wrap.scanning .da-preview-badge {
      background: rgba(4, 158, 187, .92);
      animation: daBadgePulse 1s ease-in-out infinite;
    }

    @keyframes daBadgePulse {

      0%,
      100% {
        opacity: 1
      }

      50% {
        opacity: .6
      }
    }

    /* Results fade in */
    #daResults {
      display: none;
      animation: daFadeIn .4s ease;
    }

    /* Empty state */
    .da-empty {
      text-align: center;
      padding: 50px 20px;
    }

    .da-empty-icon {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(135deg, #e0f7fa, #e8f4f0);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      font-size: 32px;
      box-shadow: 0 4px 18px rgba(4, 158, 187, .12);
    }

    .da-empty h4 {
      font-size: 18px;
      font-weight: 700;
      color: #18444c;
      margin-bottom: 8px;
    }

    .da-empty p {
      font-size: 14px;
      color: #64748b;
      margin-bottom: 24px;
    }

    .da-feature-list {
      list-style: none;
      padding: 0;
      display: inline-flex;
      flex-direction: column;
      gap: 10px;
      text-align: left;
    }

    .da-feature-list li {
      display: flex;
      align-items: center;
      gap: 9px;
      font-size: 13.5px;
      color: #334155;
    }

    .da-feature-list li .dot {
      width: 9px;
      height: 9px;
      border-radius: 50%;
      flex-shrink: 0;
    }

    /* CTA */
    .btn-book {
      width: 100%;
      border: none;
      border-radius: 10px;
      padding: 13px;
      background: linear-gradient(135deg, #18444c, #049ebb);
      color: #fff;
      font-size: 14px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(24, 68, 76, .25);
      margin-top: 20px;
      transition: transform .18s;
      text-decoration: none;
    }

    .btn-book:hover {
      transform: translateY(-2px);
      color: #fff;
    }

    .btn-analyze-again {
      width: 100%;
      border: 1.5px solid #b2d8e0;
      border-radius: 10px;
      padding: 10px;
      background: #fff;
      color: #049ebb;
      font-size: 13px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      cursor: pointer;
      margin-top: 8px;
      transition: background .15s;
    }

    .btn-analyze-again:hover {
      background: #f0fbfd;
    }

    /* Camera help banner */
    .cam-notice {
      background: #fef9c3;
      border: 1px solid #fde68a;
      border-radius: 10px;
      padding: 12px 14px;
      font-size: 13px;
      color: #78350f;
      margin-bottom: 14px;
    }

    .cam-notice i {
      color: #f59e0b;
    }

    /* Disclaimer */
    .da-disclaimer {
      background: #fffbeb;
      border: 1px solid #fde68a;
      border-radius: 10px;
      padding: 11px 14px;
      font-size: 12.5px;
      color: #78350f;
      display: flex;
      align-items: flex-start;
      gap: 8px;
      margin-top: 16px;
    }

    .da-disclaimer i {
      color: #f59e0b;
      flex-shrink: 0;
      margin-top: 1px;
    }

    .icon-search {
      width: 36px;
      height: 36px;
    }

    .da-book-link {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 11.5px;
      font-weight: 700;
      color: #049ebb;
      margin-top: 6px;
      text-decoration: none;
      padding: 3px 10px;
      border-radius: 999px;
      background: #e0f7fa;
      border: 1px solid #b2e8f0;
      transition: background .15s;
    }
    .da-book-link:hover {
      background: #b2ebf2;
      color: #0f766e;
    }
  </style>
</head>

<body class="dental-ai-page">
  <main class="main">

    <!-- Hero -->
    <section class="dental-hero section">
      <div class="container position-relative" data-aos="fade-up">
        <div class="row justify-content-center text-center text-white">
          <div class="col-lg-8">
            <div class="badge-pill">
              <i class="bi bi-cpu-fill"></i> Powered by Groq AI · Llama 4
            </div>
            <h1 class="mb-4">
              Phân tích sức khỏe răng<br>
              <span class="accent">bằng trí tuệ nhân tạo</span>
            </h1>
            <p class="mx-auto">
              Upload ảnh hoặc chụp trực tiếp — AI phát hiện vấn đề, đánh giá sức khỏe
              và tư vấn dịch vụ điều trị phù hợp trong vài giây.
            </p>
            <div class="d-flex gap-3 justify-content-center mt-4 flex-wrap">
              <span class="badge-pill"><i class="bi bi-shield-check"></i> Bảo mật — ảnh không lưu trữ</span>
              <span class="badge-pill"><i class="bi bi-lightning-charge-fill"></i> Kết quả trong ~10 giây</span>
              <span class="badge-pill"><i class="bi bi-gift-fill"></i> Miễn phí hoàn toàn</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Analyzer -->
    <section class="dental-analyzer section">
      <div class="container">
        <div class="row g-4 align-items-start">

          <!-- ── Left: Upload ── -->
          <div class="col-lg-5" data-aos="fade-right" data-aos-delay="100">
            <div class="dac">
              <div class="dac-title">
                <div class="icon-wrap"><i class="bi bi-camera-fill"></i></div>
                <span>Ảnh răng của bạn</span>
              </div>

              <!-- Tabs -->
              <div class="da-tabs">
                <button class="da-tab active" data-tab="upload">
                  <i class="bi bi-cloud-arrow-up-fill"></i> Upload
                </button>
                <button class="da-tab" data-tab="camera">
                  <i class="bi bi-camera-fill"></i> Chụp ảnh
                </button>
                <button class="da-tab" data-tab="video">
                  <i class="bi bi-camera-video-fill"></i> Quay video
                </button>
              </div>

              <!-- Photo guide trigger -->
              <button type="button" id="daGuideBtn"
                style="width:100%;background:#f0fbfd;border:1.5px dashed #7dd8e8;border-radius:10px;padding:9px 14px;display:flex;align-items:center;gap:9px;cursor:pointer;margin-bottom:14px;transition:background .15s"
                onmouseover="this.style.background='#e0f7fa'" onmouseout="this.style.background='#f0fbfd'"
                data-bs-toggle="modal" data-bs-target="#daGuideModal">
                <span style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#0f766e,#049ebb);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                  <i class="bi bi-lightbulb-fill" style="color:#fff;font-size:13px"></i>
                </span>
                <span style="flex:1;text-align:left">
                  <span style="font-size:13px;font-weight:700;color:#18444c;display:block">Cách chụp ảnh để AI phân tích chuẩn nhất</span>
                  <span style="font-size:11.5px;color:#64748b">Xem hướng dẫn góc chụp, ánh sáng, khoảng cách</span>
                </span>
                <i class="bi bi-chevron-right" style="color:#049ebb;font-size:13px"></i>
              </button>

              <!-- Upload Pane -->
              <div class="da-pane active" id="daUploadPane">
                <div class="da-drop" id="daDrop" tabindex="0" role="button">
                  <div class="drop-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                  <h6>Kéo &amp; thả ảnh vào đây</h6>
                  <p class="mb-3" style="font-size:13px;color:#64748b">hoặc</p>
                  <button type="button" class="btn btn-sm" id="daSelectBtn"
                    style="background:linear-gradient(135deg,#0f766e,#049ebb);color:#fff;border-radius:999px;padding:6px 20px;font-weight:600;border:none">
                    Chọn ảnh
                  </button>
                  <br><small class="mt-2 d-block">JPG, PNG · Tối đa 4 ảnh · 10MB/ảnh</small>
                  <input type="file" id="daFileInput" accept="image/jpeg,image/png,image/webp" multiple hidden>
                </div>
              </div>

              <!-- Camera Pane -->
              <div class="da-pane" id="daCameraPane">
                <div id="daCamNotice" class="cam-notice" style="display:none">
                  <i class="bi bi-exclamation-triangle-fill"></i>
                  <span id="daCamMsg"></span>
                </div>
                <div class="da-cam-wrap" id="daCamWrap" style="display:none">
                  <video id="daVideo" autoplay playsinline muted></video>
                  <div class="da-cam-err" id="daCamErr" style="display:none">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <p id="daCamErrMsg"></p>
                  </div>
                </div>
                <canvas id="daCanvas" hidden></canvas>
                <div class="da-cam-bar" id="daCamBar" style="display:none">
                  <button type="button" class="btn-capture" id="daCaptureBtn">
                    <i class="bi bi-lightning-charge-fill"></i> Chụp ảnh
                  </button>
                  <button type="button" class="btn-stop" id="daStopCamBtn"><i class="bi bi-x-lg"></i></button>
                </div>
                <button type="button" class="btn btn-outline-secondary w-100" id="daStartCamBtn"
                  style="border-radius:10px;font-size:13px;font-weight:600;padding:10px">
                  <i class="bi bi-camera-video-fill me-2"></i>Bật Camera
                </button>
              </div>

              <!-- Video Pane -->
              <div class="da-pane" id="daVideoPan">
                <div id="daVidNotice" class="cam-notice" style="display:none">
                  <i class="bi bi-exclamation-triangle-fill"></i>
                  <span id="daVidMsg"></span>
                </div>
                <div class="da-cam-wrap" id="daVidWrap" style="display:none">
                  <video id="daVidEl" autoplay playsinline muted></video>
                  <div class="da-cam-err" id="daVidErr" style="display:none">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <p id="daVidErrMsg"></p>
                  </div>
                  <!-- Countdown overlay -->
                  <div id="daVidCountdown" style="display:none;position:absolute;inset:0;background:rgba(2,20,24,.6);display:flex;align-items:center;justify-content:center;font-size:48px;font-weight:900;color:#fff;pointer-events:none"></div>
                </div>
                <canvas id="daVidCanvas" hidden></canvas>
                <div class="da-cam-bar" id="daVidBar" style="display:none">
                  <button type="button" class="btn-capture" id="daVidScanBtn">
                    <i class="bi bi-collection-fill"></i> Quét tự động 4 ảnh
                  </button>
                  <button type="button" class="btn-stop" id="daVidStopBtn"><i class="bi bi-x-lg"></i></button>
                </div>
                <div id="daVidProgress" style="display:none;margin-bottom:10px">
                  <div style="font-size:12px;color:#049ebb;font-weight:600;margin-bottom:6px" id="daVidProgressText">Đang quét...</div>
                  <div style="height:5px;background:#e0f0f4;border-radius:3px;overflow:hidden">
                    <div id="daVidProgressBar" style="height:100%;width:0;background:linear-gradient(90deg,#0f766e,#049ebb);transition:width .4s"></div>
                  </div>
                </div>
                <button type="button" class="btn btn-outline-secondary w-100" id="daStartVidBtn"
                  style="border-radius:10px;font-size:13px;font-weight:600;padding:10px">
                  <i class="bi bi-camera-video-fill me-2"></i>Bật Camera để quét video
                </button>
              </div>

              <!-- Thumbnail grid (dùng chung cho mọi nguồn) -->
              <div id="daThumbnailSection" style="display:none;margin-top:16px">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                  <span style="font-size:12px;font-weight:700;color:#18444c" id="daThumbnailCount">0 ảnh</span>
                  <button type="button" id="daClearAllBtn" style="font-size:11px;color:#ef4444;background:none;border:none;cursor:pointer;font-weight:600;padding:2px 6px">
                    <i class="bi bi-trash3"></i> Xóa tất cả
                  </button>
                </div>
                <div id="daThumbnailGrid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:6px"></div>
                <button type="button" class="btn-analyze" id="daAnalyzeBtn" style="margin-top:12px" disabled>
                  <i class="bi bi-search-heart-fill"></i>
                  <span id="daAnalyzeBtnText">Phân tích bằng AI</span>
                </button>
              </div>

            </div><!-- /dac -->
          </div>

          <!-- ── Right: Results ── -->
          <div class="col-lg-7" data-aos="fade-left" data-aos-delay="150">
            <div class="dac">
              <div class="dac-title">
                <div class="icon-wrap"><i class="bi bi-clipboard2-pulse-fill"></i></div>
                <span>Kết quả phân tích</span>
              </div>

              <!-- Empty state -->
              <div id="daEmpty" class="da-empty">
                <div class="da-empty-icon">

                  <svg class="icon-search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                    <path style="width: 100px;" d="M544 513L397.2 364.2C417.2 336.3 429.1 302 429.1 265C429.1 171.9 354.4 96.1 262.6 96.1C170.7 96 96 171.8 96 264.9C96 358 170.7 433.8 262.5 433.8C302.3 433.8 338.8 419.6 367.5 395.9L513.5 544L544 513zM262.5 394.8C191.9 394.8 134.4 336.5 134.4 264.9C134.4 193.3 191.9 135 262.5 135C333.1 135 390.6 193.3 390.6 264.9C390.6 336.5 333.2 394.8 262.5 394.8z" />
                  </svg>
                </div>
                <h4>Chờ phân tích</h4>
                <p>Upload ảnh răng để nhận đánh giá sức khỏe<br>và tư vấn dịch vụ từ AI</p>
                <ul class="da-feature-list">
                  <li><span class="dot" style="background:#ef4444"></span>Phát hiện sâu răng, cao răng</li>
                  <li><span class="dot" style="background:#f59e0b"></span>Đánh giá tình trạng nướu</li>
                  <li><span class="dot" style="background:#10b981"></span>Điểm sức khỏe 0–100</li>
                  <li><span class="dot" style="background:#049ebb"></span>Tư vấn dịch vụ điều trị phù hợp</li>
                </ul>
              </div>

              <!-- Loading -->
              <div id="daLoading">
                <div class="da-loading-card">
                  <div class="da-orbit-wrap">
                    <div class="da-orbit-ring"></div>
                    <div class="da-orbit-ring"></div>
                    <div class="da-tooth-center">🦷</div>
                  </div>
                  <div class="da-loading-title" id="daLoadingTitle">AI đang phân tích...</div>
                  <div class="da-loading-sub">Vui lòng chờ trong giây lát</div>

                  <div class="da-step-list" id="daStepList">
                    <div class="da-step-item active" data-step="0">
                      <span class="step-dot"><i class="bi bi-image"></i></span>
                      <span>Đọc &amp; xử lý ảnh</span>
                    </div>
                    <div class="da-step-item" data-step="1">
                      <span class="step-dot"><i class="bi bi-search"></i></span>
                      <span>Phát hiện vùng răng</span>
                    </div>
                    <div class="da-step-item" data-step="2">
                      <span class="step-dot"><i class="bi bi-cpu"></i></span>
                      <span>AI phân tích chuyên sâu</span>
                    </div>
                    <div class="da-step-item" data-step="3">
                      <span class="step-dot"><i class="bi bi-clipboard2-check"></i></span>
                      <span>Tổng hợp kết quả</span>
                    </div>
                  </div>

                  <div class="da-prog-wrap">
                    <div class="da-prog-fill" id="daProgFill"></div>
                  </div>
                </div>
              </div>

              <!-- Results -->
              <div id="daResults">

                <!-- Score -->
                <div class="da-score-row">
                  <div class="da-score-circle" id="daScoreCircle">0</div>
                  <div class="da-score-meta">
                    <div class="da-status-pill" id="daStatusPill"></div>
                    <h5 id="daStatusTitle">—</h5>
                    <p id="daStatusDesc">—</p>
                  </div>
                </div>

                <!-- Issues -->
                <div class="da-section-title">
                  <i class="bi bi-exclamation-circle-fill"></i> Vấn đề phát hiện
                </div>
                <div id="daIssuesList"></div>

                <!-- Recommendations -->
                <div class="da-section-title" style="margin-top:20px">
                  <i class="bi bi-patch-check-fill"></i> Dịch vụ khuyến nghị
                </div>
                <div id="daRecList"></div>

                <!-- Advice -->
                <div class="da-advice" id="daAdvice" style="display:none">
                  <strong>💡 Lời khuyên:</strong> <span id="daAdviceText"></span>
                </div>

                <!-- Disclaimer -->
                <div class="da-disclaimer">
                  <i class="bi bi-exclamation-triangle-fill"></i>
                  Đây là kết quả từ AI — không thay thế chẩn đoán của bác sĩ nha khoa chuyên nghiệp.
                </div>

                <!-- CTAs -->
                <a href="index.php?page=appointment" class="btn-book">
                  <i class="bi bi-calendar2-check-fill"></i> Đặt lịch khám ngay
                </a>
                <button type="button" class="btn-analyze-again" id="daAnalyzeAgainBtn">
                  <i class="bi bi-arrow-counterclockwise"></i> Phân tích ảnh khác
                </button>

              </div>

            </div><!-- /dac -->
          </div>

        </div>
      </div>
    </section>

  </main>

  <!-- ── Photo Guide Modal ── -->
  <div class="modal fade" id="daGuideModal" tabindex="-1" aria-labelledby="daGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden">

        <!-- Header -->
        <div class="modal-header" style="background:linear-gradient(135deg,#021418,#0f2e35);border:none;padding:20px 24px">
          <div>
            <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#67e8f9;margin-bottom:4px">
              <i class="bi bi-lightbulb-fill me-1"></i> Hướng dẫn
            </div>
            <h5 class="modal-title mb-0" id="daGuideModalLabel" style="color:#fff;font-weight:800;font-size:1.1rem">
              Cách chụp ảnh / quay video răng chuẩn nhất
            </h5>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>

        <!-- Body -->
        <div class="modal-body" style="padding:24px;background:#f8fafc">

          <!-- Quick rule -->
          <div style="background:linear-gradient(135deg,#e0f7fa,#e8f9f2);border:1px solid #b2e8d8;border-radius:12px;padding:14px 16px;margin-bottom:20px;display:flex;gap:12px;align-items:flex-start">
            <span style="font-size:22px;flex-shrink:0">🎯</span>
            <div>
              <div style="font-size:13.5px;font-weight:700;color:#18444c;margin-bottom:3px">Quy tắc vàng</div>
              <div style="font-size:13px;color:#334155;line-height:1.6">
                <strong>Ánh sáng tốt + Miệng há rộng + Camera thẳng + Giữ tay ổn định</strong> — 4 yếu tố này quyết định 90% độ chính xác của AI.
              </div>
            </div>
          </div>

          <!-- 3 góc chụp -->
          <div style="font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#049ebb;margin-bottom:12px">
            <i class="bi bi-camera-fill me-1"></i> 3 góc chụp được khuyến nghị
          </div>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px">

            <!-- Góc 1: Thẳng mặt -->
            <div style="background:#fff;border-radius:12px;border:1.5px solid #e0f0f4;padding:14px 10px;text-align:center">
              <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#e0f7fa,#b2ebf2);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:24px">😁</div>
              <div style="font-size:12.5px;font-weight:700;color:#18444c;margin-bottom:4px">Thẳng mặt</div>
              <div style="font-size:11.5px;color:#64748b;line-height:1.5">Há rộng, chụp toàn bộ hàm trên + dưới<br><span style="color:#10b981;font-weight:600">→ Góc tốt nhất</span></div>
            </div>

            <!-- Góc 2: Bên trái/phải -->
            <div style="background:#fff;border-radius:12px;border:1.5px solid #e0f0f4;padding:14px 10px;text-align:center">
              <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#fef3c7,#fde68a);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:24px">😬</div>
              <div style="font-size:12.5px;font-weight:700;color:#18444c;margin-bottom:4px">Nghiêng 45°</div>
              <div style="font-size:11.5px;color:#64748b;line-height:1.5">Chụp răng hàm, bên trái hoặc phải<br><span style="color:#f59e0b;font-weight:600">→ Phát hiện răng hàm</span></div>
            </div>

            <!-- Góc 3: Vùng cụ thể -->
            <div style="background:#fff;border-radius:12px;border:1.5px solid #e0f0f4;padding:14px 10px;text-align:center">
              <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#fce7f3,#fbcfe8);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:24px">🔍</div>
              <div style="font-size:12.5px;font-weight:700;color:#18444c;margin-bottom:4px">Zoom vùng đau</div>
              <div style="font-size:11.5px;color:#64748b;line-height:1.5">Zoom sát vào vùng nghi ngờ có vấn đề<br><span style="color:#ec4899;font-weight:600">→ Phân tích chi tiết</span></div>
            </div>
          </div>

          <!-- Do & Don't -->
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px">

            <!-- DO -->
            <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:14px">
              <div style="font-size:12px;font-weight:800;color:#166534;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px">
                <i class="bi bi-check-circle-fill me-1"></i> Nên làm
              </div>
              <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:7px">
                <li style="display:flex;gap:8px;align-items:flex-start;font-size:12.5px;color:#15803d">
                  <i class="bi bi-sun-fill" style="flex-shrink:0;margin-top:1px;color:#16a34a"></i>
                  Chụp nơi có ánh sáng đủ, dùng đèn flash nếu tối
                </li>
                <li style="display:flex;gap:8px;align-items:flex-start;font-size:12.5px;color:#15803d">
                  <i class="bi bi-arrows-fullscreen" style="flex-shrink:0;margin-top:1px;color:#16a34a"></i>
                  Há miệng rộng tự nhiên, dùng gương hoặc nhờ người khác chụp
                </li>
                <li style="display:flex;gap:8px;align-items:flex-start;font-size:12.5px;color:#15803d">
                  <i class="bi bi-rulers" style="flex-shrink:0;margin-top:1px;color:#16a34a"></i>
                  Camera cách miệng <strong>15–25 cm</strong>
                </li>
                <li style="display:flex;gap:8px;align-items:flex-start;font-size:12.5px;color:#15803d">
                  <i class="bi bi-phone" style="flex-shrink:0;margin-top:1px;color:#16a34a"></i>
                  Chụp ngang (landscape) để thấy toàn hàm
                </li>
                <li style="display:flex;gap:8px;align-items:flex-start;font-size:12.5px;color:#15803d">
                  <i class="bi bi-droplet-fill" style="flex-shrink:0;margin-top:1px;color:#16a34a"></i>
                  Súc miệng sạch trước khi chụp để thấy rõ màu răng
                </li>
              </ul>
            </div>

            <!-- DON'T -->
            <div style="background:#fff8f8;border:1.5px solid #fecaca;border-radius:12px;padding:14px">
              <div style="font-size:12px;font-weight:800;color:#991b1b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px">
                <i class="bi bi-x-circle-fill me-1"></i> Tránh
              </div>
              <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:7px">
                <li style="display:flex;gap:8px;align-items:flex-start;font-size:12.5px;color:#b91c1c">
                  <i class="bi bi-brightness-high" style="flex-shrink:0;margin-top:1px;color:#dc2626"></i>
                  Ngược sáng (đứng quay lưng cửa sổ)
                </li>
                <li style="display:flex;gap:8px;align-items:flex-start;font-size:12.5px;color:#b91c1c">
                  <i class="bi bi-arrows-move" style="flex-shrink:0;margin-top:1px;color:#dc2626"></i>
                  Tay rung, ảnh bị mờ/nhòe
                </li>
                <li style="display:flex;gap:8px;align-items:flex-start;font-size:12.5px;color:#b91c1c">
                  <i class="bi bi-eye-slash" style="flex-shrink:0;margin-top:1px;color:#dc2626"></i>
                  Môi che mất phần răng cần phân tích
                </li>
                <li style="display:flex;gap:8px;align-items:flex-start;font-size:12.5px;color:#b91c1c">
                  <i class="bi bi-zoom-in" style="flex-shrink:0;margin-top:1px;color:#dc2626"></i>
                  Chụp quá gần (&lt;10cm) gây mất nét
                </li>
                <li style="display:flex;gap:8px;align-items:flex-start;font-size:12.5px;color:#b91c1c">
                  <i class="bi bi-image" style="flex-shrink:0;margin-top:1px;color:#dc2626"></i>
                  Ảnh chụp lại màn hình/ảnh cũ độ phân giải thấp
                </li>
              </ul>
            </div>
          </div>

          <!-- Camera / Video tips -->
          <div style="background:#fff;border:1.5px solid #e0f0f4;border-radius:12px;padding:14px 16px">
            <div style="font-size:12px;font-weight:800;color:#0369a1;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px">
              <i class="bi bi-camera-video-fill me-1"></i> Dùng camera trực tiếp
            </div>
            <div style="display:flex;flex-direction:column;gap:8px">
              <div style="display:flex;gap:10px;align-items:flex-start;font-size:12.5px;color:#334155">
                <span style="min-width:22px;height:22px;background:#e0f0f4;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#0369a1;flex-shrink:0">1</span>
                Nhờ người thân giữ điện thoại hoặc đặt điện thoại trước gương — hạn chế rung khi tự chụp
              </div>
              <div style="display:flex;gap:10px;align-items:flex-start;font-size:12.5px;color:#334155">
                <span style="min-width:22px;height:22px;background:#e0f0f4;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#0369a1;flex-shrink:0">2</span>
                Mở thêm đèn phòng tắm hoặc đèn bàn rọi vào miệng trước khi bấm "Chụp ảnh"
              </div>
              <div style="display:flex;gap:10px;align-items:flex-start;font-size:12.5px;color:#334155">
                <span style="min-width:22px;height:22px;background:#e0f0f4;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#0369a1;flex-shrink:0">3</span>
                Chờ video rõ nét, đứng yên 1–2 giây rồi mới bấm "Chụp ảnh" — tránh bấm khi hình còn mờ
              </div>
              <div style="display:flex;gap:10px;align-items:flex-start;font-size:12.5px;color:#334155">
                <span style="min-width:22px;height:22px;background:#e0f0f4;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#0369a1;flex-shrink:0">4</span>
                Thiết bị dùng camera sau (rear camera) sẽ cho chất lượng tốt hơn camera trước nhiều
              </div>
            </div>
          </div>

        </div><!-- /modal-body -->

        <!-- Footer -->
        <div class="modal-footer" style="background:#f8fafc;border-top:1px solid #e0f0f4;padding:14px 24px">
          <span style="font-size:12px;color:#94a3b8;flex:1"><i class="bi bi-shield-check me-1 text-success"></i>Ảnh chỉ dùng để phân tích — không lưu trên server</span>
          <button type="button" class="btn btn-sm" data-bs-dismiss="modal"
            style="background:linear-gradient(135deg,#0f766e,#049ebb);color:#fff;border:none;border-radius:8px;padding:7px 20px;font-weight:700">
            Đã hiểu, bắt đầu chụp
          </button>
        </div>

      </div>
    </div>
  </div><!-- /daGuideModal -->

  <!-- Vendor JS -->
  <script src="frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="frontend/assets/vendor/aos/aos.js"></script>
  <script src="frontend/assets/js/main.js"></script>

  <script>
    (function() {
      'use strict';

      /* ── DOM ── */
      const $ = id => document.getElementById(id);

      /* ── Shared image store (max 4) ── */
      const MAX_IMG = 4;
      let allImages  = [];   // array of base64 strings
      let camStream  = null;
      let vidStream  = null;
      let vidScanTimer = null;

      /* ── Tabs ── */
      document.querySelectorAll('.da-tab').forEach(tab => {
        tab.addEventListener('click', () => {
          document.querySelectorAll('.da-tab').forEach(t => t.classList.remove('active'));
          document.querySelectorAll('.da-pane').forEach(p => p.classList.remove('active'));
          tab.classList.add('active');
          const paneId = 'da' + tab.dataset.tab.charAt(0).toUpperCase() + tab.dataset.tab.slice(1) + (tab.dataset.tab === 'video' ? 'Pan' : 'Pane');
          const pane = $(paneId);
          if (pane) pane.classList.add('active');
          if (tab.dataset.tab !== 'camera') stopCam();
          if (tab.dataset.tab !== 'video')  stopVid();
        });
      });

      /* ── Thumbnail grid ── */
      function renderThumbnails() {
        const grid   = $('daThumbnailGrid');
        const section = $('daThumbnailSection');
        const count  = $('daThumbnailCount');
        const btn    = $('daAnalyzeBtn');
        const btnTxt = $('daAnalyzeBtnText');

        if (!allImages.length) {
          section.style.display = 'none';
          setState('empty');
          return;
        }

        section.style.display = 'block';
        count.textContent = allImages.length + ' ảnh' + (allImages.length >= MAX_IMG ? ' (tối đa)' : '');
        btnTxt.textContent = allImages.length > 1
          ? `Phân tích ${allImages.length} ảnh bằng AI`
          : 'Phân tích bằng AI';
        btn.disabled = false;

        grid.innerHTML = allImages.map((b64, i) => `
          <div style="position:relative;border-radius:8px;overflow:hidden;aspect-ratio:1;background:#f0fbfd">
            <img src="data:image/jpeg;base64,${b64}" style="width:100%;height:100%;object-fit:cover;display:block">
            <button onclick="removeImage(${i})" style="position:absolute;top:3px;right:3px;width:20px;height:20px;border-radius:50%;background:rgba(2,20,24,.7);color:#fff;border:none;display:flex;align-items:center;justify-content:center;font-size:11px;cursor:pointer;line-height:1">✕</button>
            <span style="position:absolute;bottom:3px;left:4px;font-size:10px;font-weight:700;color:#fff;background:rgba(2,20,24,.55);border-radius:4px;padding:1px 5px">${i + 1}</span>
          </div>`).join('');
      }

      window.removeImage = function(idx) {
        allImages.splice(idx, 1);
        renderThumbnails();
      };

      function addImage(b64) {
        if (allImages.length >= MAX_IMG) allImages.shift();
        allImages.push(b64);
        renderThumbnails();
      }

      $('daClearAllBtn').addEventListener('click', () => {
        allImages = [];
        renderThumbnails();
      });

      /* ── File upload (multiple) ── */
      const drop = $('daDrop');
      $('daSelectBtn').addEventListener('click', e => { e.stopPropagation(); $('daFileInput').click(); });
      drop.addEventListener('click', () => $('daFileInput').click());
      drop.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') $('daFileInput').click(); });
      ['dragenter','dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('dragover'); }));
      ['dragleave','drop'].forEach(ev => drop.addEventListener(ev, e => {
        e.preventDefault(); drop.classList.remove('dragover');
        if (ev === 'drop') Array.from(e.dataTransfer?.files || []).forEach(f => { if (f.type.startsWith('image/')) processFile(f); });
      }));
      $('daFileInput').addEventListener('change', () => {
        Array.from($('daFileInput').files).forEach(f => processFile(f));
        $('daFileInput').value = '';
      });

      function processFile(file) {
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
            addImage(cv.toDataURL('image/jpeg', 0.88).split(',')[1]);
          };
          img.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }

      /* ── Camera (chụp thủ công, nhiều lần) ── */
      $('daStartCamBtn').addEventListener('click', startCam);
      $('daCaptureBtn').addEventListener('click', capturePhoto);
      $('daStopCamBtn').addEventListener('click', stopCam);

      async function startCam() {
        const secure = location.protocol === 'https:' || ['localhost','127.0.0.1'].includes(location.hostname) || location.hostname.endsWith('.localhost');
        $('daStartCamBtn').style.display = 'none';
        $('daCamNotice').style.display   = 'none';
        $('daCamWrap').style.display     = '';
        $('daCamBar').style.display      = '';
        $('daCamErr').style.display      = 'none';
        if (!secure || !navigator.mediaDevices?.getUserMedia) { showCamErr('Camera yêu cầu HTTPS hoặc localhost.'); return; }
        try {
          camStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 } }, audio: false })
            .catch(() => navigator.mediaDevices.getUserMedia({ video: true, audio: false }));
          $('daVideo').srcObject = camStream;
        } catch (err) {
          const msgs = { NotAllowedError: 'Trình duyệt đang chặn camera — cấp quyền trong thanh địa chỉ', NotFoundError: 'Không tìm thấy camera', NotReadableError: 'Camera đang dùng bởi app khác' };
          showCamErr(msgs[err.name] || 'Không thể mở camera: ' + (err.message || err.name));
        }
      }

      function showCamErr(msg) { $('daCamErrMsg').textContent = msg; $('daCamErr').style.display = 'flex'; }

      function capturePhoto() {
        const vid = $('daVideo'), cv = $('daCanvas');
        cv.width  = vid.videoWidth  || 640;
        cv.height = vid.videoHeight || 480;
        cv.getContext('2d').drawImage(vid, 0, 0);
        addImage(cv.toDataURL('image/jpeg', 0.88).split(',')[1]);
        // flash feedback
        $('daCamWrap').style.outline = '3px solid #10b981';
        setTimeout(() => $('daCamWrap').style.outline = '', 300);
      }

      function stopCam() {
        camStream?.getTracks().forEach(t => t.stop());
        camStream = null;
        $('daVideo').srcObject    = null;
        $('daStartCamBtn').style.display = '';
        $('daCamWrap').style.display     = 'none';
        $('daCamBar').style.display      = 'none';
      }

      /* ── Video — quét tự động 4 ảnh ── */
      $('daStartVidBtn').addEventListener('click', startVid);
      $('daVidScanBtn').addEventListener('click', startVidScan);
      $('daVidStopBtn').addEventListener('click', stopVid);

      async function startVid() {
        const secure = location.protocol === 'https:' || ['localhost','127.0.0.1'].includes(location.hostname) || location.hostname.endsWith('.localhost');
        $('daStartVidBtn').style.display = 'none';
        $('daVidNotice').style.display   = 'none';
        $('daVidWrap').style.display     = '';
        $('daVidBar').style.display      = '';
        $('daVidErr').style.display      = 'none';
        if (!secure || !navigator.mediaDevices?.getUserMedia) { showVidErr('Camera yêu cầu HTTPS hoặc localhost.'); return; }
        try {
          vidStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 } }, audio: false })
            .catch(() => navigator.mediaDevices.getUserMedia({ video: true, audio: false }));
          $('daVidEl').srcObject = vidStream;
        } catch (err) {
          const msgs = { NotAllowedError: 'Trình duyệt đang chặn camera — cấp quyền trong thanh địa chỉ', NotFoundError: 'Không tìm thấy camera', NotReadableError: 'Camera đang dùng bởi app khác' };
          showVidErr(msgs[err.name] || 'Không thể mở camera: ' + (err.message || err.name));
        }
      }

      function showVidErr(msg) { $('daVidErrMsg').textContent = msg; $('daVidErr').style.display = 'flex'; }

      function startVidScan() {
        if (!vidStream) return;
        const FRAMES = 4, INTERVAL = 1500;
        let captured = 0;
        $('daVidScanBtn').disabled  = true;
        $('daVidProgress').style.display = 'block';

        const captureFrame = () => {
          const vid = $('daVidEl'), cv = $('daVidCanvas');
          cv.width  = vid.videoWidth  || 640;
          cv.height = vid.videoHeight || 480;
          cv.getContext('2d').drawImage(vid, 0, 0);
          addImage(cv.toDataURL('image/jpeg', 0.88).split(',')[1]);
          captured++;
          const pct = Math.round(captured / FRAMES * 100);
          $('daVidProgressBar').style.width  = pct + '%';
          $('daVidProgressText').textContent = `Đã chụp ${captured}/${FRAMES} ảnh...`;
          // flash
          $('daVidWrap').style.outline = '3px solid #10b981';
          setTimeout(() => $('daVidWrap').style.outline = '', 250);
          if (captured >= FRAMES) {
            clearInterval(vidScanTimer);
            $('daVidProgressText').textContent = `Hoàn thành! ${FRAMES} ảnh đã được chụp ✓`;
            setTimeout(() => { $('daVidProgress').style.display = 'none'; $('daVidScanBtn').disabled = false; }, 1500);
          }
        };

        captureFrame();
        vidScanTimer = setInterval(captureFrame, INTERVAL);
      }

      function stopVid() {
        clearInterval(vidScanTimer);
        vidStream?.getTracks().forEach(t => t.stop());
        vidStream = null;
        $('daVidEl').srcObject           = null;
        $('daStartVidBtn').style.display  = '';
        $('daVidWrap').style.display      = 'none';
        $('daVidBar').style.display       = 'none';
        $('daVidProgress').style.display  = 'none';
      }

      /* ── Analyze ── */
      $('daAnalyzeBtn').addEventListener('click', runAnalysis);
      $('daAnalyzeAgainBtn').addEventListener('click', () => {
        allImages = [];
        renderThumbnails();
        setState('empty');
      });

      async function runAnalysis() {
        if (!allImages.length) return;
        $('daAnalyzeBtn').disabled = true;
        setState('loading');
        startProgress();
        if (window.innerWidth < 992) {
          setTimeout(() => $('daLoading').scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
        }
        try {
          const res = await fetch('index.php?page=dental-analyze', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ images: allImages }),
          });
          if (!res.ok) throw new Error(`Lỗi server (HTTP ${res.status})`);
          const data = await res.json();
          if (data.error) throw new Error(data.error);
          stopProgress();
          renderResults(data);
          setState('results');
          setTimeout(() => $('daResults').scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
        } catch (err) {
          stopProgress();
          setState('empty');
          $('daAnalyzeBtn').disabled = false;
          showAlert(err.message || 'Lỗi khi phân tích. Vui lòng thử lại.');
        }
      }

      function showAlert(msg) {
        const existing = document.querySelector('.da-alert');
        if (existing) existing.remove();
        const div = document.createElement('div');
        div.className = 'da-alert alert alert-danger alert-dismissible fade show mt-3';
        div.role = 'alert';
        div.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i>${esc(msg)}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        $('daThumbnailSection').insertAdjacentElement('afterend', div);
        setTimeout(() => div.classList.remove('show'), 7000);
        setTimeout(() => div.remove(), 7500);
      }

      /* ── State machine ── */
      function setState(s) {
        $('daEmpty').style.display   = s === 'empty'   ? 'block' : 'none';
        $('daLoading').style.display = s === 'loading' ? 'block' : 'none';
        $('daResults').style.display = s === 'results' ? 'block' : 'none';
      }

      /* ── Progress ── */
      const STEPS_DATA = [
        { pct: 12, title: 'Đang đọc ảnh...', idx: 0 },
        { pct: 35, title: 'Đang phát hiện vùng răng...', idx: 1 },
        { pct: 68, title: 'AI đang phân tích chuyên sâu...', idx: 2 },
        { pct: 92, title: 'Đang tổng hợp kết quả...', idx: 3 },
      ];
      let progTimer = null;

      function startProgress() {
        const fill = $('daProgFill'), items = document.querySelectorAll('.da-step-item'), titleEl = $('daLoadingTitle');
        if (fill) fill.style.width = '0%';
        items.forEach(el => el.className = 'da-step-item');
        let i = 0;
        progTimer = setInterval(() => {
          if (i >= STEPS_DATA.length) { clearInterval(progTimer); return; }
          const s = STEPS_DATA[i];
          if (fill) fill.style.width = s.pct + '%';
          if (titleEl) titleEl.textContent = s.title;
          items.forEach((el, j) => {
            if (j < s.idx)      el.className = 'da-step-item done';
            else if (j === s.idx) el.className = 'da-step-item active';
            else                  el.className = 'da-step-item';
          });
          i++;
        }, 800);
      }

      function stopProgress() {
        clearInterval(progTimer);
        const fill = $('daProgFill'), titleEl = $('daLoadingTitle');
        if (fill) fill.style.width = '100%';
        if (titleEl) titleEl.textContent = 'Hoàn thành! ✓';
        document.querySelectorAll('.da-step-item').forEach(el => el.className = 'da-step-item done');
      }

      /* ── Render results ── */
      const ST_MAP  = { green: { cls: 'green', lbl: 'Tốt' }, amber: { cls: 'amber', lbl: 'Cần chú ý' }, red: { cls: 'red', lbl: 'Cần điều trị' } };
      const PRI_LBL = { urgent: 'Khẩn cấp', soon: 'Sớm', routine: 'Định kỳ' };

      function renderResults(d) {
        const score = Math.max(0, Math.min(100, d.health_score || 0));
        const st    = ST_MAP[d.overall_status] || ST_MAP.amber;

        const sc = $('daScoreCircle');
        sc.textContent = score;
        sc.className   = 'da-score-circle ' + st.cls;

        const pill = $('daStatusPill');
        pill.innerHTML = `<i class="bi bi-circle-fill me-1" style="font-size:8px"></i>${esc(d.status_title || st.lbl)}`;
        pill.className = 'da-status-pill ' + st.cls;
        $('daStatusTitle').textContent = d.status_title || st.lbl;
        $('daStatusDesc').textContent  = d.status_description || '';

        // Issues
        const issEl  = $('daIssuesList');
        const issues = Array.isArray(d.issues) ? d.issues.slice(0, 4) : [];
        issEl.innerHTML = issues.length
          ? issues.map(i => `<div class="da-issue ${i.severity||'amber'}"><span class="da-issue-name">${esc(i.name)}</span><span class="da-issue-desc">${esc(i.description||'')}</span></div>`).join('')
          : '<p style="color:#10b981;font-size:13px;font-weight:600;padding:6px 0"><i class="bi bi-check-circle-fill me-2"></i>Không phát hiện vấn đề đáng kể</p>';

        // Recommendations — dùng service_id từ DB để link đặt lịch
        const recEl = $('daRecList');
        const recs  = Array.isArray(d.recommendations) ? d.recommendations.slice(0, 4) : [];
        recEl.innerHTML = recs.map(r => {
          const bookUrl = r.service_id
            ? `index.php?page=appointment&service_id=${encodeURIComponent(r.service_id)}`
            : 'index.php?page=appointment';
          return `
          <div class="da-rec pri-${r.priority||'routine'}">
            <span class="da-rec-icon">${esc(r.icon||'🦷')}</span>
            <div class="flex-grow-1">
              <div class="da-rec-top">
                <span class="da-rec-name">${esc(r.service)}</span>
                <span class="da-rec-badge ${r.priority||'routine'}">${PRI_LBL[r.priority]||'Định kỳ'}</span>
              </div>
              <div class="da-rec-reason">${esc(r.reason||r.description||'')}</div>
              ${r.cost_range ? `<div class="da-rec-cost">💰 ${esc(r.cost_range)}</div>` : ''}
              <a href="${bookUrl}" class="da-book-link">
                <i class="bi bi-calendar2-check-fill"></i> Đặt lịch dịch vụ này
              </a>
            </div>
          </div>`;
        }).join('');

        if (d.general_advice) { $('daAdviceText').textContent = d.general_advice; $('daAdvice').style.display = ''; }
      }

      /* ── Helpers ── */
      function esc(s) {
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
      }

      AOS.init({ duration: 600, easing: 'ease-in-out', once: true });
    })();
  </script>

</body>

</html>