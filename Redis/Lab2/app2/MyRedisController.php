<?php

class MyRedisController {
    private Redis $redis;
    public function __construct() {
        $this->redis = new Redis();
        $this->redisConnect();

    }

    private function redisConnect(): void
    {
        try {
            $this->redis->connect('127.0.0.1', 6379);
            $this->addInitialData();
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
    }

    private function addInitialData(): void
    {
        $this->addStringData();
        $this->addListData();
        $this->addSetData();
        $this->addSortedSetData();
        $this->addHashData();
    }

    private function addStringData(): void
    {
        $this->redis->set("firstname", "Sahil");
        $this->redis->set("lastname", "Saini");
    }

    private function addListData(): void
    {
        $this->redis->lpush("list1", ...["one", "two", "three", "four", "five"]);
        $this->redis->lpush("list2", ...["six", "seven", "eight", "nine"]);
    }

    private function addSetData(): void
    {
        $this->redis->sAdd("set1", ...["one", "two", "three", "four", "five"]);
        $this->redis->sAdd("set2", ...["six", "seven", "eight", "nine", "one", "three"]);
    }

    private function addSortedSetData(): void
    {
        $zet1Items = [
            1 => "one",
            2 => "two",
            3 => "three",
            4 => "four",
            5 => "five"
        ];
        $zet2Items = [
            6 => "six",
            7 => "seven",
            8 => "eight",
            9 => "nine",
            1 => "one",
            3 => "three"
        ];
        foreach ($zet1Items as $scores => $member) {
            $this->redis->zAdd("zet1", $scores, $member);
        }
        foreach ($zet2Items as $scores => $member) {
            $this->redis->zAdd("zet2", $scores, $member);
        }
    }

    private function addHashData(): void
    {
        $hashItems = [
            "name" => "Sahil Saini",
            "email" => "sahil.saini@hof-university.de",
            "age" => 27
        ];
        $this->redis->hMSet("hash", $hashItems);
    }

    //------- String methods -----------

    public function getMyString(string $string): string {
        $result = "";
        try {
            $result = $this->redis->get($string);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
        return $result;
    }

    public function appendString(string $key, string $append): string
    {
        $result = "";
        try {
            $this->redis->append($key, $append);
            $result = $this->getMyString($key);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
        return $result;
    }

    //------------- List methods --------

    public function getMyList(string $key): string{
        $result = "";
        try {
            $arr = $this->redis->lRange($key, 0, -1);
            foreach ($arr as $value) {
                $result .= ", ".$value;
            }
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
        return $result;
    }

    public function insertItem(string $key, string $position, string $pivot, string $value): void{
        try {
            $this->redis->lInsert($key, $position, $pivot, $value);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
    }

    public function lPopFromList(string $key): void
    {
        try {
            $this->redis->lPop($key);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
    }

    public function rPopLPush(string $src, string $dest): void
    {
        try {
            $this->redis->rPopLPush($src, $dest);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
    }

    public function lMove(string $src, string $dest, string $whereFrom, string $whereTo): void{
        $this->redis->lMove($src, $dest, $whereFrom, $whereTo);
    }

    //----------- Set methods ------

    public function  scanMySet($key): array{
        $cursor = null;
        $result = [];
        do{
            try {
                $members = $this->redis->sScan($key, $cursor);
                if ($members !== false){
                    $result = [...$result, ...$members];
                }
            } catch (RedisException $e) {
                echo $e->getMessage();
            }
        } while ($cursor != 0);
        return $result;
    }

    public function findSetIntersection(string $set1, string $set2): mixed{
        $result = [];
        try {
            $result = $this->redis->sInter($set1, $set2);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
        return $result;
    }

    public function findSetUnion(string $set1, string $set2): array
    {
        $result = [];
        try {
            $result = $this->redis->sUnion($set1, $set2);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
        return $result;
    }

    public function findSetDifference(string $set1, string $set2): array
    {
        $result = [];
        try {
            $result = $this->redis->sDiff($set1, $set2);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
        return $result;
    }

    //--------- Sorted Set ---------

    public function getAllMembers(string $key): array {
        $result = [];
        try {
            $result = $this->redis->zRange($key, 0, -1, ['WITHSCORES'=>true]);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
        return $result;
    }

    public function findZetIntersection(string $output, array $keys): void{
        try {
            $this->redis->zInterStore($output, $keys);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
    }

    public function findZetUnion(string $output, array $keys, array $weights): void
    {
        try {
            $this->redis->zUnionStore($output, $keys, $weights);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
    }

    public function findZetDifference(array $keys, array $options): array
    {
        return $this->redis->zdiff($keys, $options);
    }


    //-------- Hash -------

    public function getHashMembers(string $key): array
    {
        $result = [];
        try {
            $result = $this->redis->hGetAll($key);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
        return $result;
    }

    public function addNewHashEntry(string $key, string $field, string $value): void
    {
        try {
            $this->redis->hSet($key, $field, $value);
        } catch (RedisException $e) {
            echo $e->getMessage();
        }
    }



}
