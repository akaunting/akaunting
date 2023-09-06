From 1.x to 2.x
=================

- All classes uses `Masterminds` namespace.
- All public static methods has been removed from `HTML5` class and the general API to access the HTML5 functionalities has changed. 

    Before:
    
        $dom = \HTML5::loadHTML('<html>....');
        \HTML5::saveHTML($dom);
        
    After:

        use Masterminds\HTML5;
        
        $html5 = new HTML5();
        
        $dom = $html5->loadHTML('<html>....');
        echo $html5->saveHTML($dom);


