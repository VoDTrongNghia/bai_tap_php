<?php
/** @var array $vouchers */
/** @var string $title */
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?= $title ?></h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#voucherModal">
                        <i class="fas fa-plus"></i> Thêm Voucher Mới
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã Voucher</th>
                                    <th>Mô tả</th>
                                    <th>Giảm giá</th>
                                    <th>Loại</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Số lượng</th>
                                    <th>Đã dùng</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vouchers as $voucher): ?>
                                    <tr>
                                        <td><?= $voucher['id'] ?></td>
                                        <td><strong><?= htmlspecialchars($voucher['ma_voucher']) ?></strong></td>
                                        <td><?= htmlspecialchars($voucher['mo_ta']) ?></td>
                                        <td class="text-end">
                                            <?= number_format($voucher['giam_gia']) ?>
                                            <?= $voucher['kieu_giam'] === 'percent' ? '%' : 'đ' ?>
                                        </td>
                                        <td><?= $voucher['kieu_giam'] === 'percent' ? 'Phần trăm' : 'Tiền mặt' ?></td>
                                        <td><?= date('d/m/Y', strtotime($voucher['ngay_bat_dau'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($voucher['ngay_ket_thuc'])) ?></td>
                                        <td class="text-center"><?= $voucher['so_luong'] ?></td>
                                        <td class="text-center"><?= $voucher['da_su_dung'] ?? 0 ?></td>
                                        <td>
                                            <span class="badge bg-<?= $voucher['trang_thai'] ? 'success' : 'danger' ?>">
                                                <?= $voucher['trang_thai'] ? 'Kích hoạt' : 'Vô hiệu hóa' ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary edit-voucher" 
                                                    data-id="<?= $voucher['id'] ?>"
                                                    data-ma-voucher="<?= htmlspecialchars($voucher['ma_voucher']) ?>"
                                                    data-mo-ta="<?= htmlspecialchars($voucher['mo_ta']) ?>"
                                                    data-giam-gia="<?= $voucher['giam_gia'] ?>"
                                                    data-kieu-giam="<?= $voucher['kieu_giam'] ?>"
                                                    data-gia-tri-toi-thieu="<?= $voucher['gia_tri_toi_thieu'] ?>"
                                                    data-so-luong="<?= $voucher['so_luong'] ?>"
                                                    data-ngay-bat-dau="<?= date('Y-m-d', strtotime($voucher['ngay_bat_dau'])) ?>"
                                                    data-ngay-ket-thuc="<?= date('Y-m-d', strtotime($voucher['ngay_ket_thuc'])) ?>"
                                                    data-trang-thai="<?= $voucher['trang_thai'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-voucher" data-id="<?= $voucher['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Voucher Modal -->
<div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="voucherForm" action="<?= BASE_PATH ?>admin/vouchers/save" method="post">
                <input type="hidden" name="id" id="voucherId">
                <div class="modal-header">
                    <h5 class="modal-title" id="voucherModalLabel">Thêm Voucher Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ma_voucher" class="form-label">Mã Voucher <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ma_voucher" name="ma_voucher" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kieu_giam" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                                <select class="form-select" id="kieu_giam" name="kieu_giam" onchange="toggleDiscountType()" required>
                                    <option value="percent">Phần trăm (%)</option>
                                    <option value="fixed">Giảm tiền mặt (đ)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="mo_ta" name="mo_ta" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="giam_gia" class="form-label">Giá trị giảm <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="giam_gia" name="giam_gia" min="1" step="1" required>
                                    <span class="input-group-text" id="discountSuffix">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gia_tri_toi_thieu" class="form-label">Giá trị đơn hàng tối thiểu</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="gia_tri_toi_thieu" name="gia_tri_toi_thieu" min="0" value="0">
                                    <span class="input-group-text">đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="so_luong" class="form-label">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="so_luong" name="so_luong" min="1" value="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="trang_thai" name="trang_thai" value="1" checked>
                                <label class="form-check-label" for="trang_thai">Kích hoạt</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ngay_bat_dau" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="ngay_bat_dau" name="ngay_bat_dau" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ngay_ket_thuc" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="ngay_ket_thuc" name="ngay_ket_thuc" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('ngay_bat_dau').value = today;
    document.getElementById('ngay_ket_thuc').value = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

    // Handle form submission
    const form = document.getElementById('voucherForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            // Convert dates to proper format
            const startDate = new Date(formData.get('ngay_bat_dau'));
            const endDate = new Date(formData.get('ngay_ket_thuc'));
            formData.set('ngay_bat_dau', startDate.toISOString().split('T')[0] + ' 00:00:00');
            formData.set('ngay_ket_thuc', endDate.toISOString().split('T')[0] + ' 23:59:59');
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showAlert('danger', data.message || 'Đã xảy ra lỗi');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Đã xảy ra lỗi khi lưu voucher');
            });
        });
    }

    // Handle edit button clicks
    document.querySelectorAll('.edit-voucher').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const modal = new bootstrap.Modal(document.getElementById('voucherModal'));
            
            // Set modal title
            document.getElementById('voucherModalLabel').textContent = 'Chỉnh sửa Voucher';
            
            // Set form values
            document.getElementById('voucherId').value = id;
            document.getElementById('ma_voucher').value = this.dataset.maVoucher;
            document.getElementById('mo_ta').value = this.dataset.moTa;
            document.getElementById('giam_gia').value = this.dataset.giamGia;
            document.getElementById('kieu_giam').value = this.dataset.kieuGiam;
            document.getElementById('gia_tri_toi_thieu').value = this.dataset.giaTriToiThieu;
            document.getElementById('so_luong').value = this.dataset.soLuong;
            document.getElementById('ngay_bat_dau').value = this.dataset.ngayBatDau;
            document.getElementById('ngay_ket_thuc').value = this.dataset.ngayKetThuc;
            document.getElementById('trang_thai').checked = this.dataset.trangThai === '1';
            
            // Update discount type UI
            toggleDiscountType();
            
            // Show modal
            modal.show();
        });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-voucher').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Bạn có chắc chắn muốn xóa voucher này?')) {
                const formData = new FormData();
                formData.append('id', id);
                
                fetch('<?= BASE_PATH ?>admin/vouchers/delete', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showAlert('danger', data.message || 'Đã xảy ra lỗi');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Đã xảy ra lỗi khi xóa voucher');
                });
            }
        });
    });

    // Initialize datepickers
    const today = new Date();
    const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());

    flatpickr("#ngay_bat_dau", {
        dateFormat: "Y-m-d",
        minDate: "today",
        defaultDate: "today",
        locale: "vi"
    });

    flatpickr("#ngay_ket_thuc", {
        dateFormat: "Y-m-d",
        minDate: "today",
        defaultDate: nextMonth,
        locale: "vi"
    });
});

// Toggle discount type UI
function toggleDiscountType() {
    const type = document.getElementById('kieu_giam').value;
    const suffix = document.getElementById('discountSuffix');
    const input = document.getElementById('giam_gia');
    
    if (type === 'percent') {
        suffix.textContent = '%';
        input.step = '1';
        input.max = '100';
    } else {
        suffix.textContent = 'đ';
        input.step = '1000';
        input.removeAttribute('max');
    }
}

// Show alert message
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>

<style>
    .table th {
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
    }
</style>