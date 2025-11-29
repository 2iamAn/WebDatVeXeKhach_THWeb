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
    });
});
</script>
