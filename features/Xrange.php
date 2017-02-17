<?php

/**
 * @link http://api.ibos.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */
class Xrange implements Iterator
{
    protected $start;
    protected $limit;
    protected $step;
    protected $i;

    public function __construct($start, $limit, $step = 0)
    {
        $this->start = $start;
        $this->limit = $limit;
        $this->step  = $step;
    }

    public function rewind()
    {
        $this->i = $this->start;
    }

    public function next()
    {
        $this->i += $this->step;
    }

    public function current()
    {
        return $this->i;
    }

    public function key()
    {
        return $this->i + 1;
    }

    public function valid()
    {
        return $this->i <= $this->limit;
    }
}

foreach (new Xrange(0, 10, 2) as $key => $value) {
    printf("%d %d\n", $key, $value);
}

//1 0
//3 2
//5 4
//7 6
//9 8
//11 10

// use
//如一个对象 StudentsContact，这个对象是用于处理学生联系方式的，通过 addStudent 方法注册学生，通过 getAllStudent 获取全部注册的学生联系方式数组。我们以往遍历是通过 StudentsContact::getAllStudent() 获取一个数组然后遍历该数组，但是现在有了迭代器，只要这个类继承这个接口，就可以直接遍历该对象获取学生数组，并且可以在获取之前在类的内部就对输出的数据做好处理工作。

function xrange($start, $limit, $step = 1) {
    for ($i = $start; $i <= $limit; $i += $step) {
        yield $i + 1 => $i; // 关键字 yield 表明这是一个 generator
    }
}

// 我们可以这样调用
foreach (xrange(0, 10, 2) as $key => $value) {
    printf("%d %d\n", $key, $value);
}