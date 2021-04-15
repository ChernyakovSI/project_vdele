<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 20.01.2021
 * Time: 9:43
 */

namespace common\models\fin;

use yii\db\Query;

class Reports
{
    public static function getTotalByExpenceCatsByUser($id_user, $beginDate = 0, $endDate = 0, $option = []){
        $query = new Query();
        $body = $query->Select('Reg.`id_category` as id_category,
                                            Cat.`name` as CatName,
                                            SUM(Reg.`sum`) as sum
                                            ')
            ->from('fin_register as Reg')

            ->join('LEFT JOIN', Category::tableName().' as Cat', 'Cat.`id` = Reg.`id_category`')

            ->where(['Reg.`id_user`' => $id_user, 'Reg.`is_deleted`' => 0]);

        $body = $body->andWhere('Reg.`id_type` = 0');

        if ($beginDate > 0 || $endDate > 0){
            if ($beginDate > $endDate){
                $temp = $beginDate;
                $beginDate = $endDate;
                $endDate = $temp;
            }
            $body = $body->andWhere('Reg.`date` >= '.$beginDate)
                ->andWhere('Reg.`date` <= '.$endDate);
        }

        $body = $body->groupBy(['id_category', 'CatName']);

        $strOrder = '';
        $separator = '';
        if(isset($option['sortCat'])) {
            if($option['sortCat'] === 1) {
                $strOrder = $strOrder.$separator.'CatName';
                $separator = ', ';
            }
            if($option['sortCat'] === -1) {
                $strOrder = $strOrder.$separator.'CatName DESC';
                $separator = ', ';
            }
        }
        if(isset($option['sortAmo'])) {
            if($option['sortAmo'] === 1) {
                $strOrder = $strOrder.$separator.'sum';
                $separator = ', ';
            }
            if($option['sortAmo'] === -1) {
                $strOrder = $strOrder.$separator.'sum DESC';
                $separator = ', ';
            }
        }
        if($strOrder === '') {
            $result = $body->orderBy('CatName')->all();
        }
        else {
            $result = $body->orderBy($strOrder)->all();
        }

        return $result;
    }

    public static function getTotalByProfitCatsByUser($id_user, $beginDate = 0, $endDate = 0, $option = []){
        $query = new Query();
        $body = $query->Select('Reg.`id_category` as id_category,
                                            Cat.`name` as CatName,
                                            SUM(Reg.`sum`) as sum
                                            ')
            ->from('fin_register as Reg')

            ->join('LEFT JOIN', Category::tableName().' as Cat', 'Cat.`id` = Reg.`id_category`')

            ->where(['Reg.`id_user`' => $id_user, 'Reg.`is_deleted`' => 0]);

        $body = $body->andWhere('Reg.`id_type` = 1');

        if ($beginDate > 0 || $endDate > 0){
            if ($beginDate > $endDate){
                $temp = $beginDate;
                $beginDate = $endDate;
                $endDate = $temp;
            }
            $body = $body->andWhere('Reg.`date` >= '.$beginDate)
                ->andWhere('Reg.`date` <= '.$endDate);
        }

        $body = $body->groupBy(['id_category', 'CatName']);

        //$result = $body->orderBy('CatName')->all();
        $strOrder = '';
        $separator = '';
        if(isset($option['sortCat'])) {
            if($option['sortCat'] === 1) {
                $strOrder = $strOrder.$separator.'CatName';
                $separator = ', ';
            }
            if($option['sortCat'] === -1) {
                $strOrder = $strOrder.$separator.'CatName DESC';
                $separator = ', ';
            }
        }
        if(isset($option['sortAmo'])) {
            if($option['sortAmo'] === 1) {
                $strOrder = $strOrder.$separator.'sum';
                $separator = ', ';
            }
            if($option['sortAmo'] === -1) {
                $strOrder = $strOrder.$separator.'sum DESC';
                $separator = ', ';
            }
        }
        if($strOrder === '') {
            $result = $body->orderBy('CatName')->all();
        }
        else {
            $result = $body->orderBy($strOrder)->all();
        }

        return $result;
    }

    public static function getTotalByExpenceSubsByUser($id_user, $beginDate = 0, $endDate = 0, $option = []){
        $query = new Query();
        $body = $query->Select('Reg.`id_category` as id_category,
                                            Cat.`name` as CatName,
                                            Reg.`id_subcategory` as id_subcategory,
                                            Sub.`name` as SubName,
                                            SUM(Reg.`sum`) as sum
                                            ')
            ->from('fin_register as Reg')

            ->join('LEFT JOIN', Category::tableName().' as Cat', 'Cat.`id` = Reg.`id_category`')
            ->join('LEFT JOIN', Category::tableName().' as Sub', 'Sub.`id` = Reg.`id_subcategory`')

            ->where(['Reg.`id_user`' => $id_user, 'Reg.`is_deleted`' => 0]);

        $body = $body->andWhere('Reg.`id_type` = 0');

        if ($beginDate > 0 || $endDate > 0){
            if ($beginDate > $endDate){
                $temp = $beginDate;
                $beginDate = $endDate;
                $endDate = $temp;
            }
            $body = $body->andWhere('Reg.`date` >= '.$beginDate)
                ->andWhere('Reg.`date` <= '.$endDate);
        }

        $body = $body->groupBy(['id_category', 'CatName', 'id_subcategory', 'SubName']);


        //$result = $body->orderBy('Cat.`name`, Sub.`name`')->all();
        $strOrder = '';
        $separator = '';
        if(isset($option['sortCat'])) {
            if($option['sortCat'] === 1) {
                $strOrder = $strOrder.$separator.'CatName';
                $separator = ', ';
            }
            if($option['sortCat'] === -1) {
                $strOrder = $strOrder.$separator.'CatName DESC';
                $separator = ', ';
            }
        }
        if(isset($option['sortSub'])) {
            if($option['sortSub'] === 1) {
                $strOrder = $strOrder.$separator.'SubName';
                $separator = ', ';
            }
            if($option['sortSub'] === -1) {
                $strOrder = $strOrder.$separator.'SubName DESC';
                $separator = ', ';
            }
        }
        if(isset($option['sortAmo'])) {
            if($option['sortAmo'] === 1) {
                $strOrder = $strOrder.$separator.'sum';
                $separator = ', ';
            }
            if($option['sortAmo'] === -1) {
                $strOrder = $strOrder.$separator.'sum DESC';
                $separator = ', ';
            }
        }
        if($strOrder === '') {
            $result = $body->orderBy('Cat.`name`, Sub.`name`')->all();
        }
        else {
            $result = $body->orderBy($strOrder)->all();
        }

        return $result;
    }

    public static function getTotalByProfitSubsByUser($id_user, $beginDate = 0, $endDate = 0, $option = []){
        $query = new Query();
        $body = $query->Select('Reg.`id_category` as id_category,
                                            Cat.`name` as CatName,
                                            Reg.`id_subcategory` as id_subcategory,
                                            Sub.`name` as SubName,
                                            SUM(Reg.`sum`) as sum
                                            ')
            ->from('fin_register as Reg')

            ->join('LEFT JOIN', Category::tableName().' as Cat', 'Cat.`id` = Reg.`id_category`')
            ->join('LEFT JOIN', Category::tableName().' as Sub', 'Sub.`id` = Reg.`id_subcategory`')

            ->where(['Reg.`id_user`' => $id_user, 'Reg.`is_deleted`' => 0]);

        $body = $body->andWhere('Reg.`id_type` = 1');

        if ($beginDate > 0 || $endDate > 0){
            if ($beginDate > $endDate){
                $temp = $beginDate;
                $beginDate = $endDate;
                $endDate = $temp;
            }
            $body = $body->andWhere('Reg.`date` >= '.$beginDate)
                ->andWhere('Reg.`date` <= '.$endDate);
        }

        $body = $body->groupBy(['id_category', 'CatName', 'id_subcategory', 'SubName']);


        //$result = $body->orderBy('Cat.`name`, Sub.`name`')->all();
        $strOrder = '';
        $separator = '';
        if(isset($option['sortCat'])) {
            if($option['sortCat'] === 1) {
                $strOrder = $strOrder.$separator.'CatName';
                $separator = ', ';
            }
            if($option['sortCat'] === -1) {
                $strOrder = $strOrder.$separator.'CatName DESC';
                $separator = ', ';
            }
        }
        if(isset($option['sortSub'])) {
            if($option['sortSub'] === 1) {
                $strOrder = $strOrder.$separator.'SubName';
                $separator = ', ';
            }
            if($option['sortSub'] === -1) {
                $strOrder = $strOrder.$separator.'SubName DESC';
                $separator = ', ';
            }
        }
        if(isset($option['sortAmo'])) {
            if($option['sortAmo'] === 1) {
                $strOrder = $strOrder.$separator.'sum';
                $separator = ', ';
            }
            if($option['sortAmo'] === -1) {
                $strOrder = $strOrder.$separator.'sum DESC';
                $separator = ', ';
            }
        }
        if($strOrder === '') {
            $result = $body->orderBy('Cat.`name`, Sub.`name`')->all();
        }
        else {
            $result = $body->orderBy($strOrder)->all();
        }

        return $result;
    }

    public static function timestampToDateString($timestamp) {
        return date('d.m.Y', $timestamp);
    }
}