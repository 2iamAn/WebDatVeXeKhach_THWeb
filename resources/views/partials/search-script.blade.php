<script>
document.addEventListener('DOMContentLoaded', function () {

    const motChieu = document.getElementById('motchieu');
    const khuHoi = document.getElementById('khuhui');
    const ngayVeGroup = document.getElementById('ngayve-group');

    ngayVeGroup.style.display = "none";

    motChieu.addEventListener("change", function () {
        ngayVeGroup.style.display = "none";
        ngayVeGroup.classList.add("hidden");
    });

    khuHoi.addEventListener("change", function () {
        ngayVeGroup.style.display = "block";
        ngayVeGroup.classList.remove("hidden");
        // Cập nhật min của ngày về khi chuyển sang khứ hồi
        const ngayDi = document.getElementById('ngaydi');
        const ngayVe = document.getElementById('ngayve');
        if (ngayDi && ngayVe) {
            if (ngayDi.value) {
                ngayVe.setAttribute('min', ngayDi.value);
            } else {
                const today = new Date().toISOString().split('T')[0];
                ngayVe.setAttribute('min', today);
            }
        }
    });

    // Thiết lập ngày tối thiểu cho date picker
    const ngayDi = document.getElementById('ngaydi');
    const ngayVe = document.getElementById('ngayve');
    const today = new Date().toISOString().split('T')[0];
    
    if (ngayDi) {
        // Đảm bảo min được set từ server-side
        if (!ngayDi.getAttribute('min')) {
            ngayDi.setAttribute('min', today);
        }
        
        // Khi ngày đi thay đổi, cập nhật min của ngày về
        ngayDi.addEventListener('change', function() {
            if (ngayVe && this.value) {
                ngayVe.setAttribute('min', this.value);
                // Nếu ngày về đã được chọn và nhỏ hơn ngày đi, xóa giá trị
                if (ngayVe.value && ngayVe.value < this.value) {
                    ngayVe.value = '';
                }
            }
        });
    }
    
    if (ngayVe) {
        // Đảm bảo min được set từ server-side
        if (!ngayVe.getAttribute('min')) {
            ngayVe.setAttribute('min', today);
        }
    }
});
</script>
