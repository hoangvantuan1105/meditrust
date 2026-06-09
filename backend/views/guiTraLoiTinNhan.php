<style>
    body {
        background: #eef2f7;
        font-family: "Segoe UI", Tahoma, sans-serif;
    }

    /* ===== Wrapper ===== */
    .mail-wrapper {
        max-width: 780px;
        margin: 32px auto;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    /* ===== Header ===== */
    .mail-header {
        padding: 18px 24px;
        border-bottom: 1px solid #edf1f6;
        background: #fafbfe;
    }

    .mail-header h4 {
        margin: 0;
        font-weight: 600;
        color: #2c3e50;
    }

    .mail-sub {
        font-size: 13px;
        color: #8a97a8;
    }

    /* ===== Form ===== */
    #formMail {
        padding: 20px 24px 26px;
    }

    .form-row {
        margin-bottom: 14px;
    }

    .form-row label {
        font-size: 13px;
        font-weight: 600;
        color: #5a6b7b;
        display: block;
        margin-bottom: 4px;
    }

    .form-control {
        width: 100%;
        border: 1px solid #dde3ec;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 2px rgba(78, 115, 223, .15);
        outline: none;
    }

    .mail-to {
        background: #f5f7fb;
    }

    /* ===== Editor ===== */
    .editor-box {
        border: 1px solid #dde3ec;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 12px;
    }

    /* ===== Attach ===== */
    .attach-row {
        margin-top: 16px;
    }

    .attach-label {
        font-size: 14px;
        color: #4e73df;
        cursor: pointer;
        font-weight: 500;
    }

    .attach-label input {
        display: block;
        margin-top: 6px;
    }

    /* ===== Actions ===== */
    .mail-actions {
        text-align: right;
        margin-top: 22px;
    }

    .btn-send-mail {
        background: linear-gradient(135deg, #4e73df, #224abe);
        border: none;
        color: white;
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 600;
        transition: .25s;
    }

    .btn-send-mail:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(78, 115, 223, .25);
    }
</style>
<div class="mail-wrapper">

    <!-- HEADER -->
    <div class="mail-header">
        <h4>📩 Soạn phản hồi</h4>
        <span class="mail-sub">Gửi phản hồi tới khách hàng</span>
    </div>

    <form method="POST" action="admin.php?admin=xuLyGuiTraLoi" enctype="multipart/form-data" id="formMail">

        <input type="hidden" name="id" value="<?= $tin['id'] ?>">

        <!-- EMAIL NGƯỜI NHẬN -->
        <div class="form-row">
            <label>To</label>
            <input type="text" class="form-control mail-to" value="<?= $tin['email_nguoi_gui'] ?>" disabled>
        </div>

        <!-- SUBJECT -->
        <div class="form-row">
            <label>Subject</label>
            <input type="text" class="form-control" value="Phản hồi: <?= htmlspecialchars($tin['tieu_de']) ?>" disabled>
        </div>

        <!-- EDITOR -->
        <div class="editor-box">
            <textarea name="noi_dung" id="editorEmail"></textarea>
        </div>

        <!-- ATTACH -->
        <div class="attach-row">
            <label class="attach-label">
                📎 Đính kèm file
                <input type="file" name="file">
            </label>
        </div>

        <!-- ACTION -->
        <div class="mail-actions">
            <button type="submit" class="btn-send-mail">
                ✉️ Gửi phản hồi
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editorEmail'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'link',
                'bulletedList', 'numberedList', '|',
                'undo', 'redo'
            ]
        })
        .then(editor => {
            document.querySelector('#formMail')
                .addEventListener('submit', function () {
                    document.querySelector('#editorEmail').value = editor.getData();
                });
        })
        .catch(error => console.error(error));
</script>