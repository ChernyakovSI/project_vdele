<?php


namespace common\models\goal;

use yii\db\ActiveRecord;
use yii\db\Query;
use common\models\goal\DiaryData;

class DiaryRecord extends ActiveRecord
{

    public static function tableName()
    {
        return '{{diary_record}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'id_user', 'id_diary', 'date'], 'integer'],
            [['text'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'updated_at' => 'Дата изменения',
            'id_user' => 'Пользователь',
            'id_diary' => 'Дневник',
            'text' => 'Содержание',
            'date' => 'Дата',
        );
    }

    public static function getDiaryRecordById($id){
        return self::find()->where(['id' => $id])->one();
    }

    public static function addRecord($params, $id_user, $fields) {
        $newRec = new DiaryRecord();

        $newRec->created_at = time();
        $newRec->id_user = $id_user;
        $newRec->updated_at = time();

        $newRec->text = strip_tags($params['text']);

        $newRec->id_diary = (integer)$params['id_diary'];
        $newRec->date = (integer)$params['date'];

        $newRec->save();

        $rec = [];
        if($newRec->id > 0) {
            foreach ($fields as $field) {
                $rec = DiaryData::renewRecord($field, $newRec->id, $id_user);
            }
        }

        return $newRec;
    }

    public static function editRecord($params, $fields) {
        $rec = SELF::getDiaryRecordById((integer)$params['id']);

        $rec->updated_at = time();

        $rec->text = strip_tags($params['text']);

        $rec->id_diary = (integer)$params['id_diary'];
        $rec->date = (integer)$params['date'];

        $rec->save();

        $recData = [];
        if($rec->id > 0) {
            foreach ($fields as $field) {
                $recData = DiaryData::renewRecord($field, $rec->id, $rec->id_user);
            }
        }

        return $recData;
    }

    public static function deleteRecord($params) {
        $rec = SELF::getDiaryRecordById((integer)$params['id']);

        $rec->updated_at = time();

        $rec->is_deleted = 1;

        $rec->save();

        return $rec;
    }

    public static function getRecordsForDiary($id_diary, $showFirst, $option) {
        if(isset($option['dateFrom'])) {
            $dateFrom = (integer)$option['dateFrom'];
        } else {
            $dateFrom = -1;
        }
        if(isset($option['dateTo'])) {
            $dateTo = (integer)$option['dateTo'];
        } else {
            $dateTo = -1;
        }
        //++ 1-3-1-005 28/04/2023
        if(isset($option['minElem'])) {
            $minElem = (integer)$option['minElem'];
        } else {
            $minElem = 0;
        }
        //-- 1-3-1-005 28/04/2023

        //Основные значения записей
        $query = new Query();
        $body = $query->Select(['diary_record.`id` as id',
            'diary_record.`updated_at` as updated_at',
            'diary_record.`id_diary` as id_diary',
            'diary_record.`date` as date',
            'diary_record.`text` as text',
        ])
            ->from('diary_record as diary_record');

        $strWhere = ' diary_record.`id_diary` = '.(integer)$id_diary;
        $strWhere = $strWhere.' AND diary_record.`is_deleted` = 0';
        if($dateFrom > -1) {
            $strWhere = $strWhere.' AND diary_record.`date` >= '.$dateFrom;
        }
        if($dateTo > -1) {
            $strWhere = $strWhere.' AND diary_record.`date` <= '.$dateTo;
        }

        $body = $body
            ->where($strWhere)
            ->orderBy('diary_record.`date` DESC');


        $records = $body->all();

        //++ 1-3-1-005 28/04/2023
        if($minElem == 1) {
            $query = new Query();
            $body = $query->Select(['diary_record.`id` as id',
                'diary_record.`updated_at` as updated_at',
                'diary_record.`id_diary` as id_diary',
                'diary_record.`date` as date',
                'diary_record.`text` as text',
            ])
                ->from('diary_record as diary_record');

            $strWhere = ' diary_record.`id_diary` = '.(integer)$id_diary;
            $strWhere = $strWhere.' AND diary_record.`is_deleted` = 0';

            $body = $body
                ->where($strWhere)
                ->limit(30)
                ->orderBy('diary_record.`date` DESC');


            $records2 = $body->all();

            if(count($records2) > count($records)) {
                $records = $records2;

                $minDate = $dateFrom;
                foreach ($records as $record) {
                    if($record->date < $minDate) {
                        $minDate = $record->date;
                    }
                }
                if($minDate < $dateFrom) {
                    $dateFrom = $minDate;
                }
            }
        }
        //-- 1-3-1-005 28/04/2023

        //Данные пользовательских полей
        $query = new Query();
        $body = $query->Select(['diary_record.`id` as id',
            'diary_data.`id` as value_id',
            'diary_data.`value_str` as value_str',
            'diary_data.`value_int` as value_int',
            'diary_data.`value_boo` as value_boo',
            'diary_data.`value_txt` as value_txt',
            'diary_data.`value_dat` as value_dat',
            'diary_settings.`id` as param_id',
            'diary_settings.`title` as param_title',
            'diary_settings.`type` as param_type',
            'diary_settings.`is_show` as param_is_show',
            'diary_settings.`show_priority` as param_show_priority',
            'diary_settings.`is_active` as param_is_active',
        ])
            ->from('diary_record as diary_record')
            ->join('INNER JOIN', 'diary_data as diary_data', 'diary_data.`id_record` = diary_record.`id`')
            ->join('INNER JOIN', 'diary_settings as diary_settings', 'diary_data.`id_param` = diary_settings.`id`');

        $strWhere = ' diary_record.`id_diary` = '.(integer)$id_diary;
        $strWhere = $strWhere.' AND diary_record.`is_deleted` = 0';
        if($dateFrom > -1) {
            $strWhere = $strWhere.' AND diary_record.`date` >= '.$dateFrom;
        }
        if($dateTo > -1) {
            $strWhere = $strWhere.' AND diary_record.`date` <= '.$dateTo;
        }
        $strWhere = $strWhere.' AND diary_settings.`is_active` = 1';

        $body = $body
            ->where($strWhere)
            ->orderBy('diary_record.`date` DESC, diary_settings.`show_priority` DESC, diary_settings.`num`');


        $userFields = $body->all();

        //Колонки
        $query = new Query();
        $body = $query->Select(['diary_settings.`id` as param_id',
            'diary_settings.`title` as param_title',
            'diary_settings.`type` as param_type',
            'diary_settings.`is_show` as param_is_show',
            'diary_settings.`show_priority` as param_show_priority'
        ])->distinct()
            ->from('diary_record as diary_record')
            ->join('INNER JOIN', 'diary_data as diary_data', 'diary_data.`id_record` = diary_record.`id`')
            ->join('INNER JOIN', 'diary_settings as diary_settings', 'diary_data.`id_param` = diary_settings.`id`');

        $strWhere = ' diary_record.`id_diary` = '.(integer)$id_diary;
        $strWhere = $strWhere.' AND diary_record.`is_deleted` = 0';
        if($dateFrom > -1) {
            $strWhere = $strWhere.' AND diary_record.`date` >= '.$dateFrom;
        }
        if($dateTo > -1) {
            $strWhere = $strWhere.' AND diary_record.`date` <= '.$dateTo;
        }
        $strWhere = $strWhere.' AND diary_settings.`is_active` = 1';

        $body = $body
            ->where($strWhere)
            ->orderBy('diary_settings.`show_priority` DESC, diary_settings.`num`');


        $setFields = $body->all();

        $diaries = [];
        $diaries['records'] = $records;
        $diaries['userFields'] = $userFields;
        $diaries['setFields'] = $setFields;

        $data = [];

        $usedFields = 0;
        //++ 1-3-1-005 28/04/2023
        $i = 0;
        //-- 1-3-1-005 28/04/2023
        foreach ($setFields as $field) {
            //++ 1-3-1-005 28/04/2023
            //if($usedFields >= 5) {
            //    break;
            //}
            //-- 1-3-1-005 28/04/2023

            if($field['param_is_show'] == '1') {
                $newRec = [];
                $newRec['param_id'] = (integer)$field['param_id'];
                $newRec['param_title'] = $field['param_title'];
                $newRec['param_type'] = (integer)$field['param_type'];

                //++ 1-3-1-005 28/04/2023
                $data[$i] = $newRec;
                //-- 1-3-1-005 28/04/2023

                //++ 1-3-1-005 28/04/2023
                if($usedFields < 5) {
                //-- 1-3-1-005 28/04/2023
                    $usedFields = $usedFields + 1;
                //++ 1-3-1-005 28/04/2023
                }
                $i = $i + 1;
                //-- 1-3-1-005 28/04/2023
            }
        }
        $i = 0;
        foreach ($data as $field) {
            //++ 1-3-1-005 28/04/2023
            if($i < 5) {
            //-- 1-3-1-005 28/04/2023
                $field['width'] = 90/$usedFields;
                if($field['width'] == 18) {
                    $field['widthClass'] = 'column-18';
                } elseif ($field['width'] == 22.5) {
                    $field['widthClass'] = 'column-22';
                } elseif ($field['width'] == 30) {
                    $field['widthClass'] = 'column-30';
                } elseif ($field['width'] == 45) {
                    $field['widthClass'] = 'column-45';
                } elseif ($field['width'] == 90) {
                    $field['widthClass'] = 'column-90';
                }
            //++ 1-3-1-005 28/04/2023
            }
            //-- 1-3-1-005 28/04/2023
            $data[$i] = $field;
            $i = $i + 1;
        }

        $diaries['dataTable'] = $data;

        $sumRec = [];
        $quantity = 0;

        $data = [];
        $newRec = [];
        $thisIdRec = 0;
        $isSave = false;
        foreach ($userFields as $field) {
            if ($thisIdRec == 0) {
                $thisIdRec = $field['id'];
                $quantity = $quantity + 1;
            }
            if ($thisIdRec != $field['id']) {
                $data[$thisIdRec] = $newRec;
                $thisIdRec = $field['id'];
                $newRec = [];
                $isSave = false;
                $quantity = $quantity + 1;
            }

            foreach ($diaries['dataTable'] as $column) {
                if((integer)$field['param_id'] == (integer)$column['param_id']) {
                    if((integer)$column['param_type'] == 1) {
                        $newRec[(integer)$field['param_id']] = mb_substr($field['value_str'], 0, 20, 'UTF-8').($field['value_str'] != mb_substr($field['value_str'], 0, 20, 'UTF-8')?'...':'');
                        $isSave = true;
                    } elseif ((integer)$column['param_type'] == 2) {
                        //integer
                        $newRec[(integer)$field['param_id']] = rtrim(rtrim($field['value_int'], '0'), '.');
                        $isSave = true;
                        if($sumRec[(integer)$field['param_id']] == null) {
                            $sumRec[(integer)$field['param_id']] = [];
                            $sumRec[(integer)$field['param_id']]['min'] = 0;
                            $sumRec[(integer)$field['param_id']]['max'] = 0;
                            $sumRec[(integer)$field['param_id']]['average'] = 0;
                            $sumRec[(integer)$field['param_id']]['sum'] = 0;

                            $sumRec[(integer)$field['param_id']]['min_val'] = 0;
                            $sumRec[(integer)$field['param_id']]['max_val'] = 0;
                            $sumRec[(integer)$field['param_id']]['average_val'] = 0;
                            $sumRec[(integer)$field['param_id']]['sum_val'] = 0;

                            $sumRec[(integer)$field['param_id']]['accuracy'] = 0;
                        }
                        if($quantity == 1){
                            $sumRec[(integer)$field['param_id']]['min_val'] = $field['value_int'];
                            $sumRec[(integer)$field['param_id']]['min'] = rtrim(rtrim($field['value_int'], '0'), '.');
                        } else {
                            if ($sumRec[(integer)$field['param_id']]['min_val'] > $field['value_int']) {
                                $sumRec[(integer)$field['param_id']]['min_val'] = $field['value_int'];
                                $sumRec[(integer)$field['param_id']]['min'] = rtrim(rtrim($field['value_int'], '0'), '.');
                            }
                        }
                        if($sumRec[(integer)$field['param_id']]['max_val'] < $field['value_int']) {
                            $sumRec[(integer)$field['param_id']]['max_val'] = $field['value_int'];
                            $sumRec[(integer)$field['param_id']]['max'] = rtrim(rtrim($field['value_int'], '0'), '.');
                        }
                        $sumRec[(integer)$field['param_id']]['sum_val'] = $sumRec[(integer)$field['param_id']]['sum_val'] + $field['value_int'];

                        $thisStr = rtrim(rtrim($field['value_int'], '0'), '.');
                        $arrParts = explode('.', $thisStr);
                        if(count($arrParts) > 1 && $sumRec[(integer)$field['param_id']]['accuracy'] < strlen($arrParts[1])) {
                            $sumRec[(integer)$field['param_id']]['accuracy'] = strlen($arrParts[1]);
                        }
                    } elseif ((integer)$column['param_type'] == 3) {
                        //boolean
                        $newRec[(integer)$field['param_id']] = (integer)$field['value_boo'];
                        $isSave = true;
                        if((integer)$field['value_boo'] == 1){
                            if($sumRec[(integer)$field['param_id']] == null) {
                                $sumRec[(integer)$field['param_id']] = 0;
                            }
                            $sumRec[(integer)$field['param_id']] = $sumRec[(integer)$field['param_id']] + 1;
                        }
                    } elseif ((integer)$column['param_type'] == 4) {
                        $newRec[(integer)$field['param_id']] = mb_substr($field['value_txt'], 0, 20, 'UTF-8').($field['value_txt'] != mb_substr($field['value_txt'], 0, 20, 'UTF-8')?'...':'');
                        $isSave = true;
                    } elseif ((integer)$column['param_type'] == 5) {
                        //time
                        $newRec[(integer)$field['param_id']] = (integer)$field['value_dat'];
                        $isSave = true;
                        if($sumRec[(integer)$field['param_id']] == null) {
                            $sumRec[(integer)$field['param_id']] = [];
                            $sumRec[(integer)$field['param_id']]['min_val'] = 0;
                            $sumRec[(integer)$field['param_id']]['max_val'] = 0;
                            $sumRec[(integer)$field['param_id']]['average_val'] = 0;
                            $sumRec[(integer)$field['param_id']]['sum_val'] = 0;
                        }

                        if($quantity == 1){
                            $sumRec[(integer)$field['param_id']]['min_val'] = $field['value_dat'];
                        } else {
                            if($sumRec[(integer)$field['param_id']]['min_val'] > $field['value_dat']) {
                                $sumRec[(integer)$field['param_id']]['min_val'] = $field['value_dat'];
                            }
                        }

                        if($sumRec[(integer)$field['param_id']]['max_val'] < $field['value_dat']) {
                            $sumRec[(integer)$field['param_id']]['max_val'] = $field['value_dat'];
                        }
                        $sumRec[(integer)$field['param_id']]['sum_val'] = $sumRec[(integer)$field['param_id']]['sum_val'] + $field['value_dat'];
                    }

                    break;
                }
            }

        }

        if($isSave == true) {
            $data[$thisIdRec] = $newRec;
        }

        //Итоги
        foreach ($diaries['dataTable'] as $column) {
            if ((integer)$column['param_type'] == 2) {
                $sumRec[(integer)$column['param_id']]['sum'] = rtrim(rtrim($sumRec[(integer)$column['param_id']]['sum_val'], '0'), '.');
                $sumRec[(integer)$column['param_id']]['average_val'] = $sumRec[(integer)$column['param_id']]['sum_val'] / $quantity;
                $sumRec[(integer)$column['param_id']]['average'] = rtrim(rtrim($sumRec[(integer)$column['param_id']]['average_val'], '0'), '.');
                if($sumRec[(integer)$column['param_id']]['accuracy'] <= 1) {
                    $arrParts = explode('.', $sumRec[(integer)$column['param_id']]['average']);
                    if(count($arrParts) > 1 && strlen($arrParts[1]) > 1) {
                        $sumRec[(integer)$column['param_id']]['average'] = $arrParts[0] . '.' . mb_substr($arrParts[1], 0, 1, 'UTF-8');
                    }
                } else {
                    $arrParts = explode('.', $sumRec[(integer)$column['param_id']]['average']);
                    if(count($arrParts) > 1 && strlen($arrParts[1]) > 1) {
                        $sumRec[(integer)$column['param_id']]['average'] = $arrParts[0] . '.' . mb_substr($arrParts[1], 0, $sumRec[(integer)$column['param_id']]['accuracy'], 'UTF-8');
                    }
                }
            } else if((integer)$column['param_type'] == 5) {
                $sumRec[(integer)$column['param_id']]['average_val'] = (integer)($sumRec[(integer)$column['param_id']]['sum_val'] / $quantity);
            }
        }
        $data[0] = $sumRec;


        $diaries['dataRecords'] = $data;

        return $diaries;
    }

}