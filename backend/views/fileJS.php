<script src="backend/assets/vendor/jquery/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#ho_so_select').select2({
            width: '100%',
            placeholder: "Chọn hồ sơ bệnh nhân",
            allowClear: true
        });
    });

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field')
            .setAttribute('placeholder', 'Tìm theo tên hoặc số điện thoại...');
    });
</script>
<script src="backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript -->
<script src="backend/assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages -->
<script src="backend/assets/js/sb-admin-2.min.js"></script>

<!-- Page level plugins - DataTables -->
<script src="backend/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="backend/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts - DataTables Demo -->
<script>
    $(document).ready(function () {
        if (!$.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable({
                order: [
                    [0, 'desc']
                ],
                language: {
                    sProcessing: "Đang xử lý...",
                    sLengthMenu: "Hiển thị _MENU_ mục",
                    sZeroRecords: "Không tìm thấy kết quả phù hợp",
                    sInfo: "Hiển thị từ _START_ đến _END_ trong _TOTAL_ mục",
                    sInfoEmpty: "Hiển thị từ 0 đến 0 trong 0 mục",
                    sInfoFiltered: "(lọc từ _MAX_ mục tổng cộng)",
                    sSearch: "Tìm kiếm:",
                    oPaginate: {
                        sFirst: "Đầu tiên",
                        sPrevious: "Trước",
                        sNext: "Tiếp theo",
                        sLast: "Cuối cùng"
                    }
                }
            });
        }
    });
</script>