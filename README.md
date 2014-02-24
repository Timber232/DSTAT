DSTAT
=====

Delimited String To Array Toolkit

# Example Code: #

    $example_string = "
    ^ name
    ^^^ Bob Smith ^^^
    
    ^ date
    ^^^ February 23, 2014 ^^^
    
    ^ description
    ^^^ 
    Bear claw dragée icing gingerbread croissant gummies biscuit powder. 
    Marzipan marshmallow fruitcake gummi bears cotton candy muffin gummies. 
    Fruitcake oat cake soufflé pie marshmallow. 
    ^^^
    
    Gingerbread lollipop. Bear claw cookie cupcake. 
    Halvah sugar plum toffee cupcake tootsie roll soufflé. 
    Croissant lollipop soufflé croissant sweet roll bonbon carrot cake powder. 
    ";
    
    $delimiter = array('^', '^^^');
    $dstat = new DSTAT($example_string, $delimiter);
    
    echo $dstat->isKeyAndValueMatching() ? 
    	"All Good. Keys and values are matching" :
    	"Key and values are not matching, please double check your string.";
    
    echo "<p><b>Original Text: </b>" .
    	$dstat->text .
    	"</p>";
    
    var_dump($dstat->getKeys());
    var_dump($dstat->getValues());
    var_dump($dstat->getBoth());
