(() => {
  const togglePasswordButtons = document.querySelectorAll("[data-toggle-password]");
  togglePasswordButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const inputId = btn.getAttribute("data-toggle-password");
      const input = document.getElementById(inputId);
      if (!input) return;

      const isPassword = input.type === "password";
      input.type = isPassword ? "text" : "password";
      const icon = btn.querySelector("i");
      if (icon) {
        icon.className = isPassword ? "bi bi-eye-slash" : "bi bi-eye";
      }
    });
  });

  const passwordInput = document.getElementById("mat_khau");
  const confirmPasswordInput = document.getElementById("mat_khau_nhap_lai");
  const passwordStrength = document.getElementById("passwordStrength");
  const passwordMatch = document.getElementById("passwordMatch");

  const calcPasswordScore = (value) => {
    let score = 0;
    if (value.length >= 6) score += 1;
    if (value.length >= 10) score += 1;
    if (/[A-Z]/.test(value) && /[a-z]/.test(value)) score += 1;
    if (/\d/.test(value)) score += 1;
    if (/[^a-zA-Z0-9]/.test(value)) score += 1;
    return Math.min(score, 5);
  };

  const updatePasswordStrength = () => {
    if (!passwordInput || !passwordStrength) return;

    const value = passwordInput.value || "";
    const score = calcPasswordScore(value);
    const ratio = (score / 5) * 100;

    let text = "Mật khẩu yếu";
    let levelClass = "is-weak";
    if (score >= 4) {
      text = "Mật khẩu mạnh";
      levelClass = "is-strong";
    } else if (score >= 3) {
      text = "Mật khẩu trung bình";
      levelClass = "is-medium";
    }

    passwordStrength.classList.remove("is-weak", "is-medium", "is-strong");
    passwordStrength.classList.add(levelClass);

    const bar = passwordStrength.querySelector(".strength-bar span");
    const hint = passwordStrength.querySelector(".strength-text");
    if (bar) bar.style.width = `${ratio}%`;
    if (hint) hint.textContent = value ? text : "Mật khẩu nên có chữ hoa, chữ thường, số và ký tự đặc biệt.";
  };

  const updatePasswordMatch = () => {
    if (!passwordInput || !confirmPasswordInput || !passwordMatch) return;

    const password = passwordInput.value || "";
    const confirm = confirmPasswordInput.value || "";

    passwordMatch.classList.remove("is-ok", "is-error");
    if (confirm.length === 0) {
      passwordMatch.textContent = "";
      return;
    }

    if (password === confirm) {
      passwordMatch.classList.add("is-ok");
      passwordMatch.textContent = "Mật khẩu xác nhận đã khớp.";
    } else {
      passwordMatch.classList.add("is-error");
      passwordMatch.textContent = "Mật khẩu xác nhận chưa khớp.";
    }
  };

  if (passwordInput) {
    passwordInput.addEventListener("input", () => {
      updatePasswordStrength();
      updatePasswordMatch();
    });
    updatePasswordStrength();
  }

  if (confirmPasswordInput) {
    confirmPasswordInput.addEventListener("input", updatePasswordMatch);
  }

  const profileForm = document.getElementById("profileForm");
  const profileEditBtn = document.getElementById("profileEditBtn");
  const profileCancelBtn = document.getElementById("profileCancelBtn");
  const profileEditActions = document.getElementById("profileEditActions");

  if (profileForm) {
    const editableInputs = profileForm.querySelectorAll("[data-editable='1']");
    const initialValues = new Map();

    editableInputs.forEach((input) => {
      initialValues.set(input, input.value);
    });

    const setEditMode = (enabled) => {
      editableInputs.forEach((input) => {
        input.disabled = !enabled;
      });

      if (profileEditBtn) {
        profileEditBtn.style.display = enabled ? "none" : "inline-flex";
      }
      if (profileEditActions) {
        profileEditActions.style.display = enabled ? "flex" : "none";
      }
    };

    const bootEditMode = document.body.getAttribute("data-profile-edit") === "1";
    setEditMode(bootEditMode);

    if (profileEditBtn) {
      profileEditBtn.addEventListener("click", () => setEditMode(true));
    }

    if (profileCancelBtn) {
      profileCancelBtn.addEventListener("click", () => {
        editableInputs.forEach((input) => {
          if (initialValues.has(input)) {
            input.value = initialValues.get(input);
          }
        });
        setEditMode(false);
      });
    }
  }
})();
