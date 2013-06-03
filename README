# Batchio

---

Batchio is a little tool to take a batch of numbers and, using Twilio services, 
make Calls/Send text and log the output.

## Usage

    php ./app.php batchio:run --sync=1 /path/to/file.csv /path/to/config.yml

    Output:
    AC00000000000000000000000000000000    +000000000000	+00000000000	sent

(Columns are Account SID, recipient number, Twilio number calling from and status)

## Components

There is a web service component ([Backio][1])
which deals with syncing both the console application and the service status (which
btw is asynchronous).

Backio re-uses Batchio components (Syncr) to communicate with Batchio processing.

In order to use the `Syncr\Db` component you must create the table it uses:

    CREATE TABLE calls (
        id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        sid VARCHAR(256) NOT NULL,
        status VARCHAR(25) NOT NULL, 
        account_sid VARCHAR(256) NOT NULL,
        call_from  VARCHAR(128) NOT NULL,
        call_to VARCHAR(128) NOT NULL
    ) ENGINE = MYISAM;


Then configure syncr\driver and syncr\db in the configuration file:

    syncr:
      driver: db

      db:
        max_attemps: 5
        attemps_delay: 5
        hostname: localhost
        username: example
        password: example
        database: batchio

The application uses the following components from different sources through [Composer][2]:

- Symfony's Console component [https://github.com/symfony/Console]
- Symfony's Yaml component [https://github.com/symfony/Yaml]
- Goodby's csv library [https://github.com/goodby/csv]
- Twilio's sdk [https://github.com/twilio/twilio-php]

## To Do

- Make input format configurable. Currently it only supports CSV and an specific
format (a single column of numbers).

- Decouple components. Syncr, Importer and Service all comes bundle on to Batchio.

- Add unit tests.

- Add other services.

## Highlights

- Twilio API
- CSV
- External configuration
- Log process output
- Bitbucket

~
[1]: https://bitbucket.org/dcavuotti/backio
[2]: http://getcomposer.org
