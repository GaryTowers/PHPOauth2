# PHP Oauth2 Sample
This is a small Oauth2 server that allows an external application (**TestApp**) to access important information. The process is simplified by just using a single endpoint (**/auth**) to start the flow internally and return an access token.

The project makes use of
  - [PHP Slim](http://www.slimframework.com/): A small REST framework. On this project Slim is only used to handle http requests. All other functionality is handled by Oauth2 Library
  - [Oauth2 Server Library](https://github.com/bshaffer/oauth2-server-php): PHP library to implement Oauth2 flow 
  - Plain PHP
  - MySQL

#### Install
   - Clone the project
   - Run `composer install` 
   
#### Endpoints

**[GET]** **/auth**
Asks the user to give access to **TestApp** to access protected information on his behalf
Parameters:
  * response_type : **code**
  * client_id: **TestApp**
  * state: **xyz**
***Example:*** [get] /auth?response_type=code&client_id=TestApp&state=xyz

**[GET]** **/treasure**
Protected endpoint. Add query parameter `access_token` to access it

#### Assumptions
  * The user is already signed in
  * The only client registered is **TestApp** so the `client_id` is always TestApp
  * JSON is used for requests and responses