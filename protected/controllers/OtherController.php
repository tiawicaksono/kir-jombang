<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OtherController
 *
 * @author TIA.WICAKSONO
 */
class OtherController extends Controller
{

    //put your code here

    public function filters()
    {
        return array(
            //            'Rights', // perform access control for CRUD operations
        );
    }

    public function actionGrafik($param)
    {
        $page = 'grafik';
        $year = Yii::app()->params['tahunGrafik'];
        $this->render('grafik_bar', array(
            'year' => $year,
            'param' => $param,
            'page' => $page
        ));
    }

    public function actionAutoComplete()
    {
        if (isset($_REQUEST['term'])) {
            $term = $_REQUEST['term'];
            $criteria = new CDbCriteria;
            $criteria->compare('lower(username)', strtolower($term), true);
            $criteria->limit = 10;
            $datas = TblUser::model()->findAll($criteria);

            $return_array = array();
            foreach ($datas as $data) :
                $return_array[] = array(
                    'id' => $data->id_user,
                    'value' => $data->username,
                );
            endforeach;
            if (count($return_array) > 0) {
                echo CJSON::encode($return_array);
            } else {
                $return_array[] = array(
                    'id' => '',
                    'value' => 'data not found!',
                );
                echo CJSON::encode($return_array);
            }
        }
    }

    public function actionUpload()
    {
        $file = Yii::getPathOfAlias('webroot') . '/uploads/6.xls';


        $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        $objPHPExcel = $objReader->load($file);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIdex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        for ($row = 1; $row <= $highestRow; $row++) {
            //===========VARIABLLE===========
            $nama_pemilik = str_replace("'", "`", $objWorksheet->getCell([1, $row])->getValue());
            $alamat_pemilik = str_replace("'", "`", $objWorksheet->getCell([2, $row])->getValue());
            $no_kendaraan = $objWorksheet->getCell([3, $row])->getValue();
            $no_uji = $objWorksheet->getCell([4, $row])->getValue();
            $pengimport = strtoupper($objWorksheet->getCell([5, $row])->getValue());
            $nm_komersil = strtoupper($objWorksheet->getCell([6, $row])->getValue());
            $merk = strtoupper($objWorksheet->getCell([7, $row])->getValue());
            $tahun = strtoupper($objWorksheet->getCell([8, $row])->getValue());
            $kar_jenis = strtoupper($objWorksheet->getCell([9, $row])->getValue());
            $no_chassis = strtoupper($objWorksheet->getCell([11, $row])->getValue());
            $no_mesin = strtoupper($objWorksheet->getCell([12, $row])->getValue());
            $bahan_bakar = strtoupper($objWorksheet->getCell([13, $row])->getValue());
            $status = strtoupper($objWorksheet->getCell([14, $row])->getValue());
            $p_utama = strtoupper($objWorksheet->getCell([15, $row])->getValue());
            $l_utama = strtoupper($objWorksheet->getCell([16, $row])->getValue());
            $t_utama = strtoupper($objWorksheet->getCell([17, $row])->getValue());
            $jsb_1_2 = strtoupper($objWorksheet->getCell([18, $row])->getValue());
            $foh = strtoupper($objWorksheet->getCell([22, $row])->getValue());
            $roh = strtoupper($objWorksheet->getCell([23, $row])->getValue());
            $jbb = strtoupper($objWorksheet->getCell([29, $row])->getValue());
            $tgl_pemakaian = strtoupper($objWorksheet->getCell([31, $row])->getValue());
            if ($tgl_pemakaian != "") {
                $tgl_pemakaian = date('m/d/Y', \PhpOffice\PhpSpreadsheet\Shared\Date::ExcelToPHP($tgl_pemakaian));
            } else {
                $tgl_pemakaian = NULL;
            }
            $duduk = strtoupper($objWorksheet->getCell([34, $row])->getValue());
            $kar_bahan = strtoupper($objWorksheet->getCell([35, $row])->getValue());
            $b_sb1 = strtoupper($objWorksheet->getCell([37, $row])->getValue());
            $b_sb2 = strtoupper($objWorksheet->getCell([38, $row])->getValue());
            $daya_angkut_orang = strtoupper($objWorksheet->getCell([43, $row])->getValue());
            $daya_angkut_barang = strtoupper($objWorksheet->getCell([45, $row])->getValue());
            $jbi = strtoupper($objWorksheet->getCell([46, $row])->getValue());
            $mst = strtoupper($objWorksheet->getCell([47, $row])->getValue());
            $kls_jln = strtoupper($objWorksheet->getCell([48, $row])->getValue());
            $p_sb1 = strtoupper($objWorksheet->getCell([49, $row])->getValue());
            $p_sb2 = strtoupper($objWorksheet->getCell([50, $row])->getValue());
            $tgl_sertifikasi = strtoupper($objWorksheet->getCell([54, $row])->getValue());
            if ($tgl_sertifikasi != "") {
                $tgl_sertifikasi = date('m/d/Y', \PhpOffice\PhpSpreadsheet\Shared\Date::ExcelToPHP($tgl_sertifikasi));
            } else {
                $tgl_sertifikasi = NULL;
            }
            $no_sertifikasi = strtoupper($objWorksheet->getCell([55, $row])->getValue());
            $tgl_uji_tipe = strtoupper($objWorksheet->getCell([56, $row])->getValue());
            if ($tgl_uji_tipe != "") {
                $tgl_uji_tipe = date('m/d/Y', \PhpOffice\PhpSpreadsheet\Shared\Date::ExcelToPHP($tgl_uji_tipe));
            } else {
                $tgl_uji_tipe = NULL;
            }
            $no_uji_tipe = strtoupper($objWorksheet->getCell([57, $row])->getValue());
            $tgl_rancang_bangun = strtoupper($objWorksheet->getCell([59, $row])->getValue());
            if ($tgl_rancang_bangun != "") {
                $tgl_rancang_bangun = date('m/d/Y', \PhpOffice\PhpSpreadsheet\Shared\Date::ExcelToPHP($tgl_rancang_bangun));
            } else {
                $tgl_rancang_bangun = NULL;
            }
            $no_rancang_bangun = strtoupper($objWorksheet->getCell([58, $row])->getValue());
            $oleh_rancang_bangun = strtoupper($objWorksheet->getCell([100, $row])->getValue());
            $p_tangki = strtoupper($objWorksheet->getCell([88, $row])->getValue());
            $l_tangki = strtoupper($objWorksheet->getCell([89, $row])->getValue());
            $t_tangki = strtoupper($objWorksheet->getCell([90, $row])->getValue());
            $warna = strtoupper($objWorksheet->getCell([65, $row])->getValue());
            $jrk_terendah = strtoupper($objWorksheet->getCell([66, $row])->getValue());
            $isi_silinder = strtoupper($objWorksheet->getCell([67, $row])->getValue());
            $daya_motor = strtoupper($objWorksheet->getCell([68, $row])->getValue());
            $konf_sumbu = strtoupper($objWorksheet->getCell([69, $row])->getValue());
            $p = strtoupper($objWorksheet->getCell([76, $row])->getValue());
            $q = strtoupper($objWorksheet->getCell([77, $row])->getValue());
            $kecamatan = strtoupper($objWorksheet->getCell([98, $row])->getValue());
            $desa = strtoupper($objWorksheet->getCell([99, $row])->getValue());


            //CEK DATA SUDAH ADA ATAU BELUM
            $data_kendaraan = TblKendaraan::model()->findByAttributes(array('no_uji' => $no_uji));
            if (count($data_kendaraan) == 0) {
                //                $tbl = new TblKendaraan();
                //                $tbl->no_uji = $no_uji;
                //                $tbl->no_kendaraan = $no_kendaraan;
                //                $tbl->alamat = $alamat_pemilik;
                //                $tbl->propinsi = 'JAWA TIMUR';
                //                $tbl->kota = $desa;
                //                $tbl->kecamatan = $kecamatan;
                //                $tbl->jenis = 'M. BARANG';
                //                $tbl->id_jns_kend = 0;
                //                $tbl->merk = $merk;
                //                $tbl->tipe = '';
                //                $tbl->tahun = $tahun;
                //                $tbl->sifat = $status;
                //                $tbl->no_chasis = $no_chassis;
                //                $tbl->no_mesin = $no_mesin;
                //                $tbl->pengimport = $pengimport;
                //                $tbl->tgl_mati_uji = date('m/d/Y');
                //                if($tbl->save()){
                //                    $criteria = new CDbCriteria();
                //                    $criteria->order = "id_kendaraan DESC";
                //                    $id_kendaraan = TblKendaraan::model()->find($criteria)->id_kendaraan;
                //                    $tblType = new TblType();
                //                    $tblType->id_kendaraan = $id_kendaraan;
                //                    $tblType->nm_komersil = $nm_komersil;
                //                    $tblType->isi_silinder = $isi_silinder;
                //                    $tblType->daya_motor = $daya_motor;
                //                    $tblType->bahan_bakar = $bahan_bakar;
                //                    $tblType->warna = $warna;
                //                    $tblType->warna_bak = '';
                //                    $tblType->bagian_belakang = $roh;
                //                    $tblType->bagian_depan = $foh;
                //                    $tblType->kemjbb = $jbb;
                //                    $tblType->kemjbkb = 0;
                //                    $tblType->bagian_jterendah = $jrk_terendah;
                //                    $tblType->ukuran_panjang = $p_utama;
                //                    $tblType->ukuran_lebar = $l_utama;
                //                    $tblType->ukuran_tinggi = $t_utama;
                //                    $tblType->jsumbu1 = $jsb_1_2;
                //                    $tblType->jsumbu2 = 0;
                //                    $tblType->jsumbu3 = 0;
                //                    $tblType->dimpanjang = $p_tangki;
                //                    $tblType->dimlebar = $l_tangki;
                //                    $tblType->dimtinggi = $t_tangki;
                //                    $tblType->psumbu1 = $p_sb1;
                //                    $tblType->psumbu2 = $p_sb2;
                //                    $tblType->psumbu3 = 0;
                //                    $tblType->psumbu4 = 0;
                //                    $tblType->konsumbu = $konf_sumbu;
                //                    $tblType->bsumbu1 = $b_sb1;
                //                    $tblType->bsumbu2 = $b_sb2;
                //                    $tblType->bsumbu3 = 0;
                //                    $tblType->bsumbu4 = 0;
                //                    $tblType->karoseri_duduk = $duduk;
                //                    $tblType->kemorang = $daya_angkut_orang;
                //                    $tblType->kembarang = $daya_angkut_barang;
                //                    $tblType->mst = $mst;
                //                    $tblType->kls_jln = $kls_jln;
                //                    $tblType->karoseri_bahan = $kar_bahan;
                //                    $tblType->karoseri_berdiri = 0;
                //                    $tblType->nm_karoseri = '';
                //                    $tblType->ukp = $p;
                //                    $tblType->ukq = $q;
                //                    $tblType->save();
                //                    
                //                    $tblSertifikasi = new TblSertifikasi();
                //                    $tblSertifikasi->id_kendaraan = $id_kendaraan;
                //                    $tblSertifikasi->no_regis = $no_sertifikasi;
                //                    $tblSertifikasi->oleh_regis = '';
                //                    $tblSertifikasi->tgl_regis = $tgl_sertifikasi;
                //                    $tblSertifikasi->no_tipe = $no_uji_tipe;
                //                    $tblSertifikasi->oleh_tipe = '';
                //                    $tblSertifikasi->tgl_tipe = $tgl_uji_tipe;
                //                    $tblSertifikasi->no_rancang = $no_rancang_bangun;
                //                    $tblSertifikasi->oleh_rancang = $oleh_rancang_bangun;
                //                    $tblSertifikasi->tgl_rancang = $tgl_rancang_bangun;
                //                    $tblSertifikasi->save();
                //                }
            } else {
                $id_kendaraan = $data_kendaraan->id_kendaraan;
                $sql = "UPDATE tbl_kendaraan SET nama_pemilik='$nama_pemilik' where id_kendaraan=$id_kendaraan";
                Yii::app()->db->createCommand($sql)->execute();
                //
                //                $sql = "UPDATE tbl_type SET nm_komersil='$nm_komersil',isi_silinder='$silinder',daya_motor='$daya_motor',bahan_bakar='$bahan_bakar',"
                //                        . "warna = '$warna_cabin', warna_bak = '$warna_bak',bagian_belakang='$roh',bagian_depan='$foh',kemjbb='$jbb',kemjbkb='$jbkb',"
                //                        . "ukuran_panjang='$panjang',ukuran_lebar='$lebar',ukuran_tinggi='$tinggi',"
                //                        . "jsumbu1='$jsmb1',jsumbu2='$jsmb2',jsumbu3='$jsmb3',dimpanjang='$panjang_bak',dimlebar='$lebar_bak',dimtinggi='$tinggi_bak',"
                //                        . "psumbu1 = '$pemakaian_sb1_ban',psumbu2 = '$pemakaian_sb2_ban',psumbu3 = '$pemakaian_sb3_ban',psumbu4 = '$pemakaian_sb4_ban', "
                //                        . "konsumbu='$konf_sumbu',bsumbu1='$berat_sumbu1',bsumbu2='$berat_sumbu2',bsumbu3='$berat_sumbu3',bsumbu4='$berat_sumbu4',"
                //                        . "karoseri_duduk = '$duduk', kemorang='$daya_orang',kembarang='$daya_barang',"
                //                        . "mst1='$mst',kls_jln='$kls_jln',karoseri_bahan='$bahan',karoseri_berdiri='$berdiri', ukq='$q',ukp='$p1' "
                //                        . " where id_kendaraan=$id_kendaraan";
                //                Yii::app()->db->createCommand($sql)->execute();
            }
        }
    }

    public function actionReadExcel()
    {
        $file = Yii::getPathOfAlias('webroot') . '/uploads/desa.xls';


        $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        $objPHPExcel = $objReader->load($file);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIdex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        for ($row = 1; $row <= $highestRow; $row++) {
            //===========VARIABLLE===========
            $id_kec = $objWorksheet->getCell([1, $row])->getValue();
            $nama = $objWorksheet->getCell([2, $row])->getValue();
            $tbl = new TblDesa();
            $tbl->nm_desa = $nama;
            $tbl->id_kecamatan = $id_kec;
            $tbl->save();
        }
    }
}
