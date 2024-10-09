<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tpsData = [
            ['namaTps' => 'TPS Tegalsari', 'lat' => -7.2657571, 'lng' => 112.7341463, 'alamat' => 'Jalan Tegalsari No. 15, Surabaya'],
            ['namaTps' => 'TPS Genteng', 'lat' => -7.2602182, 'lng' => 112.7397985, 'alamat' => 'Jalan Genteng Kali No. 12, Surabaya'],
            ['namaTps' => 'TPS Tambaksari', 'lat' => -7.2634169, 'lng' => 112.7688457, 'alamat' => 'Jalan Tambaksari No. 22, Surabaya'],
            ['namaTps' => 'TPS Wonokromo', 'lat' => -7.290579, 'lng' => 112.739539, 'alamat' => 'Jalan Wonokromo No. 8, Surabaya'],
            ['namaTps' => 'TPS Dukuh Pakis', 'lat' => -7.290228, 'lng' => 112.705527, 'alamat' => 'Jalan Dukuh Pakis No. 7, Surabaya'],
            ['namaTps' => 'TPS Rungkut', 'lat' => -7.325706, 'lng' => 112.784478, 'alamat' => 'Jalan Raya Rungkut No. 14, Surabaya'],
            ['namaTps' => 'TPS Gubeng', 'lat' => -7.272408, 'lng' => 112.761793, 'alamat' => 'Jalan Raya Gubeng No. 33, Surabaya'],
            ['namaTps' => 'TPS Wiyung', 'lat' => -7.306097, 'lng' => 112.670278, 'alamat' => 'Jalan Wiyung No. 5, Surabaya'],
            ['namaTps' => 'TPS Lakarsantri', 'lat' => -7.321148, 'lng' => 112.663175, 'alamat' => 'Jalan Raya Lakarsantri No. 10, Surabaya'],
            ['namaTps' => 'TPS Benowo', 'lat' => -7.253654, 'lng' => 112.648496, 'alamat' => 'Jalan Raya Benowo No. 3, Surabaya'],
            ['namaTps' => 'TPS Tandes', 'lat' => -7.271665, 'lng' => 112.675961, 'alamat' => 'Jalan Raya Tandes No. 19, Surabaya'],
            ['namaTps' => 'TPS Karangpilang', 'lat' => -7.342671, 'lng' => 112.696076, 'alamat' => 'Jalan Raya Karangpilang No. 1, Surabaya'],
            ['namaTps' => 'TPS Sambikerep', 'lat' => -7.313537, 'lng' => 112.646513, 'alamat' => 'Jalan Raya Sambikerep No. 27, Surabaya'],
            ['namaTps' => 'TPS Bulak', 'lat' => -7.242901, 'lng' => 112.806656, 'alamat' => 'Jalan Raya Bulak No. 45, Surabaya'],
            ['namaTps' => 'TPS Simokerto', 'lat' => -7.240227, 'lng' => 112.754314, 'alamat' => 'Jalan Simokerto No. 9, Surabaya'],
            ['namaTps' => 'TPS Kenjeran', 'lat' => -7.249078, 'lng' => 112.771232, 'alamat' => 'Jalan Kenjeran No. 55, Surabaya'],
            ['namaTps' => 'TPS Pabean Cantikan', 'lat' => -7.227944, 'lng' => 112.733326, 'alamat' => 'Jalan Pabean No. 2, Surabaya'],
            ['namaTps' => 'TPS Mulyorejo', 'lat' => -7.268163, 'lng' => 112.781748, 'alamat' => 'Jalan Mulyorejo No. 31, Surabaya'],
            ['namaTps' => 'TPS Tenggilis Mejoyo', 'lat' => -7.308863, 'lng' => 112.755676, 'alamat' => 'Jalan Tenggilis No. 20, Surabaya'],
            ['namaTps' => 'TPS Sawahan', 'lat' => -7.271732, 'lng' => 112.717772, 'alamat' => 'Jalan Sawahan No. 3, Surabaya'],
            ['namaTps' => 'TPS Pakal', 'lat' => -7.268846, 'lng' => 112.645158, 'alamat' => 'Jalan Pakal No. 23, Surabaya'],
            ['namaTps' => 'TPS Gayungan', 'lat' => -7.319458, 'lng' => 112.734086, 'alamat' => 'Jalan Gayungan No. 4, Surabaya'],
            ['namaTps' => 'TPS Semampir', 'lat' => -7.238115, 'lng' => 112.748867, 'alamat' => 'Jalan Raya Semampir No. 21, Surabaya'],
            ['namaTps' => 'TPS Krembangan', 'lat' => -7.236172, 'lng' => 112.727286, 'alamat' => 'Jalan Raya Krembangan No. 11, Surabaya'],
            ['namaTps' => 'TPS Tambak Sarioso', 'lat' => -7.253064, 'lng' => 112.682013, 'alamat' => 'Jalan Raya Tambak Sarioso No. 16, Surabaya'],
            ['namaTps' => 'TPS Sukomanunggal', 'lat' => -7.278878, 'lng' => 112.687426, 'alamat' => 'Jalan Raya Sukomanunggal No. 33, Surabaya'],
            ['namaTps' => 'TPS Asemrowo', 'lat' => -7.248552, 'lng' => 112.699407, 'alamat' => 'Jalan Asemrowo No. 7, Surabaya'],
            ['namaTps' => 'TPS Jambangan', 'lat' => -7.335512, 'lng' => 112.719963, 'alamat' => 'Jalan Raya Jambangan No. 22, Surabaya'],
            ['namaTps' => 'TPS Gunung Anyar', 'lat' => -7.344281, 'lng' => 112.798474, 'alamat' => 'Jalan Raya Gunung Anyar No. 18, Surabaya'],
            ['namaTps' => 'TPS Benyamin Sueb', 'lat' => -7.317806, 'lng' => 112.764342, 'alamat' => 'Jalan Benyamin Sueb No. 28, Surabaya'],
        ];

        DB::table('tps')->insert($tpsData);
    }
}
