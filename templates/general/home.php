<?php

class Fruit
{
    private static $counter = 1;
    public $id, $name;

    public function __construct($name)
    {
        $this->id = self::$counter++;
        $this->name = $name;
    }

    public static function addArray(array $names)
    {
        foreach ($names as $name) {
            $fruits[] = new Fruit($name);
        }

        return $fruits;
    }
}

$fruits = Fruit::addArray(['watermelon', 'strawberry', 'raspberry', 'peach']);
dump($fruits)
?>

</div>
