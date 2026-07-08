-- Fitur: owner upload bukti bayar PBB (terlihat di admin bms untuk rekap).
-- Kolom terpisah dari `file_pdf` (yg berisi PDF tagihan PBB dari admin).
-- Jalankan sekali di DB bms.
ALTER TABLE `pbb_detail`
  ADD COLUMN `bukti_bayar`      VARCHAR(255) DEFAULT NULL AFTER `id_admin_upload`,
  ADD COLUMN `bukti_tgl_upload` DATETIME     DEFAULT NULL AFTER `bukti_bayar`;
