<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; DSI ONE SA de CV 2023</div>
            <div>
                <a href="#">Política de privacidad</a>
                &middot;
                <a href="#">Términos &amp; condiciones</a>
            </div>
        </div>
    </div>
</footer>
</div>
</div>

<!-- DataTables -->
<script src="https://cdn.datatables.net/7.1.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/datatables.js"></script>


<!-- Bootstrap JavaScript (Popper.js y Bootstrap JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>


<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- líneas para exportar en excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tcpdf@6.3.5/tcpdf.js"></script>
<script src="https://unpkg.com/exceljs/dist/exceljs.min.js"></script>
<script src="../Complementos/JS/script.js"></script>

<script>
    $(document).ready(function() {
        $('#datatablesSimple').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        });
    });
</script>