<?php
    if(!defined('_INCODE')) die('Access Denied...');

    function query($sql, $data=[]){
        global $conn;
        $query = false;
        try{
            $statement = $conn->prepare($sql);

            if(empty($data)){
                $query = $statement->execute();
            }else{
                $query = $statement->execute($data);
            }
        }catch(Exception $e){
            echo "Error: " . $e->getMessage();
            echo "Line: " . $e->getLine();
        }

        if($query){
            return $statement;
        }
        return $query;
    }

    function insert($table, $dataInsert){
        $keyArr = array_keys($dataInsert);
        $fiedStr = implode(', ', $keyArr);
        $valueStr = ':'.implode(', :', $keyArr);

        echo "<pre>";
        print_r($dataInsert);
        echo "</pre>";

        echo $keyArr . "<br>";
        echo $fiedStr . "<br>";

        $sql = 'INSERT INTO ' . $table . '(' . $fiedStr . ') VALUES(' . $valueStr . ')';
        echo $sql;

        return query($sql, $dataInsert);
    } 

    function update($table, $dataUpdate, $condition = ''){
        echo "<pre>";
        print_r($dataUpdate);
        echo "</pre>";

        $updateStr = '';
        foreach($dataUpdate as $key=>$value){
            $updateStr.=$key.'=:'.$key.', ';
        }
        $updateStr = rtrim($updateStr, ', ');
        echo $updateStr;

        if(!empty($condition)){
            $sql = 'UPDATE ' .$table.' SET ' .$updateStr . ' WHERE ' . $condition;
        }else{
            $sql = 'UPDATE ' .$table.' SET ' .$updateStr;
        }
        
        echo $sql;
        return query($sql, $dataUpdate);
    }

    function delete($table, $condition = ''){
        if(!empty($condition)){
            $sql = "DELETE FROM $table WHERE $condition";
        }else{
            $sql = 'DELETE FROM ' . $table;
        }
        
        echo $sql;

        return query($sql);
    }

    //Get data from SQL statment
    function getRaw($sql){
        $statement = query($sql);
        var_dump($statement);

        if(is_object($statement)){
            $dataFetch = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $dataFetch;
        }else{
             return false;
        }
    } 

    //Get data from SQL statment - first row
    function firstRaw($sql){
        $statement = query($sql);
        if(is_object($statement)){
            $dataFetch = $statement->fetch(PDO::FETCH_ASSOC);
            return $dataFetch;
        }else{
             return false;
        }
    }

    function get($table, $field = '*', $condition = ''){
        $sql = 'SELECT ' . $field . ' FROM ' . $table;
        if(!empty($condition)){
            $sql .= ' WHERE ' . $condition;
            echo $sql;
        }
        return getRaw($sql); 
    }

    function first($table, $field = '*', $condition = ''){
        $sql = 'SELECT ' . $field . ' FROM ' . $table;
        if(!empty($condition)){
            $sql .= ' WHERE ' . $condition;
            echo $sql;
        }
        return firstRaw($sql); 
    }

    function getRows($sql){
        $statement = query($sql);
        if(!empty($statement)){
            return $statement->rowCount();
        }
    }