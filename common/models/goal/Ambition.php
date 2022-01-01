<?php


namespace common\models\goal;

use yii\db\ActiveRecord;
use yii\db\Query;
use DateTime;

class Ambition extends ActiveRecord
{
    public static function tableName()
    {
        return '{{ambition}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'date', 'id_user', 'id_sphere', 'num', 'id_level', 'status', 'dateDone'], 'integer'],
            [['title', 'text'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'date' => 'Дата',
            'id_user' => 'Пользователь',
            'id_sphere' => 'Сфера',
            'num' => 'Номер',
            'id_level' => 'Тип',
            'status' => 'Статус',
            'dateDone' => 'Дата достижения',
            'title' => 'Заголовок',
            'text' => 'Содержание',
        );
    }

    public static function getDreamByUserAndNum($id_user, $num){
        return self::find()->where(['id_user' => $id_user, 'num' => $num])->one();
    }

    public static function getDreamById($id){
        return self::find()->where(['id' => $id])->one();
    }

    public static function getDreamsForPeriodAndUser($id_user, $startDate = 0, $finishDate = 0, $option = []) {
        $query = new Query();
        $body = $query->Select(['amb.`id` as id',
            'amb.`created_at` as created_at',
            'amb.`date` as date',
            'amb.`id_sphere` as id_sphere',
            'amb.`title` as title',
            'amb.`text` as text',
            'amb.`num` as num',
            'amb.`status` as status',
            'amb.`dateDone` as dateDone',
            'amb.`id_level` as id_level',
        ])
            ->from(self::tableName().' as amb');

        $strWhere = 'amb.`id_user`= '.(integer)$id_user;
        if($startDate > 0) {
            $strWhere = $strWhere.' AND ((amb.`id_level` <> 4 AND amb.`created_at` >= '.(integer)$startDate.') OR '.
                '(amb.`id_level` = 4 AND amb.`date` >= '.(integer)$startDate.'))';
        }
        if($finishDate > 0 && $finishDate > $startDate) {
            $strWhere = $strWhere.' AND ((amb.`id_level` <> 4 AND amb.`created_at` <= '.(integer)$finishDate.') OR '.
                '(amb.`id_level` = 4 AND amb.`date` <= '.(integer)$finishDate.'))';;
        }
        $strWhere = $strWhere.' AND amb.`is_deleted` = 0 ';
        //$strWhere = $strWhere.' AND amb.`id_level` = 1';

        if(isset($option['id_sphere']) === true && $option['id_sphere'] != 0) {
            $strWhere = $strWhere.' AND amb.`id_sphere` = '.(integer)$option['id_sphere'];
        }

        if(isset($option['status']) === true && count($option['status']) > 0) {
            if(count($option['status']) == 0) {
                $strWhere = $strWhere.' AND amb.`status` = '.(integer)$option['status'][0];
            } else {
                $strOr = '';
                $strDiv = '';
                foreach ($option['status'] as $status) {
                    $strOr = $strOr.$strDiv.'amb.`status` = '.(integer)$status;
                    $strDiv = ' OR ';
                }
                $strWhere = $strWhere.' AND ('.$strOr.')';
            }

        }

        if(isset($option['level']) === false) {
            $level = 0;
        } else {
            $level = $option['level'];
        }

        if($level > 0) {
            $strWhere = $strWhere.' AND amb.`id_level` = '.(integer)$level;
        }

        if($level == 4) {
            $body = $body->where($strWhere)->orderBy('amb.`date`');
        }
        else {
            $body = $body->where($strWhere)->orderBy('amb.`created_at`');
        }

        return $body->all();
    }

    public static function getLevels() {
        $query = new Query();
        $body = $query->Select(['amb.`id` as id',
            'amb.`name` as name',
        ])
            ->from('ambition_level as amb');

        $body = $body->orderBy('amb.`id`');

        return $body->all();
    }

    public static function addRecord($params, $id_user) {
        $newRec = new Ambition();

        $newRec->created_at = time();
        $newRec->id_user = $id_user;
        $newRec->updated_at = time();

        /* Дэдлайн */
        if(isset($params['dateGoal'])) {
            $newRec->date = (integer)$params['dateGoal'];
        }
        else{
            $newRec->date = time();
        };

        $newRec->id_sphere = (integer)$params['id_sphere'];
        $newRec->id_level = (integer)$params['id_level'];
        $newRec->status = (integer)$params['status'];

        $newRec->result_type = (integer)$params['resultType'];
        $newRec->result_mark = (integer)$params['resultMark'];
        $newRec->result_text = strip_tags($params['resultText']);

        $newRec->title = strip_tags($params['title']);
        $newRec->text = strip_tags($params['text']);

        $newRec->num = self::getNumByUser($id_user);

        if(isset($params['dateDone'])) {
            $newRec->dateDone = (integer)$params['dateDone'];
        };

        $newRec->save();

        return $newRec;
    }

    public static function editRecord($params) {
        $rec = self::getDreamById((integer)$params['id']);

        $rec->updated_at = time();

        /* Дэдлайн */
        if(isset($params['dateGoal'])) {
            $rec->date = (integer)$params['dateGoal'];
        }
        else{
            $rec->date = time();
        };

        if(isset($params['dateDone'])) {
            $rec->dateDone = (integer)$params['dateDone'];
        };

        $rec->id_sphere = (integer)$params['id_sphere'];
        $rec->id_level = (integer)$params['id_level'];
        $rec->status = (integer)$params['status'];

        $rec->result_type = (integer)$params['resultType'];
        $rec->result_mark = (integer)$params['resultMark'];
        $rec->result_text = strip_tags($params['resultText']);

        $rec->title = strip_tags($params['title']);
        $rec->text = strip_tags($params['text']);

        $rec->save();

        return $rec;
    }

    public static function getNumByUser($id_user){
        $notes =  self::find()->select('num')
            ->where(['id_user' => $id_user])
            ->orderBy('num DESC')->all();

        if (count($notes) > 0){
            $lastNum = $notes[0]['num'] + 1;
        }
        else{
            $lastNum = 1;
        }

        return $lastNum;
    }

    public static function getColorForDateline($date, $done = 0, $level = 0){
        if($level <> 4) {
            return '';
        }

        $color = '';
        $nowStamp = (new DateTime())->getTimestamp();

        if ($done == 1) {
            $color = 'deadline-done';
        } else if($nowStamp > $date){
            $color = 'deadline-fail';
        } else if (($nowStamp + 24*60*60) > $date) {
            $color = 'deadline-today';
        } else if (($nowStamp + 2*24*60*60) > $date) {
            $color = 'deadline-1';
        } else if (($nowStamp + 4*24*60*60) > $date) {
            $color = 'deadline-3';
        } else if (($nowStamp + 8*24*60*60) > $date) {
            $color = 'deadline-7';
        } else if (($nowStamp + 16*24*60*60) > $date) {
            $color = 'deadline-15';
        } else if (($nowStamp + 31*24*60*60) > $date) {
            $color = 'deadline-30';
        } else if (($nowStamp + 61*24*60*60) > $date) {
            $color = 'deadline-60';
        } else if (($nowStamp + 101*24*60*60) > $date) {
            $color = 'deadline-100';
        }

        return $color;
    }

    public static function getPriorityForDayAndUser($id_user, $curDate = 0, $option = []) {
        $result['zachet'] = [];
        $result['exam'] = [];
        $result['sem'] = [];
        $result['option'] = Semester::getBordersSemestersOfUser($id_user);

        if($result['option']['finish'] > time()) {
            $result['sem']['date'] = $result['option']['finish']+24*60*60;
            $result['sem']['dateFinish'] = $result['option']['finish']+24*60*60;
        } else {
            $result['sem']['date'] = time();
            $result['sem']['dateFinish'] = time();
        }
        $result['sem']['name'] = '';
        $result['sem']['id'] = 0;

        $query = new Query();
        $body = $query->Select(['sem.`id` as id',
            'sem.`created_at` as created_at',
            'sem.`date` as date',
            'sem.`name` as name',
            'sem.`dateFinish` as dateFinish',
        ])
            ->from('semester as sem');

        $strWhere = 'sem.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND (sem.`date` <= '.(integer)$curDate.' AND sem.`dateFinish` >= '.(integer)$curDate.')';
        $strWhere = $strWhere.' AND sem.`is_deleted` = 0 ';
        $body = $body->where($strWhere)->orderBy('sem.`date`');

        if($body->count() > 0) {
            foreach ($body->each() as $rec) {
                $startDate = $rec['date'];
                $finishDate = $rec['dateFinish'];

                $sem['date'] = $startDate;
                $sem['dateFinish'] = $finishDate;
                $sem['name'] = $rec['name'];
                $sem['id'] = $rec['id'];
            }

        } else {
            return $result;
        }

        $result['sem'] = $sem;

        $body = $query->Select(['amb.`id` as id',
            'amb.`created_at` as created_at',
            'amb.`date` as date',
            'amb.`id_sphere` as id_sphere',
            'amb.`title` as title',
            'amb.`text` as text',
            'amb.`num` as num',
            'amb.`status` as status',
            'amb.`dateDone` as dateDone',
            'amb.`id_level` as id_level',
            'amb.`result_type` as result_type',
            'amb.`result_mark` as result_mark',
            'amb.`result_text` as result_text',
        ])
            ->from(self::tableName().' as amb');

        $strWhere = 'amb.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND (amb.`date` >= '.(integer)$startDate.' AND amb.`date` <= '.(integer)$finishDate.')';
        $strWhere = $strWhere.' AND amb.`is_deleted` = 0 ';
        $strWhere = $strWhere.' AND amb.`result_type` = 1';

        if(isset($option['level']) === false) {
            $level = 0;
        } else {
            $level = $option['level'];
        }

        if($level > 0) {
            $strWhere = $strWhere.' AND amb.`id_level` = '.(integer)$level;
        }

        if($level == 4) {
            $body = $body->where($strWhere)->orderBy('amb.`date`');
        }
        else {
            $body = $body->where($strWhere)->orderBy('amb.`created_at`');
        }

        $result['zachet'] = $body->all();


        $body = $query->Select(['amb.`id` as id',
            'amb.`created_at` as created_at',
            'amb.`date` as date',
            'amb.`id_sphere` as id_sphere',
            'amb.`title` as title',
            'amb.`text` as text',
            'amb.`num` as num',
            'amb.`status` as status',
            'amb.`dateDone` as dateDone',
            'amb.`id_level` as id_level',
            'amb.`result_type` as result_type',
            'amb.`result_mark` as result_mark',
            'amb.`result_text` as result_text',
        ])
            ->from(self::tableName().' as amb');

        $strWhere = 'amb.`id_user`= '.(integer)$id_user;
        $strWhere = $strWhere.' AND (amb.`date` >= '.(integer)$startDate.' AND amb.`date` <= '.(integer)$finishDate.')';
        $strWhere = $strWhere.' AND amb.`is_deleted` = 0 ';
        $strWhere = $strWhere.' AND amb.`result_type` = 2';

        if(isset($option['level']) === false) {
            $level = 0;
        } else {
            $level = $option['level'];
        }

        if($level > 0) {
            $strWhere = $strWhere.' AND amb.`id_level` = '.(integer)$level;
        }

        if($level == 4) {
            $body = $body->where($strWhere)->orderBy('amb.`date`');
        }
        else {
            $body = $body->where($strWhere)->orderBy('amb.`created_at`');
        }

        $result['exam'] = $body->all();

        return $result;
    }

    public static function getNameMark($mark, $isExam = true) {
        $name = '';

        $mark = (integer)$mark;

        if($isExam == true) {
            if ($mark == 5) {
                $name = 'ОТЛ';
            } else if ($mark == 4) {
                $name = 'ХОР';
            } else if ($mark == 3) {
                $name = 'УДОВЛ';
            } else if ($mark == 2) {
                $name = 'НЕУДОВЛ';
            } else if ($mark == 1) {
                $name = 'ОТВРАТ';
            }
        } else {
            if ($mark >= 1) {
                $name = 'ЗАЧЕТ';
            } else {
                $name = 'НЕЗАЧЕТ';
            }
        }



        return $name;
    }
}