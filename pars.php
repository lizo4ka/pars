//функция для перекодировки файла
        function coding($file){
            $file = mb_convert_encoding($file, "UTF-8", "CP866");
            //echo $file;
            return $file;
        }
        
//функция для разбиения строк
        function pars($file, $soli_numb, $codik, $classik){

            $lines = explode("\n", coding ($file)); //разбиваем файл по строкам

            foreach ($lines as $line) {
                if ($line != NULL){
                    $notspace = trim($line); //обрезаем пробелы
                    //echo '<pre>'.$notspace.'</pre>';
                    $cod = mb_substr($notspace, 0, 5, "UTF-8");
                    //echo '<pre>'.$cod.'</pre>';
                    $name = mb_substr($notspace, 13, 50, "UTF-8");
                    //echo '<pre>'.$name.'</pre>';
                    //'<br>';

                    
                    //для случая, когда у нас название с точкой
                    $pos = strripos($name, '.');

                    if ($pos == true) {
                        $name = trim($name, ".");
                        //запрос в базу данных
                        $content = NsiContent::find()->where(['soli_id' => $soli_numb])->andWhere(['like', 'attr_textval', $name])->one();
                    }
                        else {
                        //запрос в базу данных
                        $content = NsiContent::find()->where(['soli_id' => $soli_numb])->andWhere(['or', ['attr_textval' => $name], ['like', 'attr_textval', $name.' (']])->one();

                    }

                    if ($content != NULL){
                        //добавляем в базу данных классификатор и кодификатор
                        $content->$codik = $cod;
                        $content->$classik = $cod;
                        $content->save();
                    }
                    else echo $name.'&nbsp'.$cod.'<br>';
                }
            }
        }
        
        

//Пример вызова функции
        $file10200069 = file_get_contents('/web/docs/nsi/10200069.000');

        pars ($file10200069, 10200069, "cod_04", "class_04");
        