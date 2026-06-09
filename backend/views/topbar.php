  <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">
      <!-- Topbar -->
      <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
              <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <!-- <form
              class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
              <div class="input-group">
                  <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                      aria-label="Search" aria-describedby="basic-addon2">
                  <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                          <i class="fas fa-search fa-sm"></i>
                      </button>
                  </div>
              </div>
          </form> -->

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

              <!-- Nav Item - Search Dropdown (Visible Only XS) -->
              <li class="nav-item dropdown no-arrow d-sm-none">
                  <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-search fa-fw"></i>
                  </a>
                  <!-- Dropdown - Messages -->
                  <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                      aria-labelledby="searchDropdown">
                      <form class="form-inline mr-auto w-100 navbar-search">
                          <div class="input-group">
                              <input type="text" class="form-control bg-light border-0 small"
                                  placeholder="Search for..." aria-label="Search"
                                  aria-describedby="basic-addon2">
                              <div class="input-group-append">
                                  <button class="btn btn-primary" type="button">
                                      <i class="fas fa-search fa-sm"></i>
                                  </button>
                              </div>
                          </div>
                      </form>
                  </div>
              </li>

              <!-- Nav Item - Alerts -->
              <li class="nav-item dropdown no-arrow mx-1">
                  <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-bell fa-fw"></i>

                      <?php if (!empty($alerts)): ?>
                          <span class="badge badge-danger badge-counter"><?= count($alerts) ?></span>
                      <?php endif; ?>
                  </a>

                  <!-- Dropdown - Alerts -->
                  <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                      aria-labelledby="alertsDropdown">

                      <h6 class="dropdown-header">
                          Thông báo vật tư
                      </h6>

                      <?php if (empty($alerts)): ?>
                          <div class="dropdown-item text-center small text-gray-500">
                              Không có cảnh báo
                          </div>
                      <?php else: ?>
                          <?php foreach ($alerts as $a): ?>
                              <a class="dropdown-item d-flex align-items-center" href="admin.php?admin=materials">
                                  <div class="mr-3">
                                      <div class="icon-circle bg-<?= $a['type'] ?>">
                                          <i class="fas <?= $a['icon'] ?> text-white"></i>
                                      </div>
                                  </div>
                                  <div>
                                      <span class="font-weight-bold"><?= $a['msg'] ?></span>
                                  </div>
                              </a>
                          <?php endforeach; ?>
                      <?php endif; ?>

                      <a class="dropdown-item text-center small text-gray-500" href="admin.php?admin=materials">
                          Xem tất cả
                      </a>
                  </div>


              </li>


              <!-- Nav Item - Messages -->
              <?php
                $soTinChuaDoc = $soTinChuaDoc ?? 0;
                $tinGanNhat   = $tinGanNhat ?? [];
                ?>

              <li class="nav-item dropdown no-arrow mx-1">
                  <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown"
                      role="button" data-toggle="dropdown"
                      aria-haspopup="true" aria-expanded="false">

                      <i class="fas fa-envelope fa-fw"></i>

                      <?php if ($soTinChuaDoc > 0): ?>
                          <span class="badge badge-danger badge-counter">
                              <?= $soTinChuaDoc ?>
                          </span>
                      <?php endif; ?>
                  </a>

                  <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                      aria-labelledby="messagesDropdown">

                      <h6 class="dropdown-header">
                          Trung tâm tin nhắn
                      </h6>

                      <?php if (empty($tinGanNhat)): ?>
                          <div class="dropdown-item text-center small text-gray-500">
                              Không có tin nhắn
                          </div>
                      <?php else: ?>

                          <?php foreach (array_slice($tinGanNhat, 0, 5) as $tin): ?>

                              <a class="dropdown-item d-flex align-items-center"
                                  href="">

                                  <div class="dropdown-list-image mr-3">


                                      <?php if ($tin['da_doc'] == 0): ?>
                                          <div class="status-indicator bg-success"></div>
                                      <?php endif; ?>
                                  </div>

                                  <div class="<?= $tin['da_doc'] == 0 ? 'font-weight-bold' : '' ?>">
                                      <div class="text-truncate">
                                          <?= htmlspecialchars($tin['noi_dung']) ?>
                                      </div>
                                      <div class="small text-gray-500">
                                          <?= htmlspecialchars($tin['ten_nguoi_gui']) ?>
                                          · <?= date("d/m H:i", strtotime($tin['ngay_tao'])) ?>
                                      </div>
                                  </div>
                              </a>

                          <?php endforeach; ?>

                      <?php endif; ?>

                      <a class="dropdown-item text-center small text-gray-500"
                          href="admin.php?admin=tatCaTin">
                          Xem tất cả tin nhắn
                      </a>
                  </div>
              </li>


              <div class="topbar-divider d-none d-sm-block"></div>
              <?php
                $adminName = $_SESSION['admin_name'] ?? 'Admin';
                $role      = $_SESSION['role'] ?? '';
                ?>

              <!-- Nav Item - User Information -->
              <li class="nav-item dropdown no-arrow">
                  <?php
                    $adminAvatar = !empty($_SESSION['admin']['avatar'])
                        ? "backend/uploads/avatar/" . $_SESSION['admin']['avatar']
                        : "assets/img/te.jpg";
                    ?>
                  <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?= htmlspecialchars($adminName) ?></span>

                      <img class="img-profile rounded-circle"
                          src="<?= $adminAvatar ?>">
                  </a>

                  <!-- Dropdown - User Information -->
                  <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                      aria-labelledby="userDropdown">
                      <a class="dropdown-item" href="admin.php?admin=profile">
                          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                          Hồ sơ cá nhân
                      </a>
                      <a class="dropdown-item" href="admin.php?admin=formRoleAccess">
                          <i class="fas fa-address-card fa-fw mr-2 text-gray-400"></i>
                          Tạo tài khoản cho nhân viên
                      </a>
                      <a class="dropdown-item" href="admin.php?admin=adminLogs">
                          <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                          Hoạt Động </a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="admin.php?admin=logout">
                          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                          Đăng Xuất
                      </a>
                  </div>
              </li>

          </ul>

      </nav>
      <!-- End of Topbar -->