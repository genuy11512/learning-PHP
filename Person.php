<?php
namespace app;

class Person
{
    private $args;
    public function gateway($args)
    {
        $methods = get_class_methods($this);
        $gateway_index = array_search('gateway', $methods);
        unset($methods[$gateway_index]);

        // Check user pass param correctly.
        if (empty($args) || !isset($args['action'])) {
            return "Incorrect format";
        }

        // Check method existed in this class.
        if (!in_array($args['action'], $methods)) {
            return "Class not support this action";
        }

        return $methods;
    }

    private function resetUser()
    {
        return "OK men";
    }

    private function lockUser()
    {

    }
}

$person = new Person();
$methods = $person->gateway(['action' => 'lockUser']);
var_dump($methods);