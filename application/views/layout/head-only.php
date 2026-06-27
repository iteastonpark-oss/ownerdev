<head>
    <title>Owner EPR Jatinangor</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="manifest" href="<?php echo base_url('manifest.json') ?>"/>
    <link rel="shortcut icon" href="<?php echo base_url('assets/icons/icon.png') ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/icons/icon-192.png') ?>" type="image/png">

    <!-- Argon Scripts -->
    <!-- Core -->
    <script src="<?php echo base_url('assets/jquery-3.3.1.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/DataTables-1.10.9/media/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/argon/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>

    <script src="<?php echo base_url('assets/DataTables-1.10.9/extensions/Responsive/js/dataTables.responsive.js') ?>"></script>
    <script src="<?php echo base_url('assets/DataTables-1.10.9/extensions/FixedColumns/js/dataTables.fixedColumns.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/DataTables-1.10.9/extensions/FixedHeader/js/dataTables.fixedHeader.min.js') ?>"></script>


    <script src="<?php echo base_url('assets/datatable.js') ?>"></script>
    <script src="<?php echo base_url('assets/getNumber/jquery.number.js') ?>"></script>
    <script src="<?php echo base_url('assets/getNumber.js') ?>"></script>

    <script src="<?php echo base_url('assets/bootstrap4-tagsinput-master/tagsinput.js') ?>"></script>
    <script src="<?php echo base_url('assets/select2-4.0.13/dist/js/select2.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/search-box.js') ?>"></script>
    <script src="<?php echo base_url('assets/Highcharts-4.2.6/js/highcharts.js') ?>"></script>
    <script src="<?php echo base_url('assets/Highcharts-4.2.6/js/modules/data.js') ?>"></script>
    <script src="<?php echo base_url('assets/Highcharts-4.2.6/js/modules/exporting.js') ?>"></script>


    <!-- Argon -->

    <!-- Fonts -->
    <!-- Icons -->

    <link href="<?php echo base_url('assets/argon/vendor/nucleo/css/nucleo.css'); ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('assets/argon/vendor/@fortawesome/fontawesome-free/css/all.min.css'); ?>"
          rel="stylesheet">

    <link href="<?php echo base_url('assets/argon/vendor/@fortawesome/fontawesome-free/css/v4-shims.min.css'); ?>"
          rel="stylesheet">


    <link rel="stylesheet"
          href="<?php echo base_url('assets/DataTables-1.10.9/media/css/dataTables.bootstrap.min.css'); ?>">
    <link rel="stylesheet"
          href="<?php echo base_url('assets/DataTables-1.10.9/extensions/Responsive/css/responsive.dataTables.css') ?>">
    <link rel="stylesheet"
          href="<?php echo base_url('assets/DataTables-1.10.9/extensions/Responsive/css/responsive.bootstrap.css') ?>">
    <link rel="stylesheet"
          href="<?php echo base_url('assets/DataTables-1.10.9/extensions/FixedColumns/css/fixedColumns.bootstrap.css') ?>">

    <link rel="stylesheet"
          href="<?php echo base_url('assets/DataTables-1.10.9/extensions/FixedHeader/css/fixedHeader.bootstrap4.css') ?>">


    <link rel="stylesheet" href="<?php echo base_url('assets/select2-4.0.13/dist/css/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap4-tagsinput-master/tagsinput.css'); ?>">

    <!-- Argon CSS -->
    <link type="text/css" href="<?php echo base_url('assets/argon/css/argon.css?v=1.0.0'); ?>" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .bg-subtle-pattern {
            background-color: #f7f9fb;
            background-image: radial-gradient(#d5e0f8 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#715d00",
                        "on-tertiary-fixed": "#0b1c30",
                        "on-secondary-container": "#586377",
                        "surface-variant": "#e0e3e5",
                        "secondary-container": "#d5e0f8",
                        "on-secondary-fixed": "#111c2d",
                        "surface-dim": "#d8dadc",
                        "surface-container": "#eceef0",
                        "secondary-fixed-dim": "#bcc7de",
                        "on-error": "#ffffff",
                        "primary-container": "#b89e3d",
                        "tertiary-container": "#91a1ba",
                        "on-primary-fixed-variant": "#554500",
                        "on-background": "#191c1e",
                        "on-tertiary": "#ffffff",
                        "tertiary": "#505f76",
                        "surface": "#f7f9fb",
                        "surface-container-high": "#e6e8ea",
                        "background": "#f7f9fb",
                        "primary-fixed": "#ffe178",
                        "surface-bright": "#f7f9fb",
                        "on-primary": "#ffffff",
                        "outline": "#7d7765",
                        "inverse-primary": "#e1c55f",
                        "on-secondary": "#ffffff",
                        "secondary": "#545f73",
                        "outline-variant": "#cec6b1",
                        "inverse-surface": "#2d3133",
                        "surface-container-lowest": "#ffffff",
                        "error": "#ba1a1a",
                        "primary-fixed-dim": "#e1c55f",
                        "on-primary-fixed": "#231b00",
                        "on-error-container": "#93000a",
                        "surface-container-highest": "#e0e3e5",
                        "inverse-on-surface": "#eff1f3",
                        "on-primary-container": "#423600",
                        "surface-tint": "#715d00",
                        "on-tertiary-container": "#28384c",
                        "on-secondary-fixed-variant": "#3c475a",
                        "on-surface": "#191c1e",
                        "tertiary-fixed-dim": "#b7c8e1",
                        "on-tertiary-fixed-variant": "#38485d",
                        "surface-container-low": "#f2f4f6",
                        "on-surface-variant": "#4c4637",
                        "error-container": "#ffdad6",
                        "secondary-fixed": "#d8e3fb",
                        "tertiary-fixed": "#d3e4fe"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "md": "24px",
                        "base": "8px",
                        "margin-desktop": "64px",
                        "margin-mobile": "16px",
                        "xs": "4px",
                        "gutter": "24px",
                        "lg": "48px",
                        "xl": "80px",
                        "max-width": "1280px",
                        "sm": "12px"
                    },
                    "fontFamily": {
                        "body-md": ["Inter"],
                        "label-md": ["Inter"],
                        "headline-sm": ["Inter"],
                        "display-lg": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "headline-md": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-lg": ["Inter"]
                    },
                    "fontSize": {
                        "body-md": ["16px", {
                            "lineHeight": "24px",
                            "fontWeight": "400"
                        }],
                        "label-md": ["14px", {
                            "lineHeight": "20px",
                            "letterSpacing": "0.01em",
                            "fontWeight": "500"
                        }],
                        "headline-sm": ["20px", {
                            "lineHeight": "28px",
                            "fontWeight": "600"
                        }],
                        "display-lg": ["48px", {
                            "lineHeight": "56px",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "700"
                        }],
                        "body-lg": ["18px", {
                            "lineHeight": "28px",
                            "fontWeight": "400"
                        }],
                        "headline-lg-mobile": ["28px", {
                            "lineHeight": "36px",
                            "fontWeight": "600"
                        }],
                        "headline-md": ["24px", {
                            "lineHeight": "32px",
                            "fontWeight": "600"
                        }],
                        "label-sm": ["12px", {
                            "lineHeight": "16px",
                            "fontWeight": "600"
                        }],
                        "headline-lg": ["32px", {
                            "lineHeight": "40px",
                            "letterSpacing": "-0.01em",
                            "fontWeight": "600"
                        }]
                    }
                },
            },
        }
    </script>

</head>
<?php flush(); ?>

<style>
    body#modal_form {
        overflow: visible;
    }

    table {
        font-size: 10pt
    }

    td {
        white-space: normal !important;
        word-wrap: break-word;
    }
    th {
        white-space: normal !important;
        word-wrap: break-word;
    }
</style>

<script>
    $(document).ready(function () {
        $('[name=tanggal_awal]').change(function () {
            var tanggal1 = $(this).val();
            var tanggal2 = $('[name=tanggal_ahir]').val();
            if (tanggal1 > tanggal2) {
                $('[name=tanggal_ahir]').val(tanggal1);
            }
        })

        $('[name=tanggal_ahir]').change(function () {
            var tanggal2 = $(this).val();
            var tanggal1 = $('[name=tanggal_awal]').val();
            if (tanggal2 < tanggal1) {
                $('[name=tanggal_awal]').val(tanggal2);
            }
        })
    })

    window.addEventListener("pageshow", function (event) {
        var historyTraversal = event.persisted ||
                (typeof window.performance != "undefined" &&
                        window.performance.navigation.type === 2);
        if (historyTraversal) {
            // Handle page restore.
            reload_table();
        }
    });
</script>