1.  Public folder
        > Only folder which is accessable to the web
        > root of the web server
        > front controller and static files (images) are here
        > due to other folders are unaccessable, it increase the secure

2.  Front controller
        > url does not map to individual php files
        > all requests are sent through one page, that is front controller
        > provide a central entry point

3. query string
        > query string is part of url that comes after question mark
            ex : marshal/index.php?home -> home is query string
        > we can use it to route the request
        > entire query string will be request URL or a route

4. require vs include
        > if the file not found require stop the script and gives an error.
        > but include just carry on

        > we need definetely the file, so we use require

5. regular expression
        > regular expression describe the pattern of a string
        > it can use for matching strings and replacing and some advanced stuff
        > by using this we compare not a fixed string , but also a pattern. for that we use preg_match() function

        get idea of string matching in regular expression
                RegEx           String          matched?
                
                /abc/           abc             True
                /abc/           abcde           True    because still abc contain in string
                /abc/           bcde            False
                /2:3/           12:34:23        True
        > RegEx is in through //
        > everything is a charactor, even it is number

        > specific type of charactors in RegEx

                \d    ->      any digit 0 to 9
                \w    ->      any charactor from a to z, A to Z, or 0 to 9
                \d    ->      any white space (space, tab)
                ^     ->      start of the string
                $     ->      end of the string
                *     ->      zero or more time
                +     ->      one or more time
                .     ->      match with any charactor (letter, number, space)
                .*    ->      any number of any charactor
                \     ->      match meta charactors
                []    ->      match one or more charactors in the brackets
                [ - ] ->      specify charactor range ex: [0-9]
                [^ ]  ->      it except the given range
                ()    ->      capture groups in side the parentheses
                        ex: preg_match($regEx, $string, $matches)

                        $regEx          $string         $matches

                        /([a-z]+)/      jan 1999        [0 => 'jan 1999',
                                                         1 => 'jan',
                                                         2 => '1999']

                (?<name>regex)    ->      capture groups by name
                        ex: preg_match($regEx, $string, $matches)

                        $regEx                                  $string         $matches

                        /(?<month>[a-z]+ ()?<year>\d+)/         jan 1999        [0 => 'jan 1999',
                                                                                'month' => 'jan',
                                                                                'year' => '1999']

                RegEx are case sensitive, but after adding i it gonna ignore

                ex :    RegEx           String          Matched?

                        /ab\d/          ab12            True
                        /^abc/          12abc           False
                        /abc$/          12abc           True

                        /a*bc/          bc              True    because * means zero or more, so a can be there or not
                        /a+bc/          bc              False
                        /a+bc/          aaaaaabc        True
                        /a.bc/          a bc            True

                        /abc\./         abc.            True
                        /abc\./         abcd            False

                        /abc/           Abc             False
                        /abc/i          Abc             True
                        /a[123]bc       a23bc           True
                        /a[123]+b/      a1221b          True

                        /a[1-4]b/       a3b             True
                        /a[^234]/       a2              False
                        /a[^234]/       a6              True

        > use RegEx for routes

        ex:     marshal/controller/action
                /^(?<controller>[a-z-]+)\/(?<action>[a-z-]+)$/

        > replacement with regular expression
                $res = preg_replace($regEx, $replacement, $str)
                above function search $string for match to $regEx and replace with $replacement

        ex : 
                $regEx          $replace        $string
                /\d+/           --              abc23343ew ->      abc--ew
        
        > replace groups using backreference
        \1 indicate the first group
        \2 indicate the first group
        
        ex:
                $regEx                  $replace        $string
                /(\w+) and (\w+)/       \1 or \2        Jack and Jill   =>      Jack or Jill

6. Dynamically call functions

        this is user when it is required parameters
        call_user_func_array([class_name, method],[parameters])

        before call from this method we have to validate is it exists the class and method
        for that,

        class_exists() is for check the class 
        is_callable() is for check the method is callable or not

7. get controller class and action method from the route

        word separated in URL by hyphens
        controller classes and action names are named using StudlyCaps or camelCase

8. namespaces
        it is like to a folder
        it allows to have two or more classes with same name

        > create object of namespaced class 
        ex:
                namespace App
                class Product{} -> $product = new App\Product();
        simply just add namespace to front

        > in namespace, we cannot create an object which is not in a namespace, so for that we have to use \ to show the root namespace
        
        > use keyword
                when the namespace is large we use 'use' keyword
                ex:
                        $product = new App\Core\Lib\Models\Product()
                        instead of that we use 

                        use App\Core\Lib\Models\Product;
                        $product = new Product()

        > use alias
                when we define two classes with same name
                ex:     
                        use App\Core\Lib\Models\Product as CoreProduct;
                        $product = new CoreProduct()

9. autoloading  
        We define classes in separate files
        so instead of require one by one, it can autoload for make it simple

        for that,
        filename match with class name
        folder name match with namespace

10. __call magic method
        __call is magic method in php
        because we can call private and non exists methods by using this

        ex:
                class Product{
                        public function __call($name, $arguments){

                        }
                        private function modify(){

                        }
                }

                in another file,
                $product = new Product();

                $product->modify();     // private but works
                $product->publish();    // non exists but works

11. action filters
        how execute some code before or after every action
        ex:     to check,
                user logged in,
                has permission

        this can be archived by using __call magic method because if we create methods as private then __call function call first
        and also we can add a prefix to the function name, then that function not exists, then also first __call function before execute correct method

12. cross site scripting attacks
        user enter data in html script format to change the behavior of the site
        for avoid that we can use htmlspecialchar() function

13. extract arguments by using extract() function
        ex:
                $data = [
                        'name' => 'Dave',
                        'color' => 'green' 
                ];
                       |
                      \_/

                extract($data);
                
                       |
                      \_/

                $name = 'dave';
                $color = 'green';


