<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Pemetaan menu P3SRS owner (permintaan BM Easton).
 *
 * Sumber kategori: DB CMS tabel `kategori` (tipe=1, hapus=0). Pemetaan berdasarkan
 * id_kategori. Label di sini MENGGANTIKAN nama CMS (nama CMS memuat salah ketik
 * "Kuangan"/"Administrai"); data CMS tidak diubah.
 *
 * Kontrak yang sama dipakai app mobile owner & API Laravel (GET /api/v1/p3srs/kategori).
 * Ubah di sini DAN di sisi lain bila daftar berubah.
 *
 * 'utama'   : tampil dulu, urutan persis seperti di bawah.
 * 'dokumen' : bagian bawah. Kategori tipe=1 yang tidak terdaftar di kedua daftar
 *             ditambahkan paling akhir di 'dokumen' (nama dari CMS, ikon 'dokumen').
 *
 * 'ikon' = kata kunci netral, dipetakan ke Material Symbols di 'ikon_symbol'.
 */
$config['p3srs_menu']['judul'] = array(
	'utama' => 'Informasi & Laporan',
	'dokumen' => 'Dokumen Legalitas & Lainnya',
);

$config['p3srs_menu']['utama'] = array(
	1 => array('label' => 'Informasi', 'deskripsi' => 'Informasi dan pengumuman pengelolaan untuk pemilik', 'ikon' => 'info'),
	2 => array('label' => 'Laporan Kegiatan Pengelolaan', 'deskripsi' => 'Ringkasan kegiatan pengelolaan kawasan', 'ikon' => 'kegiatan'),
	9 => array('label' => 'Laporan Keuangan Bulanan', 'deskripsi' => 'Laporan pemasukan dan pengeluaran per bulan', 'ikon' => 'keuangan'),
	18 => array('label' => 'Laporan Keuangan 3 Bulan', 'deskripsi' => 'Laporan keuangan per tiga bulan', 'ikon' => 'kalender'),
	24 => array('label' => 'Laporan Keuangan Tahunan Audited', 'deskripsi' => 'Laporan keuangan tahunan yang telah diaudit', 'ikon' => 'audit'),
);

$config['p3srs_menu']['dokumen'] = array(
	10 => array('label' => 'Administrasi & Legalitas', 'deskripsi' => '', 'ikon' => 'dokumen'),
	11 => array('label' => 'Anggaran Dasar', 'deskripsi' => '', 'ikon' => 'dokumen'),
	12 => array('label' => 'Anggaran Rumah Tangga', 'deskripsi' => '', 'ikon' => 'dokumen'),
	13 => array('label' => 'RUAT', 'deskripsi' => '', 'ikon' => 'dokumen'),
	14 => array('label' => 'RUA', 'deskripsi' => '', 'ikon' => 'dokumen'),
	17 => array('label' => 'Laporan Keuangan Semester', 'deskripsi' => '', 'ikon' => 'dokumen'),
);

$config['p3srs_menu']['ikon_default'] = 'dokumen';

// Kata kunci ikon -> nama Material Symbols Outlined
$config['p3srs_menu']['ikon_symbol'] = array(
	'info' => 'campaign',
	'kegiatan' => 'assignment',
	'keuangan' => 'payments',
	'kalender' => 'date_range',
	'audit' => 'verified',
	'dokumen' => 'description',
);
