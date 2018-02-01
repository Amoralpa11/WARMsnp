<?php
// comment
# comment
/*
comment
*/
$x = 5 /* + 15 */ + 5;
echo "Hello World!<br>";

$color = "red";
#concatenation
echo "My car is" . $color . "<br>";
echo "My car is $color <br>";

/*Global vs Local scope
Outside a function is not accessible inside a function (global), inside a function (local) not accessible outside this one. You can have multiple local variables with the same name in diff functions. 
Global keyword is used to access a global variable inside a function:
ex: */
$x = 5;
$y = 10;
function myTest() {
	global $x, $y;
	$y = $x + $y;
}

myTest();
echo $y;

//PHP stores all global variables in an array called $GLOBALS[index]
function myTest2() {
	$GLOBALS['y'] = $GLOBALS['x'] + $GLOBALS['y'];
}

myTest2();
echo $y;

/* STATIC KEYWORD
when a function is completed/executed, all of its variables are deleted. However, 
sometimes we want a local variable NOT to be deleted. Variable still local!*/
function myTest3() {
	static $x = 0;
	echo $x;
	$x++;
}
myTest(); #will print 0
myTest(); #will print 1
myTest(); #will print 2

/* 	ECHO vs PRINT
echo and print are more or less the same. They are both used to output data to the screen.
The differences are small: echo has no return value while print has a return value of 1 so 
it can be used in expressions. echo can take multiple parameters (although such usage is rare) while 
print can take one argument. echo is marginally faster than print.*/
echo "This ", "string ", "was ", "made ", "with multiple parameters."; #multiple arguments
print "<h2>PHP is Fun!</h2>";

#The PHP var_dump() function returns the data type and value:
$x = 5985;
var_dump($x); #will print int(5985)

#Boolean = true, false
#Array = stores multiple values in one single variable.
$cars = array("Volvo","BMW","Toyota");

#OBJECT: stores data and information on how to process data. 
class Car {
    function Car() {
        $this->model = "VW";
    }
}

// create an object
$herbie = new Car();

// show object properties
echo $herbie->model;

//String functions
echo strlen("Hello world!"); // outputs 12
echo str_word_count("Hello world!"); // outputs 2
echo strrev("Hello world!"); // outputs !dlrow olleH
echo strpos("Hello world!", "world"); //outputs 6, the first character position where it found the second string
echo str_replace("world", "Dolly", "Hello world!"); // Output code: Hello Dolly!

/* Constants are like variables except that once they are defined they cannot 
be changed or undefined. Constants are automatically global and can be used across the entire script.
define(name, value, case-insensitive), Default of case-insensitive = false*/
define("GREETING", "Welcome to W3Schools.com!");
echo GREETING; #will print Welcome to W3Schools.com!

//arithmetic operations, with numbers
$x + $y;
$x - $y;
$x * $y;
$x / $y;
$x % $y;
$x ** $y;

//ASSIGNMENT OPERATOR, with numbers
$x = $y;
$x += $y;
$x -= $y;
$x *= $y;
$x /= $y;
$x %= $y;

//COMPARISONS, with numbers or strings
$x == $y;
$x === $y; #IDENTICAL, equal and same type
$x != $y;
$x <> $y; #same as above
$x !== $y; #not identical
$x /*<, >, <=, >=*/ $y;

//INCREMENT, DECREMENT
++$x; #pre-increment, first increments and then returns x
$x--; #post-decrement, first returns x and then decrements it. 

//LOGICAL OPERATORS
and #both true
or #one or the other true
xor #only one of both true
&& #and
|| #or
! #not

//STRING OPERATORS
$txt1 . $txt2; #concatenation
$txt1 .= $txt2; #concatenation assignment

//ARRAY OPERATORS
$x + $y; #union
$x == $y; #Equality, same key/value pairs
$x === $y; #Identity, same key/value pairs in the same order and of the same types
$x != $y; #inequality
$x <> $y; #inequality
$x !== $y; #Non-identity

//CONDITIONAL STATEMENTS
/*
if (condition) {
	# code...
} */
$t = date("H");

if ($t < "20") {
    echo "Have a good day!";
} #will output Have a good day if the current hour is less than 20. 

if ($t < "20") {
    echo "Have a good day!";
} else {
    echo "Have a good night!";
}

if ($t < "10") {
    echo "Have a good morning!";
} elseif ($t < "20") {
    echo "Have a good day!";
} else {
    echo "Have a good night!";
}

/* SWITCH STATEMENT, select one of many blocks of code
switch (n) {
    case label1:
        code to be executed if n=label1;
        break;
    case label2:
        code to be executed if n=label2;
        break;
    case label3:
        code to be executed if n=label3;
        break;
    ...
    default:
        code to be executed if n is different from all labels;
} */

$favcolor = "red";

switch ($favcolor) {
    case "red":
        echo "Your favorite color is red!";
        break;
    case "blue":
        echo "Your favorite color is blue!";
        break;
    case "green":
        echo "Your favorite color is green!";
        break;
    default:
        echo "Your favorite color is neither red, blue, nor green!";
}

//PHP LOOPS
$x = 1; 

while($x <= 5) {
    echo "The number is: $x <br>";
    $x++;
} 
$x = 1; 

do {
    echo "The number is: $x <br>";
    $x++;
} while ($x <= 5); #checks the condition at the end, execute the code at least once even if the condition is false at first.

for ($x = 0; $x <= 10; $x++) {
    echo "The number is: $x <br>";
} 

#only for arrays
$colors = array("red", "green", "blue", "yellow"); 

foreach ($colors as $value) {
    echo "$value <br>";
} #output the values. 

//USER DEFINED FUNCTIONS
/* A function name can start with a letter or underscore (not a number). Not case-sensitive. 
*/
function writeMsg() {
    echo "Hello world!";
}

writeMsg(); // call the function

function familyName($fname, $year) {
    echo "$fname Refsnes. Born in $year <br>";
}

familyName("Hege", "1975");
familyName("Stale", "1978");
familyName("Kai Jim", "1983");

function setHeight($minheight = 50) {
    echo "The height is : $minheight <br>";
}

setHeight(350);
setHeight(); // will use the default value of 50

function sum($x, $y) { #functions returning values
    $z = $x + $y;
    return $z;
}

echo "5 + 10 = " . sum(5, 10) . "<br>";
echo "7 + 13 = " . sum(7, 13) . "<br>";

/* ARRAYS
In PHP, there are three types of arrays:

Indexed arrays - Arrays with a numeric index
Associative arrays - Arrays with named keys
Multidimensional arrays - Arrays containing one or more arrays */
$cars = array("Volvo", "BMW", "Toyota");
echo "I like " . $cars[0] . ", " . $cars[1] . " and " . $cars[2] . ".";

echo count($cars) #returns length of the array. 
//Loops
$arrlength = count($cars);

for($x = 0; $x < $arrlength; $x++) {
    echo $cars[$x];
    echo "<br>";
}
//ASSOCIATIVE
$age = array("Peter"=>"35", "Ben"=>"37", "Joe"=>"43");
echo "Peter is " . $age['Peter'] . " years old.";
//Loop
foreach($age as $x => $x_value) {
    echo "Key=" . $x . ", Value=" . $x_value;
    echo "<br>";
}
/* SORT FUNCTIONS FOR ARRAYS
sort() - sort arrays in ascending order
rsort() - sort arrays in descending order
asort() - sort associative arrays in ascending order, according to the value
ksort() - sort associative arrays in ascending order, according to the key
arsort() - sort associative arrays in descending order, according to the value
krsort() - sort associative arrays in descending order, according to the key*/
$cars = array("Volvo", "BMW", "Toyota");
sort($cars);
rsort($cars);
$age = array("Peter"=>"35", "Ben"=>"37", "Joe"=>"43");
asort($age);
ksort($age);
arsort($age);
krsort($age);

/*SUPERGLOBALS
Superglobals were introduced in PHP 4.1.0, and are built-in variables that are always available in all scopes.

$GLOBALS is a PHP super global variable which is used to access global variables from anywhere in the PHP script 
(also from within functions or methods).*/
$x = 75; 
$y = 25;
 
function addition() { 
    $GLOBALS['z'] = $GLOBALS['x'] + $GLOBALS['y']; 
}
 
addition(); 
echo $z; 

/*$_SERVER is a PHP super global variable which holds information about headers, paths, and script locations.
*/


$_SERVER['PHP_SELF']	#Returns the filename of the currently executing script
$_SERVER['GATEWAY_INTERFACE']	#Returns the version of the Common Gateway Interface (CGI) the server is using
$_SERVER['SERVER_ADDR']	#Returns the IP address of the host server
$_SERVER['SERVER_NAME']	#Returns the name of the host server (such as www.w3schools.com)
$_SERVER['SERVER_SOFTWARE']	#Returns the server identification string (such as Apache/2.2.24)
$_SERVER['SERVER_PROTOCOL']	#Returns the name and revision of the information protocol (such as HTTP/1.1)
$_SERVER['REQUEST_METHOD']	#Returns the request method used to access the page (such as POST)
$_SERVER['REQUEST_TIME']	#Returns the timestamp of the start of the request (such as 1377687496)
$_SERVER['QUERY_STRING']	#Returns the query string if the page is accessed via a query string
$_SERVER['HTTP_ACCEPT']	#Returns the Accept header from the current request
$_SERVER['HTTP_ACCEPT_CHARSET']	#Returns the Accept_Charset header from the current request (such as utf-8,ISO-8859-1)
$_SERVER['HTTP_HOST']	#Returns the Host header from the current request
$_SERVER['HTTP_REFERER']	#Returns the complete URL of the current page (not reliable because not all user-agents support it)
$_SERVER['HTTPS']	#Is the script queried through a secure HTTP protocol
$_SERVER['REMOTE_ADDR']	#Returns the IP address from where the user is viewing the current page
$_SERVER['REMOTE_HOST']	#Returns the Host name from where the user is viewing the current page
$_SERVER['REMOTE_PORT']	#Returns the port being used on the user's machine to communicate with the web server
$_SERVER['SCRIPT_FILENAME']	#Returns the absolute pathname of the currently executing script
$_SERVER['SERVER_ADMIN']	#Returns the value given to the SERVER_ADMIN directive in the web server configuration file (if your script runs on a virtual host, it will be the value defined for that virtual host) (such as someone@w3schools.com)
$_SERVER['SERVER_PORT']	#Returns the port on the server machine being used by the web server for communication (such as 80)
$_SERVER['SERVER_SIGNATURE']	#Returns the server version and virtual host name which are added to server-generated pages
$_SERVER['PATH_TRANSLATED']	#Returns the file system based path to the current script
$_SERVER['SCRIPT_NAME']	#Returns the path of the current script
$_SERVER['SCRIPT_URI']	#Returns the URI of the current page

/*PHP $_GET can also be used to collect form data after submitting an HTML form with method="get".
$_GET can also collect data sent in the URL.*/
?>
<html>
<body>

<a href="test_get.php?subject=PHP&web=W3schools.com">Test $GET</a>

</body>
</html>
#Here when you click on the link, the paremeters subject and web are sent to the php script. 

<?php echo "Study " . $_GET['subject'] . " at " . $_GET['web']; ?>

//FORM HANDLING
#A SIMPLE HTML FORM
<html>
<body>

<form action="welcome.php" method="post">
Name: <input type="text" name="name"><br>
E-mail: <input type="text" name="email"><br>
<input type="submit">
</form>

</body>
</html>
/*This outputs an html form with two input fields and a submit button. When the user
submits the data, it sends both variables to the welcome.php file with the variable $_POST */
<html>
<body>

Welcome <?php echo $_POST["name"]; ?><br>
Your email address is: <?php echo $_POST["email"]; ?>

</body>
</html>

#If instead of using "post" we use "get", we can have the same output accessing the data through $_GET	
<?php
/*When to use GET?
Information sent from a form with the GET method is visible to everyone (all variable names 
and values are displayed in the URL). GET also has limits on the amount of information to send. 
The limitation is about 2000 characters. However, because the variables are displayed in the URL, 
it is possible to bookmark the page. This can be useful in some cases.
GET may be used for sending non-sensitive data.
Note: GET should NEVER be used for sending passwords or other sensitive information!

When to use POST?
Information sent from a form with the POST method is invisible to others (all names/values are embedded 
within the body of the HTTP request) and has no limits on the amount of information to send.
Moreover POST supports advanced functionality such as support for multi-part binary input while uploading files to server.
However, because the variables are not displayed in the URL, it is not possible to bookmark the page.

PHP FORM VALIDATION
*/
#More complete form
?>
<html>
<body>

<!--The code is now safe to be displayed on a page or inside an e-mail.

We will also do two more things when the user submits the form:

Strip unnecessary characters (extra space, tab, newline) from the user input data (with the PHP trim() function)
Remove backslashes (\) from the user input data (with the PHP stripslashes() function)
The next step is to create a function that will do all the checking for us (which is much more convenient than writing the same code over and over again).

We will name the function test_input().

Now, we can check each $_POST variable with the test_input() function, and the script looks like this:

-->

<?php
// define variables and set to empty values In the following code we have added some new variables: $nameErr, $emailErr, $genderErr, and $websiteErr. These error variables will hold error messages for the required fields.
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
  		$nameErr = "Only letters and white space allowed"; 
		}
  }

  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  		$emailErr = "Invalid email format"; 
}
  }

  if (empty($_POST["website"])) {
    $website = "";
  } else {
    $website = test_input($_POST["website"]);
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
  		$websiteErr = "Invalid URL"; 
}
  }

  if (empty($_POST["comment"])) {
    $comment = "";
  } else {
    $comment = test_input($_POST["comment"]);
  }

  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }
}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
	<!-- #Form Element -->
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<!-- So, the $_SERVER["PHP_SELF"] sends the submitted form data to the page itself, 
		instead of jumping to a different page. This way, the user will get error messages on the same page as the form.
		The htmlspecialchars() function converts special characters to HTML entities. This means that it will replace HTML 
		characters like < and > with &lt; and &gt;. This prevents attackers from exploiting the code by injecting HTML or 
		Javascript code (Cross-site Scripting attacks) in forms. 

	Then in the HTML form, we add a little script after each required field, which generates the correct error message if needed 
To show the values in the input fields after the user hits the submit button, we add a little PHP script inside the value attribute 
of the following input fields: name, email, and website. In the comment textarea field, we put the script between the <textarea> and </textarea> tags.-->

	Name: <input type="text" name="name" value="<?php echo $name;?>">
	<span class="error">* <?php echo $nameErr;?></span>
	<br><br>
	E-mail:
	<input type="text" name="email" value="<?php echo $email;?>">
	<span class="error">* <?php echo $emailErr;?></span>
	<br><br>
	Website:
	<input type="text" name="website" value="<?php echo $website;?>">
	<span class="error"><?php echo $websiteErr;?></span>
	<br><br>
	Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>
	<br><br>
	Gender:
	Gender:
	<input type="radio" name="gender"
	<?php if (isset($gender) && $gender=="female") echo "checked";?>
	value="female">Female
	<input type="radio" name="gender"
	<?php if (isset($gender) && $gender=="male") echo "checked";?>
	value="male">Male
	<span class="error">* <?php echo $genderErr;?></span>
	<br><br>
	<input type="submit" name="submit" value="Submit"> 

</form>

<?php
echo "<h2>Your Input:</h2>";
echo $name;
echo "<br>";
echo $email;
echo "<br>";
echo $website;
echo "<br>";
echo $comment;
echo "<br>";
echo $gender;
?>

</body>
</html>

















