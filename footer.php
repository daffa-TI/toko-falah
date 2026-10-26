</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Satu popup dipakai untuk Tambah dan Ubah.
var popup = document.getElementById('modalForm');
if (popup) {
popup.addEventListener('show.bs.modal', function (ev) {
    var tombol = ev.relatedTarget;
    var form = popup.querySelector('form');
    var modeUbah = tombol && tombol.dataset.id !== undefined;

    form.reset();
    form.elements['aksi'].value = modeUbah ? 'ubah' : 'tambah';
    form.elements['id'].value = modeUbah ? tombol.dataset.id : '';
    popup.querySelector('.modal-title').textContent = (modeUbah ? 'Ubah ' : 'Tambah ') + popup.dataset.judul;

    if (modeUbah) {
        Object.keys(tombol.dataset).forEach(function (nama) {
        if (form.elements[nama]) { form.elements[nama].value = tombol.dataset[nama]; }
        });
    }
    // halaman User: password wajib saat tambah, boleh kosong saat ubah
    if (form.elements['password']) { form.elements['password'].required = !modeUbah; }
});
}
</script>
</body>
</html>