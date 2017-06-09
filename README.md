# tiny-ticket-server
Tiny Ticket Server

A very simple one-time ticket/voucher/code server. 

Use it to give users the opportunity to claim a code that can be only used once.

## Configuration:
- copy all files to server
- edit db/allCodesFile.txt to edit the available tickets (used tickets will be copied to usedCodesFile.txt)
- don't forget to deny access to directory db (already denied in .htaccess)
 
## allCodesFile.txt format:
- one ticket per line
- line format in the form {code},id,expiration-date:
	- {xxxxxxxx},id,YYYY-MM-DD
- Example:
~~~~
{ABCDABCD},1,2017-04-30
{XPTOXPTO},2,2020-12-31
{ZXZXZXZX},3,2020-12-31
{GIFTCODE},4,2020-12-31
~~~~

## Usage:
~~~~
https://your.site/tinyticket/useticket.php?ticket=CODE
~~~~

Result of this call will be a json object with two parameters:
- result:  a return code (see below)
- message: a description of the return code

### Return codes
- 0: success (ticket validated)
- 1: error (ticket expired)
- 2: error (ticket already used)
- 3: error (ticket not found)
- 4: error (missing parameter: ticket)


