<?php
require_once __DIR__ . '/../models/nilai.php';
require_once __DIR__ . '/../models/kriteria.php';

$modelNilai = new Nilai();
$modelKriteria = new Kriteria();

$skala = $modelNilai->getAllSkala();
$normalizedBobot = $modelKriteria->getAllNormalizedBobot();
$allType = $modelKriteria->getAllKriteriaTipe();

enum Type: string
{
    case Benefit = 'benefit';
    case Cost = 'cost';
}

/**
 * Membentuk matriks dari data mentah untuk proses perhitungan TOPSIS.
 *
 * @param array $rawData Array data mentah yang berisi informasi alternatif, kriteria, nilai, dan bobot.
 * @param bool $all Jika true, mengembalikan matriks beserta daftar nama nama dari  alternatif dan kriteria.
 *                  Jika false, hanya mengembalikan matriks nilai.
 *
 * @return array Jika $all = false, mengembalikan array matriks [id_hp][id_kriteria] = poin.
 *               Jika $all = true, mengembalikan array dengan kunci:
 *                 - 'matriks' => array matriks nilai
 *                 - 'list_alternatif' => array daftar nama alternatif (HP)
 *                 - 'list_kriteria' => array daftar nama kriteria dan bobot
 */
function bentukMatriks($rawData, $all = false)
{
    $matriks = []; // [id_hp][id_kriteria] = poin
    $daftarAlt = []; // untuk simpan info nama HP
    $daftarKriteria = []; // simpan nama kriteria & bobot

    foreach ($rawData as $row) {
        $id_alt = $row['hp_id'];
        $id_kriteria = $row['kriteria_id'];

        $matriks[$id_alt][$id_kriteria] = $row['nilai'];

        $daftarAlt[$id_alt] = $row['nama_hp'];
        $daftarKriteria[$id_kriteria] = [
            'nama' => $row['nama_kriteria'],
            'bobot' => $row['bobot']
        ];
    }

    if ($all) {
        return array(
            'matriks' => $matriks,
            'list_alternatif' => $daftarAlt,
            'list_kriteria' => $daftarKriteria
        );
    }

    return $matriks;
}

function mapToSkala($matriks, $allSkala)
{
    $scaledAlt = array();
    foreach ($matriks as $alt_id => $alt) {
        $scaledKriteria = array();
        foreach ($alt as $kriteria_id => $nilai_mentah) {
            $scaledKriteria[$kriteria_id] =
                mapToPoinFromArray($allSkala, $kriteria_id, $nilai_mentah);
        }
        $scaledAlt[$alt_id] = $scaledKriteria;
    }

    return $scaledAlt;
}

function mapToPoinFromArray($allSkala, $kriteria_id, $nilai_mentah)
{
    if (!isset($allSkala[$kriteria_id]))
        return 0;
    foreach ($allSkala[$kriteria_id] as $range) {
        if ($nilai_mentah >= $range['min'] && $nilai_mentah <= $range['max']) {
            return $range['poin'];
        }
    }

}

function normalisasi($scaledMatrix)
{
    // Akar kuadrat dari tiap kategori.
    $squareRoot = array();
    //Jumlahkan dan Kuadratkan semua poin sesuai kategori
    foreach ($scaledMatrix as $scaledAlt) {
        foreach ($scaledAlt as $kategori_id => $poin) {
            // Validasi jika wadah masih kosong isi, kalau sudah ada isinya tambahkan.
            if (isset($squareRoot[$kategori_id])) {
                $squareRoot[$kategori_id] += pow($poin, 2);
            } else {
                $squareRoot[$kategori_id] = pow($poin, 2);
            }
        }
    }
    //Akarkan tiap total kuadrat kategori.
    foreach ($squareRoot as $kategori_id => $totalKuadrat) {
        $squareRoot[$kategori_id] = sqrt($totalKuadrat);
    }
    //Normalisasi tiap data alternatif sesuai kategori
    $normalizedMatrix = array();
    foreach ($scaledMatrix as $alt_id => $scaledAlt) {
        $normalizedAlt = array();
        foreach ($scaledAlt as $kategori_id => $poin) {
            $normalizedAlt[$kategori_id] = $poin / $squareRoot[$kategori_id];
        }
        $normalizedMatrix[$alt_id] = $normalizedAlt;
    }

    return $normalizedMatrix;
}

function kalikanBobot($normalizedMatrix, $normalizedBobot)
{
    $weightedMatrix = array();
    foreach ($normalizedMatrix as $alt_id => $normalizedAlt) {
        $weightedAlt = array();
        // Kalikan dengan bobot tiap alternatif
        foreach ($normalizedAlt as $kategori_id => $normalizedPoin) {
            $weightedAlt[$kategori_id] = $normalizedPoin * $normalizedBobot[$kategori_id]['bobot'];
        }
        $weightedMatrix[$alt_id] = $weightedAlt;
    }

    return $weightedMatrix;
}

function hitungIdealPositif($weightedMatrix, $allType)
{
    $idealPositive = array();
    // Kelompokkan sesuai kategori
    foreach ($weightedMatrix as $weightedAlt) {
        foreach ($weightedAlt as $kategori_id => $weightedPoin) {
            $idealPositive[$kategori_id][] = $weightedPoin;
        }
    }
    //Buat ideal positif sesuai tipe
    foreach ($allType as $kategori_id => $types) {
        if (Type::Benefit->value == $types['tipe']) {
            $idealPositive[$kategori_id] = max($idealPositive[$kategori_id]);
        }
        if (Type::Cost->value == $types['tipe']) {
            $idealPositive[$kategori_id] = min($idealPositive[$kategori_id]);
        }
    }

    return $idealPositive;
}
function hitungIdealNegatif($weightedMatrix, $allType)
{
    $idealPositive = array();
    // Kelompokkan sesuai kategori
    foreach ($weightedMatrix as $weightedAlt) {
        foreach ($weightedAlt as $kategori_id => $weightedPoin) {
            $idealPositive[$kategori_id][] = $weightedPoin;
        }
    }
    //Buat ideal negatif sesuai tipe
    foreach ($allType as $kategori_id => $types) {
        if (Type::Benefit->value == $types['tipe']) {
            $idealPositive[$kategori_id] = min($idealPositive[$kategori_id]);
        }
        if (Type::Cost->value == $types['tipe']) {
            $idealPositive[$kategori_id] = max($idealPositive[$kategori_id]);
        }
    }

    return $idealPositive;
}

function hitungJarak($weightedMatrix, $ideals)
{

    $distances = array();

    foreach ($weightedMatrix as $alt_id => $weightedAlt) {
        foreach ($weightedAlt as $kategori_id => $weightedPoin) {
            if (isset($distances[$alt_id])) {
                $distances[$alt_id] += pow($ideals[$kategori_id] - $weightedPoin, 2);
            } else {
                $distances[$alt_id] = pow($ideals[$kategori_id] - $weightedPoin, 2);
            }
        }
    }

    foreach ($distances as $alt_id => $distance) {
        $distances[$alt_id] = sqrt($distance);
    }

    return $distances;
}

function hitungPreferensi($distancePositive, $distanceNegative)
{
    $preferenceScores = array();
    foreach ($distanceNegative as $alt_id => $distancePoinNegative) {
        $distancePoinPositive = $distancePositive[$alt_id];
        $preferenceScores[$alt_id] = $distancePoinNegative / ($distancePoinPositive + $distancePoinNegative);
    }

    return $preferenceScores;
}

function urutkanRanking($preferenceScores)
{
    // Copy array supaya gak mengubah array asli langsung
    $sorted = $preferenceScores;

    // Sort descending tapi mempertahankan key (index)
    arsort($sorted);

    // Buat array hasil ranking: key = id alternatif, value = rank (1 = tertinggi)
    $ranking = [];
    $rank = 1;
    foreach ($sorted as $alt_id => $score) {
        $ranking[$alt_id]['rank'] = $rank;
        $ranking[$alt_id]['score'] = $score;
        $rank++;
    }

    return $ranking;
}