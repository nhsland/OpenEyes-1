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
        $level = 0;

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
                    // Open/close parentheses based on the parameter's level value relative to the current level value.
                    // This will handle large gaps in level values as well as single-unit gaps.
                    // Same-level separated parentheses (eg. (conditions) AND (conditions)) are not currently handled.
                    if ($param->level > $level) {
                        $whereStr .= " $param->joinCondition ";
                        for ($i = $level; $i < $param->level; $i++) {
                            $whereStr .= '(';
                        }
                        $whereStr .= "$newWhere";
                    }
                    elseif ($param->level < $level) {
                        for ($i = $level; $i > $param->level; $i--) {
                            $whereStr .= ')';
                        }
                        $whereStr .= " $param->joinCondition $newWhere";
                    }
                    else {
                        $whereStr .= " $param->joinCondition $newWhere";
                    }
                }

                $level = $param->level;
                $binds += $param->bindValues();
            }
        }
        // Close any hanging parentheses by using the level we ended the loop with.
        for ($i = $level; $i > 0; $i--) {
            $whereStr .= ')';
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