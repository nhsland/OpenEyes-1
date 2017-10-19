<?php

/**
 * Class DBProvider
 */
class DBProvider extends SearchProvider
{
    /**
     * @param array $criteria The parameters to search with. The parameters must implement the DBProviderInterface interface.
     * @return array The returned data from the search.
     * @param CaseSearchParameter
     */
    protected function executeSearch($criteria)
    {
        $queryStr = 'SELECT DISTINCT p.id FROM patient p';
        $whereStr = ' WHERE true';
        $joins = array();
        $binds = array();

        // Construct the SQL search string using each parameter as a separate dataset merged using JOINs.
        foreach ($criteria as $id => $param) {
            // Ignore any case search parameters that do not implement DBProviderInterface
            if ($param instanceof DBProviderInterface) {
                // Get the query component of the parameter, append it in the correct manner and augment the list of binds.
                $newJoins = $param->getJoins();
                if($newJoins !== null) {
                    foreach ($newJoins as $newJoin) {
                        if (!in_array($newJoin, $joins, true)) {
                            $queryStr .= ' '.$newJoin.' ';
                            $joins[] = $newJoin;
                        }
                    }
                }
                $newWhere = $param->getWhereCondition();
                if ($newWhere !== null) {
                    $whereStr .= " $param->joinCondition $newWhere ";
                }
                $binds += $param->bindValues();
            }
        }
        $queryStr.=$whereStr.';';

        /**
         * @var $command CDbCommand
         */
        $command = Yii::app()->db->createCommand($queryStr);

        //only bind what we need to
        foreach($binds as $key => $val){
            if (strpos($whereStr,$key) !== false){
                $command->bindValue($key, $val);
            }
        }

        return $command->queryAll();
    }
}