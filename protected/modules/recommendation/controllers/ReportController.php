<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportController
 *
 * @author TIA.WICAKSONO
 */
class ReportController extends Controller {

    //put your code here
    public function filters() {
        return array(
//            'Rights', // perform access control for CRUD operations
        );
    }

//    ============================================================================    
    public function actionDownloadLaporan($rekom) {
        $dataRekom = TblRekom::model()->findByPk($rekom);
        $dataKendaraan = TblKendaraan::model()->findByPk($dataRekom->id_kendaraan);
        $criteriaRiwayat = new CDbCriteria();
        $criteriaRiwayat->addCondition("nama_penguji != ''");
        $criteriaRiwayat->addCondition("nrp != ''");
        $criteriaRiwayat->addInCondition('id_kendaraan',array($dataRekom->id_kendaraan));
        $criteriaRiwayat->order = "tgl_uji DESC";
        $dataRiwayat = TblRiwayat::model()->find($criteriaRiwayat);
        $dataType = TblType::model()->findByAttributes(array('id_kendaraan' => $dataRekom->id_kendaraan));
        $dataKota = TblKota::model()->findByAttributes(array('kd_kota' => $dataRekom->kd_kota));
        if($dataRekom->mutke == true){
            $file = "template_mutasi.rtf";
            $data_hal = "Mutasi Uji Kendaraan";
            $data_pemilik = $dataRekom->pemilik_baru;
            $data_alamat_pemilik = $dataRekom->alamat_baru;
        }elseif($dataRekom->numke == true){
            $file = "template_numpang.rtf";
            $data_hal = "Numpang Uji Kendaraan";
            $data_pemilik = $dataKendaraan->nama_pemilik;
            $data_alamat_pemilik = $dataKendaraan->alamat;
        }elseif($dataRekom->ubhsifat == true){
            $file = "template_ubah_sifat.rtf";
            $data_hal = "Ubah Sifat Kendaraan";
            $data_pemilik = $dataKendaraan->nama_pemilik;
            $data_alamat_pemilik = $dataKendaraan->alamat;
        }
        $path = Yii::getPathOfAlias('webroot') . '/template_laporan/hasil';
        //buka file rtf
        $webroot = Yii::getPathOfAlias('webroot');
        $template = $webroot . "/template_laporan/".$file;
        $handle = fopen($template, "r");
        $hasilbaca = fread($handle, filesize($template));
        fclose($handle);

        //nilai yang akan dituliskan dalam template
        //pada praktek sebenarnya anda bisa mengambil data dari database
        $data_daerah_mutasi = $dataRekom->kd_kota == ''? 'Surabaya':$dataKota->nm_kota;
        $data_no_kendaraan = $dataKendaraan->no_kendaraan;
        $data_pemilik = $data_pemilik;
        $data_alamat_pemilik = $data_alamat_pemilik;
        $data_jenis_kendaraan = $dataKendaraan->jenis;
        $data_merk_tipe = $dataKendaraan->tipe;
        $data_tahun_bahan_bakar = $dataKendaraan->tahun."/".$dataType->bahan_bakar;
        $data_no_mesin = $dataKendaraan->no_mesin;
        $data_no_chasis = $dataKendaraan->no_chasis;
        $data_uji_tempat = 'Surabaya';
        $data_uji_tgl_berakhir = date("d M Y", strtotime($dataKendaraan->tgl_mati_uji));
        $data_uji_penguji = $dataRiwayat->nama_penguji;
        $data_uji_nrp = $dataRiwayat->nrp;
        $data_nama_pemilik_baru = $data_pemilik;
        $data_alamat_pemilik_baru = $data_alamat_pemilik;

        //tuliskan data dalam template
        $hasilbaca = str_replace('data_tahun', date('Y'), $hasilbaca);
        $hasilbaca = str_replace('data_tgl_hari_ini', date('d M Y'), $hasilbaca);
        $hasilbaca = str_replace('data_daerah_mutasi', $data_daerah_mutasi, $hasilbaca);
        $hasilbaca = str_replace('data_no_kendaraan', $data_no_kendaraan, $hasilbaca);
        $hasilbaca = str_replace('data_pemilik', $data_pemilik, $hasilbaca);
        $hasilbaca = str_replace('data_alamat_pemilik', $data_alamat_pemilik, $hasilbaca);
        $hasilbaca = str_replace('data_jenis_kendaraan', $data_jenis_kendaraan, $hasilbaca);
        $hasilbaca = str_replace('data_merk_tipe', $data_merk_tipe, $hasilbaca);
        $hasilbaca = str_replace('data_tahun_bahan_bakar', $data_tahun_bahan_bakar, $hasilbaca);
        $hasilbaca = str_replace('data_no_mesin', $data_no_mesin, $hasilbaca);
        $hasilbaca = str_replace('data_no_chasis', $data_no_chasis, $hasilbaca);
        $hasilbaca = str_replace('data_uji_tempat', $data_uji_tempat, $hasilbaca);
        $hasilbaca = str_replace('data_uji_tgl_berakhir', $data_uji_tgl_berakhir, $hasilbaca);
        $hasilbaca = str_replace('data_uji_penguji', $data_uji_penguji, $hasilbaca);
        $hasilbaca = str_replace('data_uji_nrp', $data_uji_nrp, $hasilbaca);
        $hasilbaca = str_replace('data_nama_pemilik_baru', $data_nama_pemilik_baru, $hasilbaca);
        $hasilbaca = str_replace('data_alamat_baru', $data_alamat_pemilik_baru, $hasilbaca);
        $hasilbaca = str_replace('data_alamat_mutasi', $data_daerah_mutasi, $hasilbaca);
        $hasilbaca = str_replace('data_hal', $data_hal, $hasilbaca);

        //membuat file baru dari hasil baca
        $hasil = $path . "/hasil_laporan_" . bin2hex(random_bytes(8)) . ".doc";
        $handle = fopen($hasil, "w+");
        fwrite($handle, $hasilbaca);
        fclose($handle);
        $this->redirect(Yii::app()->request->baseUrl . '/template_laporan/hasil/' . basename($hasil));
    }

//    ============================================================================
}
