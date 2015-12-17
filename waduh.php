<?php

/* Sistem Basis Data

----------------------------
-Nama : Dias Taufik Rahman -
-Kelas : 3IA10             -
-NPM : 52413405            -
----------------------------

*/


/* Disable PHP Error Reporting
berguna saat proses debugging aplikasi

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

*/

/* Setting koneksi MySQL

*/

$server="localhost";$username="root";$password="55656354";$database="kuesioner";

$koneksi=mysqli_connect($server,$username,$password,$database);

/* Kueri Mysql */

$dataJawaban="SELECT JAWAB FROM KNJ_DOS";
$dataKunci="SELECT * FROM KNJ_KUNCI";

$result=mysqli_query($koneksi,$dataJawaban);
$result2=mysqli_query($koneksi,$dataKunci);
$res1=mysqli_query($koneksi,"SELECT KD_DOS,KDMK,RASIO FROM KNJ_DOS");

/* Ambil data sebagai Array */

$kunci=mysqli_fetch_array($result2);

$jawaban=array();
$i=0;
while($row=mysqli_fetch_array($result)){
  array_push($jawaban,$row['JAWAB']);
}

/* Inisialisasi Variable dan Array */

$str='';
$grade='';

$ratio=0;
$jml=0;
$rata=0;
$count=0;

$rat=array();
$kddos=array();
$kdmk=array();

while($row=mysqli_fetch_array($res1)){
  array_push($rat,$row[2]);
  array_push($kddos,$row[0]);
  array_push($kdmk,$row[1]);
}
?>

<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Take Home Test Kelompok B</title>
    <link href="asset/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
      <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Sistem Basis Data</a>
          </div>

          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
              <li><a href="#">Tentang</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>

    <div class="container">
        <table class="table table-striped table-hover ">
          <thead>
            <tr>
              <th>Kode Dosen</th>
              <th>Mata Kuliah</th>
              <th>Jawaban</th>
              <th>Skor</th>
              <th>Rasio</th>
              <th>Grade</th>
            </tr>
          </thead>
          <tbody>
            <?php
            /* Melakukan looping sebanyak data pada table KNJ_DOS */
            for($x=0;$x<sizeof($kddos);$x++){
              /* Periksa apakah nilai variable x nilainya lebih kecil daripada
                 banyak data pada table KNJ_DOS yang indexnya dikurangi 1 */

              if($x<sizeof($kddos)-1){
                if(($kddos[$x]!=$kddos[$x+1])||($kdmk[$x]!=$kdmk[$x+1])){
                  $jml+=$rat[$x];
                  $count++;
                  $rata=$jml/$count;
                  $jml=0;
                  $count=0;
                }
                else {
                  $jml+=$rat[$x];
                  $count++;
                }
              }
              else{$jml+=$rat[$x];
                $count++;
                $rata=$jml/$count;
                $jml=0;
                $count=0;
              }

            for($x=0;$x<sizeof($jawaban);$x++){
              $index=0;
              $skor=0;
              for($y=0;$y<strlen($jawaban[$x]);$y++){
                if($y==0)
                  $index+=$jawaban[$x][$y]-1;
                else{
                  if($jawaban[$x][$y]==$jawaban[$x][$y-1])
                    $index+=4;
                  else{
                    $index+=($jawaban[$x][$y]+4)-$jawaban[$x][$y-1];
                  }
                }
                $skor+=$kunci[0][$index];
              }

              $jml_soal=strlen($jawaban[$x]);
              $ratio=($skor/($jml_soal*4))*100;

              /* Perhitungan Grade */
              if($ratio>=75)$grade='A';
              elseif($ratio>=60)$grade='B';
              elseif($ratio>=45)$grade='C';
              elseif($ratio<45)$grade='D';

              mysqli_query($koneksi,"UPDATE KNJ_DOS SET JML_SOAL='$jml_soal',SKORE='$skor',RASIO='$ratio',GRADE='$grade' WHERE JAWAB='$jawaban[$x]'");

              echo "<tr>"."<td>". $kddos[$x] . "</td>". "<td>". $kdmk[$x]. "</td>". "<td>" . $jawaban[$x] . "</td>" . "<td>" . $skor . "</td>" . "<td>" . $ratio . "</td>" . "<td>" . $grade . "</td>" . "</tr>";
            }
          }
            ?>
          </tbody>
        </table>

<div class="panel panel-default">
  <div class="panel-heading">Statistik</div>
    <div class="panel-body">
        <div class="panel panel-success">
          <div class="panel-heading">
            <h3 class="panel-title">Grade A</h3>
          </div>
          <div class="panel-body">
            <div class="progress">
              <?php
                  $jumlah = mysqli_query($koneksi,"SELECT count(*) as total from KNJ_DOS where GRADE='A'");
                  $data = mysqli_fetch_assoc($jumlah);
              ?>
              <div class="progress-bar progress-bar-success" style="width: <?php echo $data['total'] ?>%"></div>
            </div>
          </div>
        </div>

        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Grade B</h3>
          </div>
          <div class="panel-body">
            <div class="progress">
              <?php
                  $jumlah = mysqli_query($koneksi,"SELECT count(*) as total from KNJ_DOS where GRADE='B'");
                  $data = mysqli_fetch_assoc($jumlah);
              ?>
              <div class="progress-bar progress-bar" style="width: <?php echo $data['total'] ?>%"></div>
            </div>
          </div>
        </div>

        <div class="panel panel-warning">
          <div class="panel-heading">
            <h3 class="panel-title">Grade C</h3>
          </div>
          <div class="panel-body">
            <div class="progress">
              <?php
                  $jumlah = mysqli_query($koneksi,"SELECT count(*) as total from KNJ_DOS where GRADE='C'");
                  $data = mysqli_fetch_assoc($jumlah);
              ?>
              <div class="progress-bar progress-bar-warning" style="width: <?php echo $data['total'] ?>%"></div>
            </div>
          </div>
        </div>

        <div class="panel panel-danger">
          <div class="panel-heading">
            <h3 class="panel-title">Grade D</h3>
          </div>
          <div class="panel-body">
            <div class="progress">
              <?php
                  $jumlah = mysqli_query($koneksi,"SELECT count(*) as total from KNJ_DOS where GRADE='D'");
                  $data = mysqli_fetch_assoc($jumlah);
              ?>
              <div class="progress-bar progress-bar-danger" style="width: <?php echo $data['total'] ?>%"></div>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>

    <script src="asset/js/jquery-2.1.4.min.js" charset="utf-8"></script>
    </body>
</html>
