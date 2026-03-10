<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
<script src="assets/dt_table/js/jquery-3.5.1.js"></script>
<!-- <link href='assets/fullcalendar-5.11.3/lib/main.css' rel='stylesheet' /> -->
<!-- <link href='assets/fullcalendar-scheduler-5.11.3/lib/main.css' rel='stylesheet' /> -->

<?php
if ($datatable == true) {
?>
    <link rel="stylesheet" type="text/css" href="assets/dt_table/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="assets/dt_table/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="assets/dt_table/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css">
<?php
}
?>

<style>
    /* Global Modern UI Overrides */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .card-header {
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
        border-bottom: none !important;
    }

    .btn {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .btn:not(.btn-icon):hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
    }

    .btn-light {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
    }

    .btn-light:hover {
        background-color: #e2e6ea;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>