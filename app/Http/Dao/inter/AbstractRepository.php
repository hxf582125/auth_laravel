<?php
/**
 * Created by PhpStorm.
 * User: warth
 * Date: 2017/9/3
 * Time: 21:59
 */

namespace App\Http\Dao\inter;


use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class AbstractRepository extends Model
{
    protected $table;
    protected $db = null;

    protected function db($db = null)
    {
        return DB::connection($db ?? $this->db)->table($this->table);
    }

    protected function table($table = null)
    {
        return DB::connection($db ?? $this->db)->table($table);
    }

    protected function doWhere($where) {
        return $this->db()->where(function ($query) use ($where) {
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    list($key, $formula, $val) = $value;
                    $query->where($key, $formula, $val, $value[4] ?? 'and');
                } else {
                    $query->where($key, '=', $value);
                }
            }
        });
    }

    /**
     * @param null $where
     * @param array $fields
     * @param array $order
     * @return \Illuminate\Support\Collection
     * @throws Exception
     */
    public function findAll($where = null, $fields = ['*'], $group = [], $order = [])
    {
        try {

            $query = $this->doWhere($where)->when($group, function ($query) use ($group) {
                return $query->groupBy($group);
            })->when($order, function ($query) use ($order) {
                return $query->orderBy($order[0], $order[1]);
            });

            return $query->get($fields);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function findCount($where = null, $column = '*')
    {
        try {
            return $this->db()->where($where)->count($column);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function findOne($where = null, $column, $join = [])
    {
        try {
            $query = $this->doWhere($where)->when($join, function ($query) use ($join) {
                if (is_array($join[0])) {
                    return $query->join($join[0], $join[1], $join[2], $join[3], $join[4] ?? 'inner');
                }

                foreach ($join as $item) {
                    if (is_array($item)) {
                        $query->join($item[0], $item[1], $item[2], $item[3], $item[4] ?? 'inner');
                    }
                }
                return $query;
            });

            return $query->pluck($column);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function find($where = null, $fields = ['*'], $join=[])
    {
        try {
            return $query = $this->doWhere($where)->when($join, function ($query) use ($join) {
                if (is_array($join[0])) {
                    return $query->join($join[0], $join[1], $join[2], $join[3], $join[4] ?? 'inner');
                }

                foreach ($join as $item) {
                    if (is_array($item)) {
                        $query->join($item[0], $item[1], $item[2], $item[3], $item[4] ?? 'inner');
                    }
                }
                return $query;
            })->first($fields);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function delete($where = null)
    {
        try {
            return $this->db()->where($where)->delete();
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function update(array $data=[], array $where=[])
    {
        try {
            return $this->db()->where($where)->update($data);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}