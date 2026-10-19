<?php

class LampuController extends Controller {

    public $layout = '//layouts/main_top';
    
    public function filters() {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }
    
//    public function actionIndex() {
//        $this->pageTitle = 'Lampu';
//        $this->render('index');
//    }
    
    public function actionReloadData() {
        $tahun_kendaraan = $_POST['tahun_kendaraan'];
        $tahun_sekarang = date('Y');
        
        /*
         * LAMPU
         */
        if (($tahun_sekarang - $tahun_kendaraan) <= 5) {
            $lampu_kanan_kiri = rand(14, 20);
            $dev_kanan = $this->random(0.1, 0.34);
            $dev_kiri = $this->random(1, 1.09);
        } else {
            $lampu_kanan_kiri = rand(14, 20);
            $dev_kanan = $this->random(0.1, 0.34);
            $dev_kiri = $this->random(1, 1.09);
        }
        $data['lampu_kanan'] = $lampu_kanan_kiri;
        $data['lampu_kiri'] = $lampu_kanan_kiri;
        $data['dev_kanan'] = $dev_kanan;
        $data['dev_kiri'] = $dev_kiri;
        
        echo json_encode($data);
    }

    public function countDecimals($x) {
        return strlen(substr(strrchr($x + "", "."), 1));
    }

    public function random($min, $max, $mul = 100) {
//        $decimals = max($this->countDecimals($min), $this->countDecimals($max));
//        $factor = pow(10, $decimals);
//        return rand($min * $factor, $max * $factor) / $factor;
        return mt_rand($min * $mul, $max * $mul) / $mul;
    }

    public function actionListGrid() {
        $posisi = Yii::app()->session['posisi_cis'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'numerator';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;
//        $criteria->addCondition("posisi = '$posisi'");
        $result = VCisLampu::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $p) {
            $dataJson[] = array(
                "id_hasil_uji" => $p->id_hasil_uji,
//                "posisi" => $p->posisi,
                "no_antrian" => $p->no_antrian,
                "no_uji" => $p->no_uji,
                "no_kendaraan" => $p->no_kendaraan,
                "merk" => $p->merk,
                "tipe" => $p->tipe,
                "tahun" => $p->tahun,
                "bahan_bakar" => $p->bahan_bakar,
                "nm_komersil" => $p->nm_komersil,
                "karoseri_jenis" => $p->karoseri_jenis,
                "nm_uji" => $p->nm_uji,
                "numerator" => $p->numerator,
                "numerator_hari" => $p->numerator_hari
            );
        }
        header('Content-Type: application/json');
        echo CJSON::encode(
                array(
                    'total' => VCisLampu::model()->count($criteria),
                    'rows' => $dataJson,
                )
        );
        Yii::app()->end();
    }
    
    public function actionProses() {
        $idhasil = $_POST['id_hasil_uji'];
        $variabel = $_POST['variabel'];
        $username = $_POST['username'];
        $img1 = '';
        $img2 = '';
//        $ip_depan = pkbEnv('CAMERA_URL', '');
//        $ch_depan = curl_init($ip_depan);
//        curl_setopt($ch_depan, CURLOPT_TIMEOUT, 5);
//        curl_setopt($ch_depan, CURLOPT_CONNECTTIMEOUT, 5);
//        curl_setopt($ch_depan, CURLOPT_RETURNTRANSFER, true);
//        $data_depan = curl_exec($ch_depan);
//        $httpdcode_depan = curl_getinfo($ch_depan, CURLINFO_HTTP_CODE);
//        curl_close($ch_depan);
//        if($httpdcode_depan != 0){
//            $gmb_depan = file_get_contents(pkbEnv('CAMERA_URL', ''));
//            $img1 = base64_encode($gmb_depan);
//        }else{
//            $img1 = '';
//        }
//        
//        $ip_belakang = pkbEnv('CAMERA_URL', '');
//        $ch_belakang = curl_init($ip_belakang);
//        curl_setopt($ch_belakang, CURLOPT_TIMEOUT, 5);
//        curl_setopt($ch_belakang, CURLOPT_CONNECTTIMEOUT, 5);
//        curl_setopt($ch_belakang, CURLOPT_RETURNTRANSFER, true);
//        $data_belakang = curl_exec($ch_belakang);
//        $httpdcode_belakang = curl_getinfo($ch_belakang, CURLINFO_HTTP_CODE);
//        curl_close($ch_belakang);
//        if($httpdcode_belakang != 0){
//            $gmb_belakang = file_get_contents(pkbEnv('CAMERA_URL', ''));
//            $img2 = base64_encode($gmb_belakang);
//        }else{
//            $img2 = '';
//        }
        
        $query = "select update_cis_lampu('$variabel',$idhasil,'$username');";
        Yii::app()->db->createCommand($query)->execute();
        
//        $proses = "UPDATE tbl_hasil_uji set img1='" . $img1 . "',img2='" . $img2 . "' where id_hasil_uji=" . $idhasil;
//        Yii::app()->db->createCommand($proses)->execute();
    }

}
