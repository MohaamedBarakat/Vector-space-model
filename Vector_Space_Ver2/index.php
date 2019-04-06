<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <form method="POST" >
        <input type="text" name='Query'/>
        <input type="submit" value='Search' name='Search'/>
        <br>
    </form>

    <body>
        <?php

        function calculation_Of_Tfi($Doc) {

            $query = strtolower($Doc);
            $arrofwordsplit = preg_split("/[ ]+/", $query);
            $arrofuniqueword1 = array_unique($arrofwordsplit);
            $Kkk = 0;
            foreach ($arrofuniqueword1 as $name) {

                $arrofuniqueword[$Kkk] = $name;
                $Kkk++;
            }

            for ($i = 0; $i < count($arrofuniqueword); $i++) {
                $f[$i][0] = $arrofuniqueword[$i];
                $f[$i][1] = 0;
                $Tf[$i][0] = $arrofuniqueword[$i];
                $Tf[$i][1] = 0;
                for ($j = 0; $j < count($arrofwordsplit); $j++) {
                    if ($f[$i][0] == $arrofwordsplit[$j]) {
                        $f[$i][1] ++;
                    }
                }
            }
            $max = $f[0][1];
            for ($i = 0; $i < count($arrofuniqueword) - 1; $i++) {
                if ($f[$i][1] < $f[$i + 1][1]) {
                    $max = $f[$i + 1][1];
                }
            }
            for ($i = 0; $i < count($arrofuniqueword); $i++) {
                $Tf[$i][1] = $f[$i][1] / $max;
            }
            return $Tf;
        }

        function Calculation_of_idf($arrofuniqueword) {
            $dir_path = "documentfiles";
            if (is_dir($dir_path)) {
                $files = scandir($dir_path);
                echo"<br>";
                $count = 0;
                for ($i = 0; $i < count($files); $i++) {
                    if ($files[$i] != '.' && $files[$i] != '..') {
                        $Q[$count] = $files[$i];
                        $count++;
                    }
                }
            }
            for ($i = 0; $i < count($arrofuniqueword); $i++) {
                $df[$i][0] = $arrofuniqueword[$i][0];
                $df[$i][1] = 0;
                $idf[$i][0] = $arrofuniqueword[$i][0];
                $idf[$i][1] = 0;
            }

            for ($k = 0; $k < count($Q); $k++) {
                $myfile = fopen($Q[$k], "r") or die("Unable to open file!");
                $D2 = fread($myfile, filesize($Q[$k]));
                $D1 = strtolower($D2);
                for ($i = 0; $i < count($arrofuniqueword); $i++) {
                    for ($j = 0; $j < strlen($D1); $j++) {
                        if ($df[$i][0] == $D1[$j]) {
                            $df[$i][1] ++;
                            break;
                        }
                    }
                }
            }

            $N = count($Q);
            for ($i = 0; $i < count($arrofuniqueword); $i++) {
                if ($df[$i][1] != 0) {
                    $idf[$i][1] = log(( ($N +1)/ (1+$df[$i][1]) ), 2);
                }
            }
            return $idf;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tfi_Of_query = calculation_Of_Tfi($_POST['Query']);
            $idf_of_query = Calculation_of_idf($tfi_Of_query);
            for ($i = 0; $i < count($tfi_Of_query); $i++) {
                $weight_of_query[$i][0] = $tfi_Of_query[$i][0];
                $weight_of_query[$i][1] = ($tfi_Of_query[$i][1]) * ($idf_of_query[$i][1]);
            }
            $dir_path = "documentfiles";
            if (is_dir($dir_path)) {
                $files = scandir($dir_path);
                echo"<br>";
                $count = 0;
                for ($i = 0; $i < count($files); $i++) {
                    if ($files[$i] != '.' && $files[$i] != '..') {
                        $Q[$count] = $files[$i];
                        $count++;
                    }
                }
            }
            for ($k = 0; $k < count($Q); $k++) {
                $myfile = fopen($Q[$k], "r") or die("Unable to open file!");
                $D2 = fread($myfile, filesize($Q[$k]));
                $D1 = strtolower($D2);
                $Doc[$k] = $D1;
                $weight_of_all_document[$k][0] = $D1;
                $tfi_Of_document[$k] = calculation_Of_Tfi($Doc[$k]);
$Score_of_document[$k]=0;
                for ($j = 0; $j < count($idf_of_query); $j++) {
                    $weight_of_every_document[$j][1] = 0;
                    $weight_of_every_document[$i][0] = $tfi_Of_document[$k][$j][0];
                    

                    for ($i = 0; $i < count($idf_of_query); $i++) {
                        if ($tfi_Of_document[$k][$i][0] == $idf_of_query[$i][0]) {
                            $weight_of_every_document[$j][1] = $tfi_Of_document[$k][$j][1] * $idf_of_query[$j][1];
                            
                            if($weight_of_query[0][0]==$weight_of_every_document[0][0]){
                            $Score_of_document[$k]+=$weight_of_query[$i][1]*$weight_of_every_document[$i][1];
                            
                            }
                            
                        }
                    }
                }
              
            }
        }
        ?>

    </body>
</html>
