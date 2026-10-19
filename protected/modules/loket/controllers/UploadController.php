<?php

class UploadController extends Controller {

    public function filters() {
        return array(
            'Rights',
        );
    }

    public function actionIndex() {
        $this->render('upload');
    }

    /* =========================================================================
     * UPLOAD AND DOWNLOAD EXCEL NILAI
      ========================================================================= */

    public function actionReadUpload() {
        $file = $_FILES['myfile']['tmp_name'];


        $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        $objPHPExcel = $objReader->load($file);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        for ($row = 1; $row <= $highestRow; $row++) {
            echo $tgl_mati = strtotime('m/d/Y',$objWorksheet->getCell([76, $row])->getValue());
            //===========VARIABLLE===========
//            $id = $objWorksheet->getCell([2, $row])->getValue();
//            $no_uji = $objWorksheet->getCell([3, $row])->getValue();
//            $no_kendaraan = $objWorksheet->getCell([4, $row])->getValue();
//            $alamat_pemilik = str_replace("'", "`", $objWorksheet->getCell([5, $row])->getValue());
//            $prov = strtoupper($objWorksheet->getCell([6, $row])->getValue());
//            $kota_kab = strtoupper($objWorksheet->getCell([7, $row])->getValue());
//            $kecamatan = strtoupper($objWorksheet->getCell([8, $row])->getValue());
//            $merk = strtoupper($objWorksheet->getCell([9, $row])->getValue());
//            $type = strtoupper($objWorksheet->getCell([10, $row])->getValue());
//            $jenis = strtoupper($objWorksheet->getCell([11, $row])->getValue());
//            $nm_komersil = strtoupper($objWorksheet->getCell([12, $row])->getValue());
//            $silinder = $objWorksheet->getCell([13, $row])->getValue();
//            $daya_motor = $objWorksheet->getCell([14, $row])->getValue();
//            $bahan_bakar = $objWorksheet->getCell([15, $row])->getValue();
//            $tahun = $objWorksheet->getCell([16, $row])->getValue();
//            $status = $objWorksheet->getCell([17, $row])->getValue();
//            $no_rangka = $objWorksheet->getCell([19, $row])->getValue();
//            $no_mesin = $objWorksheet->getCell([20, $row])->getValue();
//            $warna_cabin = $objWorksheet->getCell([21, $row])->getValue();
//            $warna_bak = $objWorksheet->getCell([22, $row])->getValue();
//            $jbb = $objWorksheet->getCell([24, $row])->getValue();
//            $panjang = $objWorksheet->getCell([25, $row])->getValue();
//            $lebar = $objWorksheet->getCell([26, $row])->getValue();
//            $tinggi = $objWorksheet->getCell([27, $row])->getValue();
//            $roh = $objWorksheet->getCell([28, $row])->getValue();
//            $foh = $objWorksheet->getCell([29, $row])->getValue();
//            $jsmb1 = $objWorksheet->getCell([30, $row])->getValue();
//            $jsmb2 = $objWorksheet->getCell([31, $row])->getValue();
//            $jsmb3 = $objWorksheet->getCell([32, $row])->getValue();
//            $panjang_bak = $objWorksheet->getCell([33, $row])->getValue();
//            $lebar_bak = $objWorksheet->getCell([34, $row])->getValue();
//            $tinggi_bak = $objWorksheet->getCell([35, $row])->getValue();
//            $pemakaian_sb1_ban = $objWorksheet->getCell([41, $row])->getValue();
//            $pemakaian_sb2_ban = $objWorksheet->getCell([42, $row])->getValue();
//            $pemakaian_sb3_ban = $objWorksheet->getCell([43, $row])->getValue();
//            $pemakaian_sb4_ban = $objWorksheet->getCell([44, $row])->getValue();
//            $konf_sumbu = str_replace('-', '.', $objWorksheet->getCell([45, $row])->getValue());
//            $jbkb = $objWorksheet->getCell([46, $row])->getValue();
//            $berat_sumbu1 = $objWorksheet->getCell([47, $row])->getValue();
//            $berat_sumbu2 = $objWorksheet->getCell([48, $row])->getValue();
//            $berat_sumbu3 = $objWorksheet->getCell([49, $row])->getValue();
//            $berat_sumbu4 = $objWorksheet->getCell([50, $row])->getValue();
//            $duduk = $objWorksheet->getCell([56, $row])->getValue();
//            $daya_orang = $duduk*60;
//            $daya_barang = $objWorksheet->getCell([64, $row])->getValue();
//            $mst = $objWorksheet->getCell([61, $row])->getValue();
//            $kls_jln = $objWorksheet->getCell([62, $row])->getValue();
//            $karoseri = $objWorksheet->getCell([68, $row])->getValue();
//            $bahan = $objWorksheet->getCell([69, $row])->getValue();
//            $berdiri = $objWorksheet->getCell([71, $row])->getValue();
//            $p1 = $objWorksheet->getCell([72, $row])->getValue();
//            $q = $objWorksheet->getCell([73, $row])->getValue();
//            $tgl_mati = strtotime('m/d/Y',$objWorksheet->getCell([76, $row])->getValue());
//            $no_sert_reg_uji_tipe = strtoupper($objWorksheet->getCell([64, $row])->getValue());
//            $no_sert_uji_tipe = strtoupper($objWorksheet->getCell([65, $row])->getValue());
//            $tgl_sert_reg_uji_tipe = strtotime('m/d/Y',strtoupper($objWorksheet->getCell([66, $row])->getValue()));
//            $tgl_sert_uji_tipe = strtotime('m/d/Y',strtoupper($objWorksheet->getCell([67, $row])->getValue()));
//            
//            //CEK DATA SUDAH ADA ATAU BELUM
//            $data_kendaraan = TblKendaraan::model()->findByAttributes(array('no_uji' => $no_uji));
//            if (count($data_kendaraan) == 0) {
//                $tbl = new TblKendaraan();
//                $tbl->no_uji = $no_uji;
//                $tbl->no_kendaraan = $no_kendaraan;
//                $tbl->nama_pemilik = $nama_pemilik;
//                $tbl->no_identitas = $nik;
//                $tbl->kelamin = $jenis_kelamin;
//                $tbl->tmp_lahir = $tempat_lahir;
//                $tbl->alamat = $alamat;
//                $tbl->jenis = $jns;
//                $tbl->id_jns_kend = $id_jns;
//                $tbl->merk = $merk;
//                $tbl->tipe = $tipe;
//                $tbl->tahun = $thn;
//                $tbl->sifat = $status;
//                $tbl->no_chasis = $no_rangka;
//                $tbl->no_mesin = $no_mesin;
//                $tbl->save();
//            } else {
//                $id_kendaraan = $data_kendaraan->id_kendaraan;
//                $sql = "UPDATE tbl_kendaraan SET no_kendaraan='$no_kendaraan',nama_pemilik='$nama_pemilik',no_identitas='$nik',"
//                        . "kelamin='$jenis_kelamin',tmp_lahir='$tempat_lahir',alamat='$alamat',jenis='$jns',id_jns_kend='$id_jns',merk='$merk',"
//                        . "tipe='$tipe',tahun='$thn',sifat='$status',no_chasis='$no_rangka',no_mesin='$no_mesin' where id_kendaraan=$id_kendaraan";
//                Yii::app()->db->createCommand($sql)->execute();
//
//                $sql = "UPDATE tbl_type SET nm_komersil='$nm_komersil',isi_silinder='$isi_silinder',daya_motor='$daya_motor',bahan_bakar='$bahan_bakar',"
//                        . "ukuran_panjang='$panjang',ukuran_lebar='$lebar',ukuran_tinggi='$tinggi',bagian_belakang='$roh',bagian_depan='$foh',"
//                        . "jsumbu1='$jsmb1',jsumbu2='$jsmb2',jsumbu3='$jsmb3',ukq='$q',ukp='$p1',ukp2='$p2',dimpanjang='$panjang_bak',dimlebar='$lebar_bak',"
//                        . "dimtinggi='$tinggi_bak',karoseri_bahan='$bahan_bak',psumbu1='$pemakaian_sb_ban',konsumbu='$konf_sumbu',kemjbb='$jbb',"
//                        . "bsumbu1='$berat_sumbu1',bsumbu2='$berat_sumbu2',bsumbu3='$berat_sumbu3',bsumbu4='$berat_sumbu4',kemorang='$daya_orang',"
//                        . "kembarang='$daya_barang',mst1='$mst',kls_jln='$kelas_jalan' where id_kendaraan=$id_kendaraan";
//                Yii::app()->db->createCommand($sql)->execute();
//            }
        }
    }

    public function actionReadUpload2() {
        $file = $_FILES['myfile']['tmp_name'];


        $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $objPHPExcel = $objReader->load($file);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        echo $highestRow;
        exit;
        for ($row = 1; $row <= $highestRow; $row++) {
            echo $nama_penguji = $objWorksheet->getCell([2, $row])->getValue();
            echo $nrp = $objWorksheet->getCell([3, $row])->getValue();
//            $tbl = new TblNamaPenguji();
//            $tbl->nama_penguji = $nama_penguji;
//            $tbl->nrp = $nrp;
//            $tbl->status_penguji = false;
//            $tbl->save();
        }
    }
}
