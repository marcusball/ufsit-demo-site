template
========

An unnamed PHP template system I'm writing. 

# Setup, Requirements, and Notes
## Requirements
PHP, and Apache with mod_rewrite

## Optional Requirements
A [fork](https://github.com/marcusball/ohmy-auth) of [ohmy-auth](https://github.com/sudocode/ohmy-auth/) is included if you wish to enable OAuth2 client functionality. You could do OAuth1 client stuff, but all of the example code provided is for OAuth2. 

# API Docs
## Basic Overview

Here I will first give an overview of how this works, and important locations within code to consider when beginning a project. First, the basics on how this system works. Using Apache's .htaccess, any request to a url that does not exist is routed to index.php. In this way, it's safe to place assests anywhere in the application directory. The only exception is for the folders included with this system: htaccess will redirect any attempts to access the 'include', 'page-*', and 'server' folders back to index.php. 

Requests that get sent to index.php are then processed to determine appropriate handling. The function `runPageLogicProcedure()` is, for all intents and purposes, the entry-point for the whole system. That function will first parse the request path, then will check if the request matches any url rewrite patterns (see `$REWRITE_RULES` in _config.php_). Once everything is parsed, the results are handed to the RequestHandler class. 

RequestHandler first determines the expected file paths based on the request url. The file paths it generates are those for the handling template file, and the function file. Before I continue, I will state that this system operates similar to an [MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) architecture; it's not exactly MVC, but MVC was the inspiration for the architecture of this system. Here, as stated above, request handlers are separated into template files and function files. Essentially, template files should be mostly presentational HTML, with only basic PHP mixed in to output information generated by the function file. The function file is made up of a class extending the `RequestObject` class—though, normally you will use the children classes, `PageObject` or `APIObject`, which are children of `RequestObject`—and this class will handle any processing and data handling related to a request. 

For example, if one were to handle the log in request for a user, the function object would handle receiving the user's data and authenticating the user, while the template would be solely used to display the login form, and print out the results supplied by the function page ("Logged in!" or "Username or password is incorrect"). Overall, the goal of this architecture is to separate processing logic from UI logic. 

The function pages are located within the `page-functions` directory, and are named `example.func.php`, where "example" is the name of the requested page. The template pages are located in the `page-content` directory, and are simply named `example.php`. In case it's not obvious from this description, the files request names must match in order for the request manager to find them. For another example, the files for your home page should be 'index.func.php' and 'index.php' respectively. 

Now, for slightly more complex cases, I'll note that it is not required for both the template and function pages to be present. It is perfectly valid to have just the function class, or just the template file. If you have just the template file, then it will behave just like any other php file, as though it had been executed on its own. 

If you have just the function file, then there will be nothing displayed unless you write content from the function file. You could simply include HTML or use a function like `echo` to print output, but *it is not recommended to output directly from the function file*. Instead, you can use the `Response` class to set the output content. Every RequestObject class has a `Response` member which can be used to handle and manipulate the response that's sent back to the client, including the response data itself and the response headers. A typical example case in which you would not use a template file would be creating a function class which extends from `APIObject`. The APIObject is designed to be used for pages which will be returning json data. Within the execution code in your API response class, you could create an array of data to return, then use `$this->response->setContent($yourContentArray);` to set the data that should be returned to the client. 

This is a tangent, but if you're curious, the `page-include` folder is intended to be similar to page template, except files in there are intended to be included by the actual request template. Files one might place in the `page-include` directory would be, for example, a header and a footer for your web pages. 

Back to explaining the execution process, the RequestHandler will first execute the appropriate handling methods within the page function class (if it exists), and will then include and execute the template code. In this way, the function can handle the request, get then have any data for the template ready before the template is executed. The template is executed within the scope of the handling RequestObject class, so any member functions or variables are accessible through `$this->member` references. 

Upon execution of the handling page, the ResponseHandler continues, finishes it's handling, then finally calls the `OutputHandler` class. This class is responsible for providing standard output patterns. OutputHandler receives the Response object generated by the handling function classes, then determines how to return the data to the client. For example, if the status code is changed within the Response, the OutputHandler will respond using whatever behavior you define for that HTTP status code. That is, if you set the status code to 404 within your handling class, the OutputHandler will display whatever you set as the 404 behavior. 

## Important Classes

## Notable code locations

This is a list of locations in various classes that could be important for your development. These are mostly method stubs that haven't been implemented, or have various default functionalities that you'll likely wish to change. 

### CurrentUser
This is the class responsible for handling client sessions. 

#### `CurrentUser::isValidUser($uid)`

    /*
     * Check if the $uid stored in a user's session is valid.
     */
	private function isValidUser($uid){
		if(HAS_DATABASE){
			return $this->dbCon->isValidUid($uid);
		}
		//If there is no database, then there is no "valid" users.
		//This function should be modified as necessary on a per-application basis. 
		return true;
	}

This method is used to check if the uid (user id) stored in a user's session is valid. If using a database, this will, by default, query the DatabaseController function `isValidUid($uid)`. If you're not using a database, and the config value HAS_DATABASE is set to false, this will simply return true. This is not a problem, but it's worth considering if you want to validate your session variables. 

#### `CurrentUser::getUserInformation()`

See [`User->getUserInformation()`](#usergetuserinformation).

### User 

#### `User::getUserInformation()`

    public function getUserInformation(){
		if(HAS_DATABASE){
			$tempData = $this->dbCon->getUserInformation($this->uid);
			if($tempData !== false){
				$this->userData = $tempData;
				return true;
			}
		}
		//If there is no database, then there is no user data to fetch.
		//This function should be modified as necessary on a per-application basis.
		return false;
	}

This method is used to load information regarding a the user associated with the User object on which this is called, including that of the inheriting CurrentUser object. If using a database, this will load data using the DatabaseController function `getUserInformation($uid)` to load data from the database and store it in the private variable `$userData`. If not using a database, nothing is loaded. If you're storing any information about users that you'd like to associate with each user at runtime, you'll likely want to ensure this function loads that data as desired. 