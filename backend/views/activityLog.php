<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách thuốc</title>
    <link rel="stylesheet" href="../assets/css/admin-second.css">
</head>

<body>
    <div class="container-fluid">


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách log</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ IP</th>
                                <th>Trạng thái</th>
                                <th>Thiết bị</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $index => $log): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= $log['sdt'] ?? 'Unknown' ?></strong>
                                    </td>
                                    <td>
                                        <span>
                                            <?= $log['ip_address'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($log['status'] == 'SUCCESS'): ?>
                                            <span>Success</span>
                                        <?php else: ?>
                                            <span>Failed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-truncate" style="max-width:250px;">
                                        <?= $log['user_agent'] ?>
                                    </td>
                                    <td>
                                        <?= date("d/m/Y H:i", strtotime($log['created_at'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>



</html>