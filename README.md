This is our "Who pays the bill" [german: Wer Zahlt EigenTlich => WZET] tool.

Visuals and code are both very crude, since I just hacked it with little effort put into it.

I removed secrets from our installation (search source for ####), and emptied the DB.

To get a running instance:

1. Add a \.htaccess to prevent HTTP access to the api/db/ folder!
2. Set all #### in the source to appropriate values [there are names, description]
3. Update all references to ''kochen.sqlite'' to your database.
4. Register a user and set his flag to _1_ [=Admin].
5. (optional) add phpsqliteadmin to sqladmin.php

General todo:

1.  Prevent JS injection via Name/Login (display is not filtered atm); fix german special chars (Umlaute) while at that.
2.  Create a config.php with all the secrets & DB location
3.  setup.php?
4.  Localization
5.  A design
6.  Function: Change PW
7.  Function: Start event so people can RSVP and add their +X
8.  Function: Rollback
9.  Function: Add another bill (by someone else) - DB should be able to reflect this, but the code does not
10. DB names should be english, not german

DB layout is not quite 100% ERM, but I believe the way I did it now is a bit more versatile.
I.e. the Users.Konto value could be computed on the fly, but that way it can be bootstrapped with values != 0.
